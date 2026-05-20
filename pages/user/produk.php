<?php
// File ini di-include dari index.php (root), jadi path relatif ke root
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../process/models/produk.php';

function resolveUserProductImage($jenis, $foto)
{
    $jenis = strtolower(trim((string) $jenis));
    $foto = trim((string) $foto);
    $fallback = 'public/images/bgheader_produk.png';

    if ($jenis === 'susu') {
        return 'public/images/susu.jpg';
    }

    if ($jenis === 'rumput') {
        return 'public/images/rumput.jpg';
    }

    if ($jenis !== 'hewan' || $foto === '') {
        return $fallback;
    }

    $normalized = preg_replace('#^(\.\./|./)+#', '', str_replace('\\', '/', $foto)) ?? $foto;
    $normalized = ltrim($normalized, '/');

    if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
        return $normalized;
    }

    if (str_starts_with($normalized, 'public/') || str_starts_with($normalized, 'uploads/')) {
        return $normalized;
    }

    return 'uploads/hewan/' . basename($normalized);
}

function labelStatusKesehatanUser($status)
{
    return match ($status) {
        'sehat' => 'Sehat',
        'observasi' => 'Dalam Observasi',  // ✅ Sesuai DB
        'perawatan' => 'Dalam Perawatan',   // ✅ Sesuai DB
        default => 'Sehat'
    };
}


$database = new Database();
$db = $database->getConnection();
$produkModel = new Produk($db);

$is_logged_in = isset($_SESSION['login']) && $_SESSION['login'] === true;

$dataRaw = $produkModel->getAllForUserView();
$produk_list = [];
// ✅ Query yang benar: ambil status & tanggal terbaru

foreach ($dataRaw as $row) {
    if ($row['status_produk'] !== 'blm_terjual') continue;
    $jenis = $row['jenis_produk'] ?? '';
    $foto  = trim((string) ($row['foto_hewan'] ?? ''));
    $gambar = resolveUserProductImage($jenis, $foto);

    // Tentukan kategori filter berdasarkan jenis_produk & jenis_hewan
    $kategori = $row['jenis_produk'] ?? 'unknown';

    if ($row['jenis_produk'] === 'hewan') {
        $kategori = in_array(($row['jenis_hewan'] ?? ''), ['sapi_perah', 'sapi_po'], true)
            ? $row['jenis_hewan']
            : 'sapi_perah';
    }

    // Hitung Umur Otomatis
    $umur_text = '-';
    if (!empty($row['tgl_lahir']) && $row['tgl_lahir'] !== '0000-00-00') {
        try {
            $birthDate = new DateTime($row['tgl_lahir']);
            $today = new DateTime('today');

            if ($birthDate > $today) {
                $umur_text = 'Baru Lahir';
            } else {
                $diff = $today->diff($birthDate);
                if ($diff->y > 0) {
                    $umur_text = $diff->y . ' Thn ' . $diff->m . ' Bln';
                } elseif ($diff->m > 0) {
                    $umur_text = $diff->m . ' Bulan';
                } else {
                    $umur_text = $diff->d . ' Hari';
                }
            }
        } catch (Exception $e) {
            $umur_text = '-';
        }
    }

    $produk_list[] = [
        'id'       => $row['id_produk'],
        'nama'     => $row['nama_produk'],
        'harga'    => $row['harga'],
        'gambar'   => $gambar,
        'jenis'    => $row['jenis_produk'],
        'kategori' => $kategori,
        'satuan'   => $row['satuan'] ?? '',
        'stok'     => $row['stok'] ?? 0,
        'kode'     => $row['kode_hewan'] ?? '-',
        'umur'     => $umur_text,
        'id_hewan' => $row['id_hewan'] ?? null,
        'kesehatan' => !empty($row['status_kesehatan_terakhir'])
            ? labelStatusKesehatanUser($row['status_kesehatan_terakhir'])
            : 'Sehat',
        'tgl_pemeriksaan' => !empty($row['tgl_pemeriksaan_terakhir'])
            ? date('d M Y', strtotime($row['tgl_pemeriksaan_terakhir']))
            : '-',
        'catatan_kesehatan' => !empty($row['catatan_kesehatan_terakhir'])
            ? $row['catatan_kesehatan_terakhir']
            : '-',
        'desc'     => !empty($row['deskripsi']) ? $row['deskripsi'] : ucwords(str_replace('_', ' ', $row['jenis_produk'])) . ' berkualitas dari Hay Farm.',
    ];
}
?>

<!-- HEADER PRODUK -->
<section class="produk-header">
    <div class="banner-container container-fluid p-0">
        <div class="row g-0 align-items-stretch">
            <div class="col-md-7 farm-green-bg d-flex align-items-center p-5 p-lg-5 position-relative">
                <div class="banner-text py-5 ms-md-5">
                    <h1 class="main-title">
                        Produk Berkualitas langsung dari peternakan kami
                    </h1>
                </div>
            </div>
            <div class="col-md-5 position-relative overflow-hidden">
                <div class="image-wrapper w-100 h-100">
                    <img
                        src="public/images/bgheader_produk.png"
                        alt="Peternakan Sapi"
                        class="img-fluid w-100 h-100"
                        style="object-fit: cover; display: block;">
                    <div class="blend-mask"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="katalog-section">
    <div class="container py-5 ">
        <div class="text-center">
            <h2 class="katalog-title mb-5">Katalog Produk</h2>
        </div>
        <div class="row g-4">

            <!-- ===== FILTER SIDEBAR ===== -->
            <div class="col-lg-3 col-md-12">

                <!-- Mobile Toggle Button -->
                <button
                    class="filter-toggle-btn d-md-block d-lg-none mb-3 w-100"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filterSidebar"
                    aria-expanded="false"
                    aria-controls="filterSidebar">
                    <i class="fas fa-sliders-h me-2"></i>
                    Filter Produk
                    <i class="fas fa-chevron-down ms-auto filter-chevron"></i>
                </button>

                <div class="collapse d-lg-block" id="filterSidebar">
                    <div class="filter-sidebar p-4 rounded-4">

                        <h5 class="filter-heading d-none d-lg-flex">
                            <i class="fas fa-filter me-2"></i> Filter Produk
                        </h5>

                        <!-- Kategori Produk -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-label">Kategori Produk</h6>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw0" checked>
                                <label class="form-check-label" for="fw0">Semua Produk</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw1">
                                <label class="form-check-label" for="fw1">Sapi Perah</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw2">
                                <label class="form-check-label" for="fw2">Sapi PO</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw5">
                                <label class="form-check-label" for="fw5">Susu Segar</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw6">
                                <label class="form-check-label" for="fw6">Rumput Gajah</label>
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-label">Harga</h6>
                            <select class="form-select filter-select">
                                <option value="">Semua Harga</option>
                                <option value="low">Rp 0 - 1.000.000</option>
                                <option value="mid">Rp 1.000.000 - 5.000.000</option>
                                <option value="high">Rp 5.000.000+</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-label">Status</h6>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="st1" checked>
                                <label class="form-check-label" for="st1">Tersedia</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="st2">
                                <label class="form-check-label" for="st2">Pre-order</label>
                            </div>
                        </div>

                        <button class="btn btn-filter-apply w-100">
                            Terapkan Filter
                        </button>
                        <button class="btn btn-filter-reset w-100 mt-2" type="button">
                            Reset Filter
                        </button>

                        <div class="logo-sidebar mt-4 text-center">
                            <img src="public/images/logo_hayfarm.png" alt="Logo HayFarm" class="img-fluid" style="max-width: 160px; opacity: 0.75;">
                        </div>

                    </div>
                </div>
            </div>

            <!-- ===== PRODUCT GRID ===== -->
            <div class="col-lg-9 col-md-12 col-12">

                <!-- Satu row, semua card di dalamnya -->
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 g-3 g-md-4" id="product-grid">

                    <?php if (empty($produk_list)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada produk yang tersedia saat ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($produk_list as $i => $p):
                            $no = $i + 1;
                            $checkout_url = 'index.php?page=user/chekout&produk_id=' . urlencode($p['id']);
                        ?>

                            <?php if ($p['jenis'] === 'susu'): ?>
                                <!-- Card Susu - dengan quantity selector per liter -->
                                <div class="col" data-kategori="susu" data-harga="<?= (int)$p['harga'] ?>" data-status="tersedia">
                                    <div class="produk-card produk-susu h-100">
                                        <div class="card-img-wrap position-relative">
                                            <img src="<?= htmlspecialchars($p['gambar']) ?>"
                                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                                class="card-img-top"
                                                onerror="this.onerror=null;this.src='public/images/bgheader_produk.png';">
                                            <span class="badge-susu">Stok : <?= (int)$p['stok'] ?> L</span>
                                        </div>
                                        <div class="card-body-custom">
                                            <h5 class="card-product-name"><?= htmlspecialchars($p['nama']) ?></h5>
                                            <p class="card-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?><span class="per-liter">/liter</span></p>

                                            <!-- Quantity Selector -->
                                            <div class="quantity-control" data-stock="<?= max(1, (int)$p['stok']) ?>">
                                                <div class="quantity-selector">
                                                    <button type="button" class="qty-btn" onclick="updateQty(this, -1)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="qty-input" value="1" min="1" max="<?= max(1, (int)$p['stok']) ?>" readonly>
                                                    <button type="button" class="qty-btn" onclick="updateQty(this, 1)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div class="stock-warning" aria-live="polite">
                                                    <i class="fas fa-triangle-exclamation"></i>
                                                    <span>Stok tersedia hanya <?= max(1, (int)$p['stok']) ?> item</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer-custom d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-beli flex-grow-1"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-url="<?= htmlspecialchars($checkout_url) ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-harga="<?= (float)$p['harga'] ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                onclick="handleOrder(this, 'beli')">Beli</button>
                                            <button type="button" class="cart-icon border-0" title="Tambah ke keranjang"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-harga="<?= (float)$p['harga'] ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                onclick="handleOrder(this, 'keranjang')">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            <?php elseif ($p['jenis'] === 'rumput'): ?>
                                <!-- Card Rumput - dengan quantity selector per ton -->
                                <div class="col" data-kategori="rumput" data-harga="<?= (int)$p['harga'] ?>" data-status="tersedia">
                                    <div class="produk-card produk-rumput h-100">
                                        <div class="card-img-wrap position-relative">
                                            <img src="<?= htmlspecialchars($p['gambar']) ?>"
                                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                                class="card-img-top"
                                                onerror="this.onerror=null;this.src='public/images/bgheader_produk.png';">
                                            <span class="badge-rumput">Stok : <?= (int)$p['stok'] ?> T</span>
                                        </div>
                                        <div class="card-body-custom">
                                            <h5 class="card-product-name"><?= htmlspecialchars($p['nama']) ?></h5>
                                            <p class="card-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?><span class="per-ton">/ton</span></p>

                                            <!-- Quantity Selector -->
                                            <div class="quantity-control" data-stock="<?= max(1, (int)$p['stok']) ?>">
                                                <div class="quantity-selector">
                                                    <button type="button" class="qty-btn" onclick="updateQtyRumput(this, -1)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="qty-input" value="1" min="1" max="<?= max(1, (int)$p['stok']) ?>" readonly>
                                                    <button type="button" class="qty-btn" onclick="updateQtyRumput(this, 1)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div class="stock-warning" aria-live="polite">
                                                    <i class="fas fa-triangle-exclamation"></i>
                                                    <span>Stok tersedia hanya <?= max(1, (int)$p['stok']) ?> item</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer-custom d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-beli flex-grow-1"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-url="<?= htmlspecialchars($checkout_url) ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-harga="<?= (float)$p['harga'] ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                onclick="handleOrder(this, 'beli')">Beli</button>
                                            <button type="button" class="cart-icon border-0 bg-transparent" title="Tambah ke keranjang"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-harga="<?= (float)$p['harga'] ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                onclick="handleOrder(this, 'keranjang')">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>
                                <!-- Card Hewan - standar -->
                                <div class="col" data-kategori="<?= htmlspecialchars($p['kategori']) ?>" data-harga="<?= (int)$p['harga'] ?>" data-status="tersedia">
                                    <div class="produk-card h-100">
                                        <div class="card-img-wrap position-relative">
                                            <img src="<?= htmlspecialchars($p['gambar']) ?>"
                                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                                class="card-img-top"
                                                onerror="this.onerror=null;this.src='public/images/bgheader_produk.png';">
                                            <a href="#"
                                                class="detail-badge text-decoration-none"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-kode="<?= htmlspecialchars($p['kode'] ?? '') ?>"
                                                data-id_hewan="<?= (int)($p['id_hewan'] ?? 0) ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-umur="<?= htmlspecialchars($p['umur']) ?>"
                                                data-kesehatan="<?= htmlspecialchars($p['kesehatan']) ?>"
                                                data-tgl_pemeriksaan="<?= htmlspecialchars($p['tgl_pemeriksaan'] ?? '') ?>"
                                                data-catatan="<?= htmlspecialchars($p['catatan_kesehatan'] ?? '-') ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                data-desc="<?= htmlspecialchars($p['desc']) ?>"
                                                onclick="showDetail(this); return false;">Detail</a>
                                        </div>
                                        <div class="card-body-custom">
                                            <h5 class="card-product-name"><?= htmlspecialchars($p['nama']) ?></h5>
                                            <p class="card-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                                            <p class="card-desc"><?= htmlspecialchars($p['desc']) ?></p>
                                        </div>
                                        <div class="card-footer-custom d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-beli flex-grow-1"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-url="<?= htmlspecialchars($checkout_url) ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-harga="<?= (float)$p['harga'] ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                onclick="handleOrder(this, 'beli')">Beli</button>
                                            <button type="button" class="cart-icon border-0 bg-transparent" title="Tambah ke keranjang"
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-nama="<?= htmlspecialchars($p['nama']) ?>"
                                                data-harga="<?= (float)$p['harga'] ?>"
                                                data-gambar="<?= htmlspecialchars($p['gambar']) ?>"
                                                onclick="handleOrder(this, 'keranjang')">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <!-- MODAL DETAIL PRODUK -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-sm" style="border-radius:16px;overflow:hidden">

                <!-- Header -->
                <div class="modal-header border-0 py-3 px-4">
                    <h5 class="modal-title fw-semibold text-success mb-0">Detail Ternak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-0">

                    <!-- BARIS ATAS: foto kiri + info kanan (sejajar di desktop) -->
                    <div id="detail-top">

                        <!-- Foto -->
                        <div id="detail-img-col">
                            <img id="modal-image" src="public/images/bgheader_produk.png"
                                alt="Foto Ternak"
                                onerror="this.onerror=null;this.src='public/images/bgheader_produk.png';">
                        </div>

                        <!-- Info Utama -->
                        <div id="detail-info-col">
                            <div class="info-grid">
                                <span class="info-label">ID Ternak</span>
                                <span id="modal-id" class="text-success fw-semibold">0004</span>
                                <span class="info-label">Jenis</span>
                                <span id="modal-jenis">Sapi Perah</span>
                                <span class="info-label">Umur</span>
                                <span id="modal-umur">7 Tahun</span>
                                <span class="info-label">Status</span>
                                <span id="modal-status">
                                    <span class="badge bg-success px-3 py-1 rounded-pill">Sehat</span>
                                </span>
                            </div>

                        </div>

                        <!-- BARIS BAWAH: riwayat & catatan — full width, selalu di bawah -->
                    </div>
                    <div id="detail-bottom">

                        <hr class="my-3">

                        <p class="fw-semibold mb-2" style="font-size:13px">Riwayat Pemeriksaan Lengkap</p>
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                            <table class="table table-sm table-bordered mb-0" id="modal-riwayat"
                                style="font-size:13px;min-width:380px">
                            </table>
                        </div>

                        <hr class="my-3">

                        <p class="fw-semibold mb-2" style="font-size:13px">Catatan Medis</p>
                        <div id="modal-catatan" class="bg-light rounded-3 p-3 text-muted"
                            style="font-size:13px;line-height:1.6">
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL LOGIN PROMPT -->
    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content login-prompt-content">
                <div class="modal-body login-prompt-body">
                    <div class="prompt-icon-wrap">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="prompt-title">Wah, Hampir Selesai!</h3>
                    <p class="prompt-text">
                        Silakan login terlebih dahulu untuk mengamankan pesanan ternak Anda. <br>
                        Belum punya akun? Yuk, gabung jadi bagian dari Hay Farm!
                    </p>
                    <div class="prompt-actions">
                        <a href="#" class="btn-prompt-login" onclick="redirectToLoginFromPrompt()">Login Sekarang</a>
                        <a href="register.php" class="btn-prompt-register">Daftar Akun Baru</a>
                        <button type="button" class="btn-prompt-back" data-bs-dismiss="modal">Nanti Saja, Masih Mau Lihat-lihat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function redirectToLoginFromPrompt() {
            const pendingItem = sessionStorage.getItem('pending_cart_item');
            if (pendingItem) {
                const item = JSON.parse(pendingItem);
                const redirectUrl = item.redirect_url || 'index.php?page=user/keranjang';

                // Redirect ke login dengan parameter
                window.location.href = 'login.php?redirect=' + encodeURIComponent(redirectUrl);
            } else {
                // Fallback jika tidak ada data
                window.location.href = 'login.php';
            }
        }
    </script>


    <div class="confirm-overlay" id="confirmOverlay" onclick="closeConfirmOutside(event)" style="display:none;position:fixed;inset:0;z-index:3000;align-items:center;justify-content:center;padding:18px;background:rgba(9,33,18,.58);backdrop-filter:blur(5px);">
        <div class="confirm-card" style="width:min(420px,100%);position:relative;background:#fff;border-radius:24px;padding:30px 24px 24px;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.22);">
            <button type="button" class="confirm-close" onclick="closeConfirmOverlay()">
                <i class="fas fa-times"></i>
            </button>

            <div class="confirm-icon-wrap" id="confirm-icon-bg">
                <i class="fas fa-shopping-bag text-success" id="confirm-icon"></i>
            </div>

            <h4 class="fw-bold mb-2" id="confirm-title">Konfirmasi</h4>
            <p class="text-muted mb-2" id="confirm-text"></p>

            <div class="confirm-product-preview mb-4">
                <img id="confirm-product-img" src="" alt="" class="img-fluid rounded-4 shadow-sm">
                <div class="fw-bold mt-3" id="confirm-product-name"></div>
                <div class="text-success fw-semibold" id="confirm-product-price"></div>
            </div>

            <div class="d-grid gap-2">
                <button type="button" id="btn-confirm-action" class="btn btn-success py-3 rounded-pill fw-bold">
                    Konfirmasi
                </button>
                <button type="button" class="btn btn-light py-3 rounded-pill fw-bold text-muted" onclick="closeConfirmOverlay()">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFIKASI KERANJANG -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="cartToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 15px;">
            <div class="d-flex p-2">
                <div class="toast-body d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fas fa-check text-success"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold">Berhasil!</span>
                        <small>Produk ditambahkan ke keranjang</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        // Data login dari PHP
        const isLoggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;

        function openConfirmOverlay() {
            const overlay = document.getElementById('confirmOverlay');
            if (!overlay) return;
            overlay.style.display = 'flex';
            overlay.classList.add('show');
            document.body.classList.add('confirm-open');
        }

        function closeConfirmOverlay() {
            const overlay = document.getElementById('confirmOverlay');
            if (!overlay) return;
            overlay.style.display = 'none';
            overlay.classList.remove('show');
            document.body.classList.remove('confirm-open');
        }

        function closeConfirmOutside(event) {
            if (event.target && event.target.id === 'confirmOverlay') {
                closeConfirmOverlay();
            }
        }

        function formatRupiah(value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
        }

        function getProductQty(button) {
            const card = button.closest('.produk-card');
            const input = card ? card.querySelector('.qty-input') : null;
            if (!input) return 1;

            const control = input.closest('.quantity-control');
            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || control?.dataset.stock || '1', 10);
            let qty = parseInt(input.value || '1', 10);

            if (qty < min) qty = min;
            if (qty > max) qty = max;
            input.value = qty;
            syncQuantityState(control);

            return qty;
        }

        function syncQuantityState(control) {
            if (!control) return;

            const input = control.querySelector('.qty-input');
            const minusBtn = control.querySelector('.qty-btn:first-child');
            const plusBtn = control.querySelector('.qty-btn:last-child');
            const warning = control.querySelector('.stock-warning');
            if (!input) return;

            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || control.dataset.stock || '1', 10);
            const value = parseInt(input.value || '1', 10);
            const atMin = value <= min;
            const atMax = value >= max;

            if (minusBtn) minusBtn.disabled = atMin;
            if (plusBtn) plusBtn.disabled = atMax;
            if (warning) warning.classList.toggle('show', atMax);
        }

        function updateQuantity(btn, change) {
            const control = btn.closest('.quantity-control');
            const input = control ? control.querySelector('.qty-input') : null;
            if (!input) return;

            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || control.dataset.stock || '1', 10);
            let val = parseInt(input.value || '1', 10) + change;

            if (val < min) val = min;
            if (val > max) val = max;

            input.value = val;
            syncQuantityState(control);
        }

        function redirectToLoginFromPrompt() {
            const pendingItem = sessionStorage.getItem('pending_cart_item');
            if (pendingItem) {
                const item = JSON.parse(pendingItem);
                const redirectUrl = item.redirect_url || 'index.php?page=user/keranjang';

                // Redirect ke login dengan parameter
                window.location.href = 'login.php?redirect=' + encodeURIComponent(redirectUrl);
            } else {
                // Fallback jika tidak ada data
                window.location.href = 'login.php';
            }
        }


        function handleOrder(button, type = 'beli') {
            const id = button.dataset.id;
            const name = button.dataset.nama || '';
            const img = button.dataset.gambar || 'public/images/bgheader_produk.png';
            const price = parseFloat(button.dataset.harga || '0');
            const qty = getProductQty(button);
            const url = (button.dataset.url || 'index.php?page=user/chekout') + '&jumlah=' + encodeURIComponent(qty);

            const confirmTitle = document.getElementById('confirm-title');
            const confirmText = document.getElementById('confirm-text');
            const confirmBtn = document.getElementById('btn-confirm-action');
            const confirmIcon = document.getElementById('confirm-icon');
            const confirmImg = document.getElementById('confirm-product-img');
            const confirmName = document.getElementById('confirm-product-name');
            const confirmPrice = document.getElementById('confirm-product-price');

            if (confirmImg) confirmImg.src = img;
            if (confirmName) confirmName.textContent = name;
            if (confirmPrice) confirmPrice.textContent = formatRupiah(price) + ' x ' + qty;

            if (type === 'beli') {
                if (confirmTitle) confirmTitle.textContent = 'Konfirmasi Pembelian';
                if (confirmText) confirmText.innerHTML = `Anda akan diarahkan ke halaman checkout untuk membeli <span class="fw-bold text-dark">${name}</span>.`;
                if (confirmBtn) {
                    confirmBtn.textContent = 'Lanjut ke Checkout';
                    confirmBtn.className = 'btn btn-success py-3 rounded-pill fw-bold';
                    confirmBtn.onclick = function() {
                        // ✅ ALERT 1: Konfirmasi pembelian
                        if (!isLoggedIn) {
                            // ✅ ALERT 2: Login prompt (MODAL)
                            closeConfirmOverlay();
                            const loginModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
                            loginModal.show();

                            // ✅ Simpan info produk + URL tujuan untuk setelah login
                            const cartItem = {
                                id_produk: id,
                                jumlah: qty,
                                nama: name,
                                harga: price,
                                gambar: img,
                                redirect_url: url
                            };
                            sessionStorage.setItem('pending_cart_item', JSON.stringify(cartItem));
                            return;
                        }

                        // Jika sudah login, langsung redirect ke checkout
                        window.location.href = url;
                    };
                }
                if (confirmIcon) confirmIcon.className = 'fas fa-shopping-bag text-success';
            } else {
                // type === 'keranjang'
                if (confirmTitle) confirmTitle.textContent = 'Tambah ke Keranjang?';
                if (confirmText) confirmText.innerHTML = `Apakah Anda ingin memasukkan <span class="fw-bold text-dark">${name}</span> ke dalam keranjang belanja?`;
                if (confirmBtn) {
                    confirmBtn.textContent = 'Ya, Tambahkan';
                    confirmBtn.className = 'btn btn-success py-3 rounded-pill fw-bold';
                    confirmBtn.onclick = function() {
                        // ✅ ALERT 1: Konfirmasi tambah ke keranjang
                        if (!isLoggedIn) {
                            // ✅ ALERT 2: Login prompt (MODAL)
                            closeConfirmOverlay();
                            const loginModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
                            loginModal.show();

                            // ✅ Simpan info produk untuk setelah login
                            const cartItem = {
                                id_produk: id,
                                jumlah: qty,
                                nama: name,
                                harga: price,
                                gambar: img,
                                redirect_url: 'index.php?page=user/keranjang'
                            };
                            sessionStorage.setItem('pending_cart_item', JSON.stringify(cartItem));
                            return;
                        }

                        // Jika sudah login, langsung tambah ke keranjang
                        tambahKeKeranjang(id, qty);
                    };
                }
                if (confirmIcon) confirmIcon.className = 'fas fa-cart-plus text-success';
            }

            openConfirmOverlay();
        }


        function tambahKeKeranjang(idProduk, jumlah) {
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('id_produk', idProduk);
            formData.append('jumlah', jumlah);

            fetch('process/handlers/cart_handler.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.status) {
                        showCartNotification(null, data.message || 'Gagal menambahkan produk.', 'error');
                        return;
                    }

                    showCartNotification(data.cart_count, 'Produk ditambahkan ke keranjang', 'success');
                    closeConfirmOverlay();
                })
                .catch(() => showCartNotification(null, 'Terjadi kesalahan saat menambahkan keranjang.', 'error'));
        }

        function showCartNotification(cartCount = null, message = 'Produk ditambahkan ke keranjang', type = 'success') {
            // 1. Tampilkan Toast
            const toastEl = document.getElementById('cartToast');
            const toastBody = toastEl ? toastEl.querySelector('.toast-body') : null;
            if (toastEl && toastBody) {
                toastEl.className = `toast align-items-center text-white border-0 shadow-lg ${type === 'error' ? 'bg-danger' : 'bg-success'}`;
                toastBody.innerHTML = `
                    <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fas fa-${type === 'error' ? 'triangle-exclamation text-danger' : 'check text-success'}"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold">${type === 'error' ? 'Perhatian' : 'Berhasil!'}</span>
                        <small>${message}</small>
                    </div>
                `;
            }
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();

            // 2. Animasi Icon Keranjang di Navbar
            const cartNav = document.getElementById('cart-nav');
            if (cartNav && type !== 'error') {
                cartNav.classList.add('cart-animate');
                setTimeout(() => cartNav.classList.remove('cart-animate'), 800);
            }

            // 3. Update Badge (Simulasi update angka)
            const badge = document.getElementById('cart-badge');
            if (badge && type !== 'error') {
                let count = cartCount !== null ? parseInt(cartCount) : ((parseInt(badge.textContent) || 0) + 1);
                badge.textContent = count;
                badge.style.display = 'block';

                // Tambahkan efek scale pada badge
                badge.classList.add('badge-pop');
                setTimeout(() => badge.classList.remove('badge-pop'), 300);
            }
        }

        function updateQty(btn, change) {
            updateQuantity(btn, change);
        }

        function updateQtyRumput(btn, change) {
            updateQuantity(btn, change);
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.quantity-control').forEach(syncQuantityState);

            const btnApply = document.querySelector('.btn-filter-apply');
            const btnReset = document.querySelector('.btn-filter-reset');
            const cards = document.querySelectorAll('#product-grid .col');

            const fw0 = document.getElementById('fw0');
            const fw1 = document.getElementById('fw1');
            const fw2 = document.getElementById('fw2');
            const fw3 = document.getElementById('fw3');
            const fw4 = document.getElementById('fw4');
            const fw5 = document.getElementById('fw5');
            const fw6 = document.getElementById('fw6');
            const fwOthers = [fw1, fw2, fw3, fw4, fw5, fw6];

            const priceSelect = document.querySelector('.filter-select');
            const st1 = document.getElementById('st1');
            const st2 = document.getElementById('st2');

            // Logika Checkbox "Semua Produk"
            if (fw0) {
                fw0.addEventListener('change', function() {
                    if (this.checked) {
                        fwOthers.forEach(cb => {
                            if (cb) cb.checked = false;
                        });
                    }
                });
            }

            fwOthers.forEach(cb => {
                if (!cb) return;
                cb.addEventListener('change', function() {
                    if (this.checked && fw0) fw0.checked = false;

                    // Kalau semuanya tidak dicentang, otomatis centang "Semua Produk"
                    const anyChecked = fwOthers.some(c => c && c.checked);
                    if (!anyChecked && fw0) fw0.checked = true;
                });
            });

            // Terapkan Filter
            function applyFilter() {
                const selectedCategories = [];
                if (fw1 && fw1.checked) selectedCategories.push('sapi_perah');
                if (fw2 && fw2.checked) selectedCategories.push('sapi_po');
                if (fw5 && fw5.checked) selectedCategories.push('susu');
                if (fw6 && fw6.checked) selectedCategories.push('rumput');

                const priceFilter = priceSelect ? priceSelect.value : '';

                const selectedStatuses = [];
                if (st1 && st1.checked) selectedStatuses.push('tersedia');
                if (st2 && st2.checked) selectedStatuses.push('preorder');

                let visibleCount = 0;

                cards.forEach(card => {
                    const cat = card.getAttribute('data-kategori');
                    const price = parseInt(card.getAttribute('data-harga') || '0');
                    const status = card.getAttribute('data-status');

                    // 1. Cek Kategori
                    let matchCategory = true;
                    if (fw0 && !fw0.checked) {
                        matchCategory = selectedCategories.includes(cat);
                    }

                    // 2. Cek Harga
                    let matchPrice = true;
                    if (priceFilter === 'low') matchPrice = (price <= 1000000);
                    else if (priceFilter === 'mid') matchPrice = (price > 1000000 && price <= 5000000);
                    else if (priceFilter === 'high') matchPrice = (price > 5000000);

                    // 3. Cek Status
                    let matchStatus = selectedStatuses.length === 0 || selectedStatuses.includes(status);

                    if (matchCategory && matchPrice && matchStatus) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // (Opsional) Tampilkan pesan kalau tidak ada produk
                let empty = document.getElementById('emptyFilter');
                if (visibleCount === 0) {
                    if (!empty) {
                        empty = document.createElement('div');
                        empty.id = 'emptyFilter';
                        empty.className = 'text-center py-5 m-auto ';
                        empty.innerHTML = `<i class="fas fa-box-open fa-3x text-muted mb-3"></i><p class="text-muted">Tidak ada produk yang sesuai dengan filter.</p>`;
                        document.getElementById('product-grid').appendChild(empty);
                    }
                } else if (empty) {
                    empty.remove();
                }
            }

            function resetFilter() {
                if (fw0) fw0.checked = true;
                fwOthers.forEach(cb => {
                    if (cb) cb.checked = false;
                });

                if (priceSelect) priceSelect.value = '';
                if (st1) st1.checked = true;
                if (st2) st2.checked = false;

                applyFilter();
            }

            // Jalankan filter saat tombol ditekan
            if (btnApply) btnApply.addEventListener('click', applyFilter);
            if (btnReset) btnReset.addEventListener('click', resetFilter);

            // Karena default di HTML 'fw0' checked, rapikan dulu onload
            if (fw0 && fw0.checked) fwOthers.forEach(cb => {
                if (cb) cb.checked = false;
            });
            applyFilter();
        });
    </script>
</section>
