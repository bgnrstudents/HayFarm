<?php
require_once __DIR__ . '/manager_bootstrap.php';

$pageTitle = 'Detail Hewan Ternak';
$populationReport = manager_make_report('populasi');
$healthReport = manager_make_report('kesehatan');

$animals = $populationReport->getBaseRows();
$selectedAnimalId = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($animals[0]['id'] ?? 0);
$animal = $animals[0] ?? [
    'id' => 0,
    'kode' => '-',
    'nama' => 'Data tidak tersedia',
    'jenis' => '-',
    'berat' => '-',
    'jenis_kelamin' => '-',
    'tanggal_lahir' => '',
    'umur' => '-',
    'kandang' => '-',
    'status_kesehatan' => '-',
    'pemeriksaan_terakhir' => '',
    'total_pemeriksaan' => 0,
    'total_reproduksi' => 0,
    'catatan_medis' => 'Belum ada data ternak.',
    'gambar' => '../../public/images/bgheader_produk.png',
];

foreach ($animals as $candidate) {
    if ((int) ($candidate['id'] ?? 0) === $selectedAnimalId) {
        $animal = $candidate;
        break;
    }
}

$healthHistory = array_values(array_filter($healthReport->getBaseRows(), static function (array $record) use ($selectedAnimalId): bool {
    return (int) ($record['id_hewan'] ?? 0) === $selectedAnimalId;
}));

$reproductionHistory = manager_get_reproduction_history($selectedAnimalId);

include '../../components/manager/header_manager.php';
include '../../components/manager/sidebar_manager.php';
include '../../components/manager/topbar_manager.php';
?>

<div class="content-wrapper detail-wrapper">
    <div class="detail-page-header">
        <div class="back-title">
            <a href="javascript:history.back()">&#8592;</a>
            Detail Hewan Ternak
        </div>
        <button type="button" class="btn btn-success d-flex align-items-center gap-2" onclick="exportPDF()">
            <i class="fa fa-file-pdf"></i> Export PDF
        </button>
    </div>

    <div class="detail-top-section">
        <div class="card-info-hewan">
            <img src="<?= manager_escape($animal['gambar']) ?>"
                alt="Foto <?= manager_escape($animal['nama']) ?>"
                onerror="this.onerror=null;this.src='../../public/images/bgheader_produk.png';">

            <div class="info-grid gap-2">

                <span class="info-label">Kode Hewan</span>
                <span class="info-value">: <?= manager_escape((string) $animal['kode']) ?></span>

                <span class="info-label">Jenis</span>
                <span class="info-value">: <?= manager_escape($animal['jenis']) ?></span>

                <span class="info-label">Berat (Kg)</span>
                <span class="info-value">: <?= manager_escape((string) ($animal['berat'] ?? '-')) ?></span>

                <span class="info-label">Jenis Kelamin</span>
                <span class="info-value">: <?= manager_escape($animal['jenis_kelamin'] ?? $animal['kelamin'] ?? '-') ?></span>

                <span class="info-label">Tanggal Lahir</span>
                <span class="info-value">: <?= manager_escape(manager_format_date((string) ($animal['tanggal_lahir'] ?? $animal['tgl_lahir'] ?? ''))) ?>
                </span>

                <span class="info-label">Umur</span>
                <span class="info-value">: <?= manager_escape($animal['umur']) ?></span>

                <span class="info-label">Lokasi</span>
                <span class="info-value">: <?= manager_escape($animal['kandang']) ?></span>

                <span class="info-label">Status</span>
                <span class="info-value">:
                    <span class="badge rounded-pill <?= manager_badge_class($animal['status_kesehatan']) ?>">
                        <?= manager_escape($animal['status_kesehatan']) ?>
                    </span>
                </span>

            </div>
        </div>

        <div class="detail-stats-col">
            <div class="card-pemeriksaan-terakhir shadow-sm">
                <small>Pemeriksaan Terakhir</small>
                <div class="tanggal"><?= manager_escape(manager_format_date((string) $animal['pemeriksaan_terakhir'])) ?></div>
            </div>
            <div class="stats-row">
                <div class="card-stat shadow-sm">
                    <small>Total Pemeriksaan</small>
                    <div class="stat-number"><?= manager_escape((string) ($animal['total_pemeriksaan'] ?? 0)) ?></div>
                    <div class="stat-sub">Tahun Ini</div>
                </div>
                <div class="card-stat shadow-sm">
                    <small>Total Reproduksi</small>
                    <div class="stat-number"><?= manager_escape((string) ($animal['total_reproduksi'] ?? 0)) ?></div>
                    <div class="stat-sub">Riwayat IB</div>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-bottom-section">
        <div class="detail-tables-col">
            <div class="card-table shadow-sm">
                <h6>Riwayat Reproduksi</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal IB</th>
                                <th>Perkiraan Lahir</th>
                                <th>Hasil</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($reproductionHistory === []): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada riwayat reproduksi.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reproductionHistory as $record): ?>
                                    <tr>
                                        <td><?= manager_escape(manager_format_date((string) $record['tanggal_ib'])) ?></td>
                                        <td><?= manager_escape($record['tgl_perkiraan'] ? manager_format_date((string) $record['tgl_perkiraan']) : '-') ?></td>
                                        <td><?= manager_escape($record['hasil']) ?></td>
                                        <td><?= manager_escape($record['keterangan']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-table shadow-sm">
                <h6>Riwayat Pemeriksaan Lengkap</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Diagnosis</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($healthHistory === []): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada riwayat pemeriksaan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($healthHistory as $record): ?>
                                    <tr>
                                        <td><?= manager_escape(manager_format_date((string) $record['tanggal'])) ?></td>
                                        <td><?= manager_escape($record['diagnosis']) ?></td>
                                        <td><?= manager_escape($record['tindakan']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card-catatan shadow-sm">
            <h6>Catatan Medis</h6>
            <p><?= manager_escape($animal['catatan_medis']) ?></p>
        </div>
    </div>

    <div class="detail-footer">
        Terakhir Update : <?= manager_escape(manager_format_date((string) $animal['pemeriksaan_terakhir'], 'd-m-Y')) ?>
    </div>
</div>

<script>
    window.exportPDF = function exportPDFDetail() {
        const params = new URLSearchParams({
            report: 'detail_hewan',
            format: 'pdf',
            animal_id: '<?= manager_escape((string) ($animal['id'] ?? 0)) ?>'
        });

        window.location.href = `../../pages/manager/export_report.php?${params.toString()}`;
    };
</script>

<?php include '../../components/manager/footer_manager.php'; ?>
