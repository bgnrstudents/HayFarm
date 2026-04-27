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

<!-- CSS untuk Preview Produk Card -->
<style>
  /* Preview Card Styles */
  .preview-card-container {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    padding: 20px;
    max-height: 80vh;
    overflow-y: auto;
  }

  .preview-card {
    background-color: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    width: 360px;
    min-width: 360px;
    flex-shrink: 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }

  .preview-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background-color: #ffffff;
    border-bottom: 1px solid #eee;
  }

  .preview-header-title {
    font-size: 16px;
    font-weight: 700;
    color: #333333;
  }

  .preview-header-id {
    background-color: #2a2a2a;
    color: #ffffff;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
  }

  .preview-category {
    padding: 0 20px 12px;
    color: #666666;
    font-size: 14px;
  }

  .preview-card-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
  }

  .preview-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .preview-detail-title {
    padding: 16px 20px 12px;
    font-size: 13px;
    font-weight: 700;
    color: #333333;
    letter-spacing: 0.5px;
  }

  .preview-detail-grid {
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px 20px;
  }

  .preview-detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .preview-detail-item label {
    font-size: 12px;
    color: #888888;
    font-weight: 500;
  }

  .preview-detail-item .value {
    font-size: 14px;
    font-weight: 700;
    color: #333333;
  }

  .preview-detail-item.empty {
    visibility: hidden;
  }

  .preview-status-available {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #2ecc40 !important;
    font-weight: 700 !important;
  }

  .preview-dot {
    width: 8px;
    height: 8px;
    background-color: #2ecc40;
    border-radius: 50%;
    display: inline-block;
  }

  .preview-btn-close {
    display: block;
    width: calc(100% - 40px);
    margin: 20px 20px 20px;
    padding: 14px;
    background-color: #2ecc40;
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .preview-btn-close:hover {
    background-color: #27ae35;
  }

  /* Responsive Preview */
  @media (max-width: 1150px) {
    .preview-card-container {
      flex-direction: column;
      align-items: center;
      gap: 25px;
    }
    .preview-card {
      width: 100%;
      max-width: 360px;
      min-width: auto;
    }
  }
</style>
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

    <!-- PREVIEW MODAL (UPDATED WITH PRODUCT CARDS) -->
    <div class="modal-overlay" id="previewModal">
        <div class="preview-modal" style="max-width: 1200px; width: 95%;">
            <button class="modal-close" onclick="closePreviewModal()">&times;</button>
            <div class="preview-card-container" id="previewContainer">
                
                <!-- CARD 1 - RUMPUT -->
                <div class="preview-card">
                    <div class="preview-card-header">
                        <span class="preview-header-title">Preview Produk</span>
                        <span class="preview-header-id">ID: S-R-001</span>
                    </div>
                    <p class="preview-category">Rumput</p>
                    <div class="preview-card-image">
                        <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600" alt="Rumput">
                    </div>
                    <h3 class="preview-detail-title">DETAIL PRODUK RUMPUT</h3>
                    <div class="preview-detail-grid">
                        <div class="preview-detail-item">
                            <label>Nama Produk</label>
                            <p class="value">Rumput</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Nama Produk</label>
                            <p class="value">Rumput Odot</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Tgl Produksi</label>
                            <p class="value">05 Maret 2026</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Harga</label>
                            <p class="value">Rp 2.500 / Kg</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Stok</label>
                            <p class="value">500 Kg</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Status</label>
                            <p class="value preview-status-available">
                                <span class="preview-dot"></span> Tersedia
                            </p>
                        </div>
                    </div>
                    <button class="preview-btn-close" onclick="closePreviewModal()">Tutup Preview</button>
                </div>

                <!-- CARD 2 - HEWAN -->
                <div class="preview-card">
                    <div class="preview-card-header">
                        <span class="preview-header-title">Preview Produk</span>
                        <span class="preview-header-id">ID: S-H-001</span>
                    </div>
                    <p class="preview-category">Hewan</p>
                    <div class="preview-card-image">
                        <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600" alt="Sapi Perah">
                    </div>
                    <h3 class="preview-detail-title">DETAIL PRODUK HEWAN</h3>
                    <div class="preview-detail-grid">
                        <div class="preview-detail-item">
                            <label>Kategori</label>
                            <p class="value">Hewan</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Nama Produk</label>
                            <p class="value">Sapi Perah</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Jumlah</label>
                            <p class="value">4 Ekor</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Harga</label>
                            <p class="value">Rp 20.000.000</p>
                        </div>
                        <div class="preview-detail-item empty"></div>
                        <div class="preview-detail-item">
                            <label>Status</label>
                            <p class="value preview-status-available">
                                <span class="preview-dot"></span> Tersedia
                            </p>
                        </div>
                    </div>
                    <button class="preview-btn-close" onclick="closePreviewModal()">Tutup Preview</button>
                </div>

                <!-- CARD 3 - SUSU -->
                <div class="preview-card">
                    <div class="preview-card-header">
                        <span class="preview-header-title">Preview Produk</span>
                        <span class="preview-header-id">ID: S-S-001</span>
                    </div>
                    <p class="preview-category">Produk Olahan</p>
                    <div class="preview-card-image">
                        <img src="https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600" alt="Susu Segar">
                    </div>
                    <h3 class="preview-detail-title">DETAIL PRODUK SUSU</h3>
                    <div class="preview-detail-grid">
                        <div class="preview-detail-item">
                            <label>Kategori</label>
                            <p class="value">Produk Olahan</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Nama Produk</label>
                            <p class="value">Susu Segar</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Tgl Produksi</label>
                            <p class="value">10 Maret 2026</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Harga</label>
                            <p class="value">Rp 15.000 / Liter</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Stok</label>
                            <p class="value">200 Liter</p>
                        </div>
                        <div class="preview-detail-item">
                            <label>Status</label>
                            <p class="value preview-status-available">
                                <span class="preview-dot"></span> Tersedia
                            </p>
                        </div>
                    </div>
                    <button class="preview-btn-close" onclick="closePreviewModal()">Tutup Preview</button>
                </div>

            </div>
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