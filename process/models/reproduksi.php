<?php
class Reproduksi
{
    private $conn;
    private $table = 'data_reproduksi';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY tgl_ib DESC, id_reproduksi DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Create
    public function create($data)
    {
        try {
            // ✅ FIX 1: Validasi field yang BENAR ada di tabel reproduksi
            // Wajib ada: id_kesehatan, id_hewan, tgl_ib
            if (empty($data['id_kesehatan']) || empty($data['id_hewan']) || empty($data['tgl_ib'])) {
                return ['status' => false, 'message' => 'Data reproduksi tidak lengkap'];
            }

            // ✅ FIX 2: Assign ke variable local dulu (HARUS variable biasa agar bisa di-reference)
            $bind_id_kesehatan = (int)$data['id_kesehatan'];
            $bind_id_hewan     = (int)$data['id_hewan'];
            $bind_tgl_ib       = $data['tgl_ib'];
            $bind_ib_ke        = (int)($data['ib_ke'] ?? 0); // Default 0 jika kosong

            // Handle nullable fields (jika kosong, jadi NULL)
            $bind_tgl_perkiraan = (!empty($data['tgl_perkiraan'])) ? $data['tgl_perkiraan'] : null;
            $bind_status_ib     = (!empty($data['status_ib'])) ? $data['status_ib'] : null;

            $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " 
                (id_kesehatan, id_hewan, tgl_ib, ib_ke, tgl_perkiraan, status_ib) 
                VALUES (?, ?, ?, ?, ?, ?)");

            if (!$stmt) {
                return ['status' => false, 'message' => 'Prepare failed: ' . $this->conn->error];
            }

            // ✅ FIX 3: Bind param pakai variable local
            // Tipe: i(int), i(int), s(string), i(int), s(string), s(string)
            // ib_ke itu INT di database, jadi type-nya 'i'
            $stmt->bind_param(
                "iisiss",
                $bind_id_kesehatan,
                $bind_id_hewan,
                $bind_tgl_ib,
                $bind_ib_ke,
                $bind_tgl_perkiraan,
                $bind_status_ib
            );

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Data reproduksi berhasil ditambahkan'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Tambah method ini di class Reproduksi
    public function update($id_kesehatan, $data)
    {
        try {
            // Validasi minimal
            if (empty($data['id_hewan']) || empty($data['tgl_ib'])) {
                return ['status' => false, 'message' => 'Data reproduksi tidak lengkap'];
            }

            // Validasi enum status_ib
            $allowed_status = ['berhasil', 'tdk_berhasil', 'proses'];
            if (!empty($data['status_ib']) && !in_array($data['status_ib'], $allowed_status)) {
                return ['status' => false, 'message' => 'Status IB tidak valid'];
            }

            // ✅ Assign ke variable local untuk bind_param (reference requirement)
            $bind_id_hewan = (int)$data['id_hewan'];
            $bind_tgl_ib = $data['tgl_ib'];
            $bind_ib_ke = (int)($data['ib_ke'] ?? 0);
            $bind_tgl_perkiraan = (!empty($data['tgl_perkiraan'])) ? $data['tgl_perkiraan'] : null;
            $bind_status_ib = (!empty($data['status_ib'])) ? $data['status_ib'] : null;

            $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET 
            id_hewan = ?, 
            tgl_ib = ?, 
            ib_ke = ?, 
            tgl_perkiraan = ?, 
            status_ib = ? 
            WHERE id_kesehatan = ?");

            if (!$stmt) {
                return ['status' => false, 'message' => 'Prepare failed: ' . $this->conn->error];
            }

            // ✅ bind_param: i(int), s(string), i(int), s(string), s(string), i(int)
            $stmt->bind_param(
                "isissi",
                $bind_id_hewan,      // i
                $bind_tgl_ib,        // s
                $bind_ib_ke,         // i
                $bind_tgl_perkiraan, // s
                $bind_status_ib,     // s
                $id_kesehatan        // i (WHERE clause)
            );

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Data reproduksi berhasil diperbarui'];
            }
            return ['status' => false, 'message' => 'Gagal: ' . $stmt->error];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    // Read by ID Hewan
    public function getByHewanId($id_hewan)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_hewan = ? ORDER BY tgl_ib DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_hewan);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Read by ID Kesehatan
    public function getByKesehatanId($id_kesehatan)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_kesehatan = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_kesehatan);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
