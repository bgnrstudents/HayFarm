<?php include '../../components/header_manager.php'; ?>
<?php include '../../components/sidebar_manager.php'; ?>
<?php include '../../components/topbar_manager.php'; ?>

<div class="content-wrapper m-3">
<h1 class="page-head">Dashboard</h1>

<!-- CHART SECTION -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-dashboard p-3 shadow-sm">
            <h6>Populasi Hewan</h6>
            <canvas id="dashboardStatusChart"></canvas>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-dashboard p-3 shadow-sm">
            <h6>Kesehatan Hewan</h6>
            <canvas id="dashboardTrendChart"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-dashboard p-3 shadow-sm">
            <h6>Status Reproduksi Hewan</h6>
            <canvas id="dashboardReproChart"></canvas>
        </div>
    </div>
</div>

<div class="row mb-4">
        <div class="card card-dashboard p-3 shadow-sm">
            <h6>Kesehatan Hewan</h6>
            <canvas id="dashboardTrendChartKesehatan"></canvas>
        </div>
</div>


<script src="../../public/js/dashboard_manager.js"></script>
<?php include '../../components/footer_manager.php'; ?>
