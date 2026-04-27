<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kesehatan Hewan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* 1. Pengaturan Dasar */
        body { background-color: #f0fdf4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-label { font-weight: 500; color: #444; margin-bottom: 5px; font-size: 0.9rem; }
        
        /* 2. Gaya Kotak Catatan (Textarea) */
        textarea.form-control {
            border-radius: 12px;
            resize: none;
            padding: 15px;
            height: 120px; /* Atur tinggi kotak catatan */
        }
        textarea.form-control::placeholder { color: #ccc; font-style: italic; }

        /* 3. Tombol Status Kustom */
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
        .status-btn i { font-size: 1.2rem; }

        /* Warna Aktif */
        .status-btn.active-sehat { border-color: #198754; background-color: #f0fff4; color: #198754; }
        .status-btn.active-perawatan { border-color: #ffc107; background-color: #fffbeb; color: #d97706; }
        .status-btn.active-observasi { border-color: #0d6efd; background-color: #f0f7ff; color: #0d6efd; }
        
        /* 4. Tips Box */
        .tips-box {
            background-color: #ebf5ff;
            border-radius: 12px;
            padding: 20px;
            border-left: 5px solid #0d6efd;
        }

        /* 5. placeholder */
        .form-control::placeholder {
             color: #ccc;          /* Warna abu-abu muda agar terlihat tipis */
            font-weight: 300;     /* Membuat font lebih tipis (Light) */
}
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            
            

            <div class="card p-4 mb-4">
                <form action="proses_simpan.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">

                        <div class="d-flex align-items-center mb-4">
                            <a href="kesehatan.php" class="btn btn-light rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                            </a>
                        <div>
                            <h4 class="mb-0 fw-bold">Tambah Data Kesehatan Hewan</h4>
                            <small class="text-muted">Catat data kesehatan hewan ternak secara akurat</small>
                        </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">ID Hewan <span class="text-danger">*</span></label>
                            <input type="text" name="id_hewan" class="form-control" placeholder="Contoh: 00004" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                            <select name="jenis_hewan" class="form-select" required>
                                <option value="" selected disabled>Pilih Jenis</option>
                                <option value="Sapi Perah">Sapi Perah</option>
                                <option value="Sapi PO">Sapi PO</option>
                                <option value="Kambing">Kambing</option>
                            </select>
                        </div>
                        <div class="row g-3"></div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Pemeriksaan</label>
                            <input type="date" name="tgl_pemeriksaan" class="form-control" max="2026-12-31">
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label">Status Kesehatan Hewan <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="status-btn active-sehat text-center" id="btn-sehat" onclick="pilihStatus('sehat', this)">
                                    <i class="bi bi-check-circle-fill"></i><br>Sehat
                                </div>
                                <div class="status-btn text-center" id="btn-perawatan" onclick="pilihStatus('perawatan', this)">
                                    <i class="bi bi-exclamation-circle"></i><br>Perawatan
                                </div>
                                <div class="status-btn text-center" id="btn-observasi" onclick="pilihStatus('observasi', this)">
                                    <i class="bi bi-eye"></i><br>Observasi
                                </div>
                            </div>
                            <input type="hidden" name="status" id="statusValue" value="sehat">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <input type="text" name="diagnosis" class="form-control" placeholder="Contoh: Infeksi Ringan" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Tindakan <span class="text-danger">*</span></label>
                            <input type="text" name="tindakan" class="form-control" placeholder="Contoh: Pemberian Antibiotik" required>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="catatan" class="form-control" placeholder="Tambahkan catatan atau keterangan detail di sini..."></textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-start gap-2 mt-4">
                            <button type="button" onclick="history.back()" class="btn btn-outline-secondary px-4">Batal</button>
                            <button type="button" onclick="alert('✅ Data berhasil disimpan!'); window.location.href='kesehatan.php';" class="btn btn-success px-4 fw-bold" style="background-color: #10b981; border:none; min-width: 180px;">
                                <i class="bi bi-save me-2"></i>Simpan Data
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <div class="tips-box">
                <h6 class="fw-bold"><i class="bi bi-lightbulb"></i> Tips Pengisian</h6>
                <ul class="mb-0 small text-primary" style="list-style-type: '✦ '; padding-left: 1rem;">
                    <li>Pastikan ID Hewan sesuai dengan tag telinga atau catatan kandang.</li>
                    <li>Status kesehatan menentukan jadwal kunjungan dokter hewan berikutnya.</li>
                    <li>Gunakan kotak catatan untuk menuliskan reaksi hewan setelah tindakan.</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<script>
    function pilihStatus(status, elemen) {
        // Hapus semua kelas aktif
        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('active-sehat', 'active-perawatan', 'active-observasi');
        });

        // Tambah kelas aktif ke yang diklik
        if (status === 'sehat') elemen.classList.add('active-sehat');
        else if (status === 'perawatan') elemen.classList.add('active-perawatan');
        else if (status === 'observasi') elemen.classList.add('active-observasi');

        // Simpan nilai ke input hidden
        document.getElementById('statusValue').value = status;
    }
</script>

</body>
</html>