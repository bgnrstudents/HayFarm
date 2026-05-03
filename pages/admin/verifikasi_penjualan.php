<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Verifikasi Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../../public/css/admin_verifikasiPenjualan.css">
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
<td><button class="btn-verif" onclick="openPending()">Verifikasi</button></td>
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
<td><button class="btn-verif" onclick="openPending()">Verifikasi</button></td>
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

<div class="sales-modal-overlay" id="salesModal" onclick="closeSalesModalOutside(event)">
    <div class="sales-modal-card">
        <div class="sales-modal-body">
            <div class="sales-modal-header">
                <div>
                    <h2 class="sales-modal-title">Detail Pesanan</h2>
                    <p class="sales-modal-subtitle" id="salesSubtitle">Review pesanan pelanggan</p>
                </div>
                <button class="sales-modal-close" type="button" onclick="closeSalesModal()">&times;</button>
            </div>

            <div class="sales-order-id" id="salesOrderId">#ORD-2026-001</div>
            <div class="sales-status-badge waiting" id="salesStatusText">Menunggu Verifikasi</div>

            <div class="sales-section-title">Informasi Pelanggan</div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-user"></i></div>
                <div><span class="sales-label">Nama Lengkap</span><span class="sales-value" id="salesCustomer">Ahmad Ridwan</span></div>
            </div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-envelope"></i></div>
                <div><span class="sales-label">Email</span><span class="sales-value" id="salesEmail">ahmad.ridwan@example.com</span></div>
            </div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-phone"></i></div>
                <div><span class="sales-label">Nomor Telepon</span><span class="sales-value" id="salesPhone">08123456789</span></div>
            </div>
            <div class="sales-info-row">
                <div class="sales-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                <div><span class="sales-label">Alamat Pengiriman</span><span class="sales-value" id="salesAddress">Cianjur, Jawa Barat</span></div>
            </div>

            <div class="sales-section-title" id="proofTitle">Bukti Transfer</div>
            <div class="sales-proof-card" id="salesProof" onclick="openSalesLightbox('https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=80&w=900')">
                <img src="https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=80&w=120" alt="Bukti transfer">
                <div>
                    <span class="sales-value">Bukti_Transfer.jpg</span>
                    <span class="sales-label">Klik untuk memperbesar</span>
                </div>
            </div>

            <div class="sales-section-title">Ringkasan Pembayaran</div>
            <div class="sales-summary">
                <div class="sales-summary-row">
                    <div class="sales-value"><i class="fas fa-credit-card"></i> Metode Pembayaran</div>
                    <div class="sales-value">Transfer Bank</div>
                </div>
                <div class="sales-summary-row sales-total-row">
                    <span class="sales-total-label">Total</span>
                    <span class="sales-total" id="salesTotal">Rp 15.250.000</span>
                </div>
            </div>

            <div class="sales-actions" id="salesActions"></div>
        </div>
    </div>
</div>

<div class="sales-lightbox" id="salesLightbox" onclick="closeSalesLightbox()">
    <img id="salesLightboxImage" src="" alt="Bukti transfer">
</div>

<script src="../../public/js/verifikasiPenjualan_admin.js"></script>


</body>
</html>