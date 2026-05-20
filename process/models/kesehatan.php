<?php
class Kesehatan
{
    private $conn;
    private $table = 'data_kesehatan';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT 
                k.*, 
                t.kode_hewan, 
                t.jenis_hewan, 
                t.jenis_kelamin,
                r.tgl_ib, 
                r.ib_ke, 
                r.tgl_perkiraan, 
                r.status_ib
              FROM " . $this->table . " k 
              LEFT JOIN data_ternak t ON k.id_hewan = t.id_hewan 
              LEFT JOIN data_reproduksi r ON k.id_kesehatan = r.id_kesehatan
              WHERE (t.jenis_hewan IN ('sapi_perah', 'sapi_po') OR t.jenis_hewan IS NULL OR t.jenis_hewan = '')
                AND (t.is_deleted IS NULL OR t.is_deleted = 0)
                AND (k.is_deleted IS NULL OR k.is_deleted = 0)  /* ✅ FIX: Filter kesehatan terarsip */
             ORDER BY k.tgl_pemeriksaan DESC, k.id_kesehatan DESC";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getById($id)
    {
        $query = "SELECT k.*, t.kode_hewan, t.jenis_hewan, t.jenis_kelamin 
                  FROM " . $this->table . " k 
                  LEFT JOIN data_ternak t ON k.id_hewan = t.id_hewan 
                  WHERE k.id_kesehatan = ? 
                  AND t.jenis_hewan IN ('sapi_perah', 'sapi_po')
                  AND (k.is_deleted IS NULL OR k.is_deleted = 0)"; /* ✅ FIX */
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getByHewanId($id_hewan)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_hewan = ? ORDER BY tgl_pemeriksaan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_hewan);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Normalisasi status (dipakai di create & update)
    private function normalizeStatus($status)
    {
        return match ($status) {
            'observasi' => 'dalam_observasi',
            'perawatan' => 'dalam_perawatan',
            default => $status
        };
    }

    public function create($data)
    {
        try {
            // Validasi required
            if (empty($data['id_hewan'])) return ['status' => false, 'message' => 'Hewan wajib dipilih'];
            if (empty($data['tgl_pemeriksaan'])) return ['status' => false, 'message' => 'Tanggal pemeriksaan wajib diisi'];
            if (empty($data['status_kesehatan'])) return ['status' => false, 'message' => 'Status kesehatan wajib dipilih'];

            // Pastikan format tanggal valid (Y-m-d) agar perbandingan tidak rusak
            $tglPemeriksaan = $data['tgl_pemeriksaan'];
            $tglCheckOk = !empty($tglPemeriksaan) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglPemeriksaan);
            if (!$tglCheckOk) return ['status' => false, 'message' => 'Format tanggal pemeriksaan tidak valid'];


            // =============================
            // VALIDASI RELASI KE TANGGAL LAHIR HEWAN
            // Requirement: tgl_pemeriksaan tidak boleh < tgl_lahir hewan
            // + sesuai request: jika data IB ada, maka tgl_ib & tgl_perkiraan juga tidak boleh < tgl_lahir
            // =============================
            $idHewan = (int)$data['id_hewan'];
            $stmtTglLahir = $this->conn->prepare("SELECT tgl_lahir FROM data_ternak WHERE id_hewan = ? LIMIT 1");
            $stmtTglLahir->bind_param("i", $idHewan);
            $stmtTglLahir->execute();
            $resultTglLahir = $stmtTglLahir->get_result()->fetch_assoc();
            $stmtTglLahir->close();

            $tglLahir = !empty($resultTglLahir['tgl_lahir']) ? $resultTglLahir['tgl_lahir'] : null;

            if (!empty($tglLahir)) {
                if ($data['tgl_pemeriksaan'] < $tglLahir) {
                    return ['status' => false, 'message' => 'Tanggal pemeriksaan tidak boleh lebih awal dari tanggal lahir hewan'];
                }

                // Jika handler ikut mengirim data reproduksi (tgl_ib/tgl_perkiraan)
                $tglIb = $data['tgl_ib'] ?? null;
                $tglPerkiraan = $data['tgl_perkiraan'] ?? null;

                if (!empty($tglIb) && $tglIb < $tglLahir) {
                    return ['status' => false, 'message' => 'Tanggal IB tidak boleh lebih awal dari tanggal lahir hewan'];
                }

                if (!empty($tglPerkiraan) && $tglPerkiraan < $tglLahir) {
                    return ['status' => false, 'message' => 'Tanggal perkiraan lahir tidak boleh lebih awal dari tanggal lahir hewan'];
                }
            }


            // Validasi tanggal tidak masa depan
            if ($data['tgl_pemeriksaan'] > date('Y-m-d')) {
                return ['status' => false, 'message' => 'Tanggal pemeriksaan tidak boleh di masa depan'];
            }

            // Validasi conditional: diagnosis & tindakan wajib jika status bukan 'sehat'
            if ($data['status_kesehatan'] !== 'sehat') {

                if (empty(trim((string)($data['diagnosis'] ?? '')))) {
                    return ['status' => false, 'message' => 'Diagnosis wajib diisi untuk status ' . $data['status_kesehatan']];
                }
                if (empty(trim((string)($data['tindakan'] ?? '')))) {
                    return ['status' => false, 'message' => 'Tindakan wajib diisi untuk status ' . $data['status_kesehatan']];
                }
            }
            // Validasi enum

            $allowed_status = ['sehat', 'observasi', 'perawatan'];
            if (!in_array($data['status_kesehatan'], $allowed_status)) {
                return ['status' => false, 'message' => 'Status kesehatan tidak valid'];
            }

            // Validasi hewan masih aktif (is_deleted = 0)
            $stmt = $this->conn->prepare("SELECT id_hewan FROM data_ternak WHERE id_hewan = ? AND is_deleted = 0");
            $stmt->bind_param("i", $data['id_hewan']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                return ['status' => false, 'message' => 'Hewan tidak ditemukan atau sudah diarsipkan'];
            }

            // 3. Prepare INSERT
            $bind_id      = (int)$data['id_hewan'];
            $bind_tgl     = $data['tgl_pemeriksaan'];
            $bind_status  = $data['status_kesehatan'];
            $bind_diagn   = trim((string)($data['diagnosis'] ?? ''));
            $bind_tindak  = trim((string)($data['tindakan'] ?? ''));
            $bind_catatan = $data['catatan'] ?? '';

            $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " 
            (id_hewan, tgl_pemeriksaan, status_kesehatan, diagnosis, tindakan, catatan) 
            VALUES (?, ?, ?, ?, ?, ?)");

            if (!$stmt) {
                return ['status' => false, 'message' => 'Prepare failed: ' . $this->conn->error];
            }

            $stmt->bind_param("isssss", $bind_id, $bind_tgl, $bind_status, $bind_diagn, $bind_tindak, $bind_catatan);

            if ($stmt->execute()) {
                $stmt->close();
                return ['status' => true, 'message' => 'Data kesehatan berhasil ditambahkan'];
            }

            $error_msg = 'Gagal: ' . $stmt->error;
            $stmt->close();
            return ['status' => false, 'message' => $error_msg];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function update($id, $data)
    {
        try {
            $check = $this->getById($id);
            if (!$check) return ['status' => false, 'message' => 'Data tidak ditemukan'];
            $status = $data['status_kesehatan'] ?? $check['status_kesehatan'];

            // Cek apakah record kesehatan sudah di-soft delete
            if (!empty($check['is_deleted']) && $check['is_deleted'] == 1) {
                return ['status' => false, 'message' => 'Data telah diarsipkan dan tidak dapat diubah'];
            }

            // Validasi conditional sama seperti create()
            if (!empty($status) && $status !== 'sehat') {
                if (empty(trim((string)($data['diagnosis'] ?? '')))) {
                    return ['status' => false, 'message' => 'Diagnosis wajib diisi untuk status ' . $status];
                }
                if (empty(trim((string)($data['tindakan'] ?? '')))) {
                    return ['status' => false, 'message' => 'Tindakan wajib diisi untuk status ' . $status];
                }
            }
            // ✅ NORMALISASI STATUS: Jangan convert, langsung pakai value dari handler
            $status_produk = !empty($data['status_kesehatan']) ? $data['status_kesehatan'] : $check['status_kesehatan'];

            // ✅ Validasi enum sesuai DB
            $allowed_status = ['sehat', 'dalam_observasi', 'dalam_perawatan'];
            if (!in_array($status_produk, $allowed_status)) {
                $status_produk = 'sehat'; // Default aman
            }
            // Normalisasi status
            $status_normalized = match ($status) {
                'dalam_observasi' => 'observasi',  // Convert 'dalam_observasi' → 'observasi'
                'dalam_perawatan' => 'perawatan',  // Convert 'dalam_perawatan' → 'perawatan'
                default => $status
            };

            // ✅ Validasi sesuai enum database
            $allowed_status = ['sehat', 'observasi', 'perawatan'];
            if (!in_array($status_normalized, $allowed_status)) {
                return ['status' => false, 'message' => 'Status kesehatan tidak valid. Status yang diterima: ' . implode(', ', $allowed_status)];
            }

            $targetAnimalId = (int) ($data['id_hewan'] ?? $check['id_hewan']);
            if (!$this->isSupportedAnimal($targetAnimalId)) {
                return ['status' => false, 'message' => 'Data kesehatan hanya dapat digunakan untuk sapi'];
            }

            $bind_hewan = $targetAnimalId;
            $bind_tgl = $data['tgl_pemeriksaan'] ?? $check['tgl_pemeriksaan'];

            // =============================
            // VALIDASI RELASI KE TANGGAL LAHIR HEWAN (untuk update)
            // - tgl_pemeriksaan tidak boleh < tgl_lahir
            // - jika data IB ikut dikirim: tgl_ib & tgl_perkiraan juga tidak boleh < tgl_lahir
            // =============================
            $stmtTglLahir = $this->conn->prepare("SELECT tgl_lahir FROM data_ternak WHERE id_hewan = ? LIMIT 1");
            $stmtTglLahir->bind_param("i", $targetAnimalId);
            $stmtTglLahir->execute();
            $resultTglLahir = $stmtTglLahir->get_result()->fetch_assoc();
            $stmtTglLahir->close();

            $tglLahir = !empty($resultTglLahir['tgl_lahir']) ? $resultTglLahir['tgl_lahir'] : null;
            if (!empty($tglLahir)) {
                if ($bind_tgl < $tglLahir) {
                    return ['status' => false, 'message' => 'Tanggal pemeriksaan tidak boleh lebih awal dari tanggal lahir hewan'];
                }

                $tglIb = $data['tgl_ib'] ?? null;
                $tglPerkiraan = $data['tgl_perkiraan'] ?? null;
                if (!empty($tglIb) && $tglIb < $tglLahir) {
                    return ['status' => false, 'message' => 'Tanggal IB tidak boleh lebih awal dari tanggal lahir hewan'];
                }

                if (!empty($tglPerkiraan) && $tglPerkiraan < $tglLahir) {
                    return ['status' => false, 'message' => 'Tanggal perkiraan lahir tidak boleh lebih awal dari tanggal lahir hewan'];
                }
            }

            $bind_diagnosis = trim((string)($data['diagnosis'] ?? $check['diagnosis']));
            $bind_tindakan = trim((string)($data['tindakan'] ?? $check['tindakan']));
            $bind_catatan = $data['catatan'] ?? $check['catatan'];

            $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET 
            id_hewan = ?, 
            tgl_pemeriksaan = ?, 
            status_kesehatan = ?, 
            diagnosis = ?, 
            tindakan = ?, 
            catatan = ? 
            WHERE id_kesehatan = ?");

            if (!$stmt) {
                return ['status' => false, 'message' => 'Prepare failed: ' . $this->conn->error];
            }


            $stmt->bind_param(
                "isssssi",
                $bind_hewan,
                $bind_tgl,
                $status_normalized,
                $bind_diagnosis,
                $bind_tindakan,
                $bind_catatan,
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();
                return ['status' => true, 'message' => 'Data kesehatan berhasil diperbarui'];
            }

            $error_msg = 'Gagal: ' . $stmt->error;
            $stmt->close();
            return ['status' => false, 'message' => $error_msg];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("
            UPDATE " . $this->table . " 
            SET is_deleted = 1, deleted_at = NOW() 
            WHERE id_kesehatan = ?
        ");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Data berhasil diarsipkan'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function getAnimalsList()
    {
        $query = "SELECT id_hewan, kode_hewan, jenis_hewan, jenis_kelamin, no_kandang 
                  FROM data_ternak 
                  WHERE jenis_hewan IN ('sapi_perah', 'sapi_po')
                  AND (is_deleted IS NULL OR is_deleted = 0) 
                  ORDER BY kode_hewan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function isSupportedAnimal(int $id_hewan): bool
    {
        $query = "SELECT id_hewan 
                  FROM data_ternak 
                  WHERE id_hewan = ? AND jenis_hewan IN ('sapi_perah', 'sapi_po')
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_hewan);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
}
