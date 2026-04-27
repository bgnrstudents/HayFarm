<?php
$id = $_GET['id'] ?? '00004';
$jenis = $_GET['jenis'] ?? 'Sapi Perah';
$berat = $_GET['berat'] ?? '450 Kg';
$kelamin = $_GET['kelamin'] ?? 'Betina';
$kandang = $_GET['kandang'] ?? 'A-01';
$tglLahir = $_GET['tgl_lahir'] ?? '2021-03-15';
$status = $_GET['status'] ?? 'produktif';

$status = $status === 'tidak_produktif' ? 'tidak_produktif' : 'produktif';
$statusLabel = $status === 'produktif' ? 'Produktif' : 'Tidak Produktif';

$tanggalObj = new DateTime($tglLahir);
$hariIni = new DateTime();
$usia = $tanggalObj->diff($hariIni);
$usiaLabel = $usia->y > 0 ? $usia->y . ' Tahun' : max($usia->m, 1) . ' Bulan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Hewan</title>
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
            margin-bottom: 20px;
        }
        .header h2 { font-weight: 700; }
        .header p { font-size: 13px; color: #777; margin-bottom: 0; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #175D2B;
            font-weight: 700;
        }

        .form-modal-box {
            background: white;
            width: 100%;
            max-width: 880px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            padding: 30px;
        }
        .modal-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .modal-title { font-weight: 700; font-size: 1.4rem; color: #1e293b; margin: 0; }
        .modal-subtitle { color: #64748b; font-size: 0.9rem; margin-bottom: 18px; }

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
        .form-control, .form-select {
            border-radius: 10px;
            padding: 11px 14px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
            transition: border-color 0.2s;
            width: 100%;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            outline: none;
        }

        .health-status-btn {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 10px;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
            background: #fff;
        }
        .health-status-btn i { font-size: 1.2rem; display: block; margin-bottom: 5px; }
        .health-status-btn span { font-size: 0.82rem; font-weight: 600; }
        .status-produktif.active {
            border-color: #10b981;
            color: #10b981;
            background-color: #ecfdf5;
        }
        .status-tidak-produktif.active {
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
        .tips-box p { margin: 0; color: #4b5563; font-size: 0.9rem; }

        .btn-footer { display: flex; gap: 12px; margin-top: 30px; }
        .btn-kembali {
            background-color: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            flex: 1;
            padding: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
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

        @media (max-width: 991px) {
            .summary-strip { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 767px) {
            .summary-strip { grid-template-columns: 1fr; }
            .btn-footer { flex-direction: column; }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <?php include 'navbar.php'; ?>

    <div class="header">
        <div>
            <h2>Edit Data Hewan</h2>
            <p>Perbarui informasi ternak dengan format yang konsisten seperti halaman kesehatan.</p>
        </div>
        <a href="data.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Data Hewan</a>
    </div>

    <div class="form-modal-box">
        <div class="modal-header-row">
            <div>
                <h4 class="modal-title">Edit Data Hewan</h4>
                <p class="modal-subtitle">Kelola identitas, kandang, dan status produktivitas ternak Anda</p>
            </div>
        </div>

        <div class="summary-strip">
            <div class="summary-item"><span>ID Hewan</span><strong><?= htmlspecialchars($id) ?></strong></div>
            <div class="summary-item"><span>Jenis</span><strong><?= htmlspecialchars($jenis) ?></strong></div>
            <div class="summary-item"><span>Usia</span><strong><?= htmlspecialchars($usiaLabel) ?></strong></div>
            <div class="summary-item"><span>Status</span><strong><?= htmlspecialchars($statusLabel) ?></strong></div>
        </div>

        <form id="editHewanForm">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">ID Hewan</label>
                    <input type="text" name="id_hewan" class="form-control" value="<?= htmlspecialchars($id) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Hewan</label>
                    <select name="jenis_hewan" class="form-select">
                        <option value="Sapi Perah" <?= $jenis === 'Sapi Perah' ? 'selected' : '' ?>>Sapi Perah</option>
                        <option value="Sapi PO" <?= $jenis === 'Sapi PO' ? 'selected' : '' ?>>Sapi PO</option>
                        <option value="Kambing" <?= $jenis === 'Kambing' ? 'selected' : '' ?>>Kambing</option>
                        <option value="Domba" <?= $jenis === 'Domba' ? 'selected' : '' ?>>Domba</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Berat Badan Hewan</label>
                    <input type="text" name="berat_badan" class="form-control" value="<?= htmlspecialchars($berat) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="Jantan" <?= $kelamin === 'Jantan' ? 'selected' : '' ?>>Jantan</option>
                        <option value="Betina" <?= $kelamin === 'Betina' ? 'selected' : '' ?>>Betina</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Kandang</label>
                    <input type="text" name="no_kandang" class="form-control" value="<?= htmlspecialchars($kandang) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="<?= htmlspecialchars($tglLahir) ?>">
                </div>
            </div>

            <label class="form-label mt-3">Status Produktivitas</label>
            <div class="d-flex gap-2">
                <div class="health-status-btn status-produktif <?= $status === 'produktif' ? 'active' : '' ?>" onclick="selectStatus(this, 'produktif')">
                    <i class="bi bi-check-circle"></i>
                    <span>Produktif</span>
                </div>
                <div class="health-status-btn status-tidak-produktif <?= $status === 'tidak_produktif' ? 'active' : '' ?>" onclick="selectStatus(this, 'tidak_produktif')">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Tidak Produktif</span>
                </div>
            </div>
            <input type="hidden" name="status" id="inputStatus" value="<?= htmlspecialchars($status) ?>">

            <div class="tips-box">
                <h6><i class="bi bi-lightbulb"></i> Ringkasan Data Saat Ini</h6>
                <p>Data ini dibuka dari `data.php`, jadi identitas hewan sudah ikut terbawa ke halaman edit dan siap diperbarui.</p>
            </div>

            <div class="btn-footer">
                <a href="data.php" class="btn-kembali">Batal</a>
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('currentDate').textContent =
    new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

function selectStatus(element, value) {
    document.querySelectorAll('.health-status-btn').forEach(button => button.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('inputStatus').value = value;
}

document.getElementById('editHewanForm').addEventListener('submit', function (event) {
    event.preventDefault();
    alert('Perubahan data hewan berhasil disimpan.');
    window.location.href = 'data.php';
});
</script>
</body>
</html>