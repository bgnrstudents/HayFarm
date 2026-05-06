<?php
require_once __DIR__ . '/../../config/database.php';

function redirectProduk(string $status): void
{
    header("Location: manajemen_produk.php?status=$status");
    exit;
}

function nilaiProdukPost(string $key): string
{
    return trim((string) ($_POST[$key] ?? ''));
}

function angkaProdukPost(string $key): int
{
    return (int) preg_replace('/\D/', '', nilaiProdukPost($key));
}

function statusProdukDb(string $jenis, string $status, string $tanggalKadaluarsa): string
{
    if ($jenis === 'susu' && $tanggalKadaluarsa !== '' && $tanggalKadaluarsa < date('Y-m-d')) {
        return 'terjual';
    }

    return $status === 'Tidak Tersedia' ? 'terjual' : 'blm_terjual';
}

function satuanProdukDb(string $jenis): string
{
    return [
        'hewan' => 'ekor',
        'susu' => 'liter',
        'rumput' => '',
    ][$jenis] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = nilaiProdukPost('aksi');
    $jenis = nilaiProdukPost('jenis_produk');
    $nama = nilaiProdukPost('nama_produk');
    $harga = angkaProdukPost('harga');
    $stok = angkaProdukPost('stok');
    $tanggalKadaluarsa = nilaiProdukPost('tgl_kadaluarsa');
    $status = statusProdukDb($jenis, nilaiProdukPost('status_produk'), $tanggalKadaluarsa);
    $satuan = satuanProdukDb($jenis);
    $deskripsi = '';

    if ($aksi === 'tambah') {
        if (!in_array($jenis, ['hewan', 'rumput', 'susu'], true) || $nama === '' || $harga <= 0) {
            redirectProduk('gagal');
        }

        if ($jenis !== 'susu') {
            $tanggalKadaluarsa = '0000-00-00';
        }

        $stmt = mysqli_prepare(
            $db,
            'INSERT INTO data_produk (id_hewan, jenis_produk, nama_produk, harga, stok, satuan, tgl_kadaluarsa, deskripsi, status_produk)
             VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        if (!$stmt) {
            redirectProduk('gagal');
        }
        mysqli_stmt_bind_param($stmt, 'ssdissss', $jenis, $nama, $harga, $stok, $satuan, $tanggalKadaluarsa, $deskripsi, $status);
        mysqli_stmt_execute($stmt);
        redirectProduk(mysqli_stmt_affected_rows($stmt) > 0 ? 'berhasil' : 'gagal');
    }

    if ($aksi === 'edit') {
        $idProduk = (int) nilaiProdukPost('id_produk');
        if ($idProduk <= 0 || $idProduk >= 100000 || !in_array($jenis, ['hewan', 'rumput', 'susu'], true) || $nama === '' || $harga <= 0) {
            redirectProduk('gagal');
        }

        if ($jenis !== 'susu') {
            $tanggalKadaluarsa = '0000-00-00';
        }

        $stmt = mysqli_prepare(
            $db,
            'UPDATE data_produk
             SET jenis_produk = ?, nama_produk = ?, harga = ?, stok = ?, satuan = ?, tgl_kadaluarsa = ?, status_produk = ?
             WHERE id_produk = ?'
        );
        if (!$stmt) {
            redirectProduk('gagal');
        }
        mysqli_stmt_bind_param($stmt, 'ssdisssi', $jenis, $nama, $harga, $stok, $satuan, $tanggalKadaluarsa, $status, $idProduk);
        mysqli_stmt_execute($stmt);
        redirectProduk(mysqli_stmt_affected_rows($stmt) >= 0 ? 'berhasil' : 'gagal');
    }

    if ($aksi === 'hapus') {
        $idProduk = (int) nilaiProdukPost('id_produk');
        if ($idProduk <= 0 || $idProduk >= 100000) {
            redirectProduk('gagal');
        }

        $stmt = mysqli_prepare($db, 'DELETE FROM data_produk WHERE id_produk = ?');
        if (!$stmt) {
            redirectProduk('gagal');
        }
        mysqli_stmt_bind_param($stmt, 'i', $idProduk);
        mysqli_stmt_execute($stmt);
        redirectProduk(mysqli_stmt_affected_rows($stmt) > 0 ? 'berhasil' : 'gagal');
    }
}

function labelJenisProduk(string $jenis): string
{
    return [
        'hewan' => 'Hewan',
        'rumput' => 'Rumput',
        'susu' => 'Susu',
    ][$jenis] ?? ucfirst($jenis);
}

function labelStatusProduk(string $status): string
{
    return $status === 'terjual' ? 'Tidak Tersedia' : 'Tersedia';
}

function statusProdukManajemen(string $jenis, string $status, ?string $tanggalKadaluarsa): string
{
    if (
        $jenis === 'susu'
        && $tanggalKadaluarsa
        && $tanggalKadaluarsa !== '0000-00-00'
        && $tanggalKadaluarsa < date('Y-m-d')
    ) {
        return 'Tidak Tersedia';
    }

    return labelStatusProduk($status);
}

function labelJenisHewanProduk(string $jenis): string
{
    return [
        'sapi_perah' => 'Sapi Perah',
        'sapi_po' => 'Sapi PO',
        'kambing' => 'Kambing',
        'domba' => 'Domba',
    ][$jenis] ?? ucwords(str_replace('_', ' ', $jenis));
}

function satuanProduk(string $jenis, string $satuan): string
{
    if ($satuan !== '') {
        return ucfirst($satuan);
    }

    return [
        'hewan' => 'Ekor',
        'rumput' => 'Kg',
        'susu' => 'Liter',
    ][$jenis] ?? '';
}

function tanggalProduksiSusu(?string $tanggalKadaluarsa): string
{
    if (!$tanggalKadaluarsa || $tanggalKadaluarsa === '0000-00-00') {
        return '';
    }

    return date('Y-m-d', strtotime($tanggalKadaluarsa . ' -7 days'));
}

$produkData = [];
$queryProduk = mysqli_query(
    $db,
    "SELECT id_produk, jenis_produk, nama_produk, harga, stok, satuan, tgl_kadaluarsa, status_produk
     FROM data_produk
     ORDER BY id_produk ASC"
);

if ($queryProduk) {
    while ($row = mysqli_fetch_assoc($queryProduk)) {
        $jenis = $row['jenis_produk'];
        $tanggal = $row['tgl_kadaluarsa'] && $row['tgl_kadaluarsa'] !== '0000-00-00'
            ? $row['tgl_kadaluarsa']
            : date('Y-m-d');

        $produkData[] = [
            'id' => (int) $row['id_produk'],
            'type' => labelJenisProduk($jenis),
            'name' => $row['nama_produk'],
            'date' => $jenis === 'susu' ? tanggalProduksiSusu($tanggal) : '',
            'expiryDate' => $jenis === 'susu' ? $tanggal : '',
            'price' => 'Rp ' . number_format((float) $row['harga'], 0, ',', '.'),
            'stock' => (float) $row['stok'] . ' ' . satuanProduk($jenis, $row['satuan'] ?? ''),
            'status' => statusProdukManajemen($jenis, $row['status_produk'], $tanggal),
            'image' => '',
        ];
    }
}

$queryHewanJual = mysqli_query(
    $db,
    "SELECT id_hewan, jenis_hewan, berat_badan, no_kandang
     FROM data_ternak
     WHERE status_hewan = 'tdk_produktif'
     ORDER BY id_hewan ASC"
);

if ($queryHewanJual) {
    while ($row = mysqli_fetch_assoc($queryHewanJual)) {
        $kode = str_pad((string) $row['id_hewan'], 5, '0', STR_PAD_LEFT);
        $produkData[] = [
            'id' => 100000 + (int) $row['id_hewan'],
            'type' => 'Hewan',
            'needs_price' => true,
            'name' => labelJenisHewanProduk($row['jenis_hewan']),
            'date' => '',
            'expiryDate' => '',
            'price' => 'Rp 0',
            'stock' => '1 Ekor',
            'status' => 'Tersedia',
            'image' => '',
        ];
    }
}
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
                    <input type="hidden" id="edit-id-hewan">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Produk</label>
                            <input type="text" class="form-input" value="Hewan" readonly>
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
                            <input type="date" class="form-input" id="edit-tgl-produksi-susu" required onchange="syncMilkExpiry('edit')" oninput="syncMilkExpiry('edit')">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluwarsa <span class="required">*</span></label>
                            <input type="date" class="form-input" id="edit-tgl-expiry-susu" readonly required>
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
                    <p id="previewSubtitle">Data produk aktif</p>
                </div>
                <div class="preview-id-badge" id="previewProductId">ID: -</div>
            </div>

            <p class="preview-section-title" id="previewSectionTitle">INFORMASI PRODUK</p>

            <div class="preview-info-grid">
                <div class="preview-info-box"><span class="preview-label">Jenis Produk</span><span class="preview-value" id="previewProductType">-</span></div>
                <div class="preview-info-box"><span class="preview-label">Nama Produk</span><span class="preview-value" id="previewProductName">-</span></div>
                <div class="preview-info-box preview-date-info"><span class="preview-label" id="previewProductDateLabel">Tanggal</span><span class="preview-value" id="previewProductDate">-</span></div>
                <div class="preview-info-box preview-expiry-info"><span class="preview-label">Tanggal Kadaluwarsa</span><span class="preview-value" id="previewProductExpiry">-</span></div>
                <div class="preview-info-box"><span class="preview-label">Harga</span><span class="preview-value" id="previewProductPrice">-</span></div>
                <div class="preview-info-box preview-stock-info"><span class="preview-label">Stok</span><span class="preview-value" id="previewProductStock">-</span></div>
                <div class="preview-info-box">
                    <span class="preview-label">Status</span>
                    <div class="preview-status-pill" id="previewStatusWrap">
                        <div class="preview-dot"></div>
                        <span id="previewProductStatus">-</span>
                    </div>
                </div>
            </div>

            <button class="preview-btn-confirm" type="button" onclick="closePreviewModal()">Tutup Preview</button>
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
                            <label class="form-label">Jenis Produk</label>
                            <input type="text" class="form-input" value="Hewan" readonly>
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
                            <label class="form-label">Harga per Kg <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-rumput" placeholder="Rp 2.500" required oninput="formatCurrencyInput(this)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Kg) <span class="required">*</span></label>
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
                            <input type="date" class="form-input" id="add-tgl-produksi-susu" required onchange="syncMilkExpiry('add')" oninput="syncMilkExpiry('add')">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluwarsa <span class="required">*</span></label>
                            <input type="date" class="form-input" id="add-tgl-expiry-susu" readonly required>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if (($_GET['status'] ?? '') === 'berhasil'): ?>
    showFlashMessage('Data produk berhasil disimpan.');
    <?php elseif (($_GET['status'] ?? '') === 'gagal'): ?>
    showFlashMessage('Data produk gagal disimpan. Periksa kembali isian data.', 'danger');
    <?php endif; ?>
});
</script>
<script src="../../public/js/manajemenProduk_admin.js?v=9"></script>
</body>
</html>
