<?php
require_once __DIR__ . '/manager_bootstrap.php';

$pageTitle = 'Laporan Transaksi Penjualan';
$report = manager_make_report('transaksi', $_GET);

$monthOptions = $report->getMonthOptions();
$yearOptions = $report->getYearOptions();
$productOptions = $report->getCategoryOptions();
$selectedMonth = $report->getSelectedMonth();
$selectedYear = $report->getSelectedYear();
$selectedProduct = $report->getSelectedCategory();
$selectedLabels = $report->getSelectedFilterLabels();
$filteredTransactions = $report->getRows();
$transactionCards = $report->getSummaryCards();
$transactionChartData = $report->getChartData();

include '../../components/manager/header_manager.php';
include '../../components/manager/sidebar_manager.php';
include '../../components/manager/topbar_manager.php';
?>

<div class="content-wrapper m-3">
    <h1 class="page-head">Laporan Transaksi Penjualan Per-Tahun</h1>

    <form class="filter-container d-flex gap-2 mb-3 flex-wrap" method="get">
        <select id="month" name="month" class="form-select w-auto">
            <?php foreach ($monthOptions as $month): ?>
                <option value="<?= manager_escape($month['value']) ?>" <?= $month['value'] === $selectedMonth ? 'selected' : '' ?>><?= manager_escape($month['label']) ?></option>
            <?php endforeach; ?>
        </select>

        <select id="year" name="year" class="form-select w-auto">
            <?php foreach ($yearOptions as $year): ?>
                <option value="<?= manager_escape($year['value']) ?>" <?= $year['value'] === $selectedYear ? 'selected' : '' ?>><?= manager_escape($year['label']) ?></option>
            <?php endforeach; ?>
        </select>

        <select id="category" name="category" class="form-select w-auto">
            <?php foreach ($productOptions as $product): ?>
                <option value="<?= manager_escape($product['value']) ?>" <?= $product['value'] === $selectedProduct ? 'selected' : '' ?>><?= manager_escape($product['label']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-success">Terapkan Filter</button>
        <button type="button" class="btn btn-success" onclick="exportPDF()"><i class="fa fa-file-pdf"></i> Export PDF</button>
    </form>

    <div class="row g-3 mb-4">
        <?php foreach ($transactionCards as $card): ?>
            <div class="col-md-4">
                <div class="card card-transaksi p-3 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon">
                            <img src="<?= manager_escape($card['icon']) ?>" class="icon-lg" alt="<?= manager_escape($card['label']) ?>">
                        </div>
                        <div>
                            <h2 class="mb-0"><?= manager_escape((string) $card['value']) ?></h2>
                            <small><?= manager_escape($card['label']) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-md-6">
            <div class="card chart-card p-4 shadow-sm">
                <h6>Penjualan Per Jenis Produk</h6>
                <div class="chart-wrap chart-wrap-lg">
                    <canvas id="transaksiProChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card chart-card p-4 shadow-sm">
                <h6>Trend Penjualan 1 Tahun Terakhir</h6>
                <div class="chart-wrap chart-wrap-lg">
                    <canvas id="transaksiTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <h6 class="mb-0">Detail Transaksi Penjualan</h6>
            <small class="text-muted">Filter aktif: <?= manager_escape($selectedLabels['category']) ?>, <?= manager_escape($selectedLabels['month']) ?>, <?= manager_escape($selectedLabels['year']) ?></small>
        </div>
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
                    <?php if ($filteredTransactions === []): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi untuk filter ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredTransactions as $transaction): ?>
                            <tr>
                                <td><?= manager_escape($transaction['id']) ?></td>
                                <td><?= manager_escape(manager_format_date($transaction['tanggal'], 'd-m-Y')) ?></td>
                                <td><?= manager_escape($transaction['jenis_produk']) ?></td>
                                <td><?= manager_escape($transaction['nama_pembeli']) ?></td>
                                <td><?= manager_escape($transaction['total_harga']) ?></td>
                                <td><?= manager_escape($transaction['metode']) ?></td>
                                <td><span class="badge <?= manager_badge_class($transaction['status']) ?>"><?= manager_escape($transaction['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.managerExportConfig = { reportType: 'transaksi' };
    window.managerTransactionData = <?= manager_json($transactionChartData) ?>;
</script>
<script src="../../public/js/transaksi_manager.js"></script>
<?php include '../../components/manager/footer_manager.php'; ?>
