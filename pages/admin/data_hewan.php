<?php
session_start();

if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/database.php';
require_once '../../process/models/hewan.php';

// Koneksi & Ambil Data
$database = new Database();
$db = $database->getConnection();
$hewan = new Hewan($db);
$dataRaw = $hewan->getAll();

// Mapping Data (Pastikan id_hewan/PK ikut terbawa untuk operasi CRUD)
$dataHewan = array_map(function ($row) {
    return [
        'id_hewan'   => (int)$row['id_hewan'],
        'kode'       => $row['kode_hewan'] ?? '',
        'jenis'      => $row['jenis_hewan'],
        'nama'       => $row['nama_hewan'] ?? '',
        'berat'      => $row['berat_badan'],
        'kelamin'    => $row['jenis_kelamin'],
        'kandang'    => $row['no_kandang'],
        'tgl_lahir'  => $row['tgl_lahir'],
        'foto'       => $row['foto_hewan'] ?? '',
        'status'     => $row['status_hewan'],
    ];
}, $dataRaw);

// Statistik
$totalHewan = count($dataHewan);
$totalProduktif = count(array_filter($dataHewan, fn($h) => $h['status'] === 'produktif'));
$totalTidakProduktif = count(array_filter($dataHewan, fn($h) => $h['status'] === 'tdk_produktif'));

// Helper Functions
function usiaLabel($tgl)
{
    if (empty($tgl)) return '-';
    $lahir = new DateTime($tgl);
    $now = new DateTime();
    $diff = $now->diff($lahir);
    return $diff->y . ' th ' . $diff->m . ' bln';
}

function labelStatus($status)
{
    return match ($status) {
        'produktif' => 'Produktif',
        'tdk_produktif' => 'Tidak Produktif',
        default => '-'
    };
}

function classStatus($status)
{
    return $status === 'produktif' ? 'status-produktif' : 'status-tidak-produktif';
}

function formatTanggal($tgl)
{
    return !empty($tgl) && strtotime($tgl) ? date('d M Y', strtotime($tgl)) : '-';
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
    <title>Admin | Data Hewan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_dataHewan.css?v=4">
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

    <!-- Modal Tambah -->
    <div class="modal-overlay" id="tambahOverlay" onclick="closeModalOutside(event, 'tambahOverlay')">
        <div class="form-modal-box">
            <div class="modal-header-row">
                <div>
                    <h4 class="modal-title">Tambah Data Hewan</h4>
                    <p class="modal-subtitle">Catat identitas ternak baru</p>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeTambah()"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="tambahForm" action="../../process/handlers/hewan_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Kode Hewan *</label>
                        <!-- <input type="text" name="kode_hewan" class="form-control" placeholder="Contoh: HF-001" required maxlength="20"> -->
                        <input type="text"
                            name="kode_hewan"
                            class="form-control"
                            placeholder="Contoh: HF-001"
                            required
                            maxlength="20"
                            pattern="[A-Z0-9\-]+"
                            title="Hanya huruf kapital, angka, dan tanda hubung"
                            oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Hewan *</label>
<select name="jenis_hewan" id="tambahJenisHewan" class="form-select" required>
                            <option value="" disabled selected>Pilih jenis hewan</option>
                            <option value="sapi_perah">Sapi Perah</option>
                            <option value="sapi_po">Sapi PO</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Berat Badan (kg) *</label>
                        <!-- <input type="number" name="berat_badan" class="form-control" placeholder="Contoh: 85" min="0.1" step="0.1" required> -->
                        <input type="number"
                            name="berat_badan"
                            class="form-control"
                            placeholder="Contoh: 450"
                            min="1"
                            step="0.1"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin *</label>
<select name="jenis_kelamin" id="tambahJenisKelamin" class="form-select" required>
                            <option value="" disabled selected>Pilih</option>
                            <option value="jantan">Jantan</option>
                            <option value="betina">Betina</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kandang *</label>
                        <input type="text" name="no_kandang" class="form-control" placeholder="Contoh: B-05" required maxlength="10">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir *</label>
                        <!-- <input type="date" name="tgl_lahir" class="form-control" max="" required> -->
                        <input type="date"
                            name="tgl_lahir"
                            class="form-control"
                            max="<?= date('Y-m-d') ?>"
                            min="2020-01-01"
                            required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Foto Hewan</label>
                        <input type="file" name="foto_hewan" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Format: JPG, PNG, WebP. Maksimal 2MB.</small>
                    </div>
                </div>

                <label class="form-label mt-3">Status Produktivitas</label>
                <div class="health-status-group">
                    <div class="health-status-btn status-produktif active" id="tambah-btn-produktif" onclick="selectTambahStatus('produktif', this)">
                        <i class="bi bi-check-circle"></i><span>Produktif</span>
                    </div>
                    <div class="health-status-btn status-tidak-produktif" id="tambah-btn-tidak-produktif" onclick="selectTambahStatus('tdk_produktif', this)">
                        <i class="bi bi-exclamation-circle"></i><span>Tidak Produktif</span>
                    </div>
                </div>
                <input type="hidden" name="status_hewan" id="tambahStatusValue" value="produktif">

                <div class="btn-footer">
                    <button type="button" class="btn-kembali" onclick="closeTambah()">Batal</button>
                    <button type="submit" class="btn-simpan">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-overlay" id="editOverlay" onclick="closeModalOutside(event, 'editOverlay')">
        <div class="form-modal-box">
            <div class="modal-header-row">
                <div>
                    <h4 class="modal-title">Edit Data Hewan</h4>
                    <p class="modal-subtitle">Perbarui informasi ternak</p>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeEdit()"><i class="bi bi-x-lg"></i></button>
            </div>

            <div class="summary-strip">
                <div class="summary-item"><span>Kode</span><strong id="editSummaryKode">-</strong></div>
                <div class="summary-item"><span>Jenis</span><strong id="editSummaryJenis">-</strong></div>
                <div class="summary-item"><span>Usia</span><strong id="editSummaryUsia">-</strong></div>
                <div class="summary-item"><span>Status</span><strong id="editSummaryStatus">-</strong></div>
            </div>

            <form id="editHewanForm" action="../../process/handlers/hewan_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_hewan" id="editIdHewanPK"> <!-- Primary Key -->

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Kode Hewan</label>
                        <input type="text"
                            name="kode_hewan"
                            id="editKodeHewan"
                            class="form-control"
                            readonly
                            style="background-color: #f8f9fa; cursor: not-allowed;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Hewan</label>
                        <select name="jenis_hewan" id="editJenisHewan" class="form-select">
                            <option value="sapi_perah">Sapi Perah</option>
                            <option value="sapi_po">Sapi PO</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Berat Badan (kg)</label>
                        <input type="number"
                            name="berat_badan"
                            id="editBeratBadan"
                            class="form-control"
                            placeholder="Contoh: 450"
                            min="0.1"
                            step="0.1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="editJenisKelamin" class="form-select">
                            <option value="jantan">Jantan</option>
                            <option value="betina">Betina</option>
                        </select>
                        <small class="text-muted d-block mt-1" id="editKelaminHint">Untuk Sapi Perah otomatis Betina.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kandang</label>
                        <input type="text" name="no_kandang" id="editNoKandang" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date"
                            name="tgl_lahir"
                            id="editTanggalLahir"
                            class="form-control"
                            max="<?= date('Y-m-d') ?>"
                            min="2020-01-01">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Ganti Foto</label>
                        <input type="file" name="foto_hewan" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                    </div>
                </div>

                <label class="form-label mt-3">Status Produktivitas</label>
                <div class="health-status-group">
                    <div class="health-status-btn status-produktif" id="edit-btn-produktif" onclick="selectEditStatus(this, 'produktif')">
                        <i class="bi bi-check-circle"></i><span>Produktif</span>
                    </div>
                    <div class="health-status-btn status-tidak-produktif" id="edit-btn-tidak-produktif" onclick="selectEditStatus(this, 'tdk_produktif')">
                        <i class="bi bi-exclamation-circle"></i><span>Tidak Produktif</span>
                    </div>
                </div>
                <input type="hidden" name="status_hewan" id="editStatusValue" value="produktif">

                <div class="btn-footer">
                    <button type="button" class="btn-kembali" onclick="closeEdit()">Batal</button>
                    <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Preview -->
    <div class="Preview-overlay" id="previewOverlay" onclick="closePreviewOutside(event)">
        <div class="ringkasan-card">
            <div class="card-body">
                <div class="header-section">
                    <div>
                        <h2>Preview Hewan</h2>
                        <p class="subtitle-preview">Detail informasi ternak</p>
                    </div>
                </div>

                <div class="preview-image-container no-image">
                    <img id="previewImage" alt="Foto hewan">
                    <span class="no-image-text">Foto tidak tersedia</span>
                </div>

                <div class="section-title">Informasi Ternak</div>
                <div class="data-grid">
                    <div class="info-box">
                        <span class="label">Kode Hewan</span>
                        <span class="value" id="previewKodeHewan">-</span>
                    </div>
                    <div class="info-box"><span class="label">Jenis</span><span class="value" id="previewJenis">-</span></div>
                    <div class="info-box"><span class="label">Berat</span><span class="value" id="previewBerat">-</span></div>
                    <div class="info-box"><span class="label">Kelamin</span><span class="value" id="previewKelamin">-</span></div>
                    <div class="info-box"><span class="label">Kandang</span><span class="value" id="previewKandang">-</span></div>
                    <div class="info-box"><span class="label">Lahir</span><span class="value" id="previewTanggalLahir">-</span></div>
                    <div class="info-box"><span class="label">Usia</span><span class="value" id="previewUsia">-</span></div>
                    <div class="info-box">
                        <span class="label">Status</span>
                        <div class="status-pill-preview" id="previewStatusWrap">
                            <div class="status-dot"></div>
                            <span class="preview-status-text " id="previewStatus">-</span>
                        </div>
                    </div>
                </div>

                <div class="footer-actions">
                    <button type="button" class="btn-back" onclick="closePreview()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="Delete-overlay" id="deleteOverlay" onclick="closeDeleteOutside(event)">
        <div class="Delete-box">
            <div class="Delete-header-custom">
                <div class="icon-circle"><i class="fas fa-trash-alt"></i></div>
                <h2 class="Delete-title-custom">Konfirmasi Hapus</h2>
            </div>
            <div class="Delete-body-custom">
                <p>Anda akan menghapus: <strong id="deleteTarget">data ini</strong></p>
                <p class="warning-text">⚠️ Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            </div>
            <div class="btn-group-custom">
                <button type="button" class="btn-custom btn-cancel" onclick="closeDelete()">Batal</button>
                <form id="deleteHewanForm" action="../../process/handlers/hewan_handler.php" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_hewan" id="deleteIdHewanPK"> <!-- Primary Key -->
                    <button type="submit" class="btn-custom btn-delete">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

        <div class="header">
            <div>
                <h2>Data Hewan</h2>
                <p>Kelola identitas ternak dengan fitur preview, edit, dan hapus dalam satu halaman.</p>
            </div>
            <button type="button" class="btn-add" onclick="openTambah()">+ Tambah Data</button>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h4>Total Hewan</h4>
                <h2><?= $totalHewan ?></h2>
            </div>
            <div class="stat-card">
                <h4>Produktif</h4>
                <h2><?= $totalProduktif ?></h2>
            </div>
            <div class="stat-card">
                <h4>Tidak Produktif</h4>
                <h2><?= $totalTidakProduktif ?></h2>
            </div>
        </div>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Berat (kg)</th>
                        <th>Kelamin</th>
                        <th>Kandang</th>
                        <th>Tgl. Lahir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    <?php if (!empty($dataHewan)): ?>
                        <?php foreach ($dataHewan as $row): ?>
                            <?php
                            // JSON aman untuk JS, sertakan id_hewan (PK)
                            $recordData = [
                                'id_hewan'   => $row['id_hewan'],
                                'kode'       => $row['kode'],
                                'jenis'      => $row['jenis'],
                                'nama'       => $row['nama'],
                                'berat'      => $row['berat'],
                                'kelamin'    => $row['kelamin'],
                                'kandang'    => $row['kandang'],
                                'tgl_lahir'  => $row['tgl_lahir'],
                                'foto'       => $row['foto'],
                                'status'     => $row['status'],
                                'usia'       => usiaLabel($row['tgl_lahir']),
                                'status_label' => labelStatus($row['status']),
                            ];
                            $recordJson = esc(json_encode($recordData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP));
                            ?>
                            <tr>
                                <td><?= esc($row['kode']) ?></td>
                                <td><?= ucwords(str_replace('_', ' ', esc($row['jenis']))) ?></td>
                                <td><?= esc($row['berat']) ?></td>
                                <td><?= ucwords(esc($row['kelamin'])) ?></td>
                                <td><?= esc($row['kandang']) ?></td>
                                <td><?= formatTanggal($row['tgl_lahir']) ?></td>
                                <td>
                                    <span class="status-pill <?= classStatus($row['status']) ?>">
                                        <?= labelStatus($row['status']) ?>
                                    </span>
                                </td>
                                <td class="action">
                                    <button type="button" title="Lihat" data-record="<?= $recordJson ?>" onclick="openPreview(this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" class="edit" title="Edit" data-record="<?= $recordJson ?>" onclick="openEdit(this)">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <button type="button" title="Hapus"
                                        onclick="openDelete('<?= esc($row['jenis']) ?> - <?= esc($row['kode']) ?>', <?= (int)$row['id_hewan'] ?>)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align:center; padding:20px;">
                                Data hewan belum tersedia
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination">
                <span>Menampilkan 1-<?= $totalHewan ?> dari <?= $totalHewan ?> data</span>
                <div>
                    <button class="page-btn">Sebelumnya</button>
                    <button class="page-btn active-page">1</button>
                    <button class="page-btn">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ MODAL KONFIRMASI DELETE HEWAN (SUDAH DIBELI) -->
    <!-- <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius:16px">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size:3rem"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Hewan Ini Sudah Pernah Dibeli</h5>
                    <p class="text-muted mb-4" id="confirmDeleteMessage">
                        Hewan dengan kode <strong id="confirmHewanKode">-</strong> memiliki riwayat transaksi.
                        <br><br>
                        Apakah Anda yakin ingin menghapusnya? Data kesehatan akan dihapus permanen, dan produk terkait akan diubah menjadi "Tidak Tersedia".
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <form id="finalDeleteForm" action="../../process/handlers/hewan_handler.php" method="POST" style="display:inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_hewan" id="finalDeleteId">
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <script src="../../public/js/dataHewan_admin.js?v=4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupAdminPagination('#dataTableBody', '.table-box .pagination', 5);
        });

        // // ✅ Fungsi buka modal konfirmasi delete
        // function openConfirmDeleteModal(hewanKode, idHewan) {
        //     document.getElementById('confirmHewanKode').textContent = hewanKode;
        //     document.getElementById('finalDeleteId').value = idHewan;

        //     const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        //     modal.show();
        // }

        // // ✅ Override fungsi openDelete yang sudah ada
        // function openDelete(kodeJenis, idHewan) {
        //     // Cek via AJAX apakah hewan sudah pernah dibeli
        //     fetch(`../../process/handlers/check_hewan_beli.php?id_hewan=${idHewan}`)
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.sudah_dibeli) {
        //                 // Jika sudah dibeli → tampilkan modal konfirmasi
        //                 openConfirmDeleteModal(kodeJenis, idHewan);
        //             } else {
        //                 // Jika belum dibeli → langsung tampilkan modal delete biasa
        //                 document.getElementById('deleteTarget').textContent = kodeJenis;
        //                 document.getElementById('deleteIdHewanPK').value = idHewan;
        //                 document.getElementById('deleteOverlay').style.display = 'flex';
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error checking purchase status:', error);
        //             // Fallback: tampilkan modal delete biasa jika error
        //             document.getElementById('deleteTarget').textContent = kodeJenis;
        //             document.getElementById('deleteIdHewanPK').value = idHewan;
        //             document.getElementById('deleteOverlay').style.display = 'flex';
        //         });
        // }

        // // ✅ Tutup modal delete biasa jika klik di luar
        // function closeDeleteOutside(event) {
        //     if (event.target.id === 'deleteOverlay') {
        //         document.getElementById('deleteOverlay').style.display = 'none';
        //     }
        // }
    </script>
</body>

</html>