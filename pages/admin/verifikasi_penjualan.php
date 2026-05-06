<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi_verifikasi'])) {
    $idTransaksi = (int) ($_POST['id_transaksi'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';

    if ($idTransaksi <= 0 || !in_array($aksi, ['verifikasi', 'tolak'], true)) {
        header('Location: verifikasi_penjualan.php?status=gagal');
        exit;
    }

    $status = $aksi === 'verifikasi' ? 'telah_dikonfirmasi' : 'dibatalkan';
    $stmt = mysqli_prepare($db, 'UPDATE transaksi SET status_transaksi = ? WHERE id_transaksi = ?');

    if (!$stmt) {
        header('Location: verifikasi_penjualan.php?status=gagal');
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'si', $status, $idTransaksi);

    if (!mysqli_stmt_execute($stmt)) {
        header('Location: verifikasi_penjualan.php?status=gagal');
        exit;
    }

    header('Location: verifikasi_penjualan.php?status=berhasil');
    exit;
}

function rupiah(float $nilai): string
{
    return 'Rp ' . number_format($nilai, 0, ',', '.');
}

function labelStatusTransaksi(string $status): string
{
    return match ($status) {
        'telah_dikonfirmasi' => 'Diverifikasi',
        'dibatalkan' => 'Ditolak',
        default => 'Menunggu',
    };
}

function classStatusTransaksi(string $status): string
{
    return match ($status) {
        'telah_dikonfirmasi' => 'ok',
        'dibatalkan' => 'no',
        default => 'wait',
    };
}

$queryTransaksi = mysqli_query(
    $db,
    "SELECT
        t.id_transaksi,
        t.tgl_transaksi,
        t.nama_pembeli,
        t.no_telp,
        t.alamat,
        t.metode_pembayaran,
        t.bukti_pembayaran,
        t.jumlah_pembelian,
        t.total_tagihan,
        t.status_transaksi,
        COALESCE(GROUP_CONCAT(dp.nama_produk SEPARATOR ', '), '-') AS produk
     FROM transaksi t
     LEFT JOIN detail_transaksi dt ON dt.id_transaksi = t.id_transaksi
     LEFT JOIN data_produk dp ON dp.id_produk = dt.id_produk
     GROUP BY t.id_transaksi
     ORDER BY t.tgl_transaksi DESC, t.id_transaksi DESC"
);

$dataTransaksi = [];
if ($queryTransaksi) {
    while ($row = mysqli_fetch_assoc($queryTransaksi)) {
        $dataTransaksi[] = $row;
    }
}

$totalMenunggu = count(array_filter($dataTransaksi, fn($row) => $row['status_transaksi'] === 'menunggu_verifikasi'));
$totalDiverifikasi = count(array_filter($dataTransaksi, fn($row) => $row['status_transaksi'] === 'telah_dikonfirmasi'));
$totalDitolak = count(array_filter($dataTransaksi, fn($row) => $row['status_transaksi'] === 'dibatalkan'));
$totalTerverifikasi = array_sum(array_map(
    fn($row) => $row['status_transaksi'] === 'telah_dikonfirmasi' ? (float) $row['total_tagihan'] : 0,
    $dataTransaksi
));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Verifikasi Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../../public/css/admin_verifikasiPenjualan.css?v=2">
</head>

<body>

<!-- SIDEBAR -->
<?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

<!-- MAIN -->
<div class="main-content">
<?php include __DIR__ . '/../../components/navbar_admin.php'; ?>



<!-- HEADER -->
<div class="section-header mt-3">
<h2>Verifikasi Penjualan</h2>
<p>Review & verifikasi pesanan sebelum dikonfirmasi</p>
</div>

<?php if (($_GET['status'] ?? '') === 'berhasil'): ?>
<div class="alert alert-success">Status transaksi berhasil diperbarui.</div>
<?php elseif (($_GET['status'] ?? '') === 'gagal'): ?>
<div class="alert alert-danger">Status transaksi gagal diperbarui.</div>
<?php endif; ?>

<!-- FILTER -->
<div class="filter-box">
<select>
    <option value="">Semua Bulan</option>
    <option>Januari</option>
    <option>Februari</option>
    <option>Maret</option>
    <option>April</option>
    <option>Mei</option>
    <option>Juni</option>
    <option>Juli</option>
    <option>Agustus</option>
    <option>September</option>
    <option>Oktober</option>
    <option>November</option>
    <option>Desember</option>
</select>
<select>
    <option value="">Semua Status</option>
    <option>Menunggu</option>
    <option>Diverifikasi</option>
    <option>Ditolak</option>
</select>
<select>
    <option value="">Semua Metode</option>
    <option>Transfer</option>
    <option>COD</option>
</select>
</div>

<!-- STATS -->
<div class="stats">
<div class="stat-card"><h4>Menunggu</h4><h2><?= $totalMenunggu ?> Pesanan</h2></div>
<div class="stat-card"><h4>Diverifikasi</h4><h2><?= $totalDiverifikasi ?> Pesanan</h2></div>
<div class="stat-card"><h4>Ditolak</h4><h2><?= $totalDitolak ?> Pesanan</h2></div>
<div class="stat-card"><h4>Total Terverifikasi</h4><h2><?= rupiah($totalTerverifikasi) ?></h2></div>
</div>

<!-- TABLE -->
<div class="table-box">
<table>
<thead>
<tr>
<th>ID</th>
<th>Tanggal</th>
<th>Pelanggan</th>
<th>Produk</th>
<th>Jumlah</th>
<th>Total</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody id="verifikasiTableBody">
<?php if (count($dataTransaksi) === 0): ?>
<tr><td colspan="8" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
<?php endif; ?>
<?php foreach ($dataTransaksi as $row): ?>
<?php
$kodePesanan = '#ORD-' . str_pad((string) $row['id_transaksi'], 3, '0', STR_PAD_LEFT);
$buktiPembayaran = trim((string) ($row['bukti_pembayaran'] ?? ''));
$buktiUrl = $buktiPembayaran !== '' ? '../../public/uploads/bukti_pembayaran/' . rawurlencode($buktiPembayaran) : '';
$detailPesanan = [
    'orderId' => $kodePesanan,
    'customer' => $row['nama_pembeli'],
    'phone' => $row['no_telp'] ?: '-',
    'address' => $row['alamat'] ?: '-',
    'method' => strtoupper((string) $row['metode_pembayaran']),
    'total' => rupiah((float) $row['total_tagihan']),
    'proofName' => $buktiPembayaran ?: 'Bukti pembayaran belum tersedia',
    'proofUrl' => $buktiUrl,
];
?>
<tr>
<td><?= htmlspecialchars($kodePesanan) ?></td>
<td><?= htmlspecialchars(date('d M Y', strtotime($row['tgl_transaksi']))) ?></td>
<td><?= htmlspecialchars($row['nama_pembeli']) ?></td>
<td><?= htmlspecialchars($row['produk']) ?></td>
<td><?= htmlspecialchars((string) $row['jumlah_pembelian']) ?></td>
<td><?= rupiah((float) $row['total_tagihan']) ?></td>
<td><span class="status <?= classStatusTransaksi($row['status_transaksi']) ?>"><?= labelStatusTransaksi($row['status_transaksi']) ?></span></td>
<td>
<?php if ($row['status_transaksi'] === 'menunggu_verifikasi'): ?>
    <form id="verifyForm<?= (int) $row['id_transaksi'] ?>" action="verifikasi_penjualan.php" method="POST" style="display:inline;">
        <input type="hidden" name="id_transaksi" value="<?= (int) $row['id_transaksi'] ?>">
        <input type="hidden" name="aksi" id="verifyAction<?= (int) $row['id_transaksi'] ?>" value="verifikasi">
        <input type="hidden" name="aksi_verifikasi" value="1">
        <button
            class="btn-verif"
            type="button"
            data-order='<?= htmlspecialchars(json_encode($detailPesanan), ENT_QUOTES) ?>'
            onclick="openPendingFromButton(this, 'verifyForm<?= (int) $row['id_transaksi'] ?>', 'verifyAction<?= (int) $row['id_transaksi'] ?>')"
        >Verifikasi</button>
    </form>
<?php elseif ($row['status_transaksi'] === 'telah_dikonfirmasi'): ?>
    <button class="eye" type="button" data-order='<?= htmlspecialchars(json_encode($detailPesanan), ENT_QUOTES) ?>' onclick="openVerifiedFromButton(this)"><i class="fa fa-eye"></i></button>
<?php else: ?>
    <button class="eye" type="button" data-order='<?= htmlspecialchars(json_encode($detailPesanan), ENT_QUOTES) ?>' onclick="openRejectedFromButton(this)"><i class="fa fa-eye"></i></button>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="pagination">
    <span>Menampilkan 1-<?= count($dataTransaksi) ?> dari <?= count($dataTransaksi) ?> data</span>
    <div>
        <button class="page-btn">Sebelumnya</button>
        <button class="page-btn active-page">1</button>
        <button class="page-btn">Selanjutnya</button>
    </div>
</div>

</div>

</div>

<div class="sales-modal-overlay" id="salesModal" onclick="closeSalesModalOutside(event)">
    <div class="sales-modal-card">
        <div class="sales-modal-body">
            <div class="sales-modal-header">
                <div>
                    <h2 class="sales-modal-title">Detail Pesanan</h2>
                    <p class="sales-modal-subtitle" id="salesSubtitle">Review pesanan pelanggan</p>
                </div>
                <button class="sales-modal-close" type="button" onclick="closeSalesModal()">&times;</button>
            </div>

            <div class="sales-order-id" id="salesOrderId">#ORD-2026-001</div>
            <div class="sales-status-badge waiting" id="salesStatusText">Menunggu Verifikasi</div>

            <div class="sales-section-title">Informasi Pelanggan</div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-user"></i></div>
                <div><span class="sales-label">Nama Lengkap</span><span class="sales-value" id="salesCustomer">Ahmad Ridwan</span></div>
            </div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-envelope"></i></div>
                <div><span class="sales-label">Email</span><span class="sales-value" id="salesEmail">ahmad.ridwan@example.com</span></div>
            </div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-phone"></i></div>
                <div><span class="sales-label">Nomor Telepon</span><span class="sales-value" id="salesPhone">08123456789</span></div>
            </div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                <div><span class="sales-label">Alamat Pengiriman</span><span class="sales-value" id="salesAddress">Cianjur, Jawa Barat</span></div>
            </div>

            <div class="sales-section-title" id="proofTitle">Bukti Transfer</div>
            <div class="sales-proof-card" id="salesProof" onclick="openSalesLightbox('https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=80&w=900')">
                <img src="https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=80&w=120" alt="Bukti transfer">
                <div>
                    <span class="sales-value">Bukti_Transfer.jpg</span>
                    <span class="sales-label">Klik untuk memperbesar</span>
                </div>
            </div>

            <div class="sales-section-title">Ringkasan Pembayaran</div>
            <div class="sales-summary">
                <div class="sales-summary-row">
                    <div class="sales-value"><i class="fas fa-credit-card"></i> Metode Pembayaran</div>
                    <div class="sales-value" id="salesPaymentMethod">Transfer Bank</div>
                </div>
                <div class="sales-summary-row sales-total-row">
                    <span class="sales-total-label">Total</span>
                    <span class="sales-total" id="salesTotal">Rp 15.250.000</span>
                </div>
            </div>

            <div class="sales-actions" id="salesActions"></div>
        </div>
    </div>
</div>

<div class="sales-lightbox" id="salesLightbox" onclick="closeSalesLightbox()">
    <img id="salesLightboxImage" src="" alt="Bukti transfer">
</div>

<script src="../../public/js/verifikasiPenjualan_admin.js?v=2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    setupAdminPagination('#verifikasiTableBody', '.table-box .pagination', 5);
});
</script>


</body>
</html>
