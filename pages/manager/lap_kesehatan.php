<?php
require_once __DIR__ . '/manager_bootstrap.php';

$pageTitle = 'Laporan Kesehatan Hewan';
$report = manager_make_report('kesehatan', $_GET);

$monthOptions = $report->getMonthOptions();
$yearOptions = $report->getYearOptions();
$animalOptions = $report->getCategoryOptions();
$selectedMonth = $report->getSelectedMonth();
$selectedYear = $report->getSelectedYear();
$selectedAnimal = $report->getSelectedCategory();
$selectedLabels = $report->getSelectedFilterLabels();
$filteredHealthRecords = $report->getRows();
$healthCards = $report->getSummaryCards();
$healthChartData = $report->getChartData();

include '../../components/manager/header_manager.php';
include '../../components/manager/sidebar_manager.php';
include '../../components/manager/topbar_manager.php';
?>

<div class="content-wrapper m-3">
    <h1 class="page-head">Laporan Kesehatan Hewan</h1>

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
            <?php foreach ($animalOptions as $animal): ?>
                <option value="<?= manager_escape($animal['value']) ?>" <?= $animal['value'] === $selectedAnimal ? 'selected' : '' ?>><?= manager_escape($animal['label']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-success">Terapkan Filter</button>
        <button type="button" class="btn btn-success" onclick="exportPDF()"><i class="fa fa-file-pdf"></i> Export PDF</button>
    </form>

    <div class="row g-3 mb-4">
        <?php foreach ($healthCards as $card): ?>
            <div class="col-md-3">
                <div class="card card-kesehatan p-3 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon">
                            <img src="<?= manager_escape($card['icon']) ?>" alt="<?= manager_escape($card['label']) ?>">
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
                <h6>Kasus Berdasarkan Jenis Hewan</h6>
                <div class="chart-wrap">
                    <canvas id="kesehatanReproChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card chart-card p-4 shadow-sm">
                <h6>Trend Pemeriksaan Kesehatan 6 Bulan Terakhir</h6>
                <div class="chart-wrap">
                    <canvas id="kesehatanTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <h6 class="mb-0">Detail Riwayat Kesehatan</h6>
            <small class="text-muted">Filter aktif: <?= manager_escape($selectedLabels['category']) ?>, <?= manager_escape($selectedLabels['month']) ?>, <?= manager_escape($selectedLabels['year']) ?></small>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Hewan</th>
                        <th>Jenis Hewan</th>
                        <th>Status</th>
                        <th>Diagnosis</th>
                        <th>Tindakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($filteredHealthRecords === []): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat kesehatan untuk filter ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredHealthRecords as $record): ?>
                            <tr>
                                <td><?= manager_escape($record['kode_hewan']) ?></td>
                                <td><?= manager_escape($record['jenis_hewan']) ?></td>
                                <td><span class="badge <?= manager_badge_class($record['status']) ?>"><?= manager_escape($record['status']) ?></span></td>
                                <td><?= manager_escape($record['diagnosis']) ?></td>
                                <td><?= manager_escape($record['tindakan']) ?></td>
                                <td><a class="btn-detail" href="../../pages/manager/detail_hewan_manager.php?id=<?= urlencode((string) $record['id_hewan']) ?>">Lihat Detail</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.managerExportConfig = { reportType: 'kesehatan' };
    window.managerHealthData = <?= manager_json($healthChartData) ?>;
</script>
<script src="../../public/js/kesehatan_manager.js"></script>
<?php include '../../components/manager/footer_manager.php'; ?>
