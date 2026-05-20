<?php
session_start();

if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/database.php';
require_once '../../process/models/dashboard_admin.php';

$database = new Database();
$db = $database->getConnection();
$dashboard = new Dashboard($db);

// === AMBIL DATA DENGAN OOP ===
$stats = $dashboard->getMainStats();
$grafik = $dashboard->getMonthlyVerifiedTransactions($_GET['tahun'] ?? (int)date('Y'));
$years = $dashboard->getAvailableYears();

// Extract ke variabel untuk view (biar kode HTML tetap clean)
$jumlahProduk = $stats['jumlah_produk'];
$berhasilDiverifikasi = $stats['diverifikasi'];
$jumlahHewan = $stats['jumlah_hewan'];
$hewanSakit = $stats['hewan_sakit_hari_ini'];

$tahunGrafik = $_GET['tahun'] ?? (int)date('Y');
$tahunOptions = $years;
$bulanLabels = $grafik['labels'];
$penjualanBulanan = $grafik['values'];

$vaksinasiDiperlukan = $dashboard->getVaccinationNeededCount();
$produkKedaluwarsa = $dashboard->getExpiredProductsCount();
$hewanHamilBulanIni = $dashboard->getPregnantAnimalsThisMonth();
$menungguVerifikasi = $dashboard->getPendingVerificationCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="../../public/css/admin_dashboard.css?v=3">
</head>
<body>

<?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

<!-- MAIN -->
<div class="main-content">
    <?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

    <!-- TITLE -->
    <h4>Dashboard</h4>
    <p>Selamat datang di panel kontrol administrasi peternakan</p>

    <!-- NOTIFICATIONS -->
    <div class="notification-container" id="dashboardNotifications">
        <!-- <h2 class="notification-title">Notifikasi</h2> -->
        <div class="notification-grid">
            <!-- Card 1 - Vaksinasi -->
            <a class="notification-card card-vaksinasi" href="data_kesehatan.php">
                <div>
                    <div class="card-title">
                        <span>Vaksinasi diperlukan segera</span>
                        <span class="icon-small"></span>
                    </div>
                    <div class="card-description">Hewan perlu vaksinasi segera</div>
                </div>
                <div class="card-number">
                    <div class="card-number-value"><?= $vaksinasiDiperlukan ?></div>
                    <div class="card-number-label">Hewan</div>
                </div>
                <div class="card-arrow">›</div>
            </a>

            <!-- Card 2 - Produk Kedaluwarsa -->
            <a class="notification-card card-expired" href="manajemen_produk.php">
                <div>
                    <div class="card-title">
                        <span>Produk Kedaluwarsa</span>
                        <span class="icon-alert"></span>
                    </div>
                    <div class="card-description">Perlu cek inventaris</div>
                </div>
                <div class="card-number">
                    <div class="card-number-value text-red"><?= $produkKedaluwarsa ?></div>
                    <div class="card-number-label">Produk</div>
                </div>
                <div class="card-arrow">›</div>
            </a>

            <!-- Card 3 - Hamil -->
            <a class="notification-card card-birth" href="data_kesehatan.php">
                <div>
                    <div class="card-title">
                        <span>Hewan hamil</span>
                        <span class="icon-alert"></span>
                    </div>
                    <div class="card-description">Status IB berhasil dari data kesehatan</div>
                </div>
                <div class="card-number">
                    <div class="card-number-value text-green"><?= $hewanHamilBulanIni ?></div>
                    <div class="card-number-label">Hewan</div>
                </div>
                <div class="card-arrow">›</div>
            </a>

            <!-- Card 4 - Perlu Verifikasi -->
            <a class="notification-card card-verification" href="verifikasi_penjualan.php">
                <div>
                    <div class="card-title">
                        <span>Perlu verifikasi</span>
                        <span class="icon-alert">✓</span>
                    </div>
                    <div class="card-description">Menunggu konfirmasi admin</div>
                </div>
                <div class="card-number">
                    <div class="card-number-value text-orange"><?= $menungguVerifikasi ?></div>
                    <div class="card-number-label">Orang</div>
                </div>
                <div class="card-arrow">›</div>
            </a>
        </div>
    </div>


    <!-- STATS CARDS -->
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Jumlah Produk</h6>
                        <h2 id="produk"><?= $jumlahProduk ?></h2>
                        <small class="text-muted">Dari manajemen produk</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Jumlah Diverifikasi</h6>
                        <h2><?= $berhasilDiverifikasi ?></h2>
                        <small class="text-muted">Masuk grafik transaksi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Jumlah Hewan</h6>
                        <h2 id="hewan"><?= $jumlahHewan ?></h2>
                        <small class="text-muted">Dari tabel data ternak</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Hewan Sakit per Hari</h6>
                        <h2 id="sakit"><?= $hewanSakit ?></h2>
                        <small class="text-muted">Status bukan sehat hari ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <div>
                <h3 style="margin:0;">Grafik Transaksi Terverifikasi</h3>
                <small class="text-muted">Titik menunjukkan jumlah transaksi yang berhasil diverifikasi per bulan pada tahun <?= $tahunGrafik ?></small>
            </div>
            <form method="GET" style="margin:0;">
                <select name="tahun" onchange="this.form.submit()" style="padding:5px 10px;border-radius:6px;border:1px solid #ccc;">
                    <?php foreach ($tahunOptions as $tahun): ?>
                        <option value="<?= $tahun ?>" <?= $tahun === $tahunGrafik ? 'selected' : '' ?>><?= $tahun ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div id="chart"></div>
    </div>
</div>

<script>
window.dashboardSalesData = {
    labels: <?= json_encode($bulanLabels) ?>,
    values: <?= json_encode($penjualanBulanan) ?>
};
</script>
<script src="../../public/js/dashboard_admin.js?v=3"></script>
</body>
</html>
