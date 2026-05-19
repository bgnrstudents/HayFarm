<?php
require 'vendor/autoload.php';
require 'config/database.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$database = new Database();
$db = $database->getConnection();

$status = $_GET['status'] ?? '';
$jenis  = $_GET['jenis'] ?? '';

// Normalisasi parameter dari UI:
// - UI kirim status: “Tersedia” / “Tidak Tersedia”
// - export filter butuh: “tersedia” / “tidak_tersedia”
$normalizedStatus = $status;
if ($status === 'Tersedia' || strtolower($status) === 'tersedia') {
    $normalizedStatus = 'tersedia';
} elseif ($status === 'Tidak Tersedia' || strtolower(str_replace(' ', '_', $status)) === 'tidak_tersedia') {
    $normalizedStatus = 'tidak_tersedia';
}

$where = "WHERE 1=1";

if (!empty($normalizedStatus)) {
    if ($normalizedStatus === 'tersedia') {
        $where .= " AND status_produk = 'blm_terjual'";
    } elseif ($normalizedStatus === 'tidak_tersedia') {
        $where .= " AND status_produk = 'terjual'";
    }
}

// (Filter jenis sudah diproses via parameter $jenis)


if (!empty($jenis)) {
    $where .= " AND jenis_produk = '" . $db->real_escape_string($jenis) . "'";
}

$query = "
SELECT 
    id_produk,
    jenis_produk,
    nama_produk,
    harga,
    stok,
    satuan,
    status_produk,
    tgl_kadaluarsa
FROM data_produk
$where
ORDER BY id_produk DESC
";

$result = $db->query($query);

$data = [];
$totalPendapatan = 0;

while ($row = $result->fetch_assoc()) {

    $harga = (float)$row['harga'];
    $stok  = (int)$row['stok'];

    // TOTAL PENDAPATAN
    $totalPendapatan += ($harga * $stok);

    // Kadaluarsa hanya susu
    $expired = '-';
    if ($row['jenis_produk'] === 'susu' && !empty($row['tgl_kadaluarsa'])) {
        $expired = date('d/m/Y', strtotime($row['tgl_kadaluarsa']));
    }

    $data[] = [
        'id' => $row['id_produk'],
        'jenis' => ucfirst($row['jenis_produk']),
        'nama' => $row['nama_produk'],
        'stok' => $stok,
        'satuan' => ucfirst($row['satuan']),
        'status' => $row['status_produk'] === 'blm_terjual'
            ? 'Tersedia'
            : 'Tidak Tersedia',
        'expired' => $expired,
        'harga' => $harga
    ];
}

$totalProduk = count($data);

$dateNow = date('d/m/Y H:i:s');

$html = '
<style>
    body{
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
    }

    .header{
        width:100%;
        margin-bottom:20px;
    }

    .header-table{
        width:100%;
    }

    .title{
        font-size:20px;
        font-weight:bold;
        color:#198754;
    }

    .subtitle{
        font-size:12px;
        color:#666;
    }

    .info-left{
        width:60%;
        vertical-align:top;
    }

    .info-right{
        width:40%;
        vertical-align:top;
        text-align:right;
    }

    .summary-box{
        border:1px solid #198754;
        padding:10px;
        border-radius:6px;
        display:inline-block;
        text-align:left;
    }

    .summary-box p{
        margin:4px 0;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        background:#198754;
        color:white;
        padding:10px;
        border:1px solid #ddd;
        text-align:center;
    }

    td{
        padding:8px;
        border:1px solid #ddd;
    }

    .text-center{
        text-align:center;
    }

    .text-right{
        text-align:right;
    }

    .footer{
        margin-top:25px;
        text-align:center;
        font-size:11px;
        color:#777;
    }
</style>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="info-left">
                <div class="title">Laporan Data Produk Hay Farm</div>
                <div class="subtitle">TEFA Produksi Ternak Polije</div>

                <br>

                <table>
                    <tr>
                        <td width="120"><strong>Tanggal Cetak</strong></td>
                        <td>: ' . $dateNow . '</td>
                    </tr>
<tr>
                        <td><strong>Filter Status</strong></td>
                        <td>: ' . (!empty($normalizedStatus) ? ucfirst(str_replace('_', ' ', $normalizedStatus)) : 'Semua') . '</td>
                    </tr>
                    <tr>
                        <td><strong>Filter Jenis</strong></td>
                        <td>: ' . (!empty($jenis) ? ucfirst($jenis) : 'Semua') . '</td>
                    </tr>
                </table>
            </td>

            <td class="info-right">
                <div class="summary-box">
                    <p><strong>Total Produk</strong></p>
                    <p>' . $totalProduk . ' Item</p>

                    <br>

                    <p><strong>Total Pendapatan</strong></p>
                    <p>Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</p>
                </div>
            </td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%">ID</th>
            <th width="12%">Jenis</th>
            <th width="25%">Nama Produk</th>
            <th width="10%">Stok</th>
            <th width="10%">Satuan</th>
            <th width="13%">Status</th>
            <th width="15%">Kadaluarsa</th>
            <th width="15%">Harga</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;

foreach ($data as $item) {

    $html .= '
    <tr>
        <td class="text-center">' . $no++ . '</td>
        <td class="text-center">PROD-' . str_pad($item['id'], 3, '0', STR_PAD_LEFT) . '</td>
        <td class="text-center">' . $item['jenis'] . '</td>
        <td>' . htmlspecialchars($item['nama']) . '</td>
        <td class="text-center">' . $item['stok'] . '</td>
        <td class="text-center">' . $item['satuan'] . '</td>
        <td class="text-center">' . $item['status'] . '</td>
        <td class="text-center">' . $item['expired'] . '</td>
        <td class="text-right">Rp ' . number_format($item['harga'], 0, ',', '.') . '</td>
    </tr>';
}

$html .= '
    </tbody>
</table>

<div class="footer">
    Generated by Hay Farm System | Data Bersifat Internal TEFA Produksi Ternak
</div>
';

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream(
    'Laporan_Produk_HayFarm_' . date('Y-m-d') . '.pdf',
    ['Attachment' => false]
);
?>