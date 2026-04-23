<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Manajemen Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../public/css/admin_manajemenProduk.css">
</head>
<body>
<!-- SIDEBAR -->
<div class="sidebar">
    <img src="../../public/images/logo_hayfarm.png" class="logo" alt="Logo">

    <ul class="menu">
        <li><a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a></li>
        <li class="active"><a href="manajemen_produk.php"><i class="fa-solid fa-credit-card"></i> Manajemen Produk</a></li>
        <li><a href="#"><i class="fa-solid fa-file-circle-check"></i> Verifikasi Penjualan</a></li>
        <p class="menu-title">DATA</p>
        <li><a href="#"><i class="fa-solid fa-square-poll-vertical"></i> Data Hewan</a></li>
        <li><a href="#"><i class="fa-solid fa-heart-pulse"></i> Data Kesehatan Hewan</a></li>
        <li><a href="#"><i class="fa-solid fa-power-off"></i> Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar justify-content-end">
        <div class="topbar-right">
            <span id="currentDate"></span>
            <div class="notif">
                <i class="fa-solid fa-bell" style="color: rgb(25, 108, 51);"></i>
                <span class="badge">6</span>
            </div>
            <div class="user">
                <strong>Farel</strong>
                <small>Admin</small>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk</h3>
                <div class="number" id="totalProduk">0</div>
            </div>
            <div class="stat-icon produk"><i class="fa-solid fa-box"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk Rumput</h3>
                <div class="number" id="totalRumput">0</div>
            </div>
            <div class="stat-icon rumput"><i class="fa-solid fa-seedling"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk Susu</h3>
                <div class="number" id="totalSusu">0</div>
            </div>
            <div class="stat-icon susu"><i class="fa-solid fa-bottle-water"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk Hewan</h3>
                <div class="number" id="totalHewan">0</div>
            </div>
            <div class="stat-icon hewan"><i class="fa-solid fa-cow"></i></div>
        </div>
    </div>

    <!-- PRODUCT LIST SECTION -->
    <div class="product-section">
        <div class="section-header">
            <h2>Daftar Produk</h2>
            <p>Manajemen data produk</p>
        </div>

        <div class="table-controls">
            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari produk..." id="tableSearch">
            </div>
            <div class="table-actions">
                <button class="btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
                <button class="btn-export" onclick="exportTableToCSV('produk_data.csv')"><i class="fa-solid fa-download"></i> Export</button>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </button>
            </div>

        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Jenis Produk</th>
                    <th>Nama Produk</th>
                    <th>Tanggal</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody"></tbody>
        </table>
    
    </div>

    <!-- FILTER MODAL -->
    <div class="modal-overlay" id="filterModal">
        <div class="filter-modal">
            <h3>Filter Produk</h3>
            <div class="filter-group">
                <label for="filterJenis">Jenis Produk</label>
                <select id="filterJenis">
                    <option value="">Semua Jenis</option>
                    <option value="Rumput">Rumput</option>
                    <option value="Hewan">Hewan</option>
                    <option value="Susu">Susu</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterStatus">Status</label>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                </select>
            </div>
            <div class="filter-buttons">
                <button class="btn-filter-apply" onclick="applyFilter()">Terapkan</button>
                <button class="btn-filter-reset" onclick="resetFilter()">Reset</button>
                <button class="btn-filter-close" onclick="closeFilterModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal-overlay" id="editModal">
        <div class="edit-modal">
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
            <div class="header">
                <div>
                    <h2>Edit Data Produk</h2>
                    <p>Perbarui informasi produk</p>
                </div>
            </div>
            <div class="tabs">
                <div class="tab active" data-tab="hewan" onclick="switchEditTab('hewan')">Hewan</div>
                <div class="tab" data-tab="rumput" onclick="switchEditTab('rumput')">Rumput</div>
                <div class="tab" data-tab="susu" onclick="switchEditTab('susu')">Susu</div>
            </div>
            <div class="form-content">
                <!-- ===== FORM HEWAN ===== -->
                <form id="edit-form-hewan" class="form-section active" onsubmit="handleEditSubmit(event, 'hewan')">
                    <input type="hidden" id="edit-id-hewan">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Hewan <span class="required">*</span></label>
                            <select class="form-select" id="edit-jenis-hewan" required>
                                <option value="">Pilih jenis hewan</option>
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                                <option value="kerbau">Kerbau</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-nama-hewan" placeholder="Contoh: Sapi Perah FH" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" class="form-input" id="edit-berat-hewan" placeholder="Contoh: 450">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-harga-hewan" placeholder="Rp 20.000.000" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah/Stok <span class="required">*</span></label>
                        <input type="number" class="form-input" id="edit-stok-hewan" placeholder="Contoh: 4" min="1" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Hewan</label>
                        <div class="upload-wrapper">
                            <input type="file" id="edit-file-hewan" class="file-input" hidden accept="image/*" onchange="previewEditImage(event, 'hewan')">
                            <div class="upload-box" onclick="document.getElementById('edit-file-hewan').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="edit-preview-hewan" style="display: none;">
                                <img id="edit-img-hewan" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeEditImage('hewan')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectEditStatus(this, 'hewan')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectEditStatus(this, 'hewan')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-status-hewan" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>

                <!-- ===== FORM RUMPUT ===== -->
                <form id="edit-form-rumput" class="form-section" onsubmit="handleEditSubmit(event, 'rumput')">
                    <input type="hidden" id="edit-id-rumput">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Rumput <span class="required">*</span></label>
                            <select class="form-select" id="edit-jenis-rumput" required>
                                <option value="">Pilih jenis rumput</option>
                                <option value="odot">Rumput Odot</option>
                                <option value="gajah">Rumput Gajah</option>
                                <option value="pakan">Rumput Pakan</option>
                                <option value="lapangan">Rumput Lapangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-nama-rumput" placeholder="Contoh: Rumput Odot Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Kg <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-harga-rumput" placeholder="Rp 2.500" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Kg) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="edit-stok-rumput" placeholder="Contoh: 500" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="edit-file-rumput" class="file-input" hidden accept="image/*" onchange="previewEditImage(event, 'rumput')">
                            <div class="upload-box" onclick="document.getElementById('edit-file-rumput').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="edit-preview-rumput" style="display: none;">
                                <img id="edit-img-rumput" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeEditImage('rumput')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectEditStatus(this, 'rumput')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectEditStatus(this, 'rumput')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-status-rumput" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>

                <!-- ===== FORM SUSU ===== -->
                <form id="edit-form-susu" class="form-section" onsubmit="handleEditSubmit(event, 'susu')">
                    <input type="hidden" id="edit-id-susu">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Susu <span class="required">*</span></label>
                            <select class="form-select" id="edit-jenis-susu" required>
                                <option value="">Pilih jenis susu</option>
                                <option value="segar">Susu Segar</option>
                                <option value="pasteurisasi">Susu Pasteurisasi</option>
                                <option value="uht">Susu UHT</option>
                                <option value="fermentasi">Susu Fermentasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-nama-susu" placeholder="Contoh: Susu Segar Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Produksi <span class="required">*</span></label>
                            <input type="date" class="form-input" id="edit-tgl-produksi-susu" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluwarsa <span class="required">*</span></label>
                            <input type="date" class="form-input" id="edit-tgl-expiry-susu" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Liter <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-harga-susu" placeholder="Rp 15.000" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Liter) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="edit-stok-susu" placeholder="Contoh: 200" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="edit-file-susu" class="file-input" hidden accept="image/*" onchange="previewEditImage(event, 'susu')">
                            <div class="upload-box" onclick="document.getElementById('edit-file-susu').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="edit-preview-susu" style="display: none;">
                                <img id="edit-img-susu" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeEditImage('susu')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectEditStatus(this, 'susu')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectEditStatus(this, 'susu')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-status-susu" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PREVIEW MODAL -->
    <div class="modal-overlay" id="previewModal">
        <div class="preview-modal">
            <button class="modal-close" onclick="closePreviewModal()">&times;</button>
            <div class="preview-container" id="previewContainer"></div>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH PRODUK ================= -->
    <div class="modal-overlay" id="addProductModal">
        <div class="edit-modal" style="max-width: 600px;">
            <button class="modal-close" onclick="closeAddModal()">&times;</button>
            <div class="header">
                <div>
                    <h2>Tambah Produk Baru</h2>
                    <p>Isi form di bawah untuk menambahkan produk</p>
                </div>
            </div>
            <div class="tabs">
                <div class="tab active" data-tab="hewan" onclick="switchAddTab('hewan')"> Hewan</div>
                <div class="tab" data-tab="susu" onclick="switchAddTab('susu')"> Susu</div>
                <div class="tab" data-tab="rumput" onclick="switchAddTab('rumput')"> Rumput</div>
            </div>
            <div class="form-content">
                
                <!-- ===== FORM HEWAN ===== -->
                <form id="add-form-hewan" class="form-section active" onsubmit="handleAddSubmit(event, 'hewan')">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Hewan <span class="required">*</span></label>
                            <select class="form-select" id="add-jenis-hewan" required>
                                <option value="">Pilih jenis hewan</option>
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                                <option value="kerbau">Kerbau</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-nama-hewan" placeholder="Contoh: Sapi Perah FH" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" class="form-input" id="add-berat-hewan" placeholder="Contoh: 450">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-hewan" placeholder="Rp 20.000.000" required oninput="formatCurrencyInput(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah/Stok <span class="required">*</span></label>
                        <input type="number" class="form-input" id="add-stok-hewan" placeholder="Contoh: 4" min="1" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Hewan</label>
                        <div class="upload-wrapper">
                            <input type="file" id="add-file-hewan" class="file-input" hidden accept="image/*" onchange="previewAddImage(event, 'hewan')">
                            <div class="upload-box" onclick="document.getElementById('add-file-hewan').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="add-preview-hewan" style="display: none;">
                                <img id="add-img-hewan" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeAddImage('hewan')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectAddStatus(this, 'hewan')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectAddStatus(this, 'hewan')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="add-status-hewan" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Batal</button>
                        <button type="submit" class="btn btn-save"> Simpan Data</button>
                    </div>
                </form>

                <!-- ===== FORM RUMPUT ===== -->
                <form id="add-form-rumput" class="form-section" onsubmit="handleAddSubmit(event, 'rumput')">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Rumput <span class="required">*</span></label>
                            <select class="form-select" id="add-jenis-rumput" required>
                                <option value="">Pilih jenis rumput</option>
                                <option value="odot">Rumput Odot</option>
                                <option value="gajah">Rumput Gajah</option>
                                <option value="pakan">Rumput Pakan</option>
                                <option value="lapangan">Rumput Lapangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-nama-rumput" placeholder="Contoh: Rumput Odot Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Kg <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-rumput" placeholder="Rp 2.500" required oninput="formatCurrencyInput(this)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Kg) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="add-stok-rumput" placeholder="Contoh: 500" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="add-file-rumput" class="file-input" hidden accept="image/*" onchange="previewAddImage(event, 'rumput')">
                            <div class="upload-box" onclick="document.getElementById('add-file-rumput').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="add-preview-rumput" style="display: none;">
                                <img id="add-img-rumput" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeAddImage('rumput')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectAddStatus(this, 'rumput')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectAddStatus(this, 'rumput')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="add-status-rumput" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Batal</button>
                        <button type="submit" class="btn btn-save"> Simpan Data</button>
                    </div>
                </form>

                <!-- ===== FORM SUSU ===== -->
                <form id="add-form-susu" class="form-section" onsubmit="handleAddSubmit(event, 'susu')">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Susu <span class="required">*</span></label>
                            <select class="form-select" id="add-jenis-susu" required>
                                <option value="">Pilih jenis susu</option>
                                <option value="segar">Susu Segar</option>
                                <option value="pasteurisasi">Susu Pasteurisasi</option>
                                <option value="uht">Susu UHT</option>
                                <option value="fermentasi">Susu Fermentasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-nama-susu" placeholder="Contoh: Susu Segar Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Produksi <span class="required">*</span></label>
                            <input type="date" class="form-input" id="add-tgl-produksi-susu" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluwarsa <span class="required">*</span></label>
                            <input type="date" class="form-input" id="add-tgl-expiry-susu" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Liter <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-susu" placeholder="Rp 15.000" required oninput="formatCurrencyInput(this)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Liter) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="add-stok-susu" placeholder="Contoh: 200" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="add-file-susu" class="file-input" hidden accept="image/*" onchange="previewAddImage(event, 'susu')">
                            <div class="upload-box" onclick="document.getElementById('add-file-susu').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="add-preview-susu" style="display: none;">
                                <img id="add-img-susu" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeAddImage('susu')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectAddStatus(this, 'susu')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectAddStatus(this, 'susu')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="add-status-susu" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Batal</button>
                        <button type="submit" class="btn btn-save"> Simpan Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>
</div>
<script src="../../public/js/manajemenProduk_admin.js"></script>
</body>
</html>