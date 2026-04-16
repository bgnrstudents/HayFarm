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
      width: 97%;
      margin: 30px auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h3 {
      margin-bottom: 20px;
    }
 /*notif*/
    .notif-section h2 {
  margin-bottom: 15px;
}

.notif-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.notif-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  border-radius: 12px;
  position: relative;
  cursor: pointer;
}

/* HIJAU */
.notif-1 {
  background: #ffffff;
  color: #333;
  border: 1px solid #e0e0e0;
}

/* ABU */
.notif-gray {
  background: #ffffff;
  color: #000;
}

.notif-card h4 {
  margin: 0;
  font-size: 16px;
}

.notif-card p {
  margin: 5px 0 0;
  font-size: 12px;
  opacity: 0.8;
}

.notif-card .right {
  text-align: right;
}

.red h2 {
  color: #dc3545;
}

.orange h2 {
  color: #f59e0b;
}

.notif-card::after {
  content: "›";
  position: absolute;
  right: 15px;
  font-size: 20px;
  opacity: 0.5;
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
            <i class="fa-solid fa-bell"></i>
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
    <h3 style="margin:0;">Grafik Penjualan / Bulan</h3>

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
<div class="notif-section">
  <h2>Notifikasi</h2>

  <div class="notif-grid">

    <div class="notif-card notif-1">
      <div>
        <h4>Vaksinasi diperlukan</h4>
        <p>Hewan perlu vaksinasi segera</p>
      </div>
      <div class="right">
        <h2>9</h2>
        <small>Hewan</small>
      </div>
    </div>

    <div class="notif-card notif-gray">
      <div>
        <h4>Produk Kedaluwarsa ⚠️</h4>
        <p>Perlu cek inventaris</p>
      </div>
      <div class="right red">
        <h2>5</h2>
        <small>Produk</small>
      </div>
    </div>

    <div class="notif-card notif-gray">
      <div>
        <h4>Kelahiran bulan ini 🐣</h4>
        <p>Hewan baru lahir</p>
      </div>
      <div class="right">
        <h2>7</h2>
        <small>Hewan</small>
      </div>
    </div>

    <div class="notif-card notif-gray">
      <div>
        <h4>Perlu verifikasi ✔️</h4>
        <p>Menunggu konfirmasi admin</p>
      </div>
      <div class="right orange">
        <h2>3</h2>
        <small>Orang</small>
      </div>
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
</html>