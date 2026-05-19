<?php
// =====================================================
// LOAD DATA HEWAN DARI DATABASE UNTUK DROPDOWN
// =====================================================
$root_dir = dirname(__DIR__, 3); // /HayFarm root
require_once $root_dir . '/config/database.php';
require_once $root_dir . '/process/models/kesehatan.php';

$db_conn    = new Database();
$connection = $db_conn->getConnection();
$kesehatan_model = new Kesehatan($connection);

// Ambil daftar hewan aktif (is_deleted = 0, jenis sapi_perah / sapi_po)
$daftar_hewan = $kesehatan_model->getAnimalsList();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kesehatan Hewan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        textarea.form-control {
            border-radius: 12px;
            resize: none;
            padding: 15px;
            height: 120px;
        }

        textarea.form-control::placeholder {
            color: #ccc;
            font-style: italic;
        }

        .status-btn {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            flex: 1;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .status-btn i {
            font-size: 1.2rem;
        }

        .status-btn.active-sehat {
            border-color: #198754;
            background-color: #f0fff4;
            color: #198754;
        }

        .status-btn.active-perawatan {
            border-color: #ffc107;
            background-color: #fffbeb;
            color: #d97706;
        }

        .status-btn.active-observasi {
            border-color: #0d6efd;
            background-color: #f0f7ff;
            color: #0d6efd;
        }

        .tips-box {
            background-color: #ebf5ff;
            border-radius: 12px;
            padding: 20px;
            border-left: 5px solid #0d6efd;
        }

        .form-control::placeholder {
            color: #ccc;
            font-weight: 300;
        }

        #info-hewan {
            font-size: 0.85rem;
            margin-top: 6px;
            min-height: 1.4em;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card p-4 mb-4">
                    <form action="<?= htmlspecialchars($root_dir) ?>/process/handlers/kesehatan_handler.php" method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="row g-3">

                            <!-- Header -->
                            <div class="d-flex align-items-center mb-4">
                                <a href="../data_kesehatan.php" class="btn btn-light rounded-circle me-3">
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                                <div>
                                    <h4 class="mb-0 fw-bold">Tambah Data Kesehatan Hewan</h4>
                                    <small class="text-muted">Catat data kesehatan hewan ternak secara akurat</small>
                                </div>
                            </div>

                            <!-- DROPDOWN PILIH HEWAN -->
                            <div class="col-md-12">
                                <label class="form-label">Pilih Hewan <span class="text-danger">*</span></label>
                                <?php if (empty($daftar_hewan)): ?>
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Belum ada data hewan aktif. Tambahkan hewan terlebih dahulu di menu <strong>Data Hewan</strong>.
                                    </div>
                                    <input type="hidden" name="id_hewan" value="">
                                <?php else: ?>
                                    <select name="id_hewan" id="pilih_hewan" class="form-select" required
                                        onchange="tampilkanInfoHewan(this)">
                                        <option value="" disabled selected>-- Pilih Hewan --</option>
                                        <?php foreach ($daftar_hewan as $h):
                                            $kandang = !empty($h['no_kandang']) ? 'Kandang ' . $h['no_kandang'] : 'Kandang -';
                                            $label   = $h['kode_hewan'] . ' | ' . $h['jenis_hewan'] . ' | ' . $kandang;
                                        ?>
                                            <option value="<?= (int)$h['id_hewan'] ?>"
                                                data-kode="<?= htmlspecialchars($h['kode_hewan']) ?>"
                                                data-jenis="<?= htmlspecialchars($h['jenis_hewan']) ?>"
                                                data-kelamin="<?= htmlspecialchars($h['jenis_kelamin'] ?? '-') ?>"
                                                data-kandang="<?= htmlspecialchars($h['no_kandang'] ?? '-') ?>">
                                                <?= htmlspecialchars($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div id="info-hewan"></div>
                                <?php endif; ?>
                            </div>

                            <!-- Tanggal Pemeriksaan -->
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_pemeriksaan" class="form-control"
                                    max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <!-- Status Kesehatan -->
                            <div class="col-12 mt-3">
                                <label class="form-label">Status Kesehatan Hewan <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="status-btn active-sehat text-center" id="btn-sehat"
                                        onclick="pilihStatus('sehat', this)">
                                        <i class="bi bi-check-circle-fill"></i><br>Sehat
                                    </div>
                                    <div class="status-btn text-center" id="btn-perawatan"
                                        onclick="pilihStatus('perawatan', this)">
                                        <i class="bi bi-exclamation-circle"></i><br>Perawatan
                                    </div>
                                    <div class="status-btn text-center" id="btn-observasi"
                                        onclick="pilihStatus('observasi', this)">
                                        <i class="bi bi-eye"></i><br>Observasi
                                    </div>
                                </div>
                                <input type="hidden" name="status_kesehatan" id="statusValue" value="sehat">
                            </div>

                            <!-- Diagnosis & Tindakan -->
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Diagnosis</label>
                                <input type="text" name="diagnosis" class="form-control"
                                    placeholder="Contoh: Infeksi Ringan">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Tindakan</label>
                                <input type="text" name="tindakan" class="form-control"
                                    placeholder="Contoh: Pemberian Antibiotik">
                            </div>

                            <!-- Catatan -->
                            <div class="col-12 mt-3">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="catatan" class="form-control"
                                    placeholder="Tambahkan catatan atau keterangan detail di sini..."></textarea>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="col-12 d-flex justify-content-start gap-2 mt-4">
                                <a href="../data_kesehatan.php" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-success px-4 fw-bold"
                                    style="background-color: #10b981; border:none; min-width: 180px;">
                                    <i class="bi bi-save me-2"></i>Simpan Data
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="tips-box">
                    <h6 class="fw-bold"><i class="bi bi-lightbulb"></i> Tips Pengisian</h6>
                    <ul class="mb-0 small text-primary" style="list-style-type: '✦ '; padding-left: 1rem;">
                        <li>Pilih hewan dari dropdown — format: <strong>Kode | Jenis | Kandang</strong>.</li>
                        <li>Status <em>Perawatan</em> dan <em>Observasi</em> wajib diisi Diagnosis dan Tindakan.</li>
                        <li>Gunakan kotak catatan untuk menuliskan reaksi hewan setelah tindakan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pilihStatus(status, elemen) {
            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.classList.remove('active-sehat', 'active-perawatan', 'active-observasi');
            });
            if (status === 'sehat') elemen.classList.add('active-sehat');
            else if (status === 'perawatan') elemen.classList.add('active-perawatan');
            else if (status === 'observasi') elemen.classList.add('active-observasi');
            document.getElementById('statusValue').value = status;
        }

        function tampilkanInfoHewan(select) {
            const opt = select.options[select.selectedIndex];
            const kode = opt.getAttribute('data-kode') || '-';
            const jenis = opt.getAttribute('data-jenis') || '-';
            const kelamin = opt.getAttribute('data-kelamin') || '-';
            const kandang = opt.getAttribute('data-kandang') || '-';
            const info = document.getElementById('info-hewan');
            if (info) {
                info.innerHTML =
                    `<span class="badge bg-light text-dark border me-1">Kode: ${kode}</span>` +
                    `<span class="badge bg-light text-dark border me-1">Jenis: ${jenis}</span>` +
                    `<span class="badge bg-light text-dark border me-1">Kelamin: ${kelamin}</span>` +
                    `<span class="badge bg-light text-dark border">Kandang: ${kandang}</span>`;
            }
        }
    </script>
</body>

</html>