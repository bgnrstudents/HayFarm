<?php
class Hewan
{
    private $conn;
    private $table = 'data_ternak';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Read All
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE jenis_hewan IN ('sapi_perah', 'sapi_po') AND (is_deleted IS NULL OR is_deleted = 0)
                  ORDER BY id_hewan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Method khusus dropdown: hanya sapi tidak produktif yang boleh dijual sebagai produk hewan
    public function getAvailableForProduct()
    {
        $query = "SELECT id_hewan, kode_hewan, jenis_hewan, jenis_kelamin, no_kandang, status_hewan
              FROM " . $this->table . " 
              WHERE jenis_hewan IN ('sapi_perah', 'sapi_po') 
              AND (is_deleted IS NULL OR is_deleted = 0)
              AND status_hewan = 'tdk_produktif'
              AND id_hewan NOT IN (
                  SELECT id_hewan FROM data_produk 
                  WHERE jenis_produk = 'hewan' AND id_hewan IS NOT NULL
              )
              ORDER BY kode_hewan";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Read Single
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " 
              WHERE id_hewan = ? 
              AND jenis_hewan IN ('sapi_perah', 'sapi_po')
              AND (is_deleted IS NULL OR is_deleted = 0)"; 
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
            // Validasi required
            if (empty($data['kode_hewan'])) {
                return ['status' => false, 'message' => 'Kode hewan wajib diisi'];
            }
            if (empty($data['jenis_hewan'])) {
                return ['status' => false, 'message' => 'Jenis hewan wajib dipilih'];
            }
            if (!isset($data['berat_badan']) || $data['berat_badan'] <= 0) {
                return ['status' => false, 'message' => 'Berat badan harus lebih dari 0'];
            }
            if (!empty($data['tgl_lahir']) && $data['tgl_lahir'] > date('Y-m-d')) {
                return ['status' => false, 'message' => 'Tanggal lahir tidak boleh di masa depan'];
            }

            // Cek duplikat kode_hewan
            $stmt = $this->conn->prepare("SELECT id_hewan FROM " . $this->table . " WHERE kode_hewan = ?");
            $stmt->bind_param("s", $data['kode_hewan']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return ['status' => false, 'message' => 'Kode hewan sudah terdaftar'];
            }

            // Default values
            $jenis_kelamin = $data['jenis_kelamin'] ?? 'jantan';
            $no_kandang = $data['no_kandang'] ?? '-';
            $tgl_lahir = $data['tgl_lahir'] ?? date('Y-m-d');
            $foto_hewan = $data['foto_hewan'] ?? '';

            // Normalize status: handle fallback
            $raw_status = $data['status_hewan'] ?? 'produktif';
            $status_hewan = ($raw_status === 'tdk_produktif') ? 'tdk_produktif' : 'produktif';

            // Validate enums (sesuai database.xlsx)
            $allowed_jenis = ['sapi_perah', 'sapi_po'];
            $allowed_kelamin = ['jantan', 'betina'];
            $allowed_status = ['produktif', 'tdk_produktif'];

            if (!in_array($data['jenis_hewan'], $allowed_jenis)) return ['status' => false, 'message' => 'Jenis hewan tidak valid'];
            if (!in_array($jenis_kelamin, $allowed_kelamin)) return ['status' => false, 'message' => 'Jenis kelamin tidak valid'];
            if (!in_array($status_hewan, $allowed_status)) return ['status' => false, 'message' => 'Status hewan tidak valid'];

            // INSERT (tanpa nama_hewan)
            $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " 
                (kode_hewan, jenis_hewan, berat_badan, jenis_kelamin, no_kandang, tgl_lahir, foto_hewan, status_hewan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "ssdsssss",
                $data['kode_hewan'],
                $data['jenis_hewan'],
                $data['berat_badan'],
                $jenis_kelamin,
                $no_kandang,
                $tgl_lahir,
                $foto_hewan,
                $status_hewan
            );

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Data berhasil ditambahkan'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Update: Gunakan variabel temporary untuk bind_param
    public function update($id, $data)
    {
        try {
            $check = $this->getById($id);
            if (!$check) return ['status' => false, 'message' => 'Data tidak ditemukan'];

            if (!empty($check['is_deleted']) && $check['is_deleted'] == 1) {
                return ['status' => false, 'message' => 'Data telah diarsipkan dan tidak dapat diubah.'];
            }
            if (!empty($data['tgl_lahir']) && $data['tgl_lahir'] > date('Y-m-d')) {
                return ['status' => false, 'message' => 'Tanggal lahir tidak boleh di masa depan'];
            }

            // Validate enums
            $allowed_jenis = ['sapi_perah', 'sapi_po'];
            $allowed_kelamin = ['jantan', 'betina'];
            $allowed_status = ['produktif', 'tdk_produktif'];

            if (!empty($data['jenis_hewan']) && !in_array($data['jenis_hewan'], $allowed_jenis))
                return ['status' => false, 'message' => 'Jenis hewan tidak valid'];
            if (!empty($data['jenis_kelamin']) && !in_array($data['jenis_kelamin'], $allowed_kelamin))
                return ['status' => false, 'message' => 'Jenis kelamin tidak valid'];

            // Normalize & validate status
            $raw_status = $data['status_hewan'] ?? $check['status_hewan'];
            $status_hewan = ($raw_status === 'tdk_produktif') ? 'tdk_produktif' : 'produktif';
            if (!in_array($status_hewan, $allowed_status))
                return ['status' => false, 'message' => 'Status hewan tidak valid'];

            // ✅ FIX: Assign ke variabel temporary DULU (bind_param butuh reference)
            $bind_kode = !empty($data['kode_hewan']) ? $data['kode_hewan'] : $check['kode_hewan'];
            $bind_jenis = !empty($data['jenis_hewan']) ? $data['jenis_hewan'] : $check['jenis_hewan'];
            $bind_berat = isset($data['berat_badan']) ? floatval($data['berat_badan']) : $check['berat_badan'];
            $bind_kelamin = !empty($data['jenis_kelamin']) ? $data['jenis_kelamin'] : $check['jenis_kelamin'];
            $bind_kandang = !empty($data['no_kandang']) ? $data['no_kandang'] : $check['no_kandang'];
            $bind_lahir = !empty($data['tgl_lahir']) ? $data['tgl_lahir'] : $check['tgl_lahir'];
            $bind_foto = !empty($data['foto_hewan']) ? $data['foto_hewan'] : $check['foto_hewan'];
            $bind_status = $status_hewan;
            $bind_id = $id; // integer untuk WHERE clause

            // UPDATE query: 8 fields + 1 WHERE = 9 params
            $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET 
                kode_hewan = ?, 
                jenis_hewan = ?, 
                berat_badan = ?, 
                jenis_kelamin = ?, 
                no_kandang = ?, 
                tgl_lahir = ?, 
                foto_hewan = COALESCE(NULLIF(?, ''), foto_hewan), 
                status_hewan = ? 
                WHERE id_hewan = ?");

            $stmt->bind_param(
                "ssdsssssi",
                $bind_kode,      // s
                $bind_jenis,     // s
                $bind_berat,     // d
                $bind_kelamin,   // s
                $bind_kandang,   // s
                $bind_lahir,     // s
                $bind_foto,      // s
                $bind_status,    // s
                $bind_id         // i
            );

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Data berhasil diperbarui'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Delete
    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("
            UPDATE " . $this->table . " 
            SET is_deleted = 1, deleted_at = NOW() 
            WHERE id_hewan = ?
        ");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Data berhasil diarsipkan (soft delete)'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
