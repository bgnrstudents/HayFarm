<?php
$dataHewan = [
    [
        'id' => '00004',
        'jenis' => 'Sapi Perah',
        'berat' => '450 Kg',
        'kelamin' => 'Betina',
        'kandang' => 'A-01',
        'tgl_lahir' => '2021-03-15',
        'status' => 'produktif',
    ],
    [
        'id' => '00005',
        'jenis' => 'Sapi PO',
        'berat' => '510 Kg',
        'kelamin' => 'Jantan',
        'kandang' => 'A-03',
        'tgl_lahir' => '2020-11-08',
        'status' => 'produktif',
    ],
    [
        'id' => '00006',
        'jenis' => 'Kambing',
        'berat' => '65 Kg',
        'kelamin' => 'Betina',
        'kandang' => 'B-02',
        'tgl_lahir' => '2023-01-27',
        'status' => 'tidak_produktif',
    ],
    [
        'id' => '00008',
        'jenis' => 'Domba',
        'berat' => '72 Kg',
        'kelamin' => 'Jantan',
        'kandang' => 'C-01',
        'tgl_lahir' => '2022-07-04',
        'status' => 'produktif',
    ],
    [
        'id' => '00010',
        'jenis' => 'Kambing',
        'berat' => '58 Kg',
        'kelamin' => 'Betina',
        'kandang' => 'B-04',
        'tgl_lahir' => '2024-02-10',
        'status' => 'tidak_produktif',
    ],
];

function labelStatus(string $status): string
{
    return $status === 'produktif' ? 'Produktif' : 'Tidak Produktif';
}

function classStatus(string $status): string
{
    return $status === 'produktif' ? 'status-produktif' : 'status-tidak-produktif';
}

function usiaLabel(string $tanggal): string
{
    $lahir = new DateTime($tanggal);
    $hariIni = new DateTime();
    $usia = $lahir->diff($hariIni);

    if ($usia->y > 0) {
        return $usia->y . ' Tahun';
    }

    return max($usia->m, 1) . ' Bulan';
}

function catatanStatus(string $status): string
{
    return $status === 'produktif'
        ? 'Hewan berada pada fase aktif dan siap untuk operasional ternak.'
        : 'Hewan sedang dipantau dan belum masuk fase produktif aktif.';
}

$totalHewan = count($dataHewan);
$totalProduktif = count(array_filter($dataHewan, fn($row) => $row['status'] === 'produktif'));
$totalTidakProduktif = $totalHewan - $totalProduktif;
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

<link rel="stylesheet" href="../../public/css/admin_dataHewan.css">
</head>
<body>

<?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

<div class="modal-overlay" id="tambahOverlay" onclick="closeModalOutside(event, 'tambahOverlay')">
    <div class="form-modal-box">
        <div class="modal-header-row">
            <div>
                <h4 class="modal-title">Tambah Data Hewan</h4>
                <p class="modal-subtitle">Catat identitas ternak baru langsung dari halaman data hewan</p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeTambah()"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="tambahForm">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">ID Hewan</label>
                    <input type="text" name="id_hewan" class="form-control" placeholder="Contoh: 00011" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Hewan</label>
                    <select name="jenis_hewan" class="form-select" required>
                        <option value="" selected disabled>Pilih jenis hewan</option>
                        <option value="Sapi Perah">Sapi Perah</option>
                        <option value="Sapi PO">Sapi PO</option>
                        <option value="Kambing">Kambing</option>
                        <option value="Domba">Domba</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Berat Badan Hewan</label>
                    <input type="text" name="berat_badan" class="form-control" placeholder="Contoh: 85 Kg" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="" selected disabled>Pilih jenis kelamin</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Kandang</label>
                    <input type="text" name="no_kandang" class="form-control" placeholder="Contoh: B-05" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" required>
                </div>
            </div>

            <label class="form-label mt-3">Status Produktivitas</label>
            <div class="health-status-group">
                <div class="health-status-btn status-produktif active" id="tambah-btn-produktif" onclick="selectTambahStatus('produktif', this)">
                    <i class="bi bi-check-circle"></i>
                    <span>Produktif</span>
                </div>
                <div class="health-status-btn status-tidak-produktif" id="tambah-btn-tidak-produktif" onclick="selectTambahStatus('tidak_produktif', this)">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Tidak Produktif</span>
                </div>
            </div>
            <input type="hidden" name="status" id="tambahStatusValue" value="produktif">

            <div class="tips-box">
                <h6><i class="bi bi-lightbulb"></i> Tips Pengisian</h6>
                <ul>
                    <li>Pastikan ID hewan sesuai pencatatan kandang.</li>
                    <li>Gunakan status produktivitas sesuai kondisi aktual ternak.</li>
                    <li>Cek berat badan dan tanggal lahir sebelum menyimpan.</li>
                </ul>
            </div>

            <div class="btn-footer">
                <button type="button" class="btn-kembali" onclick="closeTambah()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editOverlay" onclick="closeModalOutside(event, 'editOverlay')">
    <div class="form-modal-box">
        <div class="modal-header-row">
            <div>
                <h4 class="modal-title">Edit Data Hewan</h4>
                <p class="modal-subtitle">Kelola identitas, kandang, dan status produktivitas ternak Anda</p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeEdit()"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="summary-strip">
            <div class="summary-item"><span>ID Hewan</span><strong id="editSummaryId">-</strong></div>
            <div class="summary-item"><span>Jenis</span><strong id="editSummaryJenis">-</strong></div>
            <div class="summary-item"><span>Usia</span><strong id="editSummaryUsia">-</strong></div>
            <div class="summary-item"><span>Status</span><strong id="editSummaryStatus">-</strong></div>
        </div>

        <form id="editHewanForm">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">ID Hewan</label>
                    <input type="text" name="id_hewan" id="editIdHewan" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Hewan</label>
                    <select name="jenis_hewan" id="editJenisHewan" class="form-select">
                        <option value="Sapi Perah">Sapi Perah</option>
                        <option value="Sapi PO">Sapi PO</option>
                        <option value="Kambing">Kambing</option>
                        <option value="Domba">Domba</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Berat Badan Hewan</label>
                    <input type="text" name="berat_badan" id="editBeratBadan" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="editJenisKelamin" class="form-select">
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Kandang</label>
                    <input type="text" name="no_kandang" id="editNoKandang" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="editTanggalLahir" class="form-control">
                </div>
            </div>

            <label class="form-label mt-3">Status Produktivitas</label>
            <div class="health-status-group">
                <div class="health-status-btn status-produktif" id="edit-btn-produktif" onclick="selectEditStatus(this, 'produktif')">
                    <i class="bi bi-check-circle"></i>
                    <span>Produktif</span>
                </div>
                <div class="health-status-btn status-tidak-produktif" id="edit-btn-tidak-produktif" onclick="selectEditStatus(this, 'tidak_produktif')">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Tidak Produktif</span>
                </div>
            </div>
            <input type="hidden" name="status" id="editStatusValue" value="produktif">

            <div class="tips-box">
                <h6><i class="bi bi-lightbulb"></i> Ringkasan Data Saat Ini</h6>
                <p>Data yang dipilih dari tabel akan langsung muncul di form ini tanpa pindah halaman.</p>
            </div>

            <div class="btn-footer">
                <button type="button" class="btn-kembali" onclick="closeEdit()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div class="Preview-overlay" id="previewOverlay" onclick="closePreviewOutside(event)">
    <div class="ringkasan-card">
        <div class="card-body">
            <div class="header-section">
                <div>
                    <h2 style="color:#111;font-size:22px;font-weight:800;">Preview Hewan</h2>
                    <p class="subtitle-preview">Data hewan ternak aktif</p>
                </div>
                <div class="id-badge">ID: <span id="previewIdBadge">-</span></div>
            </div>

            <div class="preview-image-container">
                <img id="previewImage" src="" alt="Preview hewan">
            </div>

            <div class="section-title">Informasi Ternak</div>
            <div class="data-grid">
                <div class="info-box"><span class="label">Jenis Hewan</span><span class="value" id="previewJenis">-</span></div>
                <div class="info-box"><span class="label">Berat Badan</span><span class="value" id="previewBerat">-</span></div>
                <div class="info-box"><span class="label">Tanggal Lahir</span><span class="value" id="previewTanggalLahir">-</span></div>
                <div class="info-box"><span class="label">Usia</span><span class="value" id="previewUsia">-</span></div>
                <div class="info-box"><span class="label">Nomor Kandang</span><span class="value" id="previewKandang">-</span></div>
                <div class="info-box"><span class="label">Jenis Kelamin</span><span class="value" id="previewKelamin">-</span></div>
                <div class="info-box">
                    <span class="label">Status</span>
                    <div class="status-pill-preview" id="previewStatusWrap">
                        <div class="status-dot"></div>
                        <span class="preview-status-text" id="previewStatus">-</span>
                    </div>
                </div>
            </div>

            <div class="footer-actions">
                <button type="button" class="btn-back" onclick="closePreview()">Tutup Preview</button>
            </div>
        </div>
    </div>
</div>

<div class="Delete-overlay" id="deleteOverlay" onclick="closeDeleteOutside(event)">
    <div class="Delete-box">
        <div class="Delete-header-custom">
            <div class="icon-circle"><i class="fas fa-trash-alt"></i></div>
            <h2 class="Delete-title-custom">Apakah Anda yakin?</h2>
        </div>
        <div class="Delete-body-custom">
            <p>Anda akan menghapus: <strong id="deleteTarget">data ini</strong></p>
            <p class="warning-text">Tindakan ini tidak dapat dibatalkan. Data akan dihapus secara permanen.</p>
        </div>
        <div class="btn-group-custom">
            <button type="button" class="btn-custom btn-cancel" onclick="closeDelete()">Batal</button>
            <button type="button" class="btn-custom btn-delete" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>

<div class="main-content">
<?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

<div class="header">
    <div>
        <h2>Data Hewan</h2>
        <p>Kelola identitas ternak dengan popup preview, edit, tambah, dan hapus langsung dari satu halaman.</p>
    </div>
    <button type="button" class="btn-add" onclick="openTambah()">
        + Tambah Data Hewan
    </button>
</div>

<div class="stats">
    <div class="stat-card"><h4>Total Hewan</h4><h2><?= $totalHewan ?></h2></div>
    <div class="stat-card"><h4>Produktif</h4><h2><?= $totalProduktif ?></h2></div>
    <div class="stat-card"><h4>Tidak Produktif</h4><h2><?= $totalTidakProduktif ?></h2></div>
</div>

<div class="table-box">
    
    <table>
        <thead>
            <tr>
                <th>ID Hewan</th>
                <th>Jenis Hewan</th>
                <th>Berat</th>
                <th>Kelamin</th>
                <th>No Kandang</th>
                <th>Tanggal Lahir</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="dataTableBody">
            <?php foreach ($dataHewan as $row): ?>
                <?php
                $recordJson = htmlspecialchars(json_encode([
                    'id' => $row['id'],
                    'jenis' => $row['jenis'],
                    'berat' => $row['berat'],
                    'kelamin' => $row['kelamin'],
                    'kandang' => $row['kandang'],
                    'tgl_lahir' => $row['tgl_lahir'],
                    'status' => $row['status'],
                    'usia' => usiaLabel($row['tgl_lahir']),
                    'status_label' => labelStatus($row['status']),
                    'status_class' => classStatus($row['status']),
                    'catatan' => catatanStatus($row['status']),
                ]), ENT_QUOTES, 'UTF-8');
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td><?= htmlspecialchars($row['berat']) ?></td>
                    <td><?= htmlspecialchars($row['kelamin']) ?></td>
                    <td><?= htmlspecialchars($row['kandang']) ?></td>
                    <td><?= htmlspecialchars(date('d M Y', strtotime($row['tgl_lahir']))) ?></td>
                    <td><span class="status-pill <?= classStatus($row['status']) ?>"><?= labelStatus($row['status']) ?></span></td>
                    <td class="action">
                        <button type="button" title="Lihat" data-record="<?= $recordJson ?>" onclick="openPreview(this)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="edit" title="Edit" data-record="<?= $recordJson ?>" onclick="openEdit(this)">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button type="button" title="Hapus" onclick="openDelete('<?= htmlspecialchars($row['jenis'] . ' - ' . $row['id'], ENT_QUOTES) ?>')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
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

<script src="../../public/js/dataHewan_admin.js"></script>
</body>
</html>