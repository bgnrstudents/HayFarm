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
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Nunito', sans-serif;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 150vh;

    background: linear-gradient(
        to bottom,
        #ffffff 0px,     
        #ffffff 80px,    
        #dbe7df 80px,  
        #c9d8cf 40%,
        #b8c8be 100%
    );
}
/* SIDEBAR */
.sidebar {
    width: 250px;
    height: 100vh;
    background: #fff;
    position: fixed;
    padding: 10px;
}

.logo {
    width: 130px;
    display: block;
    margin: 10px auto 20px; 
}

/* MENU */
.menu {
    list-style: none;
}

.menu li {
    margin-bottom: 10px;
}

.menu li a {
    text-decoration: none;
    color: #333;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
}

.menu li a:hover {
    background: #f2f2f2;
}

.menu .active a {
    background: #175D2B;
    color: #fff; 
}

.menu .active a i {
    color: #ffbe25; 
}

.menu-title {
    font-size: 12px;
    color: #777;
    margin: 15px 0 5px;
}

/* MAIN */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

/* TOPBAR */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff; 
    padding: 10px 20px;
    
}

/* SEARCH */
.search-box {
    position: relative;
    width: 300px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 14px;
    pointer-events: none; 
}

/* INPUT */
.search-box input {
    width: 100%;
    padding: 8px 12px 8px 35px;
    border-radius: 20px;
    border: none;
    outline: none;
    background: #f1f3f5;
    font-size: 14px;
}

/* RIGHT SECTION */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* DATE */
#currentDate {
    font-size: 13px;
    color: #555;
}

/* NOTIF */
.notif {
    position: relative;
    font-size: 16px;
    cursor: pointer;
}

.notif .badge {
    position: absolute;
    top: -6px;
    right: -8px;
    background: red;
    color: white;
    font-size: 10px;
    padding: 3px 5px;
    border-radius: 50%;
}

/* USER */
.user {
    display: flex;
    flex-direction: column;
    font-size: 12px;
    text-align: right;
}

.user strong {
    font-size: 13px;
}
/* DASHBOARD */
h4 {
    margin-top: 20px;
    font-weight: bold;
}

p {
    color: #777;
}

/* CARDS */
.cards {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.card-box {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    width: 250px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.card-box h6 {
    color: #777;
}

.card-box h2 {
    margin: 10px 0;
}

.card-box small {
    color: #777;
}
/* Grafik*/
body {
      font-family: Arial;
      background: #f5f6fa;
    }

    .card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      margin: 30px auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .row.g-4 > .col-md-4 {
      display: flex;
    }

    .row.g-4 > .col-md-4 .card {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .row.g-4 > .col-md-4 .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 180px;
    }

    .notif-card {
      min-height: 140px;
    }

    h3 {
      margin-bottom: 20px;
    }
  /* Notifikasi */
.notification-container {
  font-family: Arial, sans-serif;
  padding: 20px;
  background-color: #f5f5f5;
  margin-top: 30px;
  border-radius: 8px;
}

.notification-title {
  color: #2d5016;
  margin-bottom: 15px;
  font-size: 20px;
}

.notification-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.notification-card {
  padding: 15px;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.notification-card > div:first-child {
  flex: 1;
  min-width: 0;
}

.card-number {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: center;
  width: 70px;
  flex-shrink: 0;
}

.card-number-value,
.card-number-label {
  width: 100%;
}

.card-arrow {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
  width: 24px;
}

.card-vaksinasi {
  background-color: #1b7b3e;
  color: white;
}

.card-default {
  background-color: white;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.card-title {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 5px;
  font-weight: bold;
  font-size: 16px;
}

.card-vaksinasi .card-title {
  color: white;
}

.card-default .card-title {
  color: #333;
}

.card-description {
  font-size: 12px;
  opacity: 0.9;
}

.card-vaksinasi .card-description {
  color: rgba(255, 255, 255, 0.9);
}

.card-default .card-description {
  color: #666;
}

.card-number {
  text-align: right;
}

.card-number-value {
  font-size: 24px;
  font-weight: bold;
}

.card-vaksinasi .card-number-value {
  color: white;
}

.card-number-label {
  font-size: 12px;
  opacity: 0.9;
}

.card-vaksinasi .card-number-label {
  color: rgba(255, 255, 255, 0.9);
}

.card-default .card-number-label {
  color: #666;
}

.text-red {
  color: #e74c3c !important;
}

.text-green {
  color: #2d5016 !important;
}

.text-orange {
  color: #f39c12 !important;
}

.card-arrow {
  margin-left: 10px;
  font-size: 18px;
}

.card-vaksinasi .card-arrow {
  color: white;
}

.card-default .card-arrow {
  color: #666;
}

.icon-alert {
  font-size: 14px;
}

.icon-small {
  font-size: 12px;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="../../public/images/logo_hayfarm.png" class="logo">

    <ul class="menu">
        <li class="active">
            <a href="#"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
        </li>

        <li>
            <a href="#"><i class="fa-solid fa-credit-card"></i> Manajemen Produk</a>
        </li>

        <li>
            <a href="#"><i class="fa-solid fa-file-circle-check"></i> Verifikasi Penjualan</a>
        </li>

        <p class="menu-title">DATA</p>

        <li>
            <a href="#"><i class="fa-solid fa-square-poll-vertical"></i> Data Hewan</a>
        </li>

        <li>
            <a href="#"><i class="fa-solid fa-heart-pulse"></i> Data Kesehatan Hewan</a>
        </li>

        <li>
            <a href="#"><i class="fa-solid fa-power-off"></i> Logout</a>
        </li>
    </ul>
</div>

<!-- MAIN -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
    
    <!-- SEARCH -->
<div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Pencarian">
</div>

    <!-- RIGHT -->
    <div class="topbar-right">
        <span id="currentDate"></span>

        <div class="notif">
            <i class="fa-solid fa-bell"style="color: rgb(25, 108, 51); size: 1.25rem;"></i>
            <span class="badge">6</span>
        </div>

        <div class="user">
            <strong>Farel</strong>
            <small>Admin</small>
        </div>
    </div>

</div>

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
    <div class="notification-card card-vaksinasi">
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
    </div>

    <!-- Card 2 - Produk Kedaluwarsa -->
    <div class="notification-card card-default">
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
    </div>

    <!-- Card 3 - Kelahiran -->
    <div class="notification-card card-default">
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
    </div>

    <!-- Card 4 - Perlu Verifikasi -->
    <div class="notification-card card-default">
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
    </div>
  </div>
</div>
<script>
const dateEl = document.getElementById('currentDate');
const now = new Date();
dateEl.textContent = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});
</script>
<script>
  function updateData() {
    document.getElementById("hewan").innerText = 10000 + Math.floor(Math.random() * 500);
    document.getElementById("sakit").innerText = Math.floor(Math.random() * 20);
    document.getElementById("pembeli").innerText = 300 + Math.floor(Math.random() * 100);
  }

  setInterval(updateData, 10000); // update tiap 10 detik
</script>
<script>
let dataSeries = [
  { x: new Date().getTime(), y: 50 }
];

var options = {
  chart: {
    type: 'area',
    height: 350,
    animations: {
      enabled: true,
      easing: 'linear',
      dynamicAnimation: {
        speed: 1000
      }
    }
  },

  series: [{
    name: 'Penjualan',
    data: dataSeries
  }],

  xaxis: {
    type: 'datetime'
  },

  stroke: {
    curve: 'smooth'
  },

  colors: ['#16a34a']
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();


//UPDATE REAL-TIME
setInterval(() => {
  let newData = {
    x: new Date().getTime(),
    y: Math.floor(Math.random() * 100)
  };

  dataSeries.push(newData);

  chart.updateSeries([{
    data: dataSeries
  }]);

}, 10000);
</script>
</body>
</html>