<?php
require_once __DIR__ . '/../../config/database.php';

function nilaiScalar(mysqli $db, string $sql): int
{
    $result = mysqli_query($db, $sql);
    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_row($result);
    return (int) ($row[0] ?? 0);
}

$jumlahHewan = nilaiScalar($db, 'SELECT COUNT(*) FROM data_ternak');
$hewanSakit = nilaiScalar($db, "SELECT COUNT(*) FROM data_kesehatan WHERE tgl_pemeriksaan = CURDATE() AND status_kesehatan <> 'sehat'");
$jumlahProduk = nilaiScalar($db, 'SELECT COUNT(*) FROM data_produk')
    + nilaiScalar($db, "SELECT COUNT(*) FROM data_ternak WHERE status_hewan = 'tdk_produktif'");
$berhasilDiverifikasi = nilaiScalar($db, "SELECT COUNT(*) FROM transaksi WHERE status_transaksi = 'telah_dikonfirmasi'");
$menungguVerifikasi = nilaiScalar($db, "SELECT COUNT(*) FROM transaksi WHERE status_transaksi = 'menunggu_verifikasi'");
$produkKedaluwarsa = nilaiScalar(
    $db,
    "SELECT COUNT(*)
     FROM data_produk
     WHERE jenis_produk = 'susu'
       AND tgl_kadaluarsa <> '0000-00-00'
       AND tgl_kadaluarsa <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)"
);
$hewanHamilBulanIni = nilaiScalar(
    $db,
    "SELECT COUNT(*)
     FROM data_reproduksi
     WHERE status_ib = 'berhasil'
       AND YEAR(tgl_ib) = YEAR(CURDATE())
       AND MONTH(tgl_ib) = MONTH(CURDATE())"
);
$vaksinasiDiperlukan = nilaiScalar(
    $db,
    "SELECT COUNT(*)
     FROM data_kesehatan
     WHERE tgl_pemeriksaan <= CURDATE()
       AND tindakan LIKE '%vaksin%'"
);

$tahunSekarang = (int) date('Y');
$tahunGrafik = (int) ($_GET['tahun'] ?? $tahunSekarang);
$tahunOptions = [$tahunSekarang];
$queryTahun = mysqli_query($db, "SELECT DISTINCT YEAR(tgl_transaksi) AS tahun FROM transaksi ORDER BY tahun DESC");
if ($queryTahun) {
    while ($row = mysqli_fetch_assoc($queryTahun)) {
        $tahun = (int) $row['tahun'];
        if ($tahun > 0) {
            $tahunOptions[] = $tahun;
        }
    }
}
$tahunOptions = array_values(array_unique($tahunOptions));
rsort($tahunOptions);
if (!in_array($tahunGrafik, $tahunOptions, true)) {
    $tahunGrafik = $tahunSekarang;
}

$queryGrafik = mysqli_query(
    $db,
    "SELECT MONTH(tgl_transaksi) AS bulan, COUNT(*) AS total
     FROM transaksi
     WHERE status_transaksi = 'telah_dikonfirmasi' AND YEAR(tgl_transaksi) = $tahunGrafik
     GROUP BY MONTH(tgl_transaksi)
     ORDER BY bulan ASC"
);

$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];
$bulanLabels = array_values($namaBulan);
$penjualanBulanan = array_fill(0, 12, 0);
if ($queryGrafik) {
    while ($row = mysqli_fetch_assoc($queryGrafik)) {
        $bulanIndex = (int) $row['bulan'] - 1;
        if ($bulanIndex >= 0 && $bulanIndex < 12) {
            $penjualanBulanan[$bulanIndex] = (int) $row['total'];
        }
    }
}
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
 <link rel="stylesheet" href="../../public/css/admin_dashboard.css?v=2">  
</head>
<body>

<?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

<!-- MAIN -->
<div class="main-content">

    <!-- TOPBAR -->
    <?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

    <!-- TITLE -->
    <h4>Dashboard</h4>
    <p>Selamat datang di panel kontrol administrasi peternakan</p>
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

<!-- Notifikasi -->
<div class="notification-container" id="dashboardNotifications">
  <h2 class="notification-title">Notifikasi</h2>
  
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
        <div class="card-number-label">Hewan </div>
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
        <div class="card-description">Perlu cek inventaris </div>
      </div>
      <div class="card-number">
        <div class="card-number-value text-red"><?= $produkKedaluwarsa ?></div>
        <div class="card-number-label">Produk </div>
      </div>
      <div class="card-arrow">›</div>
    </a>

    <!-- Card 3 - Hamil -->
    <a class="notification-card card-birth" href="data_kesehatan.php">
      <div>
        <div class="card-title">
          <span>Hewan hamil bulan ini</span>
          <span class="icon-alert"></span>
        </div>
        <div class="card-description">Terverifikasi dari data kesehatan</div>
      </div>
      <div class="card-number">
        <div class="card-number-value text-green"><?= $hewanHamilBulanIni ?></div>
        <div class="card-number-label">Hewan </div>
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
        <div class="card-description">Menunggu konfirmasi admin </div>
      </div>
      <div class="card-number">
        <div class="card-number-value text-orange"><?= $menungguVerifikasi ?></div>
        <div class="card-number-label">Orang </div>
      </div>
      <div class="card-arrow">›</div>
    </a>
  </div>
</div>
</div>
<script>
window.dashboardSalesData = {
  labels: <?= json_encode(array_values($bulanLabels)) ?>,
  values: <?= json_encode(array_values($penjualanBulanan)) ?>
};
</script>
<script src="../../public/js/dashboard_admin.js?v=2"></script>
</body>
</html>
