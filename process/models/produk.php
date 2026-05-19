<?php
class Produk
{
    private $conn;
    private $table = 'data_produk';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Read All
    public function getAll(?string $status_filter = null, ?string $jenis_filter = null): array
    {
        $status_clause = "";
        $jenis_clause = "";

        // Filter status
        if ($status_filter === 'tersedia') {
            $status_clause = "AND p.status_produk = 'blm_terjual' AND (t.is_deleted IS NULL OR t.is_deleted = 0)";
        } elseif ($status_filter === 'tidak_tersedia') {
            $status_clause = "AND (p.status_produk = 'terjual' OR t.is_deleted = 1)";
        }

        // Filter jenis_produk
        if ($jenis_filter !== null && $jenis_filter !== '' && in_array(strtolower($jenis_filter), ['hewan', 'rumput', 'susu'])) {
            $jenis_clause = "AND p.jenis_produk = ?";
        }

        // Query dengan data kesehatan terbaru per hewan.
        $query = "SELECT p.*, 
                     t.foto_hewan, t.kode_hewan, t.tgl_lahir, t.no_kandang, t.jenis_hewan, t.status_hewan,
                     kh.status_kesehatan as status_kesehatan_terakhir,
                     kh.tgl_pemeriksaan as tgl_pemeriksaan_terakhir,
                     kh.catatan as catatan_kesehatan_terakhir
              FROM " . $this->table . " p 
              LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan 
              LEFT JOIN data_kesehatan kh ON kh.id_kesehatan = (
                  SELECT k.id_kesehatan
                  FROM data_kesehatan k
                  WHERE k.id_hewan = p.id_hewan
                  ORDER BY k.tgl_pemeriksaan DESC, k.id_kesehatan DESC
                  LIMIT 1
              )
              WHERE (p.jenis_produk <> 'hewan' OR t.jenis_hewan IN ('sapi_perah', 'sapi_po'))
              $status_clause
              $jenis_clause
              ORDER BY p.id_produk DESC";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed in getAll: " . $this->conn->error);
            return [];
        }

        // Bind parameter jika ada jenis_filter
        $params = [];
        $types = '';

        if ($jenis_clause) {
            $params[] = strtolower($jenis_filter);
            $types .= 's';
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    // Tambah method ini di class Produk
    public function getAllForUserView(): array
    {
        $query = "SELECT p.*, 
                     t.foto_hewan, t.kode_hewan, t.tgl_lahir, t.no_kandang, t.jenis_hewan, t.status_hewan,
                     kh.status_kesehatan as status_kesehatan_terakhir,
                     kh.tgl_pemeriksaan as tgl_pemeriksaan_terakhir,
                     kh.catatan as catatan_kesehatan_terakhir
              FROM " . $this->table . " p 
              LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan 
              LEFT JOIN data_kesehatan kh ON kh.id_kesehatan = (
                  SELECT k.id_kesehatan
                  FROM data_kesehatan k
                  WHERE k.id_hewan = p.id_hewan
                  ORDER BY k.tgl_pemeriksaan DESC, k.id_kesehatan DESC
                  LIMIT 1
              )
              WHERE p.status_produk = 'blm_terjual'
              ORDER BY p.id_produk DESC";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in getAllForUserView: " . $this->conn->error);
            return [];
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    // Read Single
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_produk = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Create
    public function create($data)
    {
        try {
            $id_hewan = !empty($data['id_hewan']) ? (int) $data['id_hewan'] : null;

            // Validasi required
            if (empty($data['jenis_produk'])) {
                return ['status' => false, 'message' => 'Jenis produk wajib diisi'];
            }
            if (empty($data['nama_produk'])) {
                return ['status' => false, 'message' => 'Nama produk wajib diisi'];
            }
            if (!isset($data['harga']) || $data['harga'] <= 0) {
                return ['status' => false, 'message' => 'Harga harus lebih dari 0'];
            }


            if ($data['jenis_produk'] === 'hewan' && !empty($id_hewan)) {
                $stmt_check = $this->conn->prepare("SELECT id_produk FROM " . $this->table . " WHERE id_hewan = ? AND jenis_produk = 'hewan'");
                $stmt_check->bind_param("i", $id_hewan);
                $stmt_check->execute();
                if ($stmt_check->get_result()->num_rows > 0) {
                    return ['status' => false, 'message' => 'Hewan ini sudah memiliki produk. Tidak dapat membuat produk duplikat.'];
                }
            }
            if ($data['jenis_produk'] === 'susu' && !empty($data['tgl_kadaluarsa'])) {
                $today = date('Y-m-d');
                if ($data['tgl_kadaluarsa'] < $today) {
                    return ['status' => false, 'message' => 'Tanggal kadaluarsa susu tidak boleh di masa lalu.'];
                }
            }
            // Default values
            $stok = isset($data['stok']) ? (int)$data['stok'] : 1;
            $satuan = $data['satuan'] ?? '';
            $tgl_kadaluarsa = !empty($data['tgl_kadaluarsa']) ? $data['tgl_kadaluarsa'] : '2099-12-31';
            $deskripsi = $data['deskripsi'] ?? '';

            $raw_status = $data['status_produk'] ?? 'tersedia';
            $status_produk = ($raw_status === 'tidak_tersedia' || $raw_status === 'terjual') ? 'terjual' : 'blm_terjual';

            $allowed_jenis = ['hewan', 'rumput', 'susu'];
            if (!in_array($data['jenis_produk'], $allowed_jenis)) {
                return ['status' => false, 'message' => 'Jenis produk tidak valid'];
            }

            if ($data['jenis_produk'] === 'hewan') {
                if (!$this->isSupportedAnimalId($id_hewan)) {
                    return ['status' => false, 'message' => 'Produk hewan hanya dapat menggunakan data sapi'];
                }

                if (!$this->isSellableAnimalId($id_hewan)) {
                    return ['status' => false, 'message' => 'Hanya hewan tidak produktif yang dapat dijual.'];
                }
            }

            // Insert Query
            $query = "INSERT INTO " . $this->table . " 
                      (id_hewan, jenis_produk, nama_produk, harga, stok, satuan, tgl_kadaluarsa, deskripsi, status_produk) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);

            // i=integer, s=string, d=double (untuk float)
            $stmt->bind_param(
                "issdissss",
                $id_hewan,
                $data['jenis_produk'],
                $data['nama_produk'],
                $data['harga'],
                $stok,
                $satuan,
                $tgl_kadaluarsa,
                $deskripsi,
                $status_produk
            );

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Produk berhasil ditambahkan'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Update
    public function update($id, $data)
    {
        try {
            $check = $this->getById($id);
            if (!$check) {
                error_log("Update failed: Produk ID $id not found");
                return ['status' => false, 'message' => 'Data produk tidak ditemukan'];
            }

            // Normalisasi status
            $status_produk = !empty($data['status_produk']) ? $data['status_produk'] : $check['status_produk'];
            $allowed_status = ['terjual', 'blm_terjual'];
            if (!in_array($status_produk, $allowed_status)) {
                $status_produk = 'blm_terjual';
            }

            // ✅ NORMALISASI tgl_kadaluarsa: kosong/null → default
            $bind_tgl = $data['tgl_kadaluarsa'] ?? $check['tgl_kadaluarsa'];
            if ($bind_tgl === '' || $bind_tgl === null) {
                $bind_tgl = '2099-12-31';
            }

            // ✅ Bind variables untuk prepared statement (hindari reference issue)
            $bind_id_hewan = isset($data['id_hewan']) ? $data['id_hewan'] : $check['id_hewan'];
            $bind_jenis = $data['jenis_produk'] ?? $check['jenis_produk'];
            $bind_nama = $data['nama_produk'] ?? $check['nama_produk'];
            $bind_harga = isset($data['harga']) ? floatval($data['harga']) : floatval($check['harga']);
            $bind_stok = isset($data['stok']) ? (int)$data['stok'] : (int)$check['stok'];
            $bind_satuan = $data['satuan'] ?? $check['satuan'];
            $bind_desc = $data['deskripsi'] ?? $check['deskripsi'];

            // ✅ Validasi khusus hewan
            if ($bind_jenis === 'hewan') {
                if (!$this->isSupportedAnimalId($bind_id_hewan)) {
                    return ['status' => false, 'message' => 'Produk hewan hanya dapat menggunakan data sapi'];
                }

                if (!$this->isSellableAnimalId((int) $bind_id_hewan, (int) $id)) {
                    return ['status' => false, 'message' => 'Hanya hewan tidak produktif yang dapat dijual.'];
                }
            }

            $query = "UPDATE " . $this->table . " SET 
                  id_hewan = ?, 
                  jenis_produk = ?, 
                  nama_produk = ?, 
                  harga = ?, 
                  stok = ?, 
                  satuan = ?, 
                  tgl_kadaluarsa = ?, 
                  deskripsi = ?, 
                  status_produk = ? 
                  WHERE id_produk = ?";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return ['status' => false, 'message' => 'Gagal prepare query: ' . $this->conn->error];
            }

            // ✅ Bind parameter: PERHATIKAN tgl_kadaluarsa pakai $bind_tgl (bukan $bind['...'])
            $stmt->bind_param(
                "issdissssi",
                $bind_id_hewan,      // i
                $bind_jenis,         // s
                $bind_nama,          // s
                $bind_harga,         // d
                $bind_stok,          // i
                $bind_satuan,        // s
                $bind_tgl,           // s ✅ PAKAI VARIABEL YANG SUDAH DINORMALISASI
                $bind_desc,          // s
                $status_produk,      // s
                $id                  // i
            );

            if ($stmt->execute()) {
                if ($stmt->affected_rows === 0) {
                    error_log("Update produk $id: no rows affected (data mungkin tidak berubah)");
                }
                return ['status' => true, 'message' => 'Produk berhasil diperbarui'];
            } else {
                error_log("Execute failed: " . $stmt->error);
                return ['status' => false, 'message' => 'Gagal update: ' . $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Exception in update: " . $e->getMessage());
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    // Delete
    public function delete($id)
    {
        try {
            $check = $this->getById($id);
            if (!$check) return ['status' => false, 'message' => 'Data tidak ditemukan'];

            // Cek apakah produk sudah ada di transaksi yang sudah dikonfirmasi
            $stmt_check = $this->conn->prepare("
                SELECT COUNT(*) as c FROM detail_transaksi dt
                INNER JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
                WHERE dt.id_produk = ? AND t.status_transaksi = 'telah_dikonfirmasi'
            ");
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            if ($stmt_check->get_result()->fetch_assoc()['c'] > 0) {
                return ['status' => false, 'message' => 'Produk tidak dapat dihapus karena sudah ada di transaksi yang dikonfirmasi. Ubah status menjadi "Tidak Tersedia" saja.'];
            }

            $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id_produk = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Produk berhasil dihapus'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    private function isSupportedAnimalId($id_hewan): bool
    {
        if (empty($id_hewan)) {
            return false;
        }

        $query = "SELECT id_hewan
                  FROM data_ternak
                  WHERE id_hewan = ? AND jenis_hewan IN ('sapi_perah', 'sapi_po')
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $animalId = (int) $id_hewan;
        $stmt->bind_param("i", $animalId);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    private function isSellableAnimalId(int $id_hewan, ?int $currentProductId = null): bool
    {
        if ($id_hewan <= 0) {
            return false;
        }

        $query = "SELECT t.id_hewan
                  FROM data_ternak t
                  WHERE t.id_hewan = ?
                    AND t.jenis_hewan IN ('sapi_perah', 'sapi_po')
                    AND t.status_hewan = 'tdk_produktif'
                    AND (t.is_deleted IS NULL OR t.is_deleted = 0)
                    AND NOT EXISTS (
                        SELECT 1
                        FROM data_produk p
                        WHERE p.id_hewan = t.id_hewan
                          AND p.jenis_produk = 'hewan'";

        if ($currentProductId !== null) {
            $query .= " AND p.id_produk <> ?";
        }

        $query .= "
                    )
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        if ($currentProductId !== null) {
            $stmt->bind_param("ii", $id_hewan, $currentProductId);
        } else {
            $stmt->bind_param("i", $id_hewan);
        }
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
}
