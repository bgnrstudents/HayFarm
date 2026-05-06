<?php
require_once __DIR__ . '/../../config/database.php';

function labelJenisKesehatan(string $jenis): string
{
    return [
        'sapi_perah' => 'Sapi Perah',
        'sapi_po' => 'Sapi PO',
        'kambing' => 'Kambing',
        'domba' => 'Domba',
    ][$jenis] ?? ucwords(str_replace('_', ' ', $jenis));
}

function labelStatusKesehatan(string $status): string
{
    return [
        'sehat' => 'Sehat',
        'perawatan' => 'Dalam Perawatan',
        'observasi' => 'Observasi',
    ][$status] ?? '-';
}

function classStatusKesehatan(string $status): string
{
    return [
        'sehat' => 'sehat',
        'perawatan' => 'rawat',
        'observasi' => 'obs',
    ][$status] ?? '';
}

function labelStatusIb(?string $status): string
{
    return [
        'berhasil' => 'Berhasil',
        'tdk_berhasil' => 'Tidak Berhasil',
        '' => '-',
        null => '-',
    ][$status] ?? '-';
}

function labelHamil(?string $statusIb): string
{
    return match ($statusIb) {
        'berhasil' => 'Hamil',
        'tdk_berhasil' => 'Tidak Hamil',
        default => '-',
    };
}

function formatPerkiraanLahir(?string $tanggal, ?string $statusIb): string
{
    return $statusIb === 'berhasil' ? formatTanggalKesehatan($tanggal) : '-';
}

function formatTanggalKesehatan(?string $tanggal): string
{
    if (!$tanggal || $tanggal === '0000-00-00') {
        return '-';
    }

    return date('d M Y', strtotime($tanggal));
}

function identitasHewan(array $row): string
{
    return 'ID ' . str_pad((string) $row['id_hewan'], 5, '0', STR_PAD_LEFT)
        . ' - ' . labelJenisKesehatan($row['jenis_hewan'])
        . ' - Kandang ' . $row['no_kandang'];
}

function nilaiKesehatanPost(string $key): string
{
    return trim($_POST[$key] ?? '');
}

function redirectKesehatan(string $status): void
{
    header("Location: data_kesehatan.php?status=$status");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'tambah') {
    $idHewan = (int) nilaiKesehatanPost('id_hewan');
    $tglPemeriksaan = nilaiKesehatanPost('tgl_pemeriksaan') ?: date('Y-m-d');
    $statusKesehatan = nilaiKesehatanPost('status');
    $diagnosis = nilaiKesehatanPost('diagnosis');
    $tindakan = nilaiKesehatanPost('tindakan');
    $catatan = nilaiKesehatanPost('catatan');

    if ($idHewan <= 0 || !in_array($statusKesehatan, ['sehat', 'perawatan', 'observasi'], true) || $diagnosis === '' || $tindakan === '') {
        redirectKesehatan('gagal');
    }

    $stmt = mysqli_prepare(
        $db,
        'INSERT INTO data_kesehatan (id_hewan, tgl_pemeriksaan, status_kesehatan, diagnosis, tindakan, catatan)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    if (!$stmt) {
        redirectKesehatan('gagal');
    }

    mysqli_stmt_bind_param($stmt, 'isssss', $idHewan, $tglPemeriksaan, $statusKesehatan, $diagnosis, $tindakan, $catatan);
    if (!mysqli_stmt_execute($stmt)) {
        redirectKesehatan('gagal');
    }

    $tglIb = nilaiKesehatanPost('tgl_awal_ib');
    $ibKe = (int) nilaiKesehatanPost('ib_ke');
    $statusIb = nilaiKesehatanPost('status_ib');
    $tglPerkiraan = nilaiKesehatanPost('perkiraan_tgl_lahir');

    if ($tglIb !== '' && $ibKe > 0 && in_array($statusIb, ['berhasil', 'tdk_berhasil'], true)) {
        if ($tglPerkiraan === '' && $statusIb === 'berhasil') {
            $perkiraan = new DateTime($tglIb);
            $perkiraan->modify('+9 months');
            $tglPerkiraan = $perkiraan->format('Y-m-d');
        }
        $tglPerkiraan = $tglPerkiraan ?: '0000-00-00';

        $stmtRepro = mysqli_prepare(
            $db,
            'INSERT INTO data_reproduksi (id_hewan, tgl_ib, ib_ke, tgl_perkiraan, status_ib)
             VALUES (?, ?, ?, ?, ?)'
        );

        if ($stmtRepro) {
            mysqli_stmt_bind_param($stmtRepro, 'isiss', $idHewan, $tglIb, $ibKe, $tglPerkiraan, $statusIb);
            mysqli_stmt_execute($stmtRepro);
        }
    }

    redirectKesehatan('berhasil');
}

$opsiHewan = [];
$queryHewan = mysqli_query(
    $db,
    "SELECT id_hewan, jenis_hewan, no_kandang
     FROM data_ternak
     ORDER BY id_hewan ASC"
);

if ($queryHewan) {
    while ($row = mysqli_fetch_assoc($queryHewan)) {
        $opsiHewan[] = $row;
    }
}

$dataKesehatan = [];
$queryKesehatan = mysqli_query(
    $db,
    "SELECT
        dk.id_kesehatan,
        dk.id_hewan,
        dk.tgl_pemeriksaan,
        dk.status_kesehatan,
        dk.diagnosis,
        dk.tindakan,
        dk.catatan,
        dt.jenis_hewan,
        dt.no_kandang,
        dr.tgl_ib,
        dr.ib_ke,
        dr.tgl_perkiraan,
        dr.status_ib
     FROM data_kesehatan dk
     INNER JOIN data_ternak dt ON dt.id_hewan = dk.id_hewan
     LEFT JOIN data_reproduksi dr ON dr.id_reproduksi = (
        SELECT dr2.id_reproduksi
        FROM data_reproduksi dr2
        WHERE dr2.id_hewan = dk.id_hewan
        ORDER BY dr2.id_reproduksi DESC
        LIMIT 1
     )
     ORDER BY dk.tgl_pemeriksaan DESC, dk.id_kesehatan DESC"
);

if ($queryKesehatan) {
    while ($row = mysqli_fetch_assoc($queryKesehatan)) {
        $dataKesehatan[] = $row;
    }
}

$totalSehat = count(array_filter($dataKesehatan, fn($row) => $row['status_kesehatan'] === 'sehat'));
$totalPerawatan = count(array_filter($dataKesehatan, fn($row) => $row['status_kesehatan'] === 'perawatan'));
$totalObservasi = count(array_filter($dataKesehatan, fn($row) => $row['status_kesehatan'] === 'observasi'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Data Kesehatan Hewan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../../public/css/admin_dataKesehatan.css?v=2">
</head>

<body>

<!-- ==================== MODAL TAMBAH ==================== -->
<div class="modal-overlay" id="tambahOverlay" onclick="closeModalOutside(event, 'tambahOverlay')">
    <div class="form-modal-box">
        <div class="modal-header-row">
            <div>
                <h4 class="modal-title">Tambah Data Kesehatan Hewan</h4>
                <p class="modal-subtitle">Catat data kesehatan hewan ternak secara akurat</p>
            </div>
            <button class="modal-close-btn" onclick="closeTambah()"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="tambahForm" action="data_kesehatan.php" method="POST">
            <input type="hidden" name="aksi" value="tambah">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pilih Hewan <span class="text-danger">*</span></label>
                    <select name="id_hewan" class="form-select" required>
                        <option value="" disabled selected>Pilih hewan dari Data Hewan</option>
                        <?php foreach ($opsiHewan as $hewan): ?>
                            <option value="<?= (int) $hewan['id_hewan'] ?>"><?= htmlspecialchars(identitasHewan($hewan)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pemeriksaan</label>
                    <input type="date" name="tgl_pemeriksaan" class="form-control" max="2026-12-31">
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
                    <input type="hidden" name="status" id="tambahStatusValue" value="sehat">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                    <input type="text" name="diagnosis" class="form-control" placeholder="Contoh: Infeksi Ringan" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tindakan <span class="text-danger">*</span></label>
                    <input type="text" name="tindakan" class="form-control" placeholder="Contoh: Pemberian Antibiotik" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan atau keterangan detail di sini..."></textarea>
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="fw-bold mb-1">Data Reproduksi / IB</h6>
                    <small class="text-muted">Isi jika hewan sedang atau sudah menjalani inseminasi buatan.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal IB</label>
                    <input type="date" name="tgl_awal_ib" id="tambahTglIb" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">IB ke</label>
                    <input type="number" name="ib_ke" class="form-control" min="1" placeholder="Contoh: 2">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Perkiraan Tanggal Lahir</label>
                    <input type="date" name="perkiraan_tgl_lahir" id="tambahPerkiraanLahir" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status IB</label>
                    <select name="status_ib" id="tambahStatusIb" class="form-select">
                        <option value="">Belum ada IB</option>
                        <option value="berhasil">Berhasil</option>
                        <option value="tdk_berhasil">Tidak Berhasil</option>
                    </select>
                </div>
            </div>

            <div class="tips-box">
                <h6><i class="bi bi-lightbulb"></i> Tips Pengisian</h6>
                <ul>
                    <li>Pastikan ID Hewan sesuai dengan tag telinga atau catatan kandang.</li>
                    <li>Status kesehatan menentukan jadwal kunjungan dokter hewan berikutnya.</li>
                    <li>Gunakan catatan untuk menuliskan reaksi hewan setelah tindakan.</li>
                </ul>
            </div>

            <div class="btn-footer">
                <button type="button" class="btn-kembali" onclick="closeTambah()">Batal</button>
                <button type="submit" class="btn-simpan">
                    <i class="bi bi-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL EDIT ==================== -->
<div class="modal-overlay" id="editOverlay" onclick="closeModalOutside(event, 'editOverlay')">
    <div class="form-modal-box">
        <div class="modal-header-row">
            <div>
                <h4 class="modal-title">Edit Data Kesehatan Hewan</h4>
                <p class="modal-subtitle">Kelola informasi kesehatan dan reproduksi ternak Anda</p>
            </div>
            <button class="modal-close-btn" onclick="closeEdit()"><i class="bi bi-x-lg"></i></button>
        </div>

        <ul class="nav nav-tabs" id="editTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="kesehatan-tab" data-bs-toggle="tab" data-bs-target="#kesehatan-content" type="button">Kesehatan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="reproduksi-tab" data-bs-toggle="tab" data-bs-target="#reproduksi-content" type="button">Reproduksi</button>
            </li>
        </ul>

        <form id="editKesehatanForm">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kesehatan-content">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">ID</label>
                            <input type="text" name="id" id="editIdKesehatan" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="editTanggal" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hewan</label>
                            <select name="id_hewan" id="editHewan" class="form-select">
                                <option value="">Pilih...</option>
                                <?php foreach ($opsiHewan as $hewan): ?>
                                    <option value="<?= (int) $hewan['id_hewan'] ?>"><?= htmlspecialchars(identitasHewan($hewan)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="form-label mt-3">Status Kesehatan</label>
                    <div class="d-flex gap-2">
                        <div class="health-status-btn status-sehat" id="edit-btn-sehat" onclick="selectStatus(this, 'sehat')">
                            <i class="bi bi-check-circle"></i>
                            <span>Sehat</span>
                        </div>
                        <div class="health-status-btn status-perawatan" id="edit-btn-perawatan" onclick="selectStatus(this, 'perawatan')">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>Dalam Perawatan</span>
                        </div>
                        <div class="health-status-btn status-observasi" id="edit-btn-observasi" onclick="selectStatus(this, 'observasi')">
                            <i class="bi bi-clock"></i>
                            <span>Observasi</span>
                        </div>
                    </div>
                    <input type="hidden" name="status_kesehatan" id="inputStatusKesehatan" value="">

                    <label class="form-label">Diagnosa</label>
                    <input type="text" name="diagnosis" id="editDiagnosa" class="form-control" placeholder="Masukkan diagnosis...">

                    <label class="form-label">Tindakan</label>
                    <input type="text" name="tindakan" id="editTindakan" class="form-control" placeholder="Masukkan tindakan yang dilakukan...">

                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="editKeterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan..."></textarea>
                </div>

                <div class="tab-pane fade" id="reproduksi-content">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Awal IB</label>
                            <input type="date" name="tgl_awal_ib" id="editTglIb" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">IB ke</label>
                            <input type="text" name="ib_ke" id="editIbKe" class="form-control" placeholder="Contoh: 2">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Status Reproduksi</label>
                            <select name="status_reproduksi" id="editStatusReproduksi" class="form-select">
                                <option value="Hamil">Hamil</option>
                                <option value="Tidak Hamil">Tidak Hamil</option>
                                <option value="Masa Subur">Masa Subur</option>
                                <option value="Anak">Anak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perkiraan Tanggal Lahir</label>
                            <input type="date" name="perkiraan_tgl_lahir" id="editTglLahir" class="form-control">
                        </div>
                    </div>
                    <label class="form-label">Informasi Tambahan</label>
                    <textarea name="informasi_tambahan" id="editInfoTambahan" class="form-control" rows="4" placeholder="Informasi tambahan..."></textarea>
                </div>
            </div>

            <div class="btn-footer">
                <button type="button" class="btn-kembali" onclick="closeEdit()">Batal</button>
                <button type="button" class="btn-simpan" onclick="simpanEdit()">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL PREVIEW ==================== -->
<div class="Preview-overlay" id="ringkasanPreview" onclick="closePreviewOutside(event)">
    <div class="ringkasan-card">
        <div class="card-body">
            <div class="header-section">
                <div>
                    <h2 style="color:#1e293b;font-size:22px;font-weight:800;">Ringkasan Data Hewan</h2>
                    <p class="subtitle-preview">Verifikasi data sebelum menyimpan ke sistem</p>
                </div>
                <div class="id-badge">ID: 00002</div>
            </div>
            <hr style="border:0;border-top:1px solid #f1f5f9;">
            <div class="section-title">Data Kesehatan</div>
            <div class="data-grid">
                <div class="info-box">
                    <span class="label">Tanggal Pemeriksaan</span>
                    <span class="value">25 Februari 2026</span>
                </div>
                <div class="info-box" style="display:flex;flex-direction:column;justify-content:center;">
                    <span class="label">Status Kesehatan</span>
                    <span class="status-health">Sehat</span>
                </div>
            </div>
            <div class="info-box">
                <span class="label" style="color:#f59e0b;">Diagnosis & Perawatan</span>
                <p class="value" style="font-weight:500;">Tidak ada gejala penyakit, kondisi fisik prima.</p>
            </div>
            <div class="section-title">Data Reproduksi</div>
            <div class="data-grid">
                <div class="info-box"><span class="label">Tanggal Awal IB</span><span class="value">10 Januari 2026</span></div>
                <div class="info-box"><span class="label">Status Reproduksi</span><span class="value">IB ke - 2</span></div>
                <div class="info-box"><span class="label">Perkiraan Tanggal Lahir</span><span class="value">15 Oktober 2026</span></div>
                <div class="info-box"><span class="label">Status Kandungan</span><span class="value">Hamil (Terverifikasi)</span></div>
            </div>
            <div class="section-title">Informasi Tambahan</div>
            <div class="note-box-preview">
                Catatan kesehatan dan reproduksi ditampilkan sesuai data yang dipilih.
            </div>
            <div class="footer-actions">
                <button class="btn-back" onclick="closePreview()">Kembali</button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL DELETE ==================== -->
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
            <button class="btn-custom btn-cancel" onclick="closeDelete()">Batal</button>
            <button class="btn-custom btn-delete" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

<!-- MAIN -->
<div class="main-content">

<!-- TOPBAR -->
<?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

<!-- HEADER -->
<div class="header">
    <div>
        <h2>Data Kesehatan Hewan</h2>
        <p>Pantau dan kelola kesehatan ternak Anda secara real-time</p>
    </div>
    <button class="btn-add" onclick="openTambah()">
        + Tambah Catatan Kesehatan
    </button>
</div>

<!-- STATS -->
<div class="stats">
    <div class="stat-card"><h4>Total Sehat</h4><h2><?= $totalSehat ?></h2></div>
    <div class="stat-card"><h4>Dalam Perawatan</h4><h2><?= $totalPerawatan ?></h2></div>
    <div class="stat-card"><h4>Observasi</h4><h2><?= $totalObservasi ?></h2></div>
</div>

<!-- TABLE -->
<div class="table-box">
<table>
<thead>
<tr>
    <th>Kode Hewan</th>
    <th>Jenis Hewan</th>
    <th>Kandang</th>
    <th>Tanggal Pemeriksaan</th>
    <th>Status</th>
    <th>Diagnosa</th>
    <th>Tindakan</th>
    <th>Keterangan</th>
    <th>Tanggal IB</th>
    <th>IB Ke</th>
    <th>Status IB</th>
    <th>Status Hamil</th>
    <th>Perkiraan Lahir</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody id="kesehatanTableBody">
<?php if (count($dataKesehatan) === 0): ?>
<tr><td colspan="14" class="text-center text-muted py-4">Belum ada data kesehatan.</td></tr>
<?php endif; ?>
<?php foreach ($dataKesehatan as $row): ?>
<?php
$hewanLabel = identitasHewan($row);
$kodeHewan = str_pad((string) $row['id_hewan'], 5, '0', STR_PAD_LEFT);
$editJson = [
    'id' => str_pad((string) $row['id_kesehatan'], 4, '0', STR_PAD_LEFT),
    'id_hewan' => (string) $row['id_hewan'],
    'tanggal' => $row['tgl_pemeriksaan'],
    'status' => $row['status_kesehatan'],
    'diagnosis' => $row['diagnosis'],
    'tindakan' => $row['tindakan'],
    'catatan' => $row['catatan'],
    'tgl_ib' => $row['tgl_ib'] ?? '',
    'ib_ke' => $row['ib_ke'] ?? '',
    'status_reproduksi' => labelHamil($row['status_ib'] ?? null),
    'tgl_perkiraan' => $row['tgl_perkiraan'] ?? '',
    'info_tambahan' => '',
];
$recordJson = htmlspecialchars(json_encode($editJson), ENT_QUOTES, 'UTF-8');
?>
<tr>
    <td><?= htmlspecialchars($kodeHewan) ?></td>
    <td><?= htmlspecialchars(labelJenisKesehatan($row['jenis_hewan'])) ?></td>
    <td><?= htmlspecialchars($row['no_kandang']) ?></td>
    <td><?= htmlspecialchars(formatTanggalKesehatan($row['tgl_pemeriksaan'])) ?></td>
    <td><span class="status <?= classStatusKesehatan($row['status_kesehatan']) ?>"><?= labelStatusKesehatan($row['status_kesehatan']) ?></span></td>
    <td><?= htmlspecialchars($row['diagnosis']) ?></td>
    <td><?= htmlspecialchars($row['tindakan']) ?></td>
    <td><?= htmlspecialchars($row['catatan']) ?></td>
    <td><?= htmlspecialchars(formatTanggalKesehatan($row['tgl_ib'] ?? null)) ?></td>
    <td><?= htmlspecialchars($row['ib_ke'] ?? '-') ?></td>
    <td><?= htmlspecialchars(labelStatusIb($row['status_ib'] ?? null)) ?></td>
    <td><?= htmlspecialchars(labelHamil($row['status_ib'] ?? null)) ?></td>
    <td><?= htmlspecialchars(formatPerkiraanLahir($row['tgl_perkiraan'] ?? null, $row['status_ib'] ?? null)) ?></td>
    <td class="action">
        <i class="fa fa-eye" title="Lihat" onclick="openPreview()"></i>
        <i class="fa fa-pen" title="Edit" data-record="<?= $recordJson ?>" onclick="openEdit(this)"></i>
        <i class="fa fa-trash" title="Hapus" onclick="openDelete('<?= htmlspecialchars($hewanLabel, ENT_QUOTES) ?>')"></i>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="pagination">
    <span>Menampilkan <?= count($dataKesehatan) > 0 ? '1-' . count($dataKesehatan) : '0-0' ?> dari <?= count($dataKesehatan) ?> data</span>
    <div>
        <button class="page-btn">Sebelumnya</button>
        <button class="page-btn active-page">1</button>
        <button class="page-btn">Selanjutnya</button>
    </div>
</div>
</div>

</div><!-- end main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../public/js/dataKesehatan_admin.js?v=2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    setupAdminPagination('#kesehatanTableBody', '.table-box .pagination', 5);
});
</script>

</body>
</html>
