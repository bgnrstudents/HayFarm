<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../process/models/kesehatan.php';
require_once __DIR__ . '/../../process/models/reproduksi.php';
require_once __DIR__ . '/../../process/models/hewan.php';


$database = new Database();
$db = $database->getConnection();
$kesehatan = new Kesehatan($db);
$hewanModel = new Hewan($db);

$dataRaw = $kesehatan->getAll();
$animals = $kesehatan->getAnimalsList();


// Mapping data kesehatan
$dataKesehatan = array_map(function ($row) {
    return [
        'id_kesehatan' => (int)$row['id_kesehatan'],
        'id_hewan' => (int)$row['id_hewan'],
        'kode_hewan' => $row['kode_hewan'] ?? '-',
        'jenis_hewan' => $row['jenis_hewan'] ? ucwords(str_replace('_', ' ', $row['jenis_hewan'])) : '-',
        'tgl_pemeriksaan' => $row['tgl_pemeriksaan'],
        'status_kesehatan' => $row['status_kesehatan'],
        'diagnosis' => $row['diagnosis'],
        'tindakan' => $row['tindakan'],
        'catatan' => $row['catatan'] ?? '',
        // Data reproduksi/IB (akan di-join atau diambil terpisah)
        'tgl_ib' => $row['tgl_ib'] ?? null,
        'ib_ke' => $row['ib_ke'] ?? null,
        'tgl_perkiraan' => $row['tgl_perkiraan'] ?? null,
        'status_ib' => $row['status_ib'] ?? null,
    ];
}, $dataRaw);

$totalSehat = count(array_filter($dataKesehatan, fn($h) => $h['status_kesehatan'] === 'sehat'));
$totalPerawatan = count(array_filter($dataKesehatan, fn($h) => $h['status_kesehatan'] === 'perawatan'));
$totalObservasi = count(array_filter($dataKesehatan, fn($h) => $h['status_kesehatan'] === 'observasi'));

function labelStatusKesehatan($status)
{
    return match ($status) {
        'sehat' => 'Sehat',
        'observasi' => 'Dalam Observasi',
        'perawatan' => 'Dalam Perawatan',
        default => '-'
    };
}

function classStatusKesehatan($status)
{
    return match ($status) {
        'sehat' => 'status-sehat',
        'observasi' => 'status-observasi',
        'perawatan' => 'status-perawatan',
        'dalam_observasi' => 'status-observasi',
        'dalam_perawatan' => 'status-perawatan',
        default => '-'
    };
}
function labelStatusdiagnosis($diagnosis)
{
    return !empty($diagnosis) ? $diagnosis : '-';
}
function labelStatustindakan($tindakan)
{
    return !empty($tindakan) ? $tindakan : '-';
}
function formatTanggal($tanggal)
{
    return !empty($tanggal) && strtotime($tanggal) ? date('d M Y', strtotime($tanggal)) : '-';
}

function labelStatusIB($status)
{
    if (empty($status)) return '-';
    return match ($status) {
        'berhasil' => 'Berhasil',
        'tdk_berhasil' => 'Tidak Berhasil',
        'proses' => 'Proses IB',
        default => '-'
    };
}

function esc($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Data Kesehatan & Reproduksi Hewan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_dataKesehatan.css?v=3">
</head>

<body>

    <?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showFlashMessage('<?= addslashes($_SESSION['flash_message']) ?>', '<?= $_SESSION['flash_type'] ?? 'danger' ?>');
            });
        </script>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    endif; ?>

    <!-- ==================== MODAL TAMBAH ==================== -->
    <div class="modal-overlay" id="tambahOverlay" onclick="closeModalOutside(event, 'tambahOverlay')">
        <div class="form-modal-box">
            <div class="modal-header-row">
                <div>
                    <h4 class="modal-title">Tambah Data Kesehatan & Reproduksi</h4>
                    <p class="modal-subtitle">Catat pemeriksaan kesehatan dan inseminasi buatan (IB)</p>
                </div>
                <button class="modal-close-btn" onclick="closeTambah()"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="tambahForm" action="../../process/handlers/kesehatan_handler.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="row g-3">
                    <!-- DATA KESEHATAN -->
                    <div class="col-md-4">
                        <label class="form-label">Pilih Hewan <span class="text-danger">*</span></label>
                        <select name="id_hewan" id="tambahIdHewan" class="form-select" required>
                            <option value="" disabled selected>Pilih hewan</option>
                            <?php foreach ($animals as $animal): ?>
                                <option value="<?= (int)$animal['id_hewan'] ?>">
                                    <?= htmlspecialchars($animal['kode_hewan']) ?> - <?= ucwords(str_replace('_', ' ', $animal['jenis_hewan'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pemeriksaan *</label>
                        <input type="date"
                            name="tgl_pemeriksaan"
                            class="form-control"
                            max="<?= date('Y-m-d') ?>"
                            min="2020-01-01"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nomor Kandang</label>
                        <input type="text" id="tambahNoKandang" class="form-control" readonly
                            placeholder="Otomatis terisi saat pilih hewan"
                            style="background-color: #f8f9fa; cursor: not-allowed;">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Status Kesehatan Hewan <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div class="health-status-btn status-sehat active" id="tambah-btn-sehat" onclick="pilihStatusTambah('sehat', this)">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Sehat</span>
                            </div>
                            <div class="health-status-btn status-perawatan" id="tambah-btn-perawatan" onclick="pilihStatusTambah('perawatan', this)">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>Perawatan</span>
                            </div>
                            <div class="health-status-btn status-observasi" id="tambah-btn-observasi" onclick="pilihStatusTambah('observasi', this)">
                                <i class="bi bi-eye"></i>
                                <span>Observasi</span>
                            </div>
                        </div>
                        <input type="hidden" name="status_kesehatan" id="tambahStatusValue" value="sehat">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Diagnosis <span id="tambah-diagnosis-required" class="text-danger" style="display:none">*</span></label>
                        <input type="text" name="diagnosis" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tindakan <span id="tambah-tindakan-required" class="text-danger" style="display:none">*</span></label>
                        <input type="text" name="tindakan" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="col-12"><label class="form-label">Catatan</label><textarea name="catatan" class="form-control" rows="3"></textarea></div>

                    <!-- ✅ DATA REPRODUKSI / IB (DEFAULT HIDDEN) -->
                    <div id="tambah-section-reproduksi" style="display: none; border-top: 2px dashed #e2e8f0; padding-top: 1rem; margin-top: 1rem;">
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold mb-1"><i class="bi bi-heart-pulse me-2"></i>Data Reproduksi / IB</h6>
                            <small class="text-muted">Terpilih: Sapi Perah/PO Betina. Lengkapi data inseminasi.</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3"><label class="form-label">Tanggal IB</label><input type="date" name="tgl_ib" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">IB ke-</label><input type="number" name="ib_ke" class="form-control" min="1"></div>
                            <div class="col-md-3"><label class="form-label">Perkiraan Lahir</label><input type="date" name="tgl_perkiraan" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">Status IB</label>
                                <select name="status_ib" class="form-select">
                                    <option value="">Belum ada</option>
                                    <option value="proses">Proses IB</option>
                                    <option value="berhasil">Berhasil</option>
                                    <option value="tidak_berhasil">Tidak Berhasil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-footer mt-3">
                    <button type="button" class="btn-kembali" onclick="closeTambah()">Batal</button>
                    <button type="submit" class="btn-simpan"><i class="bi bi-save me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT ==================== -->
    <div class="modal-overlay" id="editOverlay" onclick="closeModalOutside(event, 'editOverlay')">
        <div class="form-modal-box">
            <div class="modal-header-row">
                <div>
                    <h4 class="modal-title">Edit Data Kesehatan & Reproduksi</h4>
                    <p class="modal-subtitle">Perbarui informasi kesehatan dan IB ternak</p>
                </div>
                <button class="modal-close-btn" onclick="closeEdit()"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="editKesehatanForm" action="../../process/handlers/kesehatan_handler.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_kesehatan" id="editIdKesehatanPK">

                <div class="row g-3">
                    <!-- Pilih Hewan -->
                    <div class="col-md-4">
                        <label class="form-label">Hewan</label>

                        <input type="text" id="editHewanDisplay" class="form-control" readonly
                            placeholder="Memuat data hewan..."
                            style="background-color: #f8f9fa; cursor: not-allowed;">

                        <input type="hidden" name="id_hewan" id="editIdHewan">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pemeriksaan *</label>
                        <input type="date" name="tgl_pemeriksaan" id="editTanggalPemeriksaan" class="form-control" max="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nomor Kandang</label>
                        <input type="text" id="editNoKandang" class="form-control" readonly
                            placeholder="Otomatis terisi saat pilih hewan"
                            style="background-color: #f8f9fa; cursor: not-allowed;">
                    </div>

                    <!-- Status Kesehatan -->
                    <div class="col-12">
                        <label class="form-label">Status Kesehatan Hewan <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div class="health-status-btn status-sehat active" id="edit-btn-sehat" onclick="pilihStatusEdit('sehat', this)">
                                <i class="bi bi-check-circle"></i><span>Sehat</span>
                            </div>
                            <div class="health-status-btn status-observasi" id="edit-btn-observasi" onclick="pilihStatusEdit('observasi', this)">
                                <i class="bi bi-eye"></i><span>Dalam Observasi</span>
                            </div>
                            <div class="health-status-btn status-perawatan" id="edit-btn-perawatan" onclick="pilihStatusEdit('perawatan', this)">
                                <i class="bi bi-exclamation-circle"></i><span>Dalam Perawatan</span>
                            </div>
                        </div>
                        <input type="hidden" name="status_kesehatan" id="editStatusValue" value="sehat">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Diagnosis <span id="edit-diagnosis-required" class="text-danger" style="display:none">*</span></label>
                        <input type="text" name="diagnosis" id="editDiagnosis" class="form-control" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tindakan <span id="edit-tindakan-required" class="text-danger" style="display:none">*</span></label>
                        <input type="text" name="tindakan" id="editTindakan" class="form-control" maxlength="255">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" id="editCatatan" class="form-control" rows="3"></textarea>
                    </div>

                    <!--  IB SECTION - Hanya muncul untuk Sapi Betina -->
                    <div id="edit-section-reproduksi" style="display: none; border-top: 2px dashed #e2e8f0; padding-top: 1rem; margin-top: 1rem; width: 100%;">
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold mb-1"><i class="bi bi-heart-pulse me-2"></i>Data Reproduksi / IB</h6>
                            <small class="text-muted">Hanya untuk Sapi Perah / PO Betina</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal IB</label>
                                <input type="date" name="tgl_ib" id="editTglIb" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">IB ke-</label>
                                <input type="number" name="ib_ke" id="editIbKe" class="form-control" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Perkiraan Lahir</label>
                                <input type="date" name="tgl_perkiraan" id="editTglPerkiraan" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status IB</label>
                                <select name="status_ib" id="editStatusIb" class="form-select">
                                    <option value="">Belum ada</option>
                                    <option value="proses">Proses IB</option>
                                    <option value="berhasil">Berhasil</option>
                                    <option value="tidak_berhasil">Tidak Berhasil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-footer">
                    <button type="button" class="btn-kembali" onclick="closeEdit()">Batal</button>
                    <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL PREVIEW ==================== -->
    <div class="Preview-overlay" id="previewOverlay" onclick="closePreviewOutside(event)">
        <div class="ringkasan-card">
            <div class="card-body">
                <div class="header-section">
                    <h2>Preview Kesehatan Hewan</h2>
                </div>

                <div class="section-title">Informasi Hewan</div>
                <div class="data-grid">
                    <div class="info-box"><span class="label">Jenis</span><span class="value" id="previewJenis">-</span></div>
                    <div class="info-box"><span class="label">Kode</span><span class="value" id="previewKodeHewan">-</span></div>
                </div>

                <div class="section-title">Data Kesehatan</div>
                <div class="data-grid">
                    <div class="info-box"><span class="label">Tanggal</span><span class="value" id="previewTanggal">-</span></div>
                    <div class="info-box">
                        <span class="label">Status</span>
                        <span class="status-pill" id="previewStatus">-</span>
                    </div>
                </div>
                <div class="info-box">
                    <span class="label">Diagnosis</span>
                    <p class="value" id="previewDiagnosis">-</p>
                </div>
                <div class="info-box">
                    <span class="label">Tindakan</span>
                    <p class="value" id="previewTindakan">-</p>
                </div>
                <div class="info-box">
                    <span class="label">Catatan</span>
                    <p class="value" id="previewCatatan">-</p>
                </div>

                <div class="section-title">Data Reproduksi / IB</div>
                <div class="data-grid">
                    <div class="info-box"><span class="label">Tanggal IB</span><span class="value" id="previewTglIb">-</span></div>
                    <div class="info-box"><span class="label">IB ke-</span><span class="value" id="previewIbKe">-</span></div>
                    <div class="info-box"><span class="label">Perkiraan Lahir</span><span class="value" id="previewTglPerkiraan">-</span></div>
                    <div class="info-box"><span class="label">Status IB</span><span class="value" id="previewStatusIb">-</span></div>
                </div>

                <div class="footer-actions">
                    <button class="btn-back" onclick="closePreview()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL DELETE ==================== -->
    <div class="Delete-overlay" id="deleteOverlay" onclick="closeDeleteOutside(event)">
        <div class="Delete-box">
            <div class="Delete-header-custom">
                <div class="icon-circle"><i class="fas fa-trash-alt"></i></div>
                <h2 class="Delete-title-custom">Konfirmasi Hapus</h2>
            </div>
            <div class="Delete-body-custom">
                <p>Anda akan menghapus data kesehatan: <strong id="deleteTarget">-</strong></p>
                <p class="warning-text">⚠️ Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            </div>
            <div class="btn-group-custom">
                <button class="btn-custom btn-cancel" onclick="closeDelete()">Batal</button>
                <form id="deleteKesehatanForm" action="../../process/handlers/kesehatan_handler.php" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_kesehatan" id="deleteIdKesehatanPK">
                    <button type="submit" class="btn-custom btn-delete">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content">
        <?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

        <div class="header">
            <div>
                <h2>Data Kesehatan & Reproduksi Hewan</h2>
                <p>Pantau kesehatan dan siklus reproduksi (IB) ternak secara real-time</p>
            </div>
            <button class="btn-add" onclick="openTambah()">+ Tambah Catatan</button>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h4>Sehat</h4>
                <h2><?= $totalSehat ?></h2>
            </div>
            <div class="stat-card">
                <h4>Dalam Observasi</h4>
                <h2><?= $totalObservasi ?></h2>
            </div>
            <div class="stat-card">
                <h4>Dalam Perawatan</h4>
                <h2><?= $totalPerawatan ?></h2>
            </div>
        </div>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Diagnosis</th>
                        <th>Tindakan</th>
                        <th>IB ke-</th>
                        <th>Status IB</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="kesehatanTableBody">
                    <?php if (empty($dataKesehatan)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">Belum ada data kesehatan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataKesehatan as $row): ?>
                            <?php
                            // ✅ Build array dulu
                            $recordArray = [
                                'id_kesehatan' => (int)$row['id_kesehatan'],
                                'id_hewan' => (int)$row['id_hewan'],
                                'kode_hewan' => $row['kode_hewan'] ?? '-',
                                'jenis_hewan' => $row['jenis_hewan'],
                                'tgl_pemeriksaan' => $row['tgl_pemeriksaan'],
                                'status_kesehatan' => $row['status_kesehatan'],
                                'diagnosis' => $row['diagnosis'],
                                'tindakan' => $row['tindakan'],
                                'catatan' => $row['catatan'] ?? '',
                                // ✅ Field reproduksi (sekarang sudah ada karena join)
                                'tgl_ib' => $row['tgl_ib'],
                                'ib_ke' => $row['ib_ke'],
                                'tgl_perkiraan' => $row['tgl_perkiraan'],
                                'status_ib' => $row['status_ib']
                            ];

                            // ✅ Encode JSON dengan flag aman + error check
                            $recordJson = json_encode($recordArray, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

                            if ($recordJson === false) {
                                error_log("JSON encode failed: " . json_last_error_msg());
                                $recordJson = '{}'; // Fallback
                            }

                            // ✅ Esc untuk atribut HTML
                            $recordJson = htmlspecialchars($recordJson, ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td><?= esc($row['kode_hewan']) ?></td>
                                <td><?= esc($row['jenis_hewan']) ?></td>
                                <td><?= formatTanggal($row['tgl_pemeriksaan']) ?></td>
                                <td><span class="status-badge <?= classStatusKesehatan($row['status_kesehatan']) ?>"><?= labelStatusKesehatan($row['status_kesehatan']) ?></span></td>
                                <td><?= labelStatusdiagnosis($row['diagnosis']) ?></td>
                                <td><?= labelStatustindakan($row['tindakan']) ?></td>
                                <td><?= esc($row['ib_ke'] ?? '-') ?></td>
                                <td><?= labelStatusIB($row['status_ib']) ?></td>
                                <td class="action">
                                    <button type="button" title="Lihat" data-record="<?= $recordJson ?>" onclick="openPreview(this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" title="Edit" data-record="<?= $recordJson ?>" onclick="openEdit(this)">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <button type="button" title="Hapus"
                                        onclick="openDelete('<?= esc($row['kode_hewan'] . ' - ' . $row['diagnosis']) ?>', <?= (int)$row['id_kesehatan'] ?>)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination">
                <span>Menampilkan 1-<?= count($dataKesehatan) ?> dari <?= count($dataKesehatan) ?> data</span>
                <div>
                    <button class="page-btn">Sebelumnya</button>
                    <button class="page-btn active-page">1</button>
                    <button class="page-btn">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../public/js/dataKesehatan_admin.js?v=3"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupAdminPagination('#kesehatanTableBody', '.table-box .pagination', 5);
        });
        window.animalsData = <?= json_encode($animals) ?>;
    </script>
</body>

</html>