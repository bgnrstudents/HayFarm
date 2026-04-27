<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Verifikasi Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:'Nunito',sans-serif;}

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
    background: #f8f9fa;
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

.main-content {
    margin-left:250px;
    padding:20px;
    min-height:100vh;
    background: linear-gradient(to bottom,#ffffff 0px,#ffffff 80px,#dbe7df 80px,#c9d8cf 40%,#b8c8be 100%);
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

.section-header h2 {font-weight:700;}
.section-header p {color:#777;font-size:13px;}

/* FILTER */
.filter-box {
    background:white;
    padding:15px;
    border-radius:12px;
    display:flex;
    gap:10px;
    margin:20px 0;
}

.filter-box select {
    padding:8px;
    border-radius:8px;
    border:1px solid #ddd;
}

/* STATS */
.stats {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-bottom:20px;
}

.stat-card {
    background:white;
    padding:15px;
    border-radius:12px;
}

.stat-card h4 {font-size:13px;color:#777;}
.stat-card h2 {font-size:20px;font-weight:bold;}

/* TABLE */
.table-box {
    background:white;
    border-radius:12px;
    padding:20px;
}

table {width:100%;border-collapse:collapse;}
th,td {padding:12px;font-size:14px;border-bottom:1px solid #eee;}
thead th {
    background:#ffc107;
    color:#3b2f00;
    font-weight:800;
    border-bottom:1px solid #e0a800;
}
tbody td {
    background:#fffdf5;
}
tbody tr:nth-child(even) td {
    background:#fffaf0;
}

.status {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.wait {background:#fff3cd;color:#856404;}
.ok {background:#d4edda;color:#155724;}
.no {background:#f8d7da;color:#721c24;}

.btn-verif {
    background:#175D2B;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:8px;
}

.eye {
    background:#eee;
    border:none;
    padding:6px 10px;
    border-radius:6px;
}

.btn-verif:hover {background:#0f4921;}
.eye:hover {background:#dde2e6;}

/* POPUP */
.popup-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 9999;
}

.popup-overlay.active {
    display: flex;
}

.popup-card {
    width: min(980px, 100%);
    max-height: 90vh;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 18px 44px rgba(0, 0, 0, 0.18);
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
}

.popup-title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
}

.popup-close {
    background: #f1f3f5;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
}

.popup-close:hover {
    background: #e2e8f0;
}

.popup-iframe {
    width: 100%;
    height: 78vh;
    border: none;
    display: block;
}

/* PAGINATION */
.pagination {
    display:flex;
    justify-content:flex-end;
    margin-top:10px;
    gap:5px;
}
.pagination button {
    border:none;
    padding:6px 10px;
    border-radius:6px;
}
.active-page {
    background:#175D2B;
    color:white;
}
</style>
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

<!-- FILTER -->
<div class="filter-box">
<select><option>1 - 24 Apr 2026</option></select>
<select><option>Semua Status</option></select>
<select><option>Semua Metode</option></select>
</div>

<!-- STATS -->
<div class="stats">
<div class="stat-card"><h4>Menunggu</h4><h2>10 Pesanan</h2></div>
<div class="stat-card"><h4>Diverifikasi</h4><h2>45 Pesanan</h2></div>
<div class="stat-card"><h4>Ditolak</h4><h2>3 Pesanan</h2></div>
<div class="stat-card"><h4>Total</h4><h2>Rp 52.430.000</h2></div>
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

<tbody>
<tr>
<td>#ORD-001</td>
<td>25 Feb 2026</td>
<td>Ahmad</td>
<td>Sapi</td>
<td>2</td>
<td>15.250.000</td>
<td><span class="status wait">Menunggu</span></td>
<td><button class="btn-verif" onclick="openDelete()">Verifikasi</button></td>
</tr>

<tr>
<td>#ORD-002</td>
<td>24 Feb</td>
<td>Siti</td>
<td>Sapi</td>
<td>1</td>
<td>28.500.000</td>
<td><span class="status ok">Diverifikasi</span></td>
<td><button class="eye" onclick="openVerified()"><i class="fa fa-eye"></i></button></td>
</tr>

<tr>
<td>#ORD-003</td>
<td>24 Feb</td>
<td>Budi</td>
<td>Domba</td>
<td>3</td>
<td>8.750.000</td>
<td><span class="status wait">Menunggu</span></td>
<td><button class="btn-verif" onclick="openDelete()">Verifikasi</button></td>
</tr>

<tr>
<td>#ORD-004</td>
<td>23 Feb</td>
<td>Dewi</td>
<td>Kambing</td>
<td>5</td>
<td>12.500.000</td>
<td><span class="status no">Ditolak</span></td>
<td><button class="eye" onclick="openRejected()"><i class="fa fa-eye"></i></button></td>
</tr>

</tbody>
</table>

<div class="pagination">
<button>1</button>
<button class="active-page">2</button>
<button>3</button>
</div>

</div>

</div>

<div class="popup-overlay" id="statusPopup" onclick="closePopupOutside(event)">
    <div class="popup-card">
        <div class="popup-header">
            <h4 class="popup-title" id="popupTitle">Detail Verifikasi</h4>
            <button class="popup-close" type="button" onclick="closePopup()">&times;</button>
        </div>
        <iframe class="popup-iframe" id="popupFrame" src=""></iframe>
    </div>
</div>

<script>
// Fungsi utama untuk membuka popup
function openPopup(page, title) {
    document.getElementById('popupTitle').textContent = title;
    document.getElementById('popupFrame').src = page;
    document.getElementById('statusPopup').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk tombol Verifikasi (Baris ini yang memanggil file)
function openDelete() {
    // Arahkan ke folder verifikasi_penjualan dan file menunggu_verifikasi.php
    openPopup('verifikasi_penjualan/menunggu_verifikasi.php', 'Menunggu Verifikasi');
}

function openVerified() {
    openPopup('verifikasi_penjualan/sudah_verifikasi.php', 'Pesanan Diverifikasi');
}

function openRejected() {
    openPopup('verifikasi_penjualan/verifikasi_ditolak.php', 'Pesanan Ditolak');
}

// Fungsi menutup popup
function closePopup() {
    document.getElementById('statusPopup').classList.remove('active');
    document.getElementById('popupFrame').src = '';
    document.body.style.overflow = 'auto';
}

function closePopupOutside(event) {
    if (event.target.id === 'statusPopup') {
        closePopup();
    }
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closePopup();
    }
});
</script>


</body>
</html>