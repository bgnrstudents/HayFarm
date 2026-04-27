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

<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:'Nunito',sans-serif;}

/* SIDEBAR */
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
.menu li a { text-decoration: none; color: #333; padding: 10px; display: flex; align-items: center; gap: 10px; border-radius: 8px; }
.menu li a:hover { background: #f8f9fa; }
.menu .active a { background: #175D2B; color: #fff; }
.menu .active a i { color: #ffbe25; }
.menu-title { font-size: 12px; color: #777; margin: 15px 0 5px; }

.main-content {
    margin-left:250px;
    padding:20px;
    min-height:100vh;
    background: linear-gradient(to bottom,#ffffff 0px,#ffffff 80px,#dbe7df 80px,#c9d8cf 40%,#b8c8be 100%);
}

/* TOPBAR */
.topbar { display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 10px 20px; }
.search-box { position: relative; width: 300px; }
.search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 14px; pointer-events: none; }
.search-box input { width: 100%; padding: 8px 12px 8px 35px; border-radius: 20px; border: none; outline: none; background: #f1f3f5; font-size: 14px; }
.topbar-right { display: flex; align-items: center; gap: 15px; }
#currentDate { font-size: 13px; color: #555; }
.notif { position: relative; font-size: 16px; cursor: pointer; }
.notif .badge { position: absolute; top: -6px; right: -8px; background: red; color: white; font-size: 10px; padding: 3px 5px; border-radius: 50%; }
.user { display: flex; flex-direction: column; font-size: 12px; text-align: right; }
.user strong { font-size: 13px; }

/* HEADER */
.header { display:flex; justify-content:space-between; align-items:center; margin-top:20px; }
.header h2 {font-weight:700;}
.header p {font-size:13px;color:#777;}
.btn-add { background:#175D2B; color:white; border:none; padding:10px 15px; border-radius:8px; cursor:pointer; }

/* STATS */
.stats { display:grid; grid-template-columns:repeat(3,1fr); gap:15px; margin:20px 0; }
.stat-card { background:white; padding:15px; border-radius:12px; }
.stat-card h4 {font-size:13px;color:#777;}
.stat-card h2 {font-weight:bold;}

/* TABLE */
.table-box { background:white; border-radius:12px; padding:20px; }
.table-box input { width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; margin-bottom:15px; }
table {width:100%;border-collapse:collapse;}
th,td {padding:12px;border-bottom:1px solid #eee;font-size:14px;}
thead th {
    background:#ffc107;
    color:#3b2f00;
    font-weight:800;
    border-bottom:1px solid #e0a800;
}
tbody td {
    background:#fffdf5;
}
tbody tr:nth-child(even) td {
    background:#fffaf0;
}
.status { padding:5px 10px; border-radius:20px; font-size:12px; }
.sehat {background:#d4edda;color:#155724;}
.rawat {background:#fff3cd;color:#856404;}
.obs {background:#cce5ff;color:#004085;}
.action i { margin-right:10px; cursor:pointer; }

/* PAGINATION */
.pagination { display:flex; justify-content:space-between; margin-top:15px; }
.page-btn { border:none; padding:6px 10px; border-radius:6px; }
.active-page { background:#175D2B; color:white; }

/* ===================== */
/* MODAL SHARED STYLES   */
/* ===================== */
.modal-overlay {
    display: none;
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    justify-content: center; align-items: center;
    padding: 15px;
}
.modal-overlay.active { display: flex; }

/* ===================== */
/* MODAL TAMBAH & EDIT   */
/* ===================== */
.form-modal-box {
    background: white;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    border-radius: 16px;
    overflow-y: auto;
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    padding: 30px;
    position: relative;
}
.form-modal-box::-webkit-scrollbar { width: 6px; }
.form-modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

.modal-header-row {
    display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;
}
.modal-title { font-weight: 700; font-size: 1.4rem; color: #1e293b; margin: 0; }
.modal-subtitle { color: #64748b; font-size: 0.9rem; margin-bottom: 20px; }
.modal-close-btn {
    background: none; border: none; font-size: 1.4rem; color: #94a3b8;
    cursor: pointer; line-height: 1; padding: 4px 8px; border-radius: 8px;
    transition: background 0.2s;
}
.modal-close-btn:hover { background: #f1f5f9; color: #374151; }

/* Tabs */
.nav-tabs { border-bottom: 1px solid #e2e8f0; margin-bottom: 20px; }
.nav-tabs .nav-link { border: none; padding: 10px 20px; color: #6b7280; font-weight: 600; background: none; border-radius: 8px 8px 0 0; }
.nav-tabs .nav-link.active { color: #1a532b; background: #f0fdf4; border-bottom: 2px solid #1a532b; }

/* Form elements */
.form-label { font-weight: 600; font-size: 0.88rem; margin-top: 16px; margin-bottom: 6px; color: #374151; display: block; }
.form-control, .form-select {
    border-radius: 10px; padding: 11px 14px; border: 1px solid #e2e8f0;
    font-size: 0.9rem; transition: border-color 0.2s; width: 100%;
}
.form-control:focus, .form-select:focus {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); outline: none;
}
.form-control::placeholder { color: #ccc; font-weight: 300; }

/* Status buttons */
.health-status-btn {
    border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 10px;
    cursor: pointer; transition: all 0.3s; flex: 1; text-align: center; background: #fff;
}
.health-status-btn i { font-size: 1.2rem; display: block; margin-bottom: 5px; }
.health-status-btn span { font-size: 0.82rem; font-weight: 600; }
.health-status-btn:hover { transform: translateY(-1px); }
.status-sehat.active  { border-color: #10b981; color: #10b981; background-color: #ecfdf5; }
.status-perawatan.active { border-color: #f59e0b; color: #f59e0b; background-color: #fef3c7; }
.status-observasi.active { border-color: #3b82f6; color: #3b82f6; background-color: #eff6ff; }

/* Footer buttons */
.btn-footer { display: flex; gap: 12px; margin-top: 30px; }
.btn-kembali {
    background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0;
    flex: 1; padding: 13px; font-weight: 600; border-radius: 10px;
    transition: all 0.2s; cursor: pointer;
}
.btn-kembali:hover { background-color: #f1f5f9; }
.btn-simpan {
    background-color: #175D2B; color: #fff; border: none;
    flex: 1; padding: 13px; font-weight: 600; border-radius: 10px;
    transition: all 0.2s; cursor: pointer;
}
.btn-simpan:hover { background-color: #166534; }

/* Tips box */
.tips-box {
    background-color: #f0fdf4; border-radius: 12px; padding: 16px;
    border-left: 4px solid #175D2B; margin-top: 20px;
}
.tips-box h6 { font-weight: 700; color: #175D2B; margin-bottom: 8px; }
.tips-box ul { margin: 0; padding-left: 1.2rem; font-size: 0.85rem; color: #374151; }

/* ===================== */
/* MODAL PREVIEW         */
/* ===================== */
.Preview-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5); display: none;
    justify-content: center; align-items: center; z-index: 9999; padding: 15px;
}
.Preview-overlay.active { display: flex; }
.ringkasan-card {
    background: white; width: 100%; max-width: 520px;
    max-height: 90vh; border-radius: 20px; overflow-y: auto;
    position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.card-body { padding: 30px; }
.header-section { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
.id-badge { background: #1e293b; color: white; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; }
.subtitle-preview { color: #14b8a6; font-size: 13px; font-weight: 600; margin-top: 4px; }
.section-title { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 25px 0 15px; }
.data-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
.info-box { border: 1px solid #f1f5f9; padding: 12px 15px; border-radius: 12px; background: #fff; }
.label { font-size: 10px; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 5px; }
.value { font-size: 14px; color: #334155; font-weight: 700; }
.status-health { background: #ecfdf5; color: #10b981; padding: 4px 15px; border-radius: 8px; font-size: 12px; font-weight: 800; border: 1px solid #d1fae5; }
.note-box-preview { background: #fffbeb; border: 1px solid #fef3c7; padding: 15px; border-radius: 12px; color: #b45309; font-size: 13px; line-height: 1.6; }
.footer-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 35px; }
.btn-back { background: white; border: 1px solid #e2e8f0; color: #64748b; padding: 14px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; }
.btn-save { background: #009688; color: white; padding: 14px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; border: none; }

/* ===================== */
/* MODAL DELETE          */
/* ===================== */
.Delete-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.45); display: flex; justify-content: center;
    align-items: center; z-index: 10000; opacity: 0; visibility: hidden; transition: 0.3s;
}
.Delete-overlay.active { opacity: 1; visibility: visible; }
.Delete-box {
    background: #ffffff; width: 460px; max-width: 92vw; border-radius: 18px;
    padding: 32px 36px 70px; position: relative;
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    transform: translateY(20px); transition: 0.3s ease;
    border-bottom: 3px solid #4a90e2;
}
.Delete-overlay.active .Delete-box { transform: translateY(0); }
.Delete-header-custom { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
.icon-circle { width: 50px; height: 50px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.icon-circle i { font-size: 22px; color: #ef4444; }
.Delete-title-custom { font-size: 21px; font-weight: 700; color: #111827; margin: 0; }
.Delete-body-custom p { font-size: 15px; color: #6b7280; line-height: 1.6; margin-bottom: 8px; }
.warning-text { font-size: 14px; color: #9ca3af; }
.btn-group-custom { position: absolute; bottom: -24px; right: 28px; display: flex; gap: 12px; }
.btn-custom { padding: 11px 26px; font-size: 15px; font-weight: 600; border-radius: 12px; cursor: pointer; transition: 0.2s; border: none; }
.btn-cancel { background: #ffffff; color: #374151; border: 1px solid #e5e7eb; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.btn-cancel:hover { background: #f9fafb; transform: translateY(-1px); }
.btn-delete { background: #ef4444; color: #ffffff; box-shadow: 0 4px 14px rgba(239,68,68,0.35); }
.btn-delete:hover { background: #dc2626; transform: translateY(-1px); }
</style>
</head>

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

        <form id="tambahForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                    <select name="jenis_hewan" class="form-select" required>
                        <option value="" disabled selected>Pilih Jenis</option>
                        <option value="Sapi Perah">Sapi Perah</option>
                        <option value="Sapi PO">Sapi PO</option>
                        <option value="Kambing">Kambing</option>
                        <option value="Domba">Domba</option>
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
                <button type="button" class="btn-simpan" onclick="simpanTambah()">
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
                <!-- Tab Kesehatan -->
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
                            <label class="form-label">Jenis Hewan</label>
                            <select name="jenis_hewan" id="editJenis" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="Sapi Perah">Sapi Perah</option>
                                <option value="Sapi PO">Sapi PO</option>
                                <option value="Kambing">Kambing</option>
                                <option value="Domba">Domba</option>
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

                <!-- Tab Reproduksi -->
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
                Sapi dalam pemantauan nutrisi tambahan untuk mendukung masa kehamilan. Nafsu makan stabil dan berat badan meningkat sesuai target.
            </div>
            <div class="footer-actions">
                <button class="btn-back" onclick="closePreview()">Kembali</button>
                <button class="btn-save" onclick="alert('Data Berhasil Disimpan!');closePreview();">Konfirmasi & Simpan</button>
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

<body>

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
    <div class="stat-card"><h4>Total Sehat</h4><h2>2</h2></div>
    <div class="stat-card"><h4>Dalam Perawatan</h4><h2>1</h2></div>
    <div class="stat-card"><h4>Observasi</h4><h2>2</h2></div>
</div>

<!-- TABLE -->
<div class="table-box">
<table>
<thead>
<tr>
    <th>Jenis Hewan</th>
    <th>Tanggal Pemeriksaan</th>
    <th>Status</th>
    <th>Diagnosa</th>
    <th>Tindakan</th>
    <th>Keterangan</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<tr>
    <td>Sapi</td><td>25 Feb 2026</td>
    <td><span class="status sehat">Sehat</span></td>
    <td>-</td><td>Vaksin</td><td>Pemeriksaan rutin</td>
    <td class="action">
        <i class="fa fa-eye" title="Lihat" onclick="openPreview()"></i>
        <i class="fa fa-pen" title="Edit" onclick="openEdit('0001','2026-02-25','Sapi','sehat','-','Vaksin','Pemeriksaan rutin')"></i>
        <i class="fa fa-trash" title="Hapus" onclick="openDelete('Sapi (Kandang 00004)')"></i>
    </td>
</tr>
<tr>
    <td>Kambing</td><td>24 Feb 2026</td>
    <td><span class="status rawat">Dalam Perawatan</span></td>
    <td>Infeksi ringan</td><td>Antibiotik</td><td>Perlu kontrol</td>
    <td class="action">
        <i class="fa fa-eye" title="Lihat" onclick="openPreview()"></i>
        <i class="fa fa-pen" title="Edit" onclick="openEdit('0002','2026-02-24','Kambing','perawatan','Infeksi ringan','Antibiotik','Perlu kontrol')"></i>
        <i class="fa fa-trash" title="Hapus" onclick="openDelete('Kambing (Kandang 00005)')"></i>
    </td>
</tr>
<tr>
    <td>Domba</td><td>23 Feb 2026</td>
    <td><span class="status obs">Observasi</span></td>
    <td>Demam</td><td>Monitoring</td><td>Pantau 3 hari</td>
    <td class="action">
        <i class="fa fa-eye" title="Lihat" onclick="openPreview()"></i>
        <i class="fa fa-pen" title="Edit" onclick="openEdit('0003','2026-02-23','Domba','observasi','Demam','Monitoring','Pantau 3 hari')"></i>
        <i class="fa fa-trash" title="Hapus" onclick="openDelete('Domba (Kandang 00006)')"></i>
    </td>
</tr>
<tr>
    <td>Sapi</td><td>22 Feb 2026</td>
    <td><span class="status sehat">Sehat</span></td>
    <td>Vitamin</td><td>Vitamin</td><td>Kondisi stabil</td>
    <td class="action">
        <i class="fa fa-eye" title="Lihat" onclick="openPreview()"></i>
        <i class="fa fa-pen" title="Edit" onclick="openEdit('0004','2026-02-22','Sapi','sehat','Vitamin','Vitamin','Kondisi stabil')"></i>
        <i class="fa fa-trash" title="Hapus" onclick="openDelete('Sapi (Kandang 00008)')"></i>
    </td>
</tr>
<tr>
    <td>Kambing</td><td>21 Feb 2026</td>
    <td><span class="status obs">Observasi</span></td>
    <td>Nafsu makan turun</td><td>Suplemen</td><td>Pantau berat badan</td>
    <td class="action">
        <i class="fa fa-eye" title="Lihat" onclick="openPreview()"></i>
        <i class="fa fa-pen" title="Edit" onclick="openEdit('0005','2026-02-21','Kambing','observasi','Nafsu makan turun','Suplemen','Pantau berat badan')"></i>
        <i class="fa fa-trash" title="Hapus" onclick="openDelete('Kambing (Kandang 00010)')"></i>
    </td>
</tr>
</tbody>
</table>

<div class="pagination">
    <span>Menampilkan 1-5 dari 5 data</span>
    <div>
        <button class="page-btn">Sebelumnya</button>
        <button class="page-btn active-page">1</button>
        <button class="page-btn">Selanjutnya</button>
    </div>
</div>
</div>

</div><!-- end main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ---- Tanggal ----
document.getElementById('currentDate').textContent =
    new Date().toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'});

// ---- Utility: tutup modal jika klik backdrop ----
function closeModalOutside(event, overlayId) {
    if (event.target.id === overlayId) {
        document.getElementById(overlayId).classList.remove('active');
        document.getElementById(overlayId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ============ MODAL TAMBAH ============
function openTambah() {
    document.getElementById('tambahForm').reset();
    // Reset status buttons
    document.querySelectorAll('#tambahOverlay .health-status-btn').forEach(b =>
        b.classList.remove('active'));
    document.getElementById('tambah-btn-sehat').classList.add('active');
    document.getElementById('tambahStatusValue').value = 'sehat';

    const overlay = document.getElementById('tambahOverlay');
    overlay.style.display = 'flex';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeTambah() {
    const overlay = document.getElementById('tambahOverlay');
    overlay.classList.remove('active');
    overlay.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function pilihStatusTambah(status, elemen) {
    document.querySelectorAll('#tambahOverlay .health-status-btn').forEach(btn =>
        btn.classList.remove('active'));
    elemen.classList.add('active');
    document.getElementById('tambahStatusValue').value = status;
}

function simpanTambah() {
    alert('✅ Data berhasil disimpan!');
    closeTambah();
}

// ============ MODAL EDIT ============
function openEdit(id, tanggal, jenis, status, diagnosa, tindakan, keterangan) {
    // Isi field
    document.getElementById('editIdKesehatan').value = id || '';
    document.getElementById('editTanggal').value = tanggal || '';
    document.getElementById('editJenis').value = jenis || '';
    document.getElementById('editDiagnosa').value = diagnosa || '';
    document.getElementById('editTindakan').value = tindakan || '';
    document.getElementById('editKeterangan').value = keterangan || '';

    // Set status button aktif
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(b =>
        b.classList.remove('active'));
    const statusMap = { sehat: 'edit-btn-sehat', perawatan: 'edit-btn-perawatan', observasi: 'edit-btn-observasi' };
    if (statusMap[status]) document.getElementById(statusMap[status]).classList.add('active');
    document.getElementById('inputStatusKesehatan').value = status || '';

    // Reset tab ke Kesehatan
    const tab = new bootstrap.Tab(document.getElementById('kesehatan-tab'));
    tab.show();

    const overlay = document.getElementById('editOverlay');
    overlay.style.display = 'flex';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEdit() {
    const overlay = document.getElementById('editOverlay');
    overlay.classList.remove('active');
    overlay.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function selectStatus(element, value) {
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(btn =>
        btn.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('inputStatusKesehatan').value = value;
}

function simpanEdit() {
    alert('✅ Data berhasil disimpan!');
    closeEdit();
}

// ============ MODAL PREVIEW ============
function openPreview() {
    document.getElementById('ringkasanPreview').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closePreview() {
    document.getElementById('ringkasanPreview').classList.remove('active');
    document.body.style.overflow = 'auto';
}
function closePreviewOutside(event) {
    if (event.target.id === 'ringkasanPreview') closePreview();
}

// ============ MODAL DELETE ============
function openDelete(namaHewan) {
    document.getElementById('deleteTarget').textContent = namaHewan || 'data ini';
    document.getElementById('deleteOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeDelete() {
    document.getElementById('deleteOverlay').classList.remove('active');
    document.body.style.overflow = 'auto';
}
function closeDeleteOutside(event) {
    if (event.target.id === 'deleteOverlay') closeDelete();
}
function confirmDelete() {
    alert('✅ Data berhasil dihapus!');
    closeDelete();
}

// ESC untuk tutup semua modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTambah(); closeEdit(); closePreview(); closeDelete();
    }
});
</script>

</body>
</html>