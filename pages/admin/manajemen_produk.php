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

/* FILTER MODAL */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.filter-modal {
    background: white;
    border-radius: 12px;
    padding: 30px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.filter-modal h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #333;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    margin-bottom: 8px;
}

.filter-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    outline: none;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.filter-group select:focus {
    border-color: #175D2B;
}

.filter-buttons {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.filter-buttons button {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-filter-apply {
    background: #175D2B;
    color: white;
}

.btn-filter-apply:hover {
    background: #145024;
}

.btn-filter-reset {
    background: #e0e0e0;
    color: #333;
}

.btn-filter-reset:hover {
    background: #d0d0d0;
}

.btn-filter-close {
    background: #f5f5f5;
    color: #666;
}

.btn-filter-close:hover {
    background: #e0e0e0;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="../../public/images/logo_hayfarm.png" class="logo">

    <ul class="menu">
        <li>
            <a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
        </li>

        <li class="active">
            <a href="manajemen_produk.php"><i class="fa-solid fa-credit-card"></i> Manajemen Produk</a>
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
            <button class="btn-export" onclick="exportTableToCSV('produk_data.csv')">
                <i class="fa-solid fa-download"></i>
                Export
            </button>
            <button class="btn-add" onclick="window.location.href='produk/hewan/tambah_hewan.php'">
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

<!-- FILTER MODAL -->
<div class="modal-overlay" id="filterModal">
    <div class="filter-modal">
        <h3>Filter Produk</h3>
        
        <div class="filter-group">
            <label for="filterJenis">Jenis Produk</label>
            <select id="filterJenis">
                <option value="">Semua Jenis</option>
                <option value="Rumput">Rumput</option>
                <option value="Hewan">Hewan</option>
                <option value="Susu">Susu</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="filterStatus">Status</label>
            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="Tersedia">Tersedia</option>
            </select>
        </div>

        <div class="filter-buttons">
            <button class="btn-filter-apply" onclick="applyFilter()">Terapkan</button>
            <button class="btn-filter-reset" onclick="resetFilter()">Reset</button>
            <button class="btn-filter-close" onclick="closeFilterModal()">Tutup</button>
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

// Fungsi untuk membuka filter modal
function openFilterModal() {
    document.getElementById('filterModal').classList.add('active');
}

// Fungsi untuk menutup filter modal
function closeFilterModal() {
    document.getElementById('filterModal').classList.remove('active');
}

// Fungsi untuk menerapkan filter
function applyFilter() {
    const filterJenis = document.getElementById('filterJenis').value;
    const filterStatus = document.getElementById('filterStatus').value;
    
    const table = document.querySelector('.product-table');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let show = true;
        
        // Filter berdasarkan Jenis Produk (kolom ke-2)
        if (filterJenis) {
            const jenisProduk = row.querySelector('td:nth-child(2)').innerText;
            if (jenisProduk !== filterJenis) {
                show = false;
            }
        }
        
        // Filter berdasarkan Status (kolom ke-7)
        if (filterStatus && show) {
            const status = row.querySelector('td:nth-child(7)').innerText.trim();
            if (!status.includes(filterStatus)) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
    });
    
    closeFilterModal();
}

// Fungsi untuk reset filter
function resetFilter() {
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterStatus').value = '';
    
    const table = document.querySelector('.product-table');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        row.style.display = '';
    });
    
    closeFilterModal();
}

// Tambahkan event listener ke tombol filter
document.querySelector('.btn-filter').addEventListener('click', openFilterModal);

// Tutup modal ketika klik di luar modal
document.getElementById('filterModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFilterModal();
    }
});

// Fungsi export tabel ke CSV
function exportTableToCSV(filename) {
    const table = document.querySelector('.product-table');
    let csv = [];
    let rows = table.querySelectorAll('tr');
    
    // Loop melalui setiap baris
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');
        
        // Loop melalui setiap kolom
        for (let j = 0; j < cols.length - 1; j++) { // Abaikan kolom Aksi (terakhir)
            let text = cols[j].innerText.replace(/"/g, '""');
            row.push('"' + text + '"');
        }
        csv.push(row.join(','));
    }
    
    // Buat blob dan download
    let csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement('a');
    let url = URL.createObjectURL(csvFile);
    
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

</body>
</html>