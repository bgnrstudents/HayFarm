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
    <div class="topbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Pencarian" id="globalSearch">
        </div>
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

<script>
// ==================== UTILITIES ====================
const dateEl = document.getElementById('currentDate');
const now = new Date();
dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function generateId() { return '0000' + (Math.floor(Math.random() * 9000) + 1000); }
function formatRupiah(angka) { return 'Rp ' + parseInt(angka).toLocaleString('id-ID'); }
function formatDate(dateString) { if (!dateString) return '-'; return new Date(dateString).toLocaleDateString('id-ID'); }
function getSatuan(jenis) { const s = { 'hewan': 'Ekor', 'susu': 'Liter', 'rumput': 'Kg' }; return s[jenis?.toLowerCase()] || ''; }
function capitalizeFirst(str) { if (!str) return '-'; return str.charAt(0).toUpperCase() + str.slice(1); }

// ==================== LOCAL STORAGE ====================
const STORAGE_KEY = 'hayfarm_products';
function getProducts() { const d = localStorage.getItem(STORAGE_KEY); return d ? JSON.parse(d) : []; }
function saveProducts(p) { localStorage.setItem(STORAGE_KEY, JSON.stringify(p)); }

function addProduct(product) {
    const products = getProducts();
    product.id = generateId();
    product.tanggal = product.tanggal || product.tanggal_produksi || new Date().toISOString().split('T')[0];
    products.unshift(product);
    saveProducts(products);
    return product;
}

function updateProduct(id, updatedData) {
    let products = getProducts();
    const idx = products.findIndex(p => p.id === id);
    if (idx !== -1) { products[idx] = { ...products[idx], ...updatedData }; saveProducts(products); return true; }
    return false;
}

function deleteProduct(id) {
    let products = getProducts();
    products = products.filter(p => p.id !== id);
    saveProducts(products);
}

// ==================== RENDER TABLE ====================
function renderTable(products, searchQuery = '') {
    const tbody = document.getElementById('productTableBody');
    const emptyState = document.getElementById('emptyState');
    const table = document.querySelector('.product-table');
    tbody.innerHTML = '';
    
    let filtered = products;
    if (searchQuery) {
        const q = searchQuery.toLowerCase();
        filtered = products.filter(p => p.nama?.toLowerCase().includes(q) || p.jenis?.toLowerCase().includes(q));
    }
    
    if (filtered.length === 0) { emptyState.style.display = 'block'; table.style.display = 'none'; return; }
    emptyState.style.display = 'none'; table.style.display = 'table';
    
    filtered.forEach(product => {
        const row = document.createElement('tr');
        const statusClass = product.status === 'tersedia' ? 'status-tersedia' : 'status-tidak-tersedia';
        const statusText = product.status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia';
        const jenisDisplay = product.jenis ? capitalizeFirst(product.jenis) : '-';
        row.innerHTML = `
            <td>${product.id}</td><td>${jenisDisplay}</td><td>${product.nama || '-'}</td>
            <td>${formatDate(product.tanggal)}</td><td>${formatRupiah(product.harga)}</td>
            <td>${product.stok} ${getSatuan(product.jenis)}</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td><div class="action-buttons">
                <button class="action-btn view" onclick="openPreviewModal('${product.id}')"><i class="fa-solid fa-eye"></i></button>
                <button class="action-btn edit" onclick="openEditModal('${product.id}')"><i class="fa-solid fa-pen"></i></button>
                <button class="action-btn delete" onclick="handleDelete('${product.id}')"><i class="fa-solid fa-trash"></i></button>
            </div></td>`;
        tbody.appendChild(row);
    });
}

function updateStats() {
    const p = getProducts();
    document.getElementById('totalProduk').textContent = p.length;
    document.getElementById('totalRumput').textContent = p.filter(x => x.jenis === 'rumput').length;
    document.getElementById('totalSusu').textContent = p.filter(x => x.jenis === 'susu').length;
    document.getElementById('totalHewan').textContent = p.filter(x => x.jenis === 'hewan').length;
}

function handleDelete(id) {
    if (confirm('Yakin ingin menghapus produk ini?')) {
        deleteProduct(id); renderTable(getProducts()); updateStats();
        showToast('Produk berhasil dihapus', 'success');
    }
}

// ==================== SEARCH & FILTER ====================
document.getElementById('tableSearch').addEventListener('input', e => renderTable(getProducts(), e.target.value));
document.getElementById('globalSearch').addEventListener('input', e => { document.getElementById('tableSearch').value = e.target.value; renderTable(getProducts(), e.target.value); });

function openFilterModal() { document.getElementById('filterModal').classList.add('active'); }
function closeFilterModal() { document.getElementById('filterModal').classList.remove('active'); }
function applyFilter() {
    const jenis = document.getElementById('filterJenis').value, status = document.getElementById('filterStatus').value;
    let products = getProducts();
    if (jenis) products = products.filter(p => p.jenis?.toLowerCase() === jenis.toLowerCase());
    if (status) products = products.filter(p => p.status === (status === 'Tersedia' ? 'tersedia' : 'tidak-tersedia'));
    renderTable(products, document.getElementById('tableSearch').value); closeFilterModal();
}
function resetFilter() { document.getElementById('filterJenis').value = ''; document.getElementById('filterStatus').value = ''; renderTable(getProducts(), document.getElementById('tableSearch').value); closeFilterModal(); }
document.querySelector('.btn-filter').addEventListener('click', openFilterModal);
document.getElementById('filterModal').addEventListener('click', e => { if (e.target.id === 'filterModal') closeFilterModal(); });

// ==================== EXPORT CSV ====================
function exportTableToCSV(filename) {
    const products = getProducts();
    if (products.length === 0) { showToast('Tidak ada data untuk diexport', 'error'); return; }
    let csv = ['NO,Jenis Produk,Nama Produk,Tanggal,Harga,Stok,Satuan,Status'];
    products.forEach(p => {
        csv.push([p.id, capitalizeFirst(p.jenis), `"${p.nama || ''}"`, formatDate(p.tanggal), p.harga, p.stok, getSatuan(p.jenis), p.status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia'].join(','));
    });
    const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a'); link.href = URL.createObjectURL(blob); link.download = filename; link.click();
    showToast('Data berhasil diexport!', 'success');
}

// ==================== EDIT MODAL ====================
function openEditModal(productId) {
    const products = getProducts(), product = products.find(p => p.id === productId);
    if (!product) { showToast('Produk tidak ditemukan', 'error'); return; }
    document.querySelectorAll('.edit-modal .form-section').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('.edit-modal .tab').forEach(t => t.classList.remove('active'));
    const jenis = product.jenis?.toLowerCase(); switchEditTab(jenis);
    
    if (jenis === 'hewan') {
        document.getElementById('edit-id-hewan').value = product.id;
        document.getElementById('edit-jenis-hewan').value = product.jenis_detail || '';
        document.getElementById('edit-nama-hewan').value = product.nama || '';
        document.getElementById('edit-berat-hewan').value = product.berat || '';
        document.getElementById('edit-harga-hewan').value = product.harga ? formatRupiah(product.harga).replace(/[^0-9,]/g, '').replace(',', '.') : '';
        document.getElementById('edit-stok-hewan').value = product.stok || '';
        document.getElementById('edit-status-hewan').value = product.status || 'tersedia';
        const opts = document.querySelectorAll('#edit-form-hewan .status-option');
        opts.forEach(o => o.classList.remove('active'));
        (product.status === 'tersedia' ? opts[0] : opts[1]).classList.add('active');
        if (product.foto) { document.getElementById('edit-img-hewan').src = product.foto; document.getElementById('edit-preview-hewan').style.display = 'flex'; document.querySelector('#edit-form-hewan .upload-box').style.display = 'none'; }
        else removeEditImage('hewan');
    } else if (jenis === 'rumput') {
        document.getElementById('edit-id-rumput').value = product.id;
        document.getElementById('edit-jenis-rumput').value = product.jenis_detail || '';
        document.getElementById('edit-nama-rumput').value = product.nama || '';
        document.getElementById('edit-harga-rumput').value = product.harga ? formatRupiah(product.harga).replace(/[^0-9,]/g, '').replace(',', '.') : '';
        document.getElementById('edit-stok-rumput').value = product.stok || '';
        document.getElementById('edit-status-rumput').value = product.status || 'tersedia';
        const opts = document.querySelectorAll('#edit-form-rumput .status-option');
        opts.forEach(o => o.classList.remove('active'));
        (product.status === 'tersedia' ? opts[0] : opts[1]).classList.add('active');
        if (product.foto) { document.getElementById('edit-img-rumput').src = product.foto; document.getElementById('edit-preview-rumput').style.display = 'flex'; document.querySelector('#edit-form-rumput .upload-box').style.display = 'none'; }
        else removeEditImage('rumput');
    } else if (jenis === 'susu') {
        document.getElementById('edit-id-susu').value = product.id;
        document.getElementById('edit-jenis-susu').value = product.jenis_detail || '';
        document.getElementById('edit-nama-susu').value = product.nama || '';
        document.getElementById('edit-tgl-produksi-susu').value = product.tanggal_produksi || '';
        document.getElementById('edit-tgl-expiry-susu').value = product.tanggal_expiry || '';
        document.getElementById('edit-harga-susu').value = product.harga ? formatRupiah(product.harga).replace(/[^0-9,]/g, '').replace(',', '.') : '';
        document.getElementById('edit-stok-susu').value = product.stok || '';
        document.getElementById('edit-status-susu').value = product.status || 'tersedia';
        const opts = document.querySelectorAll('#edit-form-susu .status-option');
        opts.forEach(o => o.classList.remove('active'));
        (product.status === 'tersedia' ? opts[0] : opts[1]).classList.add('active');
        if (product.foto) { document.getElementById('edit-img-susu').src = product.foto; document.getElementById('edit-preview-susu').style.display = 'flex'; document.querySelector('#edit-form-susu .upload-box').style.display = 'none'; }
        else removeEditImage('susu');
    }
    document.getElementById('editModal').classList.add('active');
}
function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }
function switchEditTab(tab) {
    document.querySelectorAll('.edit-modal .tab').forEach(t => { t.classList.remove('active'); if(t.dataset.tab===tab) t.classList.add('active'); });
    document.querySelectorAll('.edit-modal .form-section').forEach(f => f.classList.remove('active'));
    document.getElementById(`edit-form-${tab}`).classList.add('active');
}
function previewEditImage(e, type) {
    const file = e.target.files[0]; if(!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById(`edit-img-${type}`).src = ev.target.result;
        document.getElementById(`edit-preview-${type}`).style.display = 'flex';
        document.querySelector(`#edit-form-${type} .upload-box`).style.display = 'none';
    }; reader.readAsDataURL(file);
}
function removeEditImage(type) {
    document.getElementById(`edit-file-${type}`).value = '';
    document.getElementById(`edit-preview-${type}`).style.display = 'none';
    document.querySelector(`#edit-form-${type} .upload-box`).style.display = 'flex';
}
function selectEditStatus(el, type) {
    el.parentElement.querySelectorAll('.status-option').forEach(o => o.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(`edit-status-${type}`).value = el.classList.contains('available') ? 'tersedia' : 'tidak_tersedia';
}
function handleEditSubmit(e, type) {
    e.preventDefault();
    const id = document.getElementById(`edit-id-${type}`).value, status = document.getElementById(`edit-status-${type}`).value;
    const data = { status, updated_at: new Date().toISOString() };
    if(type==='hewan') {
        data.jenis_detail = document.getElementById('edit-jenis-hewan').value;
        data.nama = document.getElementById('edit-nama-hewan').value;
        data.berat = document.getElementById('edit-berat-hewan').value;
        data.harga = parseInt(document.getElementById('edit-harga-hewan').value.replace(/\D/g,''))||0;
        data.stok = parseInt(document.getElementById('edit-stok-hewan').value)||0;
        const img = document.getElementById('edit-img-hewan').src; if(img && !img.includes('placeholder')) data.foto = img;
    } else if(type==='rumput') {
        data.jenis_detail = document.getElementById('edit-jenis-rumput').value;
        data.nama = document.getElementById('edit-nama-rumput').value;
        data.harga = parseInt(document.getElementById('edit-harga-rumput').value.replace(/\D/g,''))||0;
        data.stok = parseInt(document.getElementById('edit-stok-rumput').value)||0;
        const img = document.getElementById('edit-img-rumput').src; if(img && !img.includes('placeholder')) data.foto = img;
    } else if(type==='susu') {
        data.jenis_detail = document.getElementById('edit-jenis-susu').value;
        data.nama = document.getElementById('edit-nama-susu').value;
        data.tanggal_produksi = document.getElementById('edit-tgl-produksi-susu').value;
        data.tanggal_expiry = document.getElementById('edit-tgl-expiry-susu').value;
        data.harga = parseInt(document.getElementById('edit-harga-susu').value.replace(/\D/g,''))||0;
        data.stok = parseInt(document.getElementById('edit-stok-susu').value)||0;
        const img = document.getElementById('edit-img-susu').src; if(img && !img.includes('placeholder')) data.foto = img;
    }
    if(updateProduct(id, data)) { renderTable(getProducts()); updateStats(); closeEditModal(); showToast('Produk berhasil diperbarui!', 'success'); }
    else showToast('Gagal memperbarui produk', 'error');
}
document.getElementById('editModal').addEventListener('click', e => { if(e.target.id==='editModal') closeEditModal(); });

// ==================== PREVIEW MODAL ====================
function openPreviewModal(productId) {
    const products = getProducts(), product = products.find(p => p.id === productId);
    if(!product) { showToast('Produk tidak ditemukan', 'error'); return; }
    const container = document.getElementById('previewContainer'), jenis = product.jenis?.toLowerCase();
    const statusClass = product.status === 'tersedia' ? 'status-available' : 'status-unavailable';
    const statusText = product.status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia';
    const dotColor = product.status === 'tersedia' ? '#175D2B' : '#f44336';
    let html = '';
    const img = product.foto ? `<img src="${product.foto}" alt="${product.nama}">` : '<span class="no-image"><i class="fa-solid fa-image"></i></span>';
    if(jenis==='rumput') {
        html = `<div class="preview-card"><div class="card-header"><span class="header-title">Preview Produk</span><span class="header-id">ID: ${product.id}</span></div>
        <p class="category">${capitalizeFirst(product.jenis)}</p><div class="card-image">${img}</div><h3 class="detail-title">DETAIL PRODUK RUMPUT</h3>
        <div class="detail-grid">
            <div class="detail-item"><label>Kategori</label><p class="value">${capitalizeFirst(product.jenis)}</p></div>
            <div class="detail-item"><label>Nama Produk</label><p class="value">${product.nama||'-'}</p></div>
            <div class="detail-item"><label>Jenis</label><p class="value">${capitalizeFirst(product.jenis_detail)||'-'}</p></div>
            <div class="detail-item"><label>Harga</label><p class="value">${formatRupiah(product.harga)} / Kg</p></div>
            <div class="detail-item"><label>Stok</label><p class="value">${product.stok||0} Kg</p></div>
            <div class="detail-item"><label>Status</label><p class="value ${statusClass}"><span class="dot" style="background:${dotColor}"></span> ${statusText}</p></div>
        </div><button class="btn-close" onclick="closePreviewModal()">Tutup Preview</button></div>`;
    } else if(jenis==='hewan') {
        html = `<div class="preview-card"><div class="card-header"><span class="header-title">Preview Produk</span><span class="header-id">ID: ${product.id}</span></div>
        <p class="category">${capitalizeFirst(product.jenis)}</p><div class="card-image">${img}</div><h3 class="detail-title">DETAIL PRODUK HEWAN</h3>
        <div class="detail-grid">
            <div class="detail-item"><label>Kategori</label><p class="value">${capitalizeFirst(product.jenis)}</p></div>
            <div class="detail-item"><label>Nama Produk</label><p class="value">${product.nama||'-'}</p></div>
            <div class="detail-item"><label>Jenis Hewan</label><p class="value">${capitalizeFirst(product.jenis_detail)||'-'}</p></div>
            <div class="detail-item"><label>Berat</label><p class="value">${product.berat?product.berat+' Kg':'-'}</p></div>
            <div class="detail-item"><label>Harga</label><p class="value">${formatRupiah(product.harga)}</p></div>
            <div class="detail-item"><label>Jumlah</label><p class="value">${product.stok||0} Ekor</p></div>
            <div class="detail-item"><label>Status</label><p class="value ${statusClass}"><span class="dot" style="background:${dotColor}"></span> ${statusText}</p></div>
        </div><button class="btn-close" onclick="closePreviewModal()">Tutup Preview</button></div>`;
    } else if(jenis==='susu') {
        html = `<div class="preview-card"><div class="card-header"><span class="header-title">Preview Produk</span><span class="header-id">ID: ${product.id}</span></div>
        <p class="category">${capitalizeFirst(product.jenis)}</p><div class="card-image">${img}</div><h3 class="detail-title">DETAIL PRODUK SUSU</h3>
        <div class="detail-grid">
            <div class="detail-item"><label>Kategori</label><p class="value">${capitalizeFirst(product.jenis)}</p></div>
            <div class="detail-item"><label>Nama Produk</label><p class="value">${product.nama||'-'}</p></div>
            <div class="detail-item"><label>Jenis Susu</label><p class="value">${capitalizeFirst(product.jenis_detail)||'-'}</p></div>
            <div class="detail-item"><label>Tgl. Produksi</label><p class="value">${formatDate(product.tanggal_produksi)}</p></div>
            <div class="detail-item"><label>Tgl. Kadaluarsa</label><p class="value">${formatDate(product.tanggal_expiry)}</p></div>
            <div class="detail-item"><label>Harga</label><p class="value">${formatRupiah(product.harga)} / Liter</p></div>
            <div class="detail-item"><label>Stok</label><p class="value">${product.stok||0} Liter</p></div>
            <div class="detail-item"><label>Status</label><p class="value ${statusClass}"><span class="dot" style="background:${dotColor}"></span> ${statusText}</p></div>
        </div><button class="btn-close" onclick="closePreviewModal()">Tutup Preview</button></div>`;
    }
    container.innerHTML = html; document.getElementById('previewModal').classList.add('active');
}
function closePreviewModal() { document.getElementById('previewModal').classList.remove('active'); }
document.getElementById('previewModal').addEventListener('click', e => { if(e.target.id==='previewModal') closePreviewModal(); });

// ==================== ADD MODAL ====================
function openAddModal() {
    document.querySelectorAll('#addProductModal .form-section').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('#addProductModal .tab').forEach(t => t.classList.remove('active'));
    switchAddTab('hewan'); resetAddForm('hewan'); resetAddForm('susu'); resetAddForm('rumput');
    document.getElementById('addProductModal').classList.add('active');
}
function closeAddModal() { document.getElementById('addProductModal').classList.remove('active'); }
function switchAddTab(tab) {
    document.querySelectorAll('#addProductModal .tab').forEach(t => { t.classList.remove('active'); if(t.dataset.tab===tab) t.classList.add('active'); });
    document.querySelectorAll('#addProductModal .form-section').forEach(f => f.classList.remove('active'));
    document.getElementById(`add-form-${tab}`).classList.add('active');
}
function resetAddForm(type) {
    document.getElementById(`add-form-${type}`).reset();
    const opts = document.querySelectorAll(`#add-form-${type} .status-option`);
    opts.forEach(o => o.classList.remove('active')); opts[0].classList.add('active');
    document.getElementById(`add-status-${type}`).value = 'tersedia'; removeAddImage(type);
}
function previewAddImage(e, type) {
    const file = e.target.files[0]; if(!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById(`add-img-${type}`).src = ev.target.result;
        document.getElementById(`add-preview-${type}`).style.display = 'flex';
        document.querySelector(`#add-form-${type} .upload-box`).style.display = 'none';
    }; reader.readAsDataURL(file);
}
function removeAddImage(type) {
    document.getElementById(`add-file-${type}`).value = '';
    document.getElementById(`add-preview-${type}`).style.display = 'none';
    document.querySelector(`#add-form-${type} .upload-box`).style.display = 'flex';
}
function selectAddStatus(el, type) {
    el.parentElement.querySelectorAll('.status-option').forEach(o => o.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(`add-status-${type}`).value = el.classList.contains('available') ? 'tersedia' : 'tidak_tersedia';
}
function formatCurrencyInput(input) {
    let v = input.value.replace(/[^0-9]/g,'');
    if(v) { input.dataset.raw = v; input.value = 'Rp ' + parseInt(v).toLocaleString('id-ID'); }
}
function getRawCurrency(input) { return input.dataset.raw || input.value.replace(/[^0-9]/g,''); }

function handleAddSubmit(e, type) {
    e.preventDefault();
    const labels = { 'hewan':'Hewan', 'susu':'Susu', 'rumput':'Rumput' };
    let product = { jenis: type };
    
    if(type==='hewan') {
        const nama = document.getElementById('add-nama-hewan').value.trim(), hargaRaw = getRawCurrency(document.getElementById('add-harga-hewan')), stok = document.getElementById('add-stok-hewan').value, status = document.getElementById('add-status-hewan').value;
        if(!nama || !hargaRaw || !stok) { showToast('Mohon lengkapi semua field yang wajib diisi!', 'error'); return; }
        product.nama = nama; product.harga = parseInt(hargaRaw); product.stok = parseInt(stok); product.status = status;
        product.jenis_detail = document.getElementById('add-jenis-hewan').value;
        product.berat = document.getElementById('add-berat-hewan').value || null;
    } else if(type==='susu') {
        const nama = document.getElementById('add-nama-susu').value.trim(), tglProd = document.getElementById('add-tgl-produksi-susu').value, tglExp = document.getElementById('add-tgl-expiry-susu').value, hargaRaw = getRawCurrency(document.getElementById('add-harga-susu')), stok = document.getElementById('add-stok-susu').value, status = document.getElementById('add-status-susu').value;
        if(!nama || !tglProd || !hargaRaw || !stok) { showToast('Mohon lengkapi semua field yang wajib diisi!', 'error'); return; }
        product.nama = nama; product.tanggal_produksi = tglProd; product.tanggal_expiry = tglExp; product.tanggal = tglProd;
        product.harga = parseInt(hargaRaw); product.stok = parseInt(stok); product.status = status;
        product.jenis_detail = document.getElementById('add-jenis-susu').value;
    } else if(type==='rumput') {
        const nama = document.getElementById('add-nama-rumput').value.trim(), hargaRaw = getRawCurrency(document.getElementById('add-harga-rumput')), stok = document.getElementById('add-stok-rumput').value, status = document.getElementById('add-status-rumput').value;
        if(!nama || !hargaRaw || !stok) { showToast('Mohon lengkapi semua field yang wajib diisi!', 'error'); return; }
        product.nama = nama; product.harga = parseInt(hargaRaw); product.stok = parseInt(stok); product.status = status;
        product.jenis_detail = document.getElementById('add-jenis-rumput').value;
        product.tanggal = new Date().toISOString().split('T')[0];
    }
    
    const img = document.getElementById(`add-img-${type}`).src;
    if(img && !img.includes('placeholder') && img.startsWith('data:')) product.foto = img;
    
    addProduct(product); renderTable(getProducts()); updateStats(); closeAddModal();
    showToast(`Produk ${labels[type]} berhasil ditambahkan!`, 'success');
}
document.getElementById('addProductModal')?.addEventListener('click', e => { if(e.target.id==='addProductModal') closeAddModal(); });

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', () => {
    updateStats(); renderTable(getProducts());
    const last = sessionStorage.getItem('productCount'), curr = getProducts().length;
    if(last && curr > parseInt(last)) showToast('Produk baru berhasil ditambahkan!', 'success');
    sessionStorage.setItem('productCount', curr);
    window.addEventListener('storage', e => { if(e.key===STORAGE_KEY) { updateStats(); renderTable(getProducts()); }});
    document.querySelectorAll('input[placeholder="Rp 0"]').forEach(inp => {
        inp.addEventListener('blur', function() { if(this.value && !this.value.startsWith('Rp')) formatCurrencyInput(this); });
    });
});
</script>
</body>
</html>