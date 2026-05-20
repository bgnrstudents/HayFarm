<?php
class Dashboard
{
    private mysqli $conn;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    /**
     * Statistik utama dashboard
     */
    public function getMainStats(): array
    {
        $stats = [];

        // Jumlah Produk dari Manajemen Produk
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as c
            FROM data_produk p
            LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan
            WHERE (p.jenis_produk <> 'hewan' OR t.jenis_hewan IN ('sapi_perah', 'sapi_po'))
        ");
        $stmt->execute();
        $stats['jumlah_produk'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Jumlah Diverifikasi dari Verifikasi Penjualan
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM transaksi WHERE status_transaksi = 'telah_dikonfirmasi'");
        $stmt->execute();
        $stats['diverifikasi'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Jumlah Hewan dari Data Hewan
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as c
            FROM data_ternak
            WHERE (is_deleted IS NULL OR is_deleted = 0)
        ");
        $stmt->execute();
        $stats['jumlah_hewan'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Hewan Sakit per Hari dari Data Kesehatan Hewan (status dalam perawatan)
        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT k.id_hewan) as c
            FROM data_kesehatan k
            INNER JOIN data_ternak t ON k.id_hewan = t.id_hewan
            WHERE k.status_kesehatan = 'perawatan'
              AND (k.is_deleted IS NULL OR k.is_deleted = 0)
              AND (t.is_deleted IS NULL OR t.is_deleted = 0)
        ");
        $stmt->execute();
        $stats['hewan_sakit_hari_ini'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        return $stats;
    }

    /**
     * Data grafik transaksi terverifikasi per bulan (tahun tertentu)
     */
    public function getMonthlyVerifiedTransactions(int $year): array
    {
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);

        $stmt = $this->conn->prepare("
            SELECT MONTH(tgl_transaksi) as bulan, COUNT(*) as c 
            FROM transaksi 
            WHERE status_transaksi = 'telah_dikonfirmasi' AND YEAR(tgl_transaksi) = ?
            GROUP BY MONTH(tgl_transaksi)
        ");
        $stmt->bind_param('i', $year);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[(int)$row['bulan'] - 1] = (int)$row['c'];
        }

        return [
            'labels' => $bulanLabels,
            'values' => $data
        ];
    }

    /**
     * List tahun yang ada data transaksi (untuk dropdown filter)
     */
    public function getAvailableYears(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT YEAR(tgl_transaksi) as tahun FROM transaksi ORDER BY tahun DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = (int)$row['tahun'];
        }
        
        // Jika kosong, tambahkan tahun sekarang
        if (empty($years)) {
            $years[] = (int) date('Y');
        }
        
        return $years;
    }

    /**
     * Notifikasi: Vaksinasi diperlukan (hewan dengan status kesehatan perawatan)
     */
    public function getVaccinationNeededCount(): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT k.id_hewan) as c
            FROM data_kesehatan k
            INNER JOIN data_ternak t ON k.id_hewan = t.id_hewan
            WHERE k.status_kesehatan = 'perawatan'
              AND (k.is_deleted IS NULL OR k.is_deleted = 0)
              AND (t.is_deleted IS NULL OR t.is_deleted = 0)
        ");
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['c'];
    }

    /**
     * Notifikasi: Susu kedaluwarsa (tgl_kadaluarsa hari ini atau lewat, dan masih tersedia)
     */
    public function getExpiredProductsCount(): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as c 
            FROM data_produk 
            WHERE jenis_produk = 'susu'
              AND tgl_kadaluarsa <= CURDATE()
              AND status_produk = 'blm_terjual'
        ");
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['c'];
    }

    /**
     * Notifikasi: Hewan hamil (dari data kesehatan/reproduksi dengan status IB berhasil)
     */
    public function getPregnantAnimalsThisMonth(): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT r.id_hewan) as c
            FROM data_reproduksi r
            INNER JOIN data_kesehatan k ON r.id_kesehatan = k.id_kesehatan
            INNER JOIN data_ternak t ON r.id_hewan = t.id_hewan
            WHERE r.status_ib = 'berhasil'
              AND (k.is_deleted IS NULL OR k.is_deleted = 0)
              AND (t.is_deleted IS NULL OR t.is_deleted = 0)
        ");
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['c'];
    }

    /**
     * Notifikasi: Transaksi menunggu verifikasi
     */
    public function getPendingVerificationCount(): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM transaksi WHERE status_transaksi = 'menunggu_verifikasi'");
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['c'];
    }
}
