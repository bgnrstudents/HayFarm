<?php
require_once __DIR__ . '/../../config/database.php';

function labelJenis(string $jenis): string
{
    $labels = [
        'sapi_perah' => 'Sapi Perah',
        'sapi_po' => 'Sapi PO',
        'kambing' => 'Kambing',
        'domba' => 'Domba',
    ];

    return $labels[$jenis] ?? ucwords(str_replace('_', ' ', $jenis));
}

function labelKelamin(string $kelamin): string
{
    return ucfirst(strtolower($kelamin));
}

function normalisasiStatus(string $status): string
{
    return $status === 'tdk_produktif' ? 'tidak_produktif' : $status;
}

function redirectCrud(string $aksi, string $status): void
{
    header("Location: data_hewan.php?$aksi=$status");
    exit;
}

function nilaiPost(string $key): string
{
    return trim($_POST[$key] ?? '');
}

function enumJenisHewan(string $jenis): ?string
{
    $map = [
        'Sapi Perah' => 'sapi_perah',
        'Sapi PO' => 'sapi_po',
        'Kambing' => 'kambing',
        'Domba' => 'domba',
        'sapi_perah' => 'sapi_perah',
        'sapi_po' => 'sapi_po',
        'kambing' => 'kambing',
        'domba' => 'domba',
    ];

    return $map[$jenis] ?? null;
}

function enumJenisKelamin(string $kelamin): ?string
{
    $map = [
        'Jantan' => 'jantan',
        'Betina' => 'betina',
        'jantan' => 'jantan',
        'betina' => 'betina',
    ];

    return $map[$kelamin] ?? null;
}

function enumStatusHewan(string $status): ?string
{
    $map = [
        'produktif' => 'produktif',
        'tidak_produktif' => 'tdk_produktif',
        'tdk_produktif' => 'tdk_produktif',
    ];

    return $map[$status] ?? null;
}

function angkaBerat(string $berat): ?float
{
    $angka = str_replace(',', '.', preg_replace('/[^0-9,.]/', '', $berat));

    if ($angka === '' || !is_numeric($angka) || (float) $angka <= 0) {
        return null;
    }

    return (float) $angka;
}

function tanggalValid(string $tanggal): bool
{
    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    if (!$date || $date->format('Y-m-d') !== $tanggal) {
        return false;
    }

    $hariIni = new DateTime('today');
    return $date <= $hariIni;
}

function uploadFotoHewan(): ?string
{
    if (!isset($_FILES['foto_hewan']) || ($_FILES['foto_hewan']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES['foto_hewan']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $mime = mime_content_type($_FILES['foto_hewan']['tmp_name']);
    if (!isset($allowed[$mime])) {
        return null;
    }

    $uploadDir = __DIR__ . '/../../public/uploads/hewan';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
        return null;
    }

    $filename = 'hewan_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $target = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($_FILES['foto_hewan']['tmp_name'], $target)) {
        return null;
    }

    return 'public/uploads/hewan/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = nilaiPost('aksi');
    $idHewanInput = ltrim(nilaiPost('id_hewan'), '0');

    if ($aksi === 'tambah' || $aksi === 'edit') {
        $jenisHewan = enumJenisHewan(nilaiPost('jenis_hewan'));
        $beratBadan = angkaBerat(nilaiPost('berat_badan'));
        $jenisKelamin = enumJenisKelamin(nilaiPost('jenis_kelamin'));
        $noKandang = nilaiPost('no_kandang');
        $tglLahir = nilaiPost('tgl_lahir');
        $statusHewan = enumStatusHewan(nilaiPost('status'));

        if (
            $jenisHewan === null ||
            $beratBadan === null ||
            $jenisKelamin === null ||
            $noKandang === '' ||
            strlen($noKandang) > 5 ||
            !tanggalValid($tglLahir) ||
            $statusHewan === null
        ) {
            redirectCrud($aksi, 'gagal');
        }
    }

    if ($aksi === 'tambah') {
        if ($idHewanInput === '' || !ctype_digit($idHewanInput)) {
            redirectCrud('tambah', 'gagal');
        }

        $idHewan = (int) $idHewanInput;
        $namaHewan = ucwords(str_replace('_', ' ', $jenisHewan)) . ' ' . str_pad((string) $idHewan, 5, '0', STR_PAD_LEFT);
        $fotoHewan = uploadFotoHewan();
        if ($fotoHewan === null) {
            redirectCrud('tambah', 'gagal');
        }
        $stmt = mysqli_prepare(
            $db,
            'INSERT INTO data_ternak
                (id_hewan, jenis_hewan, nama_hewan, berat_badan, jenis_kelamin, no_kandang, tgl_lahir, foto_hewan, status_hewan)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        if (!$stmt) {
            redirectCrud('tambah', 'gagal');
        }

        mysqli_stmt_bind_param($stmt, 'issdsssss', $idHewan, $jenisHewan, $namaHewan, $beratBadan, $jenisKelamin, $noKandang, $tglLahir, $fotoHewan, $statusHewan);

        if (!mysqli_stmt_execute($stmt)) {
            redirectCrud('tambah', mysqli_errno($db) === 1062 ? 'duplikat' : 'gagal');
        }

        redirectCrud('tambah', 'berhasil');
    }

    if ($aksi === 'edit') {
        if ($idHewanInput === '' || !ctype_digit($idHewanInput)) {
            redirectCrud('edit', 'gagal');
        }

        $idHewan = (int) $idHewanInput;
        $namaHewan = ucwords(str_replace('_', ' ', $jenisHewan)) . ' ' . str_pad((string) $idHewan, 5, '0', STR_PAD_LEFT);
        $fotoHewan = uploadFotoHewan();
        if ($fotoHewan === null) {
            redirectCrud('edit', 'gagal');
        }

        $sql = 'UPDATE data_ternak
             SET jenis_hewan = ?, nama_hewan = ?, berat_badan = ?, jenis_kelamin = ?, no_kandang = ?, tgl_lahir = ?, status_hewan = ?';
        if ($fotoHewan !== '') {
            $sql .= ', foto_hewan = ?';
        }
        $sql .= ' WHERE id_hewan = ?';

        $stmt = mysqli_prepare($db, $sql);

        if (!$stmt) {
            redirectCrud('edit', 'gagal');
        }

        if ($fotoHewan !== '') {
            mysqli_stmt_bind_param($stmt, 'ssdsssssi', $jenisHewan, $namaHewan, $beratBadan, $jenisKelamin, $noKandang, $tglLahir, $statusHewan, $fotoHewan, $idHewan);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssdssssi', $jenisHewan, $namaHewan, $beratBadan, $jenisKelamin, $noKandang, $tglLahir, $statusHewan, $idHewan);
        }

        if (!mysqli_stmt_execute($stmt) || mysqli_stmt_affected_rows($stmt) < 0) {
            redirectCrud('edit', 'gagal');
        }

        redirectCrud('edit', 'berhasil');
    }

    if ($aksi === 'hapus') {
        if ($idHewanInput === '' || !ctype_digit($idHewanInput)) {
            redirectCrud('hapus', 'gagal');
        }

        $idHewan = (int) $idHewanInput;
        $stmt = mysqli_prepare($db, 'DELETE FROM data_ternak WHERE id_hewan = ?');

        if (!$stmt) {
            redirectCrud('hapus', 'gagal');
        }

        mysqli_stmt_bind_param($stmt, 'i', $idHewan);

        if (!mysqli_stmt_execute($stmt)) {
            redirectCrud('hapus', mysqli_errno($db) === 1451 ? 'terpakai' : 'gagal');
        }

        redirectCrud('hapus', mysqli_stmt_affected_rows($stmt) > 0 ? 'berhasil' : 'gagal');
    }
}

$dataHewan = [];
$queryDataHewan = mysqli_query(
    $db,
    "SELECT id_hewan, jenis_hewan, berat_badan, jenis_kelamin, no_kandang, tgl_lahir, foto_hewan, status_hewan
     FROM data_ternak
     ORDER BY id_hewan ASC"
);

if (!$queryDataHewan) {
    die('Gagal mengambil data ternak: ' . mysqli_error($db));
}

while ($row = mysqli_fetch_assoc($queryDataHewan)) {
    $dataHewan[] = [
        'id' => str_pad((string) $row['id_hewan'], 5, '0', STR_PAD_LEFT),
        'jenis' => labelJenis($row['jenis_hewan']),
        'berat' => (float) $row['berat_badan'] . ' Kg',
        'kelamin' => labelKelamin($row['jenis_kelamin']),
        'kandang' => $row['no_kandang'],
        'tgl_lahir' => $row['tgl_lahir'],
        'foto' => $row['foto_hewan'],
        'status' => normalisasiStatus($row['status_hewan']),
    ];
}

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

<link rel="stylesheet" href="../../public/css/admin_dataHewan.css?v=2">
</head>
<body>

<?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

<?php if (isset($_GET['tambah']) || isset($_GET['edit']) || isset($_GET['hapus'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if (($_GET['tambah'] ?? '') === 'berhasil'): ?>
    showFlashMessage('Data hewan berhasil ditambahkan.');
    <?php elseif (($_GET['tambah'] ?? '') === 'duplikat'): ?>
    showFlashMessage('ID hewan sudah ada di database. Gunakan ID yang berbeda.', 'warning');
    <?php elseif (($_GET['edit'] ?? '') === 'berhasil'): ?>
    showFlashMessage('Data hewan berhasil diperbarui.');
    <?php elseif (($_GET['hapus'] ?? '') === 'berhasil'): ?>
    showFlashMessage('Data hewan berhasil dihapus.');
    <?php elseif (($_GET['hapus'] ?? '') === 'terpakai'): ?>
    showFlashMessage('Data hewan tidak bisa dihapus karena masih digunakan oleh data lain.', 'warning');
    <?php else: ?>
    showFlashMessage('Proses data hewan gagal dilakukan.', 'danger');
    <?php endif; ?>
});
</script>
<?php endif; ?>

<div class="modal-overlay" id="tambahOverlay" onclick="closeModalOutside(event, 'tambahOverlay')">
    <div class="form-modal-box">
        <div class="modal-header-row">
            <div>
                <h4 class="modal-title">Tambah Data Hewan</h4>
                <p class="modal-subtitle">Catat identitas ternak baru langsung dari halaman data hewan</p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeTambah()"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="tambahForm" action="data_hewan.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="tambah">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Kode Hewan</label>
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
                    <input type="number" name="berat_badan" class="form-control" placeholder="Contoh: 85" min="0.1" step="0.1" required>
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
                    <input type="date" name="tgl_lahir" class="form-control" max="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Foto Hewan</label>
                    <input type="file" name="foto_hewan" class="form-control" accept="image/jpeg,image/png,image/webp">
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
            <div class="summary-item"><span>Kode Hewan</span><strong id="editSummaryId">-</strong></div>
            <div class="summary-item"><span>Jenis</span><strong id="editSummaryJenis">-</strong></div>
            <div class="summary-item"><span>Usia</span><strong id="editSummaryUsia">-</strong></div>
            <div class="summary-item"><span>Status</span><strong id="editSummaryStatus">-</strong></div>
        </div>

        <form id="editHewanForm" action="data_hewan.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="edit">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Kode Hewan</label>
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
                    <input type="number" name="berat_badan" id="editBeratBadan" class="form-control" min="0.1" step="0.1">
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
                    <input type="date" name="tgl_lahir" id="editTanggalLahir" class="form-control" max="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Ganti Foto Hewan</label>
                    <input type="file" name="foto_hewan" class="form-control" accept="image/jpeg,image/png,image/webp">
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

            <div class="preview-image-container no-image">
                <img id="previewImage" alt="Foto hewan belum tersedia">
                <span class="no-image-text">Foto hewan belum tersedia</span>
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
            <form id="deleteHewanForm" action="data_hewan.php" method="POST">
                <input type="hidden" name="aksi" value="hapus">
                <input type="hidden" name="id_hewan" id="deleteIdHewan">
                <button type="submit" class="btn-custom btn-delete">Hapus</button>
            </form>
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
                <th>Kode Hewan</th>
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
                    'foto' => $row['foto'],
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
                        <button type="button" title="Hapus" onclick="openDelete('<?= htmlspecialchars($row['jenis'] . ' - ' . $row['id'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['id'], ENT_QUOTES) ?>')">
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

<script src="../../public/js/adminPagination.js?v=2"></script>
<script src="../../public/js/dataHewan_admin.js?v=2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    setupAdminPagination('#dataTableBody', '.table-box .pagination', 5);
});
</script>
</body>
</html>