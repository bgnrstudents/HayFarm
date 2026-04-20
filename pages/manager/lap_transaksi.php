<?php include '../../components/header_manager.php'; ?>
<?php include '../../components/sidebar_manager.php'; ?>
<?php include '../../components/topbar_manager.php'; ?>

<div class="content-wrapper m-3">
<h1 class="page-head">Laporan Transaksi Penjualan Per-Tahun</h1>

<!-- FILTER -->
<div class="filter-container d-flex gap-2 mb-3">
    <select id="month" class="form-select w-auto">
        <option>Februari</option>
        <option>Maret</option>
    </select>

    <select id="year" class="form-select w-auto">
        <option>2026</option>
        <option>2025</option>
    </select>

    <select id="animal" class="form-select w-auto">
        <option>Semua Hewan</option>
        <option>Sapi</option>
        <option>Kambing</option>
        <option>Domba</option>
    </select>

    <button class="btn btn-success" onclick="exportPDF()">Export PDF</button>
    <button class="btn btn-success" onclick="exportExcel()">Export Excel</button>
</div>

<!-- CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-transaksi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/paket.svg" class="icon-lg">
                </div>
                <div>
                    <h2 class="mb-0">80</h2>
                    <small>Produk</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-transaksi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/uang.svg" class="icon-lg">
                </div>
                <div>
                    <h2 class="mb-0">20</h2>
                    <small>Penjualan Bulan Ini</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-transaksi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/profit.svg" class="icon-lg">
                </div>
                <div>
                    <h2 class="mb-0">10</h2>
                    <small>Total Pendapatan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHART SECTION -->
<div class="row g-2 mb-4">
    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6>Penjualan Per Jenis Produk</h6>
            <canvas id="transaksiProChart"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6>Trend Penjualan 6 Bulan Terakhir</h6>
            <canvas id="transaksiTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="card p-3 shadow-sm">
    <h6>Detail Transaksi Penjualan</h6>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Jenis Produk</th>
                    <th>Nama Pembeli</th>
                    <th>Total Harga</th>
                    <th>Metode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0001</td>
                    <td>01-10-2025</td>
                    <td>Hewan</td>
                    <td>PT Argo Jaya</td>
                    <td>Rp 10.000.000</td>
                    <td>Transfer</td>
                    <td>Selesai</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<script src="/HAYFARM-1/public/js/transaksi_manager.js"></script>
<?php include '../../components/footer_manager.php'; ?>