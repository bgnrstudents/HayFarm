<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

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
    border-bottom: 1px solid #eee; 
    
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
    margin-top: 15px;
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
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="../../public/images/logo_hayfarm.png" class="logo">

    <ul class="menu">
        <li>
            <a href="#"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
        </li>

        <li class="active">
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

    <!-- CARDS -->
    <div class="cards">
        <div class="card-box">
            <h6>Jumlah Hewan</h6>
            <h2>10.243</h2>
            <small>📈 8 Dari kemarin</small>
        </div>

        <div class="card-box">
            <h6>Hewan Sakit per Hari</h6>
            <h2>12</h2>
            <small>📉 2 Dari kemarin</small>
        </div>

        <div class="card-box">
            <h6>Jumlah Pembeli</h6>
            <h2>345</h2>
            <small>📈 7 Dari kemarin</small>
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

</body>
</html>