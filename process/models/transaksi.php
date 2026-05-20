<?php

class Transaksi
{
    private mysqli $conn;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    public function buatTransaksi(int $id_user, array $dataPembeli, array $items): array
    {
        if ($items === []) {
            return ['status' => false, 'message' => 'Tidak ada produk yang dipilih'];
        }

        $nama = trim((string) ($dataPembeli['nama_pembeli'] ?? ''));
        $noTelp = trim((string) ($dataPembeli['no_telp'] ?? ''));
        $alamat = trim((string) ($dataPembeli['alamat'] ?? ''));
        $kodePos = trim((string) ($dataPembeli['kode_pos'] ?? ''));
        $metode = $dataPembeli['metode_pembayaran'] === 'cod' ? 'cod' : 'transfer';
        $bukti = (string) ($dataPembeli['bukti_pembayaran'] ?? '');

        if ($nama === '' || $noTelp === '' || $alamat === '' || $kodePos === '') {
            return ['status' => false, 'message' => 'Data pengiriman wajib dilengkapi'];
        }

        $jumlahPembelian = 0;
        $totalTagihan = 0;

        foreach ($items as $item) {
            $stmt = $this->conn->prepare(
                "SELECT stok, status_produk
                 FROM data_produk
                 WHERE id_produk = ?
                 FOR UPDATE"
            );
            $stmt->bind_param('i', $item['id_produk']);
            $stmt->execute();
            $produk = $stmt->get_result()->fetch_assoc();

            if (!$produk) {
                throw new Exception('Produk tidak ditemukan.');
            }

            if (($produk['status_produk'] ?? '') !== 'blm_terjual') {
                throw new Exception("Produk {$item['nama_produk']} sudah tidak tersedia.");
            }

            if ((int) $produk['stok'] < (int) $item['jumlah']) {
                throw new Exception("Stok {$item['nama_produk']} berubah. Silakan coba lagi.");
            }

            $jumlahPembelian += (int) $item['jumlah'];
            $totalTagihan += (float) $item['sub_total'];
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO transaksi
                (id_user, nama_pembeli, no_telp, alamat, kode_pos, metode_pembayaran, jumlah_pembelian, bukti_pembayaran, tgl_transaksi, total_tagihan, status_transaksi)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, 'menunggu_verifikasi')"
        );

        $stmt->bind_param('isssssisd', $id_user, $nama, $noTelp, $alamat, $kodePos, $metode, $jumlahPembelian, $bukti, $totalTagihan);

        if (!$stmt->execute()) {
            return ['status' => false, 'message' => 'Gagal membuat transaksi: ' . $stmt->error];
        }

        $idTransaksi = (int) $this->conn->insert_id;

        foreach ($items as $item) {
            $resultDetail = $this->tambahDetailTransaksi($idTransaksi, $item);
            if (!$resultDetail['status']) {
                return $resultDetail;
            }

            $this->kurangiStokProduk((int) $item['id_produk'], (int) $item['jumlah']);
        }

        return [
            'status' => true,
            'message' => 'Pesanan berhasil dibuat dan menunggu verifikasi',
            'id_transaksi' => $idTransaksi,
        ];
    }

    public function kosongkanKeranjangUser(int $id_user): void
    {
        $stmt = $this->conn->prepare(
            "DELETE d FROM detail_keranjang d
             JOIN keranjang k ON d.id_keranjang = k.id_keranjang
             WHERE k.id_user = ?"
        );
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
    }

    public function getAllTransaksi(?string $status = null, ?string $bulan = null, ?string $metode = null): array
    {
        $query = "SELECT 
                t.*,
                u.username as nama_user,
                GROUP_CONCAT(p.nama_produk SEPARATOR ', ') as produk,
                COUNT(dt.id_produk) as jumlah_item
              FROM transaksi t
              LEFT JOIN user u ON t.id_user = u.id_user
              LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
              LEFT JOIN data_produk p ON dt.id_produk = p.id_produk
              WHERE 1=1";

        $params = [];
        $types = '';

        // Filter status
        if ($status !== null && $status !== '') {
            $query .= " AND t.status_transaksi = ?";
            $params[] = $status;
            $types .= 's';
        }

        // Filter bulan (berdasarkan MONTH(tgl_transaksi))
        if ($bulan !== null && $bulan !== '' && is_numeric($bulan)) {
            $query .= " AND MONTH(t.tgl_transaksi) = ?";
            $params[] = (int) $bulan;
            $types .= 'i';
        }

        // Filter metode pembayaran
        if ($metode !== null && $metode !== '') {
            $query .= " AND t.metode_pembayaran = ?";
            $params[] = $metode;
            $types .= 's';
        }

        // Urutan: GROUP BY dulu, baru ORDER BY
        $query .= " GROUP BY t.id_transaksi";
        $query .= " ORDER BY FIELD(t.status_transaksi, 'menunggu_verifikasi', 'telah_dikonfirmasi', 'dibatalkan'), t.tgl_transaksi DESC";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed in getAllTransaksi: " . $this->conn->error);
            return [];
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getAllForReport(): array
    {
        $query = "SELECT
                    t.*,
                    GROUP_CONCAT(DISTINCT p.nama_produk ORDER BY p.nama_produk SEPARATOR ', ') AS produk,
                    GROUP_CONCAT(DISTINCT p.jenis_produk ORDER BY p.jenis_produk SEPARATOR ',') AS jenis_produk
                  FROM transaksi t
                  LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                  LEFT JOIN data_produk p ON dt.id_produk = p.id_produk
                  GROUP BY t.id_transaksi
                  ORDER BY t.tgl_transaksi DESC, t.id_transaksi DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStatsVerifikasi(): array
    {
        $stats = [
            'menunggu' => 0,
            'diverifikasi' => 0,
            'ditolak' => 0,
            'total_terverifikasi' => 0.0
        ];

        // Count menunggu
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM transaksi WHERE status_transaksi = 'menunggu_verifikasi'");
        $stmt->execute();
        $stats['menunggu'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Count diverifikasi
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM transaksi WHERE status_transaksi = 'telah_dikonfirmasi'");
        $stmt->execute();
        $stats['diverifikasi'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Count ditolak
        $stmt = $this->conn->prepare("SELECT COUNT(*) as c FROM transaksi WHERE status_transaksi = 'dibatalkan'");
        $stmt->execute();
        $stats['ditolak'] = (int) $stmt->get_result()->fetch_assoc()['c'];

        // Total tagihan yang terverifikasi
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(total_tagihan), 0) as total FROM transaksi WHERE status_transaksi = 'telah_dikonfirmasi'");
        $stmt->execute();
        $stats['total_terverifikasi'] = (float) $stmt->get_result()->fetch_assoc()['total'];

        return $stats;
    }

    public function getTransaksiByUser(int $id_user): array
    {
        // 1. Ambil Header Transaksi
        $stmt = $this->conn->prepare(
            "SELECT * FROM transaksi 
         WHERE id_user = ? 
         ORDER BY tgl_transaksi DESC, id_transaksi DESC"
        );
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $transaksiList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // 2. Ambil Detail Produk + Kesehatan untuk setiap Transaksi
        foreach ($transaksiList as &$t) {
            $stmtD = $this->conn->prepare(
                "SELECT dt.*, 
                    p.nama_produk, p.satuan, p.jenis_produk, 
                    ternak.foto_hewan, ternak.kode_hewan, ternak.jenis_hewan,
                    (SELECT GROUP_CONCAT(
                        CONCAT('(', k.tgl_pemeriksaan, ') ', k.status_kesehatan, ': ', k.diagnosis)
                        ORDER BY k.tgl_pemeriksaan DESC SEPARATOR ' | ')
                     FROM data_kesehatan k 
                     WHERE k.id_hewan = p.id_hewan 
                     LIMIT 1) as riwayat_kesehatan
             FROM detail_transaksi dt 
             LEFT JOIN data_produk p ON dt.id_produk = p.id_produk 
             LEFT JOIN data_ternak ternak ON p.id_hewan = ternak.id_hewan 
             WHERE dt.id_transaksi = ?"
            );
            $stmtD->bind_param('i', $t['id_transaksi']);
            $stmtD->execute();
            $details = $stmtD->get_result()->fetch_all(MYSQLI_ASSOC);

            $t['detail'] = [];
            foreach ($details as $d) {
                // Fallback jika produk dihapus
                if (empty($d['nama_produk'])) {
                    $d['nama_produk'] = 'Produk Tidak Tersedia (Terhapus)';
                    $d['gambar'] = 'public/images/bgheader_produk.png';
                    $d['satuan'] = '';
                } else {
                    $d['gambar'] = $this->getGambarForProduk($d);
                }

                // ✅ Mapping enum status_kesehatan agar user lihat label yang sama dengan admin
                if (!empty($d['riwayat_kesehatan'])) {
                    // Replace enum value dengan label
                    $d['riwayat_kesehatan'] = str_replace(
                        ['sehat', 'observasi', 'perawatan'],
                        ['Sehat', 'Dalam Observasi', 'Dalam Perawatan'],
                        $d['riwayat_kesehatan']
                    );
                }

                $t['detail'][] = $d;
            }
        }

        return $transaksiList;
    }
    // Method baru untuk ambil detail transaksi lengkap dengan produk & email
    public function getDetailTransaksiLengkap($id_transaksi)
    {
        $query = "SELECT 
                t.*,
                u.email,
                u.username,
                dt.id_produk,
                dt.jumlah,
                dt.harga,
                dt.sub_total,
                p.nama_produk,
                p.jenis_produk,
                p.satuan,
                p.id_hewan,
                tn.kode_hewan
              FROM transaksi t
              LEFT JOIN user u ON t.id_user = u.id_user
              LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
              LEFT JOIN data_produk p ON dt.id_produk = p.id_produk
              LEFT JOIN data_ternak tn ON p.id_hewan = tn.id_hewan
              WHERE t.id_transaksi = ?
              ORDER BY dt.id_detail_transaksi ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id_transaksi);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getGambarForProduk(array $produk): string
    {
        $default = 'public/images/bgheader_produk.png';

        // Cek Jenis Produk
        if (($produk['jenis_produk'] ?? '') === 'susu') {
            return 'public/images/susu.jpg';
        }
        if (($produk['jenis_produk'] ?? '') === 'rumput') {
            return 'public/images/rumput.jpg';
        }

        // Cek Foto Hewan
        $foto = trim((string) ($produk['foto_hewan'] ?? ''));
        if ($foto !== '') {
            if (str_starts_with($foto, 'public/') || str_starts_with($foto, 'uploads/')) {
                return $foto;
            }
            return 'uploads/hewan/' . $foto; // Asumsi path relatif untuk hewan
        }

        return $default;
    }

    private function restoreStokProduk(int $id_transaksi): void
    {
        $stmt = $this->conn->prepare(
            "SELECT dt.id_produk, dt.jumlah FROM detail_transaksi dt WHERE dt.id_transaksi = ?"
        );
        $stmt->bind_param('i', $id_transaksi);
        $stmt->execute();
        $details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($details as $detail) {
            $stmt2 = $this->conn->prepare(
                "UPDATE data_produk
                 SET stok = stok + ?,
                     status_produk = 'blm_terjual'
                 WHERE id_produk = ?"
            );
            $stmt2->bind_param('ii', $detail['jumlah'], $detail['id_produk']);
            $stmt2->execute();
            $stmt2->close();
        }
        $stmt->close();
    }


    private function tambahDetailTransaksi(int $id_transaksi, array $item): array
    {
        $idProduk = (int) $item['id_produk'];
        $jumlah = (int) $item['jumlah'];
        $harga = (float) $item['harga'];
        $subtotal = (float) $item['sub_total'];

        $stmt = $this->conn->prepare(
            "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga, sub_total)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('iiidd', $id_transaksi, $idProduk, $jumlah, $harga, $subtotal);

        if (!$stmt->execute()) {
            return ['status' => false, 'message' => 'Gagal menyimpan detail transaksi'];
        }

        return ['status' => true, 'message' => 'Detail transaksi berhasil disimpan'];
    }

    private function kurangiStokProduk(int $id_produk, int $jumlah): void
    {
        $stmt = $this->conn->prepare(
            "UPDATE data_produk
             SET stok = GREATEST(stok - ?, 0),
                 status_produk = IF(GREATEST(stok - ?, 0) <= 0, 'terjual', 'blm_terjual')
             WHERE id_produk = ?"
        );
        $stmt->bind_param('iii', $jumlah, $jumlah, $id_produk);
        $stmt->execute();
    }

    public function updateStatusTransaksi(int $id_transaksi, string $status, int $admin_id): array
    {
        // Validasi status
        $allowed_status = ['telah_dikonfirmasi', 'dibatalkan'];
        if (!in_array($status, $allowed_status)) {
            return ['status' => false, 'message' => 'Status tidak valid'];
        }

        try {
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare(
                "SELECT status_transaksi, id_user
                 FROM transaksi
                 WHERE id_transaksi = ?
                 FOR UPDATE"
            );
            $stmt->bind_param('i', $id_transaksi);
            $stmt->execute();
            $transaksi = $stmt->get_result()->fetch_assoc();

            if (!$transaksi) {
                throw new Exception('Transaksi tidak ditemukan');
            }

            if ($transaksi['status_transaksi'] !== 'menunggu_verifikasi') {
                throw new Exception('Transaksi sudah diproses sebelumnya');
            }

            if ($status === 'telah_dikonfirmasi') {
                $stmt = $this->conn->prepare(
                    "UPDATE transaksi
                     SET status_transaksi = ?,
                         tgl_verifikasi = NOW()
                     WHERE id_transaksi = ?"
                );

                if ($stmt === false) {
                    // Fallback untuk database yang belum memiliki kolom tgl_verifikasi
                    $stmt = $this->conn->prepare(
                        "UPDATE transaksi
                         SET status_transaksi = ?
                         WHERE id_transaksi = ?"
                    );
                }

                if ($stmt === false) {
                    throw new Exception('Gagal menyiapkan query update status: ' . $this->conn->error);
                }

                $stmt->bind_param('si', $status, $id_transaksi);
            } else {
                $stmt = $this->conn->prepare(
                    "UPDATE transaksi
                     SET status_transaksi = ?
                     WHERE id_transaksi = ?"
                );
                $stmt->bind_param('si', $status, $id_transaksi);
            }

            if ($stmt === false || !$stmt->execute()) {
                $errorMessage = $stmt === false ? $this->conn->error : $stmt->error;
                throw new Exception('Gagal update status: ' . $errorMessage);
            }

            if ($status === 'dibatalkan') {
                $this->restoreStokProduk($id_transaksi);
            }

            $this->conn->commit();

            return [
                'status' => true,
                'message' => $status === 'telah_dikonfirmasi'
                    ? 'Transaksi berhasil diverifikasi'
                    : 'Transaksi ditolak dan stok telah dikembalikan'
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
}
