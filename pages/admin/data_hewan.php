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

<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Nunito', sans-serif; }
body { background: #f5f7f6; }

.sidebar {
    width: 250px;
    height: 100vh;
    background: #fff;
    position: fixed;
    padding: 10px;
}
.logo { width: 130px; display: block; margin: 10px auto 20px; }
.menu { list-style: none; }
.menu li { margin-bottom: 10px; }
.menu li a {
    text-decoration: none;
    color: #333;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
}
.menu li a:hover { background: #f8f9fa; }
.menu .active a { background: #175D2B; color: #fff; }
.menu .active a i { color: #ffbe25; }
.menu-title { font-size: 12px; color: #777; margin: 15px 0 5px; }

.main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 100vh;
    background: linear-gradient(to bottom, #ffffff 0px, #ffffff 80px, #dbe7df 80px, #c9d8cf 40%, #b8c8be 100%);
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 10px 20px;
}
.search-box { position: relative; width: 300px; }
.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 14px;
    pointer-events: none;
}
.search-box input {
    width: 100%;
    padding: 8px 12px 8px 35px;
    border-radius: 20px;
    border: none;
    outline: none;
    background: #f1f3f5;
    font-size: 14px;
}
.topbar-right { display: flex; align-items: center; gap: 15px; }
#currentDate { font-size: 13px; color: #555; }
.notif { position: relative; font-size: 16px; cursor: pointer; }
.notif .badge {
    position: absolute;
    top: -6px;
    right: -8px;
    background: red;
    color: white;
    font-size: 10px;
    padding: 3px 5px;
    border-radius: 50%;
}
.user { display: flex; flex-direction: column; font-size: 12px; text-align: right; }
.user strong { font-size: 13px; }

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    gap: 14px;
}
.header h2 { font-weight: 700; }
.header p { font-size: 13px; color: #777; margin-bottom: 0; }
.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #175D2B;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    font-weight: 700;
}
.btn-add:hover { background: #124822; }

.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin: 20px 0;
}
.stat-card {
    background: white;
    padding: 18px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
}
.stat-card h4 { font-size: 13px; color: #777; margin-bottom: 6px; }
.stat-card h2 { font-weight: 800; margin: 0; }

.table-box {
    background: white;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
}
.table-tools {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
}
.table-tools input {
    width: 280px;
    max-width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}
.table-note {
    font-size: 13px;
    color: #6b7280;
}
table { width: 100%; border-collapse: collapse; }
th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    vertical-align: middle;
}
thead th {
    background: #ffc107;
    color: #3b2f00;
    font-weight: 800;
    border-bottom: 1px solid #e0a800;
}
tbody td {
    background: #fffdf5;
}
tbody tr:nth-child(even) td {
    background: #fffaf0;
}

.status-pill,
.status-health {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
}
.status-produktif {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.status-tidak-produktif {
    background: #fee2e2;
    color: #b91c1c;
    border-color: #fecaca;
}

.action button,
.action i {
    color: #475569;
    margin-right: 10px;
    cursor: pointer;
    background: none;
    border: none;
}
.action button:hover,
.action i:hover { color: #175D2B; }

.pagination {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.page-btn {
    border: none;
    padding: 6px 10px;
    border-radius: 6px;
}
.active-page { background: #175D2B; color: white; }

.modal-overlay,
.Preview-overlay,
.Delete-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    display: none;
    justify-content: center;
    align-items: center;
    padding: 20px;
    z-index: 10000;
}
.modal-overlay.active,
.Preview-overlay.active,
.Delete-overlay.active { display: flex; }

.form-modal-box,
.ringkasan-card,
.Delete-box {
    background: white;
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.16);
}

.form-modal-box {
    max-width: 880px;
    padding: 30px;
    max-height: 92vh;
    overflow-y: auto;
}
.form-modal-box::-webkit-scrollbar { width: 6px; }
.form-modal-box::-webkit-scrollbar-thumb { background: #d4d4d8; border-radius: 10px; }

.modal-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 8px;
}
.modal-title { font-weight: 700; font-size: 1.4rem; color: #1e293b; margin: 0; }
.modal-subtitle { color: #64748b; font-size: 0.92rem; margin-bottom: 18px; }
.modal-close-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: #f8fafc;
    color: #475569;
}
.modal-close-btn:hover { background: #f1f5f9; color: #111827; }

.summary-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.summary-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
}
.summary-item span {
    display: block;
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 5px;
}
.summary-item strong { font-size: 15px; color: #334155; }

.form-label {
    font-weight: 600;
    font-size: 0.88rem;
    margin-top: 16px;
    margin-bottom: 6px;
    color: #374151;
    display: block;
}
.form-control,
.form-select {
    border-radius: 10px;
    padding: 11px 14px;
    border: 1px solid #e2e8f0;
    font-size: 0.9rem;
    transition: border-color 0.2s;
    width: 100%;
}
.form-control:focus,
.form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    outline: none;
}

.health-status-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.health-status-btn {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 10px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    background: #fff;
}
.health-status-btn i { font-size: 1.2rem; display: block; margin-bottom: 5px; }
.health-status-btn span { font-size: 0.82rem; font-weight: 600; }
.health-status-btn.status-produktif.active {
    border-color: #10b981;
    color: #10b981;
    background-color: #ecfdf5;
}
.health-status-btn.status-tidak-produktif.active {
    border-color: #ef4444;
    color: #ef4444;
    background-color: #fef2f2;
}

.tips-box {
    background-color: #f0fdf4;
    border-radius: 12px;
    padding: 16px;
    border-left: 4px solid #175D2B;
    margin-top: 20px;
}
.tips-box h6 { font-weight: 700; color: #175D2B; margin-bottom: 8px; }
.tips-box p,
.tips-box ul { margin: 0; color: #4b5563; font-size: 0.9rem; }
.tips-box ul { padding-left: 18px; }

.btn-footer {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}
.btn-kembali {
    background-color: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
    flex: 1;
    padding: 13px;
    font-weight: 600;
    border-radius: 10px;
}
.btn-simpan {
    background-color: #175D2B;
    color: #fff;
    border: none;
    flex: 1;
    padding: 13px;
    font-weight: 600;
    border-radius: 10px;
}

.ringkasan-card {
    max-width: 420px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}
.card-body { padding: 24px; }
.header-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 6px;
}
.id-badge {
    background: #1e293b;
    color: white;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}
.subtitle-preview {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
    margin-top: 4px;
}
.preview-image-container {
    width: 100%;
    height: 190px;
    margin: 20px 0;
    background: #fdfdfd;
    border: 1.5px dashed #cbd5e1;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.preview-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.section-title {
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 25px 0 15px;
}
.data-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 15px;
}
.info-box {
    background: #f8fafc;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
}
.label {
    font-size: 10px;
    color: #94a3b8;
    font-weight: 700;
    display: block;
    margin-bottom: 5px;
}
.value {
    font-size: 14px;
    color: #1e293b;
    font-weight: 700;
}
.status-pill-preview {
    display: flex;
    align-items: center;
    gap: 6px;
}
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 8px rgba(34, 197, 94, 0.5);
}
.status-pill-preview.status-tidak-produktif-dot .status-dot {
    background: #ef4444;
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
}
.preview-status-text {
    font-size: 14px;
    font-weight: 700;
    color: #22c55e;
}
.preview-status-text.status-tidak-produktif {
    color: #ef4444;
}
.footer-actions {
    margin-top: 10px;
}
.btn-back {
    width: 100%;
    background: #00cc44;
    border: none;
    color: white;
    padding: 16px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
}
.btn-back:hover { background: #00b33c; }

.Delete-box {
    max-width: 460px;
    padding: 32px 36px 70px;
    position: relative;
    border-bottom: 3px solid #4a90e2;
}
.Delete-header-custom { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
.icon-circle {
    width: 50px;
    height: 50px;
    background: #fee2e2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.icon-circle i { font-size: 22px; color: #ef4444; }
.Delete-title-custom { font-size: 21px; font-weight: 700; color: #111827; margin: 0; }
.Delete-body-custom p { font-size: 15px; color: #6b7280; line-height: 1.6; margin-bottom: 8px; }
.warning-text { font-size: 14px; color: #9ca3af; }
.btn-group-custom {
    position: absolute;
    bottom: -24px;
    right: 28px;
    display: flex;
    gap: 12px;
}
.btn-custom {
    padding: 11px 26px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    transition: 0.2s;
    border: none;
}
.btn-cancel {
    background: #ffffff;
    color: #374151;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.btn-delete {
    background: #ef4444;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(239,68,68,0.35);
}

@media (max-width: 991px) {
    .stats,
    .summary-strip,
    .data-grid { grid-template-columns: 1fr 1fr; }
    .header { flex-direction: column; align-items: flex-start; }
    .table-box { overflow-x: auto; }
}

@media (max-width: 767px) {
    .main-content { margin-left: 0; padding: 15px; }
    .stats,
    .summary-strip,
    .data-grid,
    .health-status-group { grid-template-columns: 1fr; }
    .btn-footer { flex-direction: column; }
    .form-modal-box,
    .card-body,
    .Delete-box { padding: 22px; }
    .btn-group-custom {
        position: static;
        margin-top: 20px;
        justify-content: flex-end;
    }
}
</style>
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

<script>
document.getElementById('currentDate').textContent =
    new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

const searchInput = document.getElementById('searchInput');
const tableRows = document.querySelectorAll('#dataTableBody tr');
let activePreviewRecord = null;

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(keyword) ? '' : 'none';
        });
    });
}

function parseRecord(trigger) {
    return JSON.parse(trigger.dataset.record);
}

function openOverlay(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeOverlay(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = 'auto';
}

function closeModalOutside(event, overlayId) {
    if (event.target.id === overlayId) {
        closeOverlay(overlayId);
    }
}

function formatTanggalIndonesia(dateString) {
    return new Date(dateString + 'T00:00:00').toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function hitungUsia(dateString) {
    const lahir = new Date(dateString + 'T00:00:00');
    const hariIni = new Date();
    let tahun = hariIni.getFullYear() - lahir.getFullYear();
    let bulan = hariIni.getMonth() - lahir.getMonth();

    if (bulan < 0) {
        tahun--;
        bulan += 12;
    }

    if (tahun > 0) {
        return `${tahun} Tahun`;
    }

    return `${Math.max(bulan, 1)} Bulan`;
}

function updateStatusButtons(scopeSelector, status) {
    document.querySelectorAll(`${scopeSelector} .health-status-btn`).forEach(button => {
        button.classList.remove('active');
    });

    const targetId = status === 'tidak_produktif' ? `${scopeSelector === '#editOverlay' ? 'edit' : 'tambah'}-btn-tidak-produktif` : `${scopeSelector === '#editOverlay' ? 'edit' : 'tambah'}-btn-produktif`;
    const targetButton = document.getElementById(targetId);
    if (targetButton) {
        targetButton.classList.add('active');
    }
}

function openTambah() {
    document.getElementById('tambahForm').reset();
    updateStatusButtons('#tambahOverlay', 'produktif');
    document.getElementById('tambahStatusValue').value = 'produktif';
    openOverlay('tambahOverlay');
}

function closeTambah() {
    closeOverlay('tambahOverlay');
}

function selectTambahStatus(status, element) {
    document.querySelectorAll('#tambahOverlay .health-status-btn').forEach(button => button.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('tambahStatusValue').value = status;
}

function fillEditForm(record) {
    document.getElementById('editIdHewan').value = record.id || '';
    document.getElementById('editJenisHewan').value = record.jenis || '';
    document.getElementById('editBeratBadan').value = record.berat || '';
    document.getElementById('editJenisKelamin').value = record.kelamin || '';
    document.getElementById('editNoKandang').value = record.kandang || '';
    document.getElementById('editTanggalLahir').value = record.tgl_lahir || '';
    document.getElementById('editStatusValue').value = record.status || 'produktif';

    document.getElementById('editSummaryId').textContent = record.id || '-';
    document.getElementById('editSummaryJenis').textContent = record.jenis || '-';
    document.getElementById('editSummaryUsia').textContent = record.usia || hitungUsia(record.tgl_lahir);
    document.getElementById('editSummaryStatus').textContent = record.status_label || '-';

    updateStatusButtons('#editOverlay', record.status || 'produktif');
}

function openEdit(trigger) {
    const record = parseRecord(trigger);
    fillEditForm(record);
    openOverlay('editOverlay');
}

function closeEdit() {
    closeOverlay('editOverlay');
}

function selectEditStatus(element, value) {
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(button => button.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('editStatusValue').value = value;
    document.getElementById('editSummaryStatus').textContent = value === 'produktif' ? 'Produktif' : 'Tidak Produktif';
}

function fillPreview(record) {
    activePreviewRecord = record;

    document.getElementById('previewIdBadge').textContent = record.id || '-';
    document.getElementById('previewJenis').textContent = record.jenis || '-';
    document.getElementById('previewKelamin').textContent = record.kelamin || '-';
    document.getElementById('previewBerat').textContent = record.berat || '-';
    document.getElementById('previewUsia').textContent = record.usia || hitungUsia(record.tgl_lahir);
    document.getElementById('previewKandang').textContent = record.kandang || '-';
    document.getElementById('previewTanggalLahir').textContent = formatTanggalIndonesia(record.tgl_lahir);
    document.getElementById('previewImage').src = getPreviewImage(record.jenis);

    const statusWrap = document.getElementById('previewStatusWrap');
    const statusElement = document.getElementById('previewStatus');
    const tersedia = record.status === 'produktif';
    statusElement.textContent = tersedia ? 'Tersedia' : 'Tidak Tersedia';
    statusElement.className = `preview-status-text ${tersedia ? '' : 'status-tidak-produktif'}`;
    statusWrap.className = `status-pill-preview ${tersedia ? '' : 'status-tidak-produktif-dot'}`;
}

function getPreviewImage(jenis) {
    const imageMap = {
        'Sapi Perah': 'https://images.unsplash.com/photo-1546445317-29f4545e9d53?q=80&w=400',
        'Sapi PO': 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=400',
        'Kambing': 'https://images.unsplash.com/photo-1524024973431-2ad916746881?q=80&w=400',
        'Domba': 'https://images.unsplash.com/photo-1484557985045-edf25e08da73?q=80&w=400'
    };

    return imageMap[jenis] || imageMap['Sapi Perah'];
}

function openPreview(trigger) {
    const record = parseRecord(trigger);
    fillPreview(record);
    openOverlay('previewOverlay');
}

function closePreview() {
    closeOverlay('previewOverlay');
}

function closePreviewOutside(event) {
    if (event.target.id === 'previewOverlay') {
        closePreview();
    }
}

function openDelete(namaHewan) {
    document.getElementById('deleteTarget').textContent = namaHewan || 'data ini';
    openOverlay('deleteOverlay');
}

function closeDelete() {
    closeOverlay('deleteOverlay');
}

function closeDeleteOutside(event) {
    if (event.target.id === 'deleteOverlay') {
        closeDelete();
    }
}

function confirmDelete() {
    alert('Data hewan berhasil dihapus.');
    closeDelete();
}

document.getElementById('tambahForm').addEventListener('submit', function (event) {
    event.preventDefault();
    alert('Data hewan berhasil disimpan.');
    closeTambah();
});

document.getElementById('editHewanForm').addEventListener('submit', function (event) {
    event.preventDefault();
    alert('Perubahan data hewan berhasil disimpan.');
    closeEdit();
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeTambah();
        closeEdit();
        closePreview();
        closeDelete();
    }
});
</script>
</body>
</html>