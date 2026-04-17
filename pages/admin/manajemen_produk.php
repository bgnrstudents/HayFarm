<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Manajemen Penjualan</title>

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
/* STATS CARDS */
.stats-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-info h3 {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
    font-weight: 600;
}

.stat-info .number {
    font-size: 32px;
    font-weight: bold;
    color: #333;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.produk {
    background: #e8f5e9;
    color: #175D2B;
}

.stat-icon.rumput {
    background: #e8f5e9;
    color: #4CAF50;
}

.stat-icon.susu {
    background: #e3f2fd;
    color: #2196F3;
}

.stat-icon.hewan {
    background: #fff3e0;
    color: #FF9800;
}

/* PRODUCT LIST SECTION */
.product-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.section-header {
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 18px;
    color: #333;
    margin-bottom: 5px;
    font-weight: 700;
}

.section-header p {
    font-size: 13px;
    color: #888;
}

/* TABLE CONTROLS */
.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.table-search {
    flex: 1;
    max-width: 300px;
    position: relative;
}

.table-search input {
    width: 100%;
    padding: 10px 15px 10px 40px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
}

.table-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.table-actions {
    display: flex;
    gap: 10px;
}

.btn-filter, .btn-export {
    padding: 10px 20px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
    transition: all 0.3s;
}

.btn-filter:hover, .btn-export:hover {
    background: #f5f5f5;
}

.btn-add {
    padding: 10px 20px;
    background: #175D2B;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-add:hover {
    background: #145024;
}

/* TABLE */
.product-table {
    width: 100%;
    border-collapse: collapse;
}

.product-table thead {
    background: #f8f9fa;
}

.product-table th {
    padding: 12px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    border-bottom: 2px solid #e0e0e0;
}

.product-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    color: #333;
}

.product-table tbody tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-tersedia {
    background: #e8f5e9;
    color: #175D2B;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.action-btn.view {
    background: #e3f2fd;
    color: #2196F3;
}

.action-btn.edit {
    background: #fff3e0;
    color: #FF9800;
}

.action-btn.delete {
    background: #ffebee;
    color: #f44336;
}

.action-btn:hover {
    transform: scale(1.1);
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
            <i class="fa-solid fa-bell"style="color: rgb(25, 108, 51); size: 1.25rem;"></i>
            <span class="badge">6</span>
        </div>

        <div class="user">
            <strong>Farel</strong>
            <small>Admin</small>
        </div>
    </div>

</div>
<!-- STATS CARDS -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Produk</h3>
            <div class="number">12</div>
        </div>
        <div class="stat-icon produk">
            <i class="fa-solid fa-box"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Produk Rumput</h3>
            <div class="number">1</div>
        </div>
        <div class="stat-icon rumput">
            <i class="fa-solid fa-seedling"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Produk Susu</h3>
            <div class="number">56</div>
        </div>
        <div class="stat-icon susu">
            <i class="fa-solid fa-bottle-water"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Produk Hewan</h3>
            <div class="number">4</div>
        </div>
        <div class="stat-icon hewan">
            <i class="fa-solid fa-cow"></i>
        </div>
    </div>
</div>

<!-- PRODUCT LIST SECTION -->
<div class="product-section">
    <div class="section-header">
        <h2>Daftar Produk</h2>
        <p>Manajemen data produk</p>
    </div>

    <div class="table-controls">
        <div class="table-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Cari produk...">
        </div>
        
        <div class="table-actions">
            <button class="btn-filter">
                <i class="fa-solid fa-filter"></i>
                Filter
            </button>
            <button class="btn-export">
                <i class="fa-solid fa-download"></i>
                Export
            </button>
            <button class="btn-add">
                <i class="fa-solid fa-plus"></i>
                Tambah Produk
            </button>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>NO</th>
                <th>Jenis Produk</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>00002</td>
                <td>Rumput</td>
                <td>Rumput</td>
                <td>22/12/2025</td>
                <td>2.000</td>
                <td>1 Ton</td>
                <td><span class="status-badge status-tersedia">Tersedia</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="action-btn edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>00003</td>
                <td>Hewan</td>
                <td>Sapi Perah</td>
                <td>24/12/2025</td>
                <td>20.000.000</td>
                <td>1 Ekor</td>
                <td><span class="status-badge status-tersedia">Tersedia</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="action-btn edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>00004</td>
                <td>Hewan</td>
                <td>Kambing</td>
                <td>22/03/2025</td>
                <td>2.000.000</td>
                <td>1 Ekor</td>
                <td><span class="status-badge status-tersedia">Tersedia</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="action-btn edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>00005</td>
                <td>Susu</td>
                <td>Susu</td>
                <td>22/01/2025</td>
                <td>2.000</td>
                <td>1 Liter</td>
                <td><span class="status-badge status-tersedia">Tersedia</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="action-btn edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>00006</td>
                <td>Rumput</td>
                <td>Rumput</td>
                <td>22/12/2025</td>
                <td>2.000</td>
                <td>1 Ton</td>
                <td><span class="status-badge status-tersedia">Tersedia</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="action-btn edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
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