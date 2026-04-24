<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kesehatan Hewan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-container">
    <h3 class="header-title">Edit Data Kesehatan Hewan</h3>
    <p class="header-subtitle">Kelola informasi kesehatan dan reproduksi ternak Anda</p>

    <ul class="nav nav-tabs" id="editTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="kesehatan-tab" data-bs-toggle="tab" data-bs-target="#kesehatan-content" type="button">Kesehatan</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="reproduksi-tab" data-bs-toggle="tab" data-bs-target="#reproduksi-content" type="button">Reproduksi</button>
        </li>
    </ul>

    <form id="editKesehatanForm" method="POST">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="kesehatan-content">
                <div class="mb-3">
                    <label class="form-label">ID</label>
                    <input type="text" name="id" id="editIdKesehatan" class="form-control" readonly>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="editTanggal" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Jenis Hewan</label>
                        <select name="jenis_hewan" id="editJenis" class="form-select form-control">
                            <option value="">Pilih...</option>
                            <option value="Sapi Perah">Sapi Perah</option>
                            <option value="Sapi PO">Sapi PO</option>
                            <option value="Kambing">Kambing</option>
                            <option value="Domba">Domba</option>
                        </select>
                    </div>
                </div>

                <label class="form-label mt-4">Status Kesehatan</label>
                <div class="d-flex gap-2">
                    <div class="health-status-btn status-sehat" onclick="selectStatus(this, 'sehat')">
                        <i class="bi bi-check-circle"></i>
                        <span>Sehat</span>
                    </div>
                    <div class="health-status-btn status-perawatan active" onclick="selectStatus(this, 'perawatan')">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Dalam Perawatan</span>
                    </div>
                    <div class="health-status-btn status-observasi" onclick="selectStatus(this, 'observasi')">
                        <i class="bi bi-clock"></i>
                        <span>Observasi</span>
                    </div>
                </div>
                <input type="hidden" name="status_kesehatan" id="inputStatusKesehatan" value="perawatan">

                <div class="mb-3">
                    <label class="form-label">Diagnosa</label>
                    <input type="text" name="diagnosis" id="editDiagnosa" class="form-control" placeholder="Masukkan diagnosis...">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tindakan</label>
                    <input type="text" name="tindakan" id="editTindakan" class="form-control" placeholder="Masukkan tindakan yang dilakukan...">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="editKeterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan..."></textarea>
                </div>
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

                <div class="mt-3">
                    <label class="form-label">Informasi Tambahan</label>
                    <textarea name="informasi_tambahan" id="editInfoTambahan" class="form-control" rows="4" placeholder="Informasi tambahan..."></textarea>
                </div>
            </div>
        </div>

        <div class="btn-footer">
            <button type="button" class="btn-kembali" onclick="window.history.back()">Kembali</button>
            <button type="submit" class="btn-simpan" onclick="handleSave(event)">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>

<style>
body { background-color: #f8fafc; font-family: 'Nunito', sans-serif; }
.main-container { max-width: 700px; margin: 40px auto; padding: 30px; background: white; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.header-title { font-weight: 700; font-size: 1.6rem; margin-bottom: 8px; color: #1e293b; }
.header-subtitle { color: #64748b; font-size: 0.95rem; margin-bottom: 30px; }
.form-label { font-weight: 600; font-size: 0.9rem; margin-top: 20px; margin-bottom: 8px; color: #374151; }
.form-control { border-radius: 10px; padding: 12px 16px; border: 1px solid #e2e8f0; font-size: 0.9rem; transition: border-color 0.2s; }
.form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.health-status-btn { border: 2px solid #e2e8f0; border-radius: 12px; padding: 16px; cursor: pointer; transition: all 0.3s; flex: 1; text-align: center; background: #fff; }
.health-status-btn i { font-size: 1.3rem; display: block; margin-bottom: 6px; }
.health-status-btn span { font-size: 0.85rem; font-weight: 600; }
.health-status-btn:hover { transform: translateY(-1px); }
.status-sehat.active { border-color: #10b981; color: #10b981; background-color: #ecfdf5; }
.status-perawatan.active { border-color: #f59e0b; color: #f59e0b; background-color: #fef3c7; }
.status-observasi.active { border-color: #3b82f6; color: #3b82f6; background-color: #eff6ff; }
.btn-footer { display: flex; gap: 15px; margin-top: 40px; }
.btn-kembali { background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0; flex: 1; padding: 14px; font-weight: 600; border-radius: 10px; transition: all 0.2s; }
.btn-kembali:hover { background-color: #f1f5f9; transform: translateY(-1px); }
.btn-simpan { background-color: #1a532b; color: #fff; border: none; flex: 1; padding: 14px; font-weight: 600; border-radius: 10px; transition: all 0.2s; }
.btn-simpan:hover { background-color: #166534; transform: translateY(-1px); }
.nav-tabs { border-bottom: 1px solid #e2e8f0; margin-bottom: 25px; }
.nav-tabs .nav-link { border: none; padding: 10px 20px; color: #6b7280; font-weight: 600; background: none; border-radius: 8px 8px 0 0; }
.nav-tabs .nav-link.active { color: #1a532b; background: #f0fdf4; border-bottom: 2px solid #1a532b; }
</style>

<script>
function selectStatus(element, value) {
    document.querySelectorAll('.health-status-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('inputStatusKesehatan').value = value;
}

// Handle save with alert simulation
function handleSave(event) {
    event.preventDefault(); // Prevent actual submit for demo
    alert('✅ Data berhasil disimpan!');
    window.history.back(); // Go back to list
}

// Populate from URL params
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    const tanggal = urlParams.get('tanggal');
    const jenis = urlParams.get('jenis');
    const status = urlParams.get('status');
    const diagnosa = urlParams.get('diagnosa');
    const tindakan = urlParams.get('tindakan');
    const keterangan = urlParams.get('keterangan');

    if (id) {
        document.getElementById('editIdKesehatan').value = id;
        document.getElementById('editTanggal').value = tanggal ? tanggal.split(' ')[0] : '';
        if (jenis) document.getElementById('editJenis').value = jenis;
        document.getElementById('editDiagnosa').value = diagnosa || '';
        document.getElementById('editTindakan').value = tindakan || '';
        document.getElementById('editKeterangan').value = keterangan || '';
    }
});
</script>

</body>
</html>