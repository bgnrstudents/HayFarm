<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Hewan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f0fdf4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-label { font-weight: 500; color: #444; }
        .form-label .text-danger { margin-left: 2px; }
        
        /* Tombol Status Kustom */
        .status-btn {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            flex: 1;
        }
        .status-btn.active-productive { border-color: #198754; background-color: #f0fff4; color: #198754; }
        .status-btn.active-unproductive { border-color: #dc3545; background-color: #fff5f5; color: #dc3545; }
        
        /* Placeholder styling */
        .form-control::placeholder { color: #ccc; font-style: italic; }
        
        .tips-box {
            background-color: #ebf5ff;
            border-radius: 12px;
            padding: 20px;
            border-left: 5px solid #0d6efd;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            

            <div class="card p-4 mb-4">
                <form action="proses_simpan.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">

                    <div class="d-flex align-items-center mb-4">
                <a href="data.php" class="btn btn-light rounded-circle me-3">
                  <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold">Tambah Data Hewan</h4>
                    <small class="text-muted">Catat data hewan ternak</small>
                </div>
            </div>
                        <div class="col-md-6">
                            <label class="form-label">ID Hewan <span class="text-danger">*</span></label>
                            <input type="text" name="id_hewan" class="form-control" placeholder="Contoh: 00004" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                            <select name="jenis_hewan" class="form-select" required>
                                <option value="" selected disabled>Pilih Jenis</option>
                                <option value="Sapi">Sapi Perah</option>
                                <option value="Sapi PO">Sapi PO</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Berat Badan Hewan <span class="text-danger">*</span></label>
                            <input type="text" name="berat_badan" class="form-control" placeholder="Contoh: 00004" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="" selected disabled>Pilih Kelamin</option>
                                <option value="Jantan">Jantan</option>
                                <option value="Betina">Betina</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Kandang</label>
                            <input type="text" name="no_kandang" class="form-control" placeholder="Contoh: Infeksi Ringan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" placeholder="Contoh: Antibiotik">
                        </div>


                        <div class="col-12 mt-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="status-btn active-productive text-center" id="btnProduktif">
                                    <i class="bi bi-check-circle-fill"></i><br>Produktif
                                </div>
                                <div class="status-btn text-center" id="btnTidakProduktif">
                                    <i class="bi bi-exclamation-circle"></i><br>Tidak Produktif
                                </div>
                            </div>
                            <input type="hidden" name="status" id="statusValue" value="produktif">
                        </div>

                        <div class="col-12 d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 py-2">Batal</button>
                            <button type="submit" class="btn btn-success px-4 py-2 flex-grow-1 fw-bold" style="background-color: #10b981; border:none;">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="tips-box">
                <h6 class="fw-bold"><i class="bi bi-lightbulb"></i> Tips Pengisian</h6>
                <ul class="mb-0 small text-primary" style="list-style-type: '✦ '; padding-left: 1rem;">
                    <li>Pastikan ID Hewan sesuai dengan catatan identifikasi ternak</li>
                    <li>Pilih status kesehatan yang sesuai dengan kondisi aktual hewan</li>
                    <li>Catat diagnosa dan tindakan secara detail untuk referensi masa depan</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<script>
    const btnP = document.getElementById('btnProduktif');
    const btnT = document.getElementById('btnTidakProduktif');
    const statusVal = document.getElementById('statusValue');

    btnP.addEventListener('click', () => {
        btnP.classList.add('active-productive');
        btnT.classList.remove('active-unproductive');
        statusVal.value = 'produktif';
    });

    btnT.addEventListener('click', () => {
        btnT.classList.add('active-unproductive');
        btnP.classList.remove('active-productive');
        statusVal.value = 'tidak_produktif';
    });
</script>

</body>
</html>
