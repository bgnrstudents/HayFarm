<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit;
}
require_once '../../config/database.php';
require_once '../../process/models/produk.php';

$database = new Database();
$db = $database->getConnection();
$produk = new Produk($db);
$status_filter = $_GET['status_filter'] ?? null;

$status_filter = $_GET['status_filter'] ?? null;
$jenis_filter = $_GET['jenis_filter'] ?? null; // ✅ TAMBAH INI

$dataRaw = $produk->getAll($status_filter, $jenis_filter);

require_once '../../process/models/hewan.php';
$hewanModel = new Hewan($db);
$listHewan = $hewanModel->getAvailableForProduct();

function normalizeAdminProductImage(?string $foto): string
{
    $foto = trim((string) $foto);
    if ($foto === '') {
        return '';
    }

    $normalized = preg_replace('#^(\.\./|./)+#', '', str_replace('\\', '/', $foto)) ?? $foto;
    $normalized = ltrim($normalized, '/');

    if (str_starts_with($normalized, 'public/') || str_starts_with($normalized, 'uploads/')) {
        return '../../' . $normalized;
    }

    return '../../uploads/hewan/' . basename($normalized);
}

$produkData = array_map(function ($row) {
    // Determine production date for milk based on expiry (-7 days)
    $date = '';
    if ($row['jenis_produk'] === 'susu' && !empty($row['tgl_kadaluarsa'])) {
        $date = date('Y-m-d', strtotime($row['tgl_kadaluarsa'] . ' -7 days'));
    }

    $image = '';
    if ($row['jenis_produk'] === 'hewan' && !empty($row['foto_hewan'])) {
        $image = normalizeAdminProductImage($row['foto_hewan']);
    }

    return [
        'id' => (int)$row['id_produk'],
        'idHewan'    => (int)($row['id_hewan'] ?? 0),
        'kodeHewan'  => $row['kode_hewan'] ?? '',
        'jenisHewan' => $row['jenis_hewan'] ?? '',
        'statusHewan' => $row['status_hewan'] ?? '',
        'noKandang'  => $row['no_kandang'] ?? '-',
        'tglLahir'   => $row['tgl_lahir'] ?? '',
        'type' => ucwords($row['jenis_produk']),
        'name' => $row['nama_produk'],
        'date' => $date,
        'expiryDate' => $row['tgl_kadaluarsa'] == '2099-12-31' ? '' : $row['tgl_kadaluarsa'],
        'price' => 'Rp ' . number_format($row['harga'], 0, ',', '.'),
        'stock' => $row['stok'] . ' ' . ucwords($row['satuan']),
        'status' => $row['status_produk'] === 'blm_terjual' ? 'Tersedia' : 'Tidak Tersedia',
        'image' => $image
    ];
}, $dataRaw);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manajemen Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_manajemenProduk.css?v=3">


</head>

<body>
    <?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

    <!-- MAIN -->
    <div class="main-content">

        <!-- TOPBAR -->
        <?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

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
                    <button class="btn-export" onclick="exportLaporanProduk('produk_data.csv')"><i class="fa-solid fa-download"></i> Export</button>
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
                        <th>Tanggal Produksi</th>
                        <th>Tanggal Kadaluwarsa</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>
            <div class="pagination">
                <span id="productPaginationInfo">Menampilkan 1-0 dari 0 data</span>
                <div>
                    <button class="page-btn" type="button" onclick="changeProductPage(-1)">Sebelumnya</button>
                    <button class="page-btn active-page">1</button>
                    <button class="page-btn" type="button" onclick="changeProductPage(1)">Selanjutnya</button>
                </div>
            </div>

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
                        <input type="hidden" id="edit-produk-id-hewan">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Jenis Produk</label>
                                <input type="text" class="form-input" value="Hewan" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pilih Hewan <span class="required">*</span></label>
                                <select class="form-select" id="edit-id-hewan" onchange="var parts = this.options[this.selectedIndex].text.split(' - '); document.getElementById('edit-nama-hewan').value = parts.length > 1 ? parts[1] : '';" required>
                                    <option value="">Pilih Hewan</option>
                                    <?php foreach ($listHewan as $h): ?>    
                                        <option value="<?= (int)$h['id_hewan'] ?>">
                                            <?= htmlspecialchars($h['kode_hewan']) ?> - <?= ucwords(str_replace('_', ' ', $h['jenis_hewan'])) ?>
                                        </option>
                                        <?php endforeach; ?>f
                                </select>
                                <input type="hidden" id="edit-nama-hewan">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Berat Badan (kg)</label>
                                <input type="number" class="form-input" id="edit-berat-hewan" placeholder="Contoh: 450">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga <span class="required">*</span></label>
                                <input type="text" class="form-input" id="edit-harga-hewan" placeholder="Rp 20.000.000" required oninput="formatCurrencyInput(this)">
                            </div>
                        </div>
                        <input type="hidden" id="edit-stok-hewan" value="1">
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
                                <label class="form-label">Jenis Produk</label>
                                <input type="text" class="form-input" value="Rumput" readonly>
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
            <div class="preview-card">
                <div class="preview-header">
                    <div>
                        <h2 id="previewTitle">Preview Produk</h2>
                        <p id="previewSubtitle">Detail informasi produk</p>
                    </div>
                    <div class="preview-id-badge" id="previewBadge">-</div>
                </div>

                <div class="preview-section-title">Informasi Utama</div>

                <div class="preview-info-grid">
                    <!-- Nama Produk (Semua tipe) -->
                    <div class="preview-info-box">
                        <span class="preview-label">Nama Produk</span>
                        <span class="preview-value" id="previewProductName">-</span>
                    </div>

                    <!-- Jenis Produk (Semua tipe) -->
                    <div class="preview-info-box">
                        <span class="preview-label">Jenis Produk</span>
                        <span class="preview-value" id="previewProductType">-</span>
                    </div>

                    <!-- KHUSUS HEWAN -->
                    <div class="preview-info-box preview-hewan-only" style="display: none;">
                        <span class="preview-label">ID Hewan</span>
                        <span class="preview-value" id="previewHewanId">-</span>
                    </div>
                    <div class="preview-info-box preview-hewan-only" style="display: none;">
                        <span class="preview-label">No. Kandang</span>
                        <span class="preview-value" id="previewNoKandang">-</span>
                    </div>
                    <div class="preview-info-box preview-hewan-only" style="display: none;">
                        <span class="preview-label">Tanggal Lahir</span>
                        <span class="preview-value" id="previewTglLahir">-</span>
                    </div>

                    <!-- KHUSUS SUSU -->
                    <div class="preview-info-box preview-susu-only" style="display: none;">
                        <span class="preview-label">Tanggal Produksi</span>
                        <span class="preview-value" id="previewProdDate">-</span>
                    </div>
                    <div class="preview-info-box preview-susu-only" style="display: none;">
                        <span class="preview-label">Tanggal Kadaluarsa</span>
                        <span class="preview-value" id="previewExpDate">-</span>
                    </div>

                    <!-- KHUSUS RUMPUT (Deskripsi full width) -->
                    <div class="preview-info-box preview-rumput-only full-width" style="display: none;">
                        <span class="preview-label">Deskripsi</span>
                        <p class="preview-value" id="previewDeskripsi" style="font-size:14px; line-height:1.6; color:#555;">-</p>
                    </div>

                    <!-- UMUM (Semua tipe) -->
                    <div class="preview-info-box">
                        <span class="preview-label">Harga</span>
                        <span class="preview-value" id="previewProductPrice">-</span>
                    </div>
                    <div class="preview-info-box">
                        <span class="preview-label">Stok</span>
                        <span class="preview-value" id="previewProductStock">-</span>
                    </div>
                    <div class="preview-info-box">
                        <span class="preview-label">Status</span>
                        <div class="preview-status-pill" id="previewStatusWrap">
                            <div class="preview-dot"></div>
                            <span id="previewProductStatus">-</span>
                        </div>
                    </div>
                </div>

                <button class="preview-btn-confirm" type="button" onclick="closePreviewModal()">Tutup</button>
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
                    <div class="tab active" data-tab="hewan" onclick="switchAddTab('hewan')">Hewan</div>
                    <div class="tab" data-tab="rumput" onclick="switchAddTab('rumput')">Rumput</div>
                    <div class="tab" data-tab="susu" onclick="switchAddTab('susu')">Susu</div>
                </div>
                <div class="form-content">

                    <!-- ===== FORM HEWAN ===== -->
                    <form id="add-form-hewan" class="form-section active" onsubmit="handleAddSubmit(event, 'hewan')">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Jenis Produk</label>
                                <input type="text" class="form-input" value="Hewan" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pilih Hewan <span class="required">*</span></label>
                                <select class="form-select" id="add-id-hewan" onchange="var parts = this.options[this.selectedIndex].text.split(' - '); document.getElementById('add-nama-hewan').value = parts.length > 1 ? parts[1] : '';" required>
                                    <option value="">Pilih Hewan</option>
                                    <?php foreach ($listHewan as $h): ?>
                                        <option value="<?= $h['id_hewan'] ?>"><?= htmlspecialchars($h['kode_hewan'] . ' - ' . ucwords(str_replace('_', ' ', $h['jenis_hewan']))) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" id="add-nama-hewan">
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
                        <input type="hidden" id="add-stok-hewan" value="1">
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
                                <label class="form-label">Jenis Produk</label>
                                <input type="text" class="form-input" value="Rumput" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Produk <span class="required">*</span></label>
                                <input type="text" class="form-input" id="add-nama-rumput" placeholder="Contoh: Rumput Odot Premium" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Harga per Ton <span class="required">*</span></label>
                                <input type="text" class="form-input" id="add-harga-rumput" placeholder="Rp 2.500" required oninput="formatCurrencyInput(this)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Stok (Ton) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="add-stok-rumput" placeholder="Contoh: 500" min="0" required>
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

        <div class="Delete-overlay" id="deleteProductOverlay" onclick="closeProductDeleteOutside(event)">
            <div class="Delete-box">
                <div class="Delete-header-custom">
                    <div class="icon-circle"><i class="fas fa-trash-alt"></i></div>
                    <h2 class="Delete-title-custom">Apakah Anda yakin?</h2>
                </div>
                <div class="Delete-body-custom">
                    <p>Anda akan menghapus: <strong id="deleteProductTarget">produk ini</strong></p>
                    <p class="warning-text">Tindakan ini tidak dapat dibatalkan. Data akan dihapus dari tabel produk.</p>
                </div>
                <div class="btn-group-custom">
                    <button type="button" class="btn-custom btn-cancel" onclick="closeProductDelete()">Batal</button>
                    <button type="button" class="btn-custom btn-delete" onclick="confirmProductDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.productData = <?= json_encode($produkData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <!-- Flash Message -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showFlashMessage('<?= addslashes($_SESSION['flash_message']) ?>', '<?= $_SESSION['flash_type'] ?? 'danger' ?>');
            });
            // Tambahkan fungsi ini di bagian atas file (setelah function closePreviewModal)
            function closePreviewOutside(event) {
                const previewModal = document.getElementById('previewModal');
                if (previewModal && event.target === previewModal) {
                    closePreviewModal();
                }
            }

            // Tambahkan event listener ini di dalam DOMContentLoaded (paling bawah)
            document.addEventListener('click', event => {
                if (event.target.classList.contains('modal-overlay')) {
                    event.target.classList.remove('active');
                    // document.body.style.overflow = 'auto';
                }
                // ✅ Tambahkan ini untuk handle preview modal
                closePreviewOutside(event);
            });
        </script>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    endif; ?>
    <script src="../../public/js/manajemenProduk_admin.js?v=9"></script>
</body>

</html>
