<?php
// process/handlers/get_detail_transaksi.php
header('Content-Type: application/json');

require_once '../../config/database.php';
require_once '../../process/models/transaksi.php';

// Security check
session_start();
if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized']);
    exit;
}

$id_transaksi = filter_input(INPUT_GET, 'id_transaksi', FILTER_VALIDATE_INT);
if (!$id_transaksi) {
    echo json_encode(['status' => false, 'message' => 'ID Transaksi tidak valid']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$transaksiModel = new Transaksi($db);

$details = $transaksiModel->getDetailTransaksiLengkap($id_transaksi);

if (empty($details)) {
    echo json_encode(['status' => false, 'message' => 'Transaksi tidak ditemukan']);
    exit;
}

// Format response
$firstRow = $details[0];
$products = [];
foreach ($details as $row) {
    $products[] = [
        'nama_produk' => $row['nama_produk'],
        'id_hewan'    => $row['id_hewan'] ?? null,
        'kode_hewan'  => $row['kode_hewan'] ?? null,
        'jenis_produk' => $row['jenis_produk'],
        'satuan'      => $row['satuan'],
        'jumlah'      => (int) $row['jumlah'],
        'harga'       => (float) $row['harga'],
        'sub_total'   => (float) $row['sub_total']
    ];
}

echo json_encode([
    'status' => true,
    'data' => [
        'email' => $firstRow['email'] ?? '-',
        'username' => $firstRow['username'] ?? '-',
        'products' => $products,
        'total_tagihan' => (float) $firstRow['total_tagihan']
    ]
]);
