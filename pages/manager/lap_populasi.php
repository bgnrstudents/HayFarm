<?php include '../../components/header_manager.php'; ?>
<?php include '../../components/sidebar_manager.php'; ?>
<?php include '../../components/topbar_manager.php'; ?>

<div class="content-wrapper m-3">
<h1 class="page-head">Laporan Populasi Hewan</h1>

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
    <div class="col-md-3">
        <div class="card card-akumulasi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/sapi.svg">
                </div>
                <div>
                    <h2 class="mb-0">50</h2>
                    <small>Sapi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-akumulasi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/kambing.svg">
                </div>
                <div>
                    <h2 class="mb-0">40</h2>
                    <small>Kambing</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-akumulasi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/domba.svg">
                </div>
                <div>
                    <h2 class="mb-0">30</h2>
                    <small>Domba</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-akumulasi p-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="icon">
                    <img src="../../public/svg/kandang.svg" alt="Kandang Icon">
                </div>
                <div>
                    <h2 class="mb-0">120</h2>
                    <small>Total Hewan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHART SECTION -->
<div class="row g-4 mb-4">  
    <div class="col-md-4">  
        <div class="card p-3 shadow-sm">
            <h6>Distribusi Status</h6>
            <canvas id="populasiStatusChart"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6>Status Reproduksi</h6>
            <canvas id="populasiReproChart"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6>Trend Populasi</h6>
            <canvas id="populasiTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="card p-3 shadow-sm">
    <h6>Detail Data Ternak</h6>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Hewan</th>
                    <th>Status</th>
                    <th>Reproduksi</th>
                    <th>Kesehatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0001</td>
                    <td>Sapi</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>IB bulan lalu</td>
                    <td>Sehat</td>
                    <td><button class="btn-detail"><a href="../../pages/manager/detail_hewan_manager.php">Lihat Detail</a></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<script>
    const labelsStatus = <?= json_encode($labelsStatus ?? ['Aktif', 'Bunting', 'Terjual', 'Tidak Produktif']) ?>;
    const dataStatus   = <?= json_encode($dataStatus ?? [70, 25, 25, 5]) ?>;
</script>
<script src="/HAYFARM-1/public/js/populasi_manager.js"></script>

<?php include '../../components/footer_manager.php'; ?>