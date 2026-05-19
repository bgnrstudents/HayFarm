<?php
require_once __DIR__ . '/manager_bootstrap.php';

$pageTitle = 'Dashboard';

// Dropdown tahun (default: tahun berjalan)
$selectedYear = isset($_GET['year']) && is_numeric($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

$populationReport = manager_make_report('populasi');
$healthReport = manager_make_report('kesehatan');
$transactionReport = manager_make_report('transaksi', ['year' => $selectedYear]);

// year options for dropdown
$yearOptions = $transactionReport->getYearOptions();


$animals = $populationReport->getRows();

$populationByType = manager_count_by_key($animals, 'jenis');
$healthChart = $healthReport->getChartData();
$transactionChart = $transactionReport->getChartData();

$reproChart = method_exists($populationReport, 'getReproductionChartData') ? $populationReport->getReproductionChartData() : ['labels' => [], 'values' => []];

$dashboardChartData = [
    'population' => [
        'labels' => array_keys($populationByType),
        'values' => array_values($populationByType),
    ],
    'healthTrend' => $healthChart['trend'],

    'reproduction' => [
        'labels' => $reproChart['labels'],
        'values' => $reproChart['values'],
    ],

    'salesTrend' => $transactionChart['trend'],
];

include '../../components/manager/header_manager.php';
include '../../components/manager/sidebar_manager.php';
include '../../components/manager/topbar_manager.php';
?>


<div class="content-wrapper m-3">
    <h1 class="page-head">Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-dashboard chart-card p-4 shadow-sm">
                <h6>Populasi Hewan</h6>
                <div class="chart-wrap chart-wrap-sm">
                    <canvas id="dashboardStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-dashboard chart-card p-4 shadow-sm">
                <h6>Trend Pemeriksaan Kesehatan</h6>
                <div class="chart-wrap chart-wrap-sm">
                    <canvas id="dashboardTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-dashboard chart-card p-4 shadow-sm">
                <h6>Status Reproduksi Hewan</h6>
                <div class="chart-wrap chart-wrap-sm">
                    <canvas id="dashboardReproChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-dashboard chart-card p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <h6 class="mb-0">Trend Penjualan</h6>

                    <form method="get" class="d-flex gap-2 align-items-center">
                        <select id="dashboardYear" name="year" class="form-select w-auto">
                            <?php
                            foreach ($yearOptions as $yearOpt):
                                $val = $yearOpt['value'];
                                $label = $yearOpt['label'];
                                if ((string)$val === '') continue;
                            ?>
                                <option value="<?= manager_escape((string)$val) ?>" <?= ((int)$val === $selectedYear) ? 'selected' : '' ?>>
                                    <?= manager_escape((string)$label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="chart-wrap chart-wrap-lg">
                    <canvas id="dashboardTrendChartPenjualan"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.managerDashboardData = <?= manager_json($dashboardChartData) ?>;
</script>
<script src="../../public/js/dashboard_manager.js"></script>
<?php include '../../components/manager/footer_manager.php'; ?>