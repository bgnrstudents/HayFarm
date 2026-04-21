<?php include '../../components/header_manager.php'; ?>
<?php include '../../components/sidebar_manager.php'; ?>
<?php include '../../components/topbar_manager.php'; ?>

<div class="content-wrapper m-3">
<h1 class="page-head">Laporan Kesehatan Hewan</h1>

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

    <button class="btn btn-success" onclick="exportPDF()"><i class="fa fa-file-pdf"></i> Export PDF</button>
    <button class="btn btn-success" onclick="exportExcel()"><i class="fa fa-file-excel"></i> Export Excel</button>
</div>

<!-- CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-kesehatan p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/heart.svg">
                </div>
                <div>
                    <h2 class="mb-0">80</h2>
                    <small>Sehat</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-kesehatan p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/suntik.svg">
                </div>
                <div>
                    <h2 class="mb-0">20</h2>
                    <small>Sakit</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-kesehatan p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/lab.svg">
                </div>
                <div>
                    <h2 class="mb-0">10</h2>
                    <small>Observasi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-kesehatan p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/warning.svg" alt="Kandang Icon">
                </div>
                <div>
                    <h2 class="mb-0">10</h2>
                    <small>Dalam Perawatan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHART SECTION -->
<div class="row g-2 mb-4">
    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6>Kasus Berdasarkan Jenis Hewan</h6>
            <canvas id="kesehatanReproChart"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6>Trend Pemeriksaan Kesehatan 6 Bulan Terakhir</h6>
            <canvas id="kesehatanTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="card p-3 shadow-sm">
    <h6>Detail Riwayat Kesehatan</h6>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Hewan</th>
                    <th>Status</th>
                    <th>Diagnosis</th>
                    <th>Tindakan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0001</td>
                    <td>Sapi</td>
                    <td><span class="badge bg-success">Sehat</span></td>
                    <td>--</td>
                    <td>Vaksinasi</td>
                    <td><button class="btn-detail"><a href="../../pages/manager/detail_hewan_manager.php">Lihat Detail</a></button></td>
                </tr>
                <tr>
                    <td>0002</td>
                    <td>Kambing</td>
                    <td><span class="badge bg-warning">Dalam Perawatan</span></td>
                    <td>Infeksi ringan</td>
                    <td>Antibiotik</td>
                    <td><button class="btn-detail"><a href="../../pages/manager/detail_hewan_manager.php">Lihat Detail</a></button></td>
                </tr>
                <tr>
                    <td>0003</td>
                    <td>Domba</td>
                    <td><span class="badge bg-danger">Observasi</span></td>
                    <td>Demam</td>
                    <td>Monitoring</td>
                    <td><button class="btn-detail"><a href="../../pages/manager/detail_hewan_manager.php">Lihat Detail</a></button></td>
                    <!-- <td><button class="btn-detail"><a href="../../pages/manager/detail_hewan_manager.php?id=0003">Lihat Detail</a></button></td> -->
                </tr>
            </tbody>
        </table>
    </div>
</div>


<script src="/HAYFARM-1/public/js/kesehatan_manager.js"></script>
<?php include '../../components/footer_manager.php'; ?>