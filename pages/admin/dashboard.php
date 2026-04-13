<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hay Farm Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

body{
background:#eef3ef;
}

.container{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:250px;
background:#1c5f3f;
height:100vh;
color:white;
padding:20px;
position:fixed;
}

.logo{
font-size:22px;
font-weight:600;
margin-bottom:30px;
}

.sidebar ul{
list-style:none;
}

.sidebar ul li{
padding:12px 15px;
margin-bottom:8px;
border-radius:10px;
cursor:pointer;
font-size:14px;
}

.sidebar ul li:hover,
.sidebar ul li.active{
background:#2f7a55;
}

.menu-title{
margin:20px 0 10px;
opacity:.7;
font-size:13px;
}

.logout{
position:absolute;
bottom:20px;
width:85%;
}

/* MAIN */

.main{
margin-left:250px;
padding:20px 30px;
width:100%;
}

/* TOPBAR */

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.search{
width:350px;
padding:10px 15px;
border-radius:20px;
border:none;
background:#f3f5f4;
}

.user{
display:flex;
align-items:center;
gap:15px;
}

.badge{
background:#ff3b3b;
color:white;
padding:3px 7px;
border-radius:50%;
font-size:12px;
}

/* HEADER */

.header{
margin-bottom:20px;
}

.header h1{
font-size:22px;
font-weight:600;
}

.header p{
font-size:14px;
color:#666;
}

/* CARDS */

.cards{
display:flex;
gap:20px;
margin-bottom:20px;
}

.card{
background:white;
padding:20px;
border-radius:15px;
flex:1;
box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.card h4{
font-size:14px;
color:#666;
}

.card h2{
margin:10px 0;
font-size:26px;
}

.green{
color:#2bb673;
font-size:13px;
}

.red{
color:#ff4d4d;
font-size:13px;
}

/* CHART */

.chart{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.05);
margin-bottom:20px;
}

.chart-header{
display:flex;
justify-content:space-between;
margin-bottom:15px;
}

.chart-box{
height:220px;
background:linear-gradient(to top,#dff2e7,#ffffff);
border-radius:10px;
position:relative;
}

/* NOTIF */

.notif{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.notif-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:15px;
margin-top:15px;
}

.notif-card{
padding:15px;
border-radius:12px;
background:#f5f5f5;
display:flex;
justify-content:space-between;
align-items:center;
}

.notif-left h4{
font-size:14px;
}

.notif-left p{
font-size:12px;
color:#666;
}

.notif-number{
font-size:22px;
font-weight:600;
}

.green-bg{
background:#1c5f3f;
color:white;
}

.red-bg{
background:#ff4d4d;
color:white;
}

.yellow-bg{
background:#ffa500;
color:white;
}

</style>

</head>

<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">

<div class="logo">Hay Farm</div>

<ul>
<li class="active">Dashboard</li>
<li>Manajemen Produk</li>
<li>Verifikasi Penjualan</li>
</ul>

<div class="menu-title">DATA</div>

<ul>
<li>Data Hewan</li>
<li>Data Kesehatan Hewan</li>
</ul>

<ul class="logout">
<li>Logout</li>
</ul>

</div>


<!-- MAIN -->
<div class="main">

<!-- TOPBAR -->
<div class="topbar">

<input class="search" type="text" placeholder="Pencarian">

<div class="user">
<span>Rabu, 25 Februari 2026</span>
<span class="badge">6</span>
<span>Farel</span>
</div>

</div>

<!-- HEADER -->
<div class="header">
<h1>Dashboard</h1>
<p>Selamat datang di panel kontrol administrasi peternakan</p>
</div>


<!-- CARDS -->
<div class="cards">

<div class="card">
<h4>Jumlah Hewan</h4>
<h2>10.243</h2>
<span class="green">↑ 8 Dari kemarin</span>
</div>

<div class="card">
<h4>Hewan Sakit per Hari</h4>
<h2>12</h2>
<span class="red">↓ 2 Dari kemarin</span>
</div>

<div class="card">
<h4>Jumlah Pembeli</h4>
<h2>345</h2>
<span class="green">↑ 7 Dari kemarin</span>
</div>

</div>


<!-- CHART -->
<div class="chart">

<div class="chart-header">
<h3>Grafik Penjualan / Minggu</h3>
<span>October</span>
</div>

<div class="chart-box"></div>

</div>


<!-- NOTIF -->
<div class="notif">

<h3>Notifikasi</h3>

<div class="notif-grid">

<div class="notif-card green-bg">
<div class="notif-left">
<h4>Butuh Vaksinasi</h4>
<p>Hewan memerlukan vaksinasi segera</p>
</div>

<div class="notif-number">9</div>

</div>

<div class="notif-card red-bg">
<div class="notif-left">
<h4>Produk Kadaluarsa</h4>
<p>Perlu pengecekan inventaris</p>
</div>

<div class="notif-number">5</div>

</div>

<div class="notif-card">
<div class="notif-left">
<h4>Kelahiran Bulan Ini</h4>
<p>Hewan yang baru lahir</p>
</div>

<div class="notif-number">7</div>

</div>

<div class="notif-card yellow-bg">
<div class="notif-left">
<h4>Perlu Verifikasi</h4>
<p>Menunggu konfirmasi admin</p>
</div>

<div class="notif-number">3</div>

</div>

</div>

</div>

</div>

</div>

</body>
</html>