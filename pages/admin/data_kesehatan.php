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

<link rel="stylesheet" href="../../public/css/admin_dataKesehatan.css">
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
                <button class="btn-save" onclick="showFlashMessage('Data berhasil disimpan.');closePreview();">Konfirmasi & Simpan</button>
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

<script src="../../public/js/dataKesehatan_admin.js"></script>

</body>
</html>