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
 <link rel="stylesheet" href="../../public/css/admin_dashboard.css">  
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

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Jumlah Hewan</h6>
          <h2 id="hewan">10243</h2>
          <small class="text-success">📈 8 Dari kemarin</small>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Hewan Sakit per Hari</h6>
          <h2 id="sakit">12</h2>
          <small class="text-danger">📉 2 Dari kemarin</small>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Jumlah Pembeli</h6>
          <h2 id="pembeli">345</h2>
          <small class="text-success">📈 7 Dari kemarin</small>
        </div>
      </div>
    </div>

  </div>
</div>
<div class="card">

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
    <h3 style="margin:0;">Grafik Penjualan </h3>

    <select style="
      padding:5px 10px;
      border-radius:6px;
      border:1px solid #ccc;
    ">
      <option>Harian</option>
      <option selected>Bulanan</option>
      <option>Tahunan</option>
    </select>
  </div>

  <div id="chart"></div>
</div>

<!-- Notifikasi -->
<div class="notification-container">
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
        <div class="card-number-value">9</div>
        <div class="card-number-label">Hewan </div>
      </div>
      <div class="card-arrow">›</div>
    </a>

    <!-- Card 2 - Produk Kedaluwarsa -->
    <a class="notification-card card-default" href="manajemen_produk.php">
      <div>
        <div class="card-title">
          <span>Produk Kedaluwarsa</span>
          <span class="icon-alert"></span>
        </div>
        <div class="card-description">Perlu cek inventaris </div>
      </div>
      <div class="card-number">
        <div class="card-number-value text-red">5</div>
        <div class="card-number-label">Produk </div>
      </div>
      <div class="card-arrow">›</div>
    </a>

    <!-- Card 3 - Kelahiran -->
    <a class="notification-card card-default" href="data_kesehatan.php">
      <div>
        <div class="card-title">
          <span>Kelahiran bulan ini</span>
          <span class="icon-alert"></span>
        </div>
        <div class="card-description">Hewan baru lahir </div>
      </div>
      <div class="card-number">
        <div class="card-number-value text-green">7</div>
        <div class="card-number-label">Hewan </div>
      </div>
      <div class="card-arrow">›</div>
    </a>

    <!-- Card 4 - Perlu Verifikasi -->
    <a class="notification-card card-default" href="verifikasi_penjualan.php">
      <div>
        <div class="card-title">
          <span>Perlu verifikasi</span>
          <span class="icon-alert">✓</span>
        </div>
        <div class="card-description">Menunggu konfirmasi admin </div>
      </div>
      <div class="card-number">
        <div class="card-number-value text-orange">3</div>
        <div class="card-number-label">Orang </div>
      </div>
      <div class="card-arrow">›</div>
    </a>
  </div>
</div>
</div>
<script src="../../public/js/dashboard_admin.js"></script>
</body>
</html>