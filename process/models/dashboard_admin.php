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

        // Jumlah Produk (yang blm_terjual)
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM data_produk WHERE status_produk = 'blm_terjual'");
        $stmt->execute();
        $stats['jumlah_produk'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Jumlah Transaksi Diverifikasi
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM transaksi WHERE status_transaksi = 'telah_dikonfirmasi'");
        $stmt->execute();
        $stats['diverifikasi'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Jumlah Hewan (produktif)
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM data_ternak WHERE status_hewan = 'produktif'");
        $stmt->execute();
        $stats['jumlah_hewan'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Hewan Sakit Hari Ini (status_kesehatan != 'sehat' AND tgl_pemeriksaan = TODAY)
        $stmt = $this->conn->prepare("SELECT COUNT(DISTINCT id_hewan) as c FROM data_kesehatan WHERE status_kesehatan != 'sehat' AND tgl_pemeriksaan = CURDATE()");
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
     * Notifikasi: Vaksinasi diperlukan (hewan dengan status 'perawatan' atau 'observasi')
     */
    public function getVaccinationNeededCount(): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT id_hewan) as c 
            FROM data_kesehatan 
            WHERE status_kesehatan IN ('perawatan', 'observasi')
        ");
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['c'];
    }

    /**
     * Notifikasi: Produk kedaluwarsa (tgl_kadaluarsa < TODAY AND status_produk = 'blm_terjual')
     */
    public function getExpiredProductsCount(): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as c 
            FROM data_produk 
            WHERE tgl_kadaluarsa < CURDATE() AND status_produk = 'blm_terjual'
        ");
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['c'];
    }

    /**
     * Notifikasi: Hewan hamil bulan ini (dari data_reproduksi: status_ib='proses' AND tgl_perkiraan bulan ini)
     */
    public function getPregnantAnimalsThisMonth(): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT id_hewan) as c 
            FROM data_reproduksi 
            WHERE status_ib = 'proses' 
            AND MONTH(tgl_perkiraan) = MONTH(CURDATE()) 
            AND YEAR(tgl_perkiraan) = YEAR(CURDATE())
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