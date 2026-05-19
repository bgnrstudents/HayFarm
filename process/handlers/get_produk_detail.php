<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';

$id_produk = filter_input(INPUT_GET, 'id_produk', FILTER_VALIDATE_INT);

if (!$id_produk) {
    echo json_encode(['status' => false, 'message' => 'ID Produk tidak valid']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $mapStatusLabel = static function (?string $status): string {
        return match ($status) {
            'sehat' => 'Sehat',
            'observasi' => 'Dalam Observasi',
            'perawatan' => 'Dalam Perawatan',
            default => '-'
        };
    };

    // Tetap ambil status terbaru untuk ringkasan/detail utama produk.
    $query = "SELECT
                p.*,
                t.kode_hewan,
                t.jenis_hewan,
                kh.status_kesehatan AS status_kesehatan_terakhir,
                kh.tgl_pemeriksaan AS tgl_pemeriksaan_terakhir,
                kh.diagnosis AS diagnosis_terakhir,
                kh.tindakan AS tindakan_terakhir,
                kh.catatan AS catatan_terakhir
              FROM data_produk p
              LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan
              LEFT JOIN data_kesehatan kh ON kh.id_kesehatan = (
                  SELECT k.id_kesehatan
                  FROM data_kesehatan k
                  WHERE k.id_hewan = p.id_hewan
                  ORDER BY k.tgl_pemeriksaan DESC, k.id_kesehatan DESC
                  LIMIT 1
              )
              WHERE p.id_produk = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => false, 'message' => 'Produk tidak ditemukan']);
        exit;
    }

    $row = $result->fetch_assoc();

    $kesehatan = 'Sehat';
    if (!empty($row['status_kesehatan_terakhir'])) {
        $kesehatan = $mapStatusLabel($row['status_kesehatan_terakhir']);
    }

    $tgl_pemeriksaan = '-';
    if (!empty($row['tgl_pemeriksaan_terakhir'])) {
        $tgl_pemeriksaan = date('d M Y', strtotime($row['tgl_pemeriksaan_terakhir']));
    }

    $riwayat_kesehatan = [];
    $catatan_medis = trim((string) ($row['catatan_terakhir'] ?? '')) !== '' ? $row['catatan_terakhir'] : '-';

    if (!empty($row['id_hewan'])) {
        $historyQuery = "SELECT
                            id_kesehatan,
                            tgl_pemeriksaan,
                            status_kesehatan,
                            diagnosis,
                            tindakan,
                            catatan
                         FROM data_kesehatan
                         WHERE id_hewan = ?
                           AND (is_deleted IS NULL OR is_deleted = 0)
                         ORDER BY tgl_pemeriksaan DESC, id_kesehatan DESC";

        $historyStmt = $db->prepare($historyQuery);

        if ($historyStmt) {
            $historyStmt->bind_param("i", $row['id_hewan']);
            $historyStmt->execute();
            $historyResult = $historyStmt->get_result();

            while ($historyRow = $historyResult->fetch_assoc()) {
                $catatan = trim((string) ($historyRow['catatan'] ?? '')) !== '' ? $historyRow['catatan'] : '-';

                if ($catatan_medis === '-' && $catatan !== '-') {
                    $catatan_medis = $catatan;
                }

                $riwayat_kesehatan[] = [
                    'id_kesehatan' => (int) $historyRow['id_kesehatan'],
                    'tgl_pemeriksaan' => $historyRow['tgl_pemeriksaan'],
                    'tgl_formatted' => !empty($historyRow['tgl_pemeriksaan'])
                        ? date('d M Y', strtotime($historyRow['tgl_pemeriksaan']))
                        : '-',
                    'status_kesehatan' => $historyRow['status_kesehatan'],
                    'status_label' => $mapStatusLabel($historyRow['status_kesehatan']),
                    'diagnosis' => trim((string) ($historyRow['diagnosis'] ?? '')) !== '' ? $historyRow['diagnosis'] : '-',
                    'tindakan' => trim((string) ($historyRow['tindakan'] ?? '')) !== '' ? $historyRow['tindakan'] : '-',
                    'catatan' => $catatan,
                    'petugas' => '-',
                ];
            }

            $historyStmt->close();
        }
    }

    echo json_encode([
        'status' => true,
        'data' => [
            'id_produk' => $row['id_produk'],
            'nama_produk' => $row['nama_produk'],
            'jenis_produk' => $row['jenis_produk'],
            'harga' => $row['harga'],
            'stok' => $row['stok'],
            'kesehatan' => $kesehatan,
            'tgl_pemeriksaan' => $tgl_pemeriksaan,
            'diagnosis' => trim((string) ($row['diagnosis_terakhir'] ?? '')) !== '' ? $row['diagnosis_terakhir'] : '-',
            'tindakan' => trim((string) ($row['tindakan_terakhir'] ?? '')) !== '' ? $row['tindakan_terakhir'] : '-',
            'catatan' => $catatan_medis,
            'kode_hewan' => $row['kode_hewan'] ?? '-',
            'jenis_hewan' => $row['jenis_hewan'] ?? '-',
            'riwayat_kesehatan' => $riwayat_kesehatan,
        ]
    ]);
} catch (Exception $e) {
    error_log("Error in get_produk_detail.php: " . $e->getMessage());
    echo json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
