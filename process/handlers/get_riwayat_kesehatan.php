<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$id_hewan = filter_input(INPUT_GET, 'id_hewan', FILTER_VALIDATE_INT);
if (!$id_hewan) {
    echo json_encode(['status' => false, 'message' => 'ID hewan tidak valid']);
    exit;
}

$mapStatusLabel = static function (?string $status): string {
    return match ($status) {
        'sehat' => 'Sehat',
        'observasi' => 'Dalam Observasi',
        'perawatan' => 'Dalam Perawatan',
        default => '-'
    };
};

$stmt = $conn->prepare("
    SELECT id_kesehatan, tgl_pemeriksaan, status_kesehatan, diagnosis, tindakan, catatan
    FROM data_kesehatan
    WHERE id_hewan = ?
      AND (is_deleted IS NULL OR is_deleted = 0)
    ORDER BY tgl_pemeriksaan DESC, id_kesehatan DESC
");
$stmt->bind_param('i', $id_hewan);
$stmt->execute();
$result = $stmt->get_result();

$riwayat = [];
while ($row = $result->fetch_assoc()) {
    $riwayat[] = [
        'id_kesehatan' => (int) $row['id_kesehatan'],
        'tgl_pemeriksaan' => $row['tgl_pemeriksaan'],
        'tgl_formatted' => !empty($row['tgl_pemeriksaan'])
            ? date('d M Y', strtotime($row['tgl_pemeriksaan']))
            : '-',
        'status_kesehatan' => $row['status_kesehatan'],
        'status_label' => $mapStatusLabel($row['status_kesehatan']),
        'diagnosis' => trim((string) ($row['diagnosis'] ?? '')) !== '' ? $row['diagnosis'] : '-',
        'tindakan' => trim((string) ($row['tindakan'] ?? '')) !== '' ? $row['tindakan'] : '-',
        'catatan' => trim((string) ($row['catatan'] ?? '')) !== '' ? $row['catatan'] : '-',
        'petugas' => '-',
    ];
}

$latest = $riwayat[0] ?? null;

echo json_encode([
    'status' => true,
    'data' => $latest,
    'riwayat' => $riwayat,
]);
