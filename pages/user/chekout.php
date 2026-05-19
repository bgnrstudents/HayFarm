<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../process/models/keranjang.php';


// ✅ FIX: Parse query string dari REQUEST_URI jika $_GET kosong
if (!isset($_GET['source']) || !isset($_GET['produk_id'])) {
    // Coba parse dari REQUEST_URI
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    parse_str(parse_url($uri, PHP_URL_QUERY) ?? '', $parsed_query);

    // Merge dengan $_GET existing
    $_GET = array_merge($_GET, $parsed_query);
}

if (!isset($_SESSION['login'], $_SESSION['id_user']) || $_SESSION['login'] !== true) {
    $current_url = $_SERVER['REQUEST_URI'];
    $_SESSION['redirect_after_login'] = ltrim($current_url, '/');

    header('Location: ../../login.php');
    exit;
}

// ... sisa kode existing ...
$source = $_GET['source'] ?? 'direct';  // ← Sekarang pasti terbaca

$database = new Database();
$db = $database->getConnection();
$keranjang = new Keranjang($db);

$idUser = (int) $_SESSION['id_user'];
$source = $_GET['source'] ?? 'direct';
$items = [];

if ($source === 'cart') {
    $items = $keranjang->getItems($idUser);
} else {
    $idProduk = (int) ($_GET['produk_id'] ?? 0);
    $jumlah = $keranjang->normalJumlah($_GET['jumlah'] ?? 1);
    $produk = $keranjang->getProdukById($idProduk);

    if ($produk && $produk['status_produk'] === 'blm_terjual') {
        $jumlah = min($jumlah, max(1, (int) $produk['stok']));
        $items[] = [
            'id_produk' => (int) $produk['id_produk'],
            'nama_produk' => $produk['nama_produk'],
            'jenis_produk' => $produk['jenis_produk'],
            'satuan' => $produk['satuan'],
            'jumlah' => $jumlah,
            'harga' => (float) $produk['harga'],
            'sub_total' => (float) $produk['harga'] * $jumlah,
            'gambar' => $produk['gambar'],
        ];
    }
}

$total = $keranjang->hitungTotal($items);

function esc_checkout($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<div class="chekout-page">
    <div class="chekout-topbar">
        <a href="<?= $source === 'cart' ? '?page=user/keranjang' : '?page=user/produk' ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Checkout</h1>

        <div class="chekout-steps">
            <div class="ck-step done">
                <div class="ck-step-num"><i class="fas fa-check" style="font-size:9px"></i></div>
                Keranjang
            </div>
            <div class="ck-step-line done"></div>
            <div class="ck-step active">
                <div class="ck-step-num">2</div>
                Pengiriman
            </div>
            <div class="ck-step-line"></div>
            <div class="ck-step">
                <div class="ck-step-num">3</div>
                Konfirmasi
            </div>
        </div>
    </div>

    <!-- ✅ ALERT VALIDATION ERROR (Centered Overlay) -->
    <div id="validationOverlay" class="ck-overlay" style="display:none;" onclick="if(event.target===this)hideValidationAlert()">
        <div id="validationAlert" class="ck-validation-modal">
            <div class="ck-modal-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="#dc3545" stroke-width="1.5"/>
                    <path d="M12 8V13" stroke="#dc3545" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="16.5" r="1" fill="#dc3545"/>
                </svg>
            </div>
            <div class="ck-modal-title">Data Belum Lengkap</div>
            <div class="ck-modal-subtitle">Silakan perbaiki data berikut sebelum melanjutkan:</div>
            <ul id="errorList" class="ck-modal-error-list"></ul>
            <button type="button" class="ck-modal-btn" onclick="hideValidationAlert()">Mengerti</button>
        </div>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="container pt-3">
            <div class="alert alert-<?= ($_SESSION['flash_type'] ?? 'success') === 'error' ? 'danger' : 'success' ?> rounded-4">
                <?= esc_checkout($_SESSION['flash_message']) ?>
            </div>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <?php if ($items === []): ?>
        <div class="chekout-wrap" style="display:block;max-width:680px">
            <div class="ck-card text-center">
                <i class="fa-solid fa-box-open text-success mb-3" style="font-size:3rem"></i>
                <h2 class="ck-section-title justify-content-center">Tidak Ada Produk Dipilih</h2>
                <p class="text-muted">Silakan pilih produk terlebih dahulu sebelum checkout.</p>
                <a href="?page=user/produk" class="btn btn-success rounded-4 px-4 py-2">Kembali ke Produk</a>
            </div>
        </div>
    <?php else: ?>
        <form action="process/handlers/transaction.php" method="POST" enctype="multipart/form-data" id="checkoutForm">
            <input type="hidden" name="source" value="<?= esc_checkout($source) ?>">
            <input type="hidden" name="metode_pembayaran" id="metodePembayaran" value="transfer">
            <?php foreach ($items as $item): ?>
                <input type="hidden" name="id_produk[]" value="<?= (int) $item['id_produk'] ?>">
                <input type="hidden" name="jumlah[]" value="<?= (int) $item['jumlah'] ?>">
            <?php endforeach; ?>

            <div class="chekout-wrap">
                <div>
                    <div class="ck-card">
                        <h2 class="ck-section-title">
                            <i class="fas fa-map-marker-alt"></i> Informasi Pengiriman
                        </h2>

                        <div class="ck-mb">
                            <label class="ck-label">Nama Lengkap</label>
                            <div class="ck-input-wrap">
                                <i class="fas fa-user ck-icon"></i>
                                <input type="text" name="nama_pembeli" id="namaPembeli" class="ck-input" placeholder="Budi Santoso">
                            </div>
                        </div>

                        <div class="ck-mb">
                            <label class="ck-label">No Telepon</label>
                            <div class="ck-input-wrap">
                                <i class="fas fa-phone ck-icon"></i>
                                <input type="text" name="no_telp" id="noTelp" class="ck-input" placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>

                        <div class="ck-mb">
                            <label class="ck-label">Alamat Pengiriman</label>
                            <div class="ck-input-wrap">
                                <i class="fas fa-map-marker-alt ck-icon"></i>
                                <input type="text" name="alamat" id="alamat" class="ck-input" placeholder="Jl. Nama Jalan No. X">
                            </div>
                        </div>

                        <div class="ck-row2 ck-mb">
                            <div>
                                <label class="ck-label">Kota / Kabupaten</label>
                                <div class="ck-input-wrap">
                                    <i class="fas fa-city ck-icon"></i>
                                    <input type="text" name="kota" id="kota" class="ck-input" placeholder="Jember">
                                </div>
                            </div>
                            <div>
                                <label class="ck-label">Kode Pos</label>
                                <div class="ck-input-wrap">
                                    <i class="fas fa-map-pin ck-icon"></i>
                                    <input type="text" name="kode_pos" id="kodePos" class="ck-input" placeholder="68125">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ck-card">
                        <h2 class="ck-section-title">
                            <i class="fas fa-credit-card"></i> Metode Pembayaran
                        </h2>

                        <div class="ck-metode-grid">
                            <div class="ck-metode-btn active" id="btnTransfer" onclick="setMetode('Transfer')" role="button">
                                <i class="fas fa-university"></i>
                                Transfer Bank
                            </div>
                            <div class="ck-metode-btn" id="btnCod" onclick="setMetode('Cod')" role="button">
                                <i class="fas fa-handshake"></i>
                                COD
                            </div>
                        </div>

                        <div id="panelTransfer">
                            <div class="ck-rek-box">
                                <div>
                                    <div class="ck-rek-bank">BCA - a.n. Hay Farms Indonesia</div>
                                    <div class="ck-rek-no" id="rekeningNo">1234 5678 90</div>
                                </div>
                                <button class="ck-btn-salin" type="button" onclick="salinRek()">Salin</button>
                            </div>
                        </div>

                        <div id="codInfoBox" style="display: none;">
                            <div class="ck-info-box">
                                <div class="ck-info-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ck-info-content">
                                    <p class="ck-info-title">Pembayaran COD</p>
                                    <p class="ck-info-text">
                                        Setelah pesanan dibuat, silakan hubungi admin melalui WhatsApp untuk konfirmasi pesanan dan proses pembayaran.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ck-summary-card">
                    <p class="ck-summary-title">Ringkasan Pesanan</p>

                    <?php foreach ($items as $item): ?>
                        <div class="ck-produk-row">
                            <img class="ck-produk-img" src="<?= esc_checkout($item['gambar']) ?>" alt="<?= esc_checkout($item['nama_produk']) ?>"
                                onerror="this.onerror=null;this.src='public/images/bgheader_produk.png';">
                            <div>
                                <div class="ck-produk-nama"><?= esc_checkout($item['nama_produk']) ?></div>
                                <div class="ck-produk-varian"><?= (int) $item['jumlah'] ?> <?= esc_checkout($item['satuan'] ?: 'item') ?></div>
                                <div class="ck-produk-harga"><?= $keranjang->formatRupiah((float) $item['sub_total']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="ck-total-row">
                        <span class="ck-total-label">Total Tagihan</span>
                        <span class="ck-total-amount"><?= $keranjang->formatRupiah($total) ?></span>
                    </div>

                    <div id="uploadSection">
                        <p class="ck-upload-title">Bukti Pembayaran</p>
                        <label class="ck-upload-box" for="fileBukti">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik untuk upload bukti transfer</span>
                            <span style="font-size:11px;color:#bbb">JPG / PNG - Maks 5MB</span>
                            <input type="file" name="bukti_pembayaran" id="fileBukti" accept="image/*" onchange="previewBukti(this)">
                        </label>
                        <div id="fileNameDisplay" class="ck-file-name"></div>
                        <img id="previewImg" class="ck-preview-img" src="#" alt="Preview">
                    </div>

                    <button class="ck-btn-bayar" id="btnBayar" type="button">
                        <i class="fas fa-lock"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<div class="modal fade" id="mSalin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body text-center py-3">
                <i class="fas fa-clipboard-check text-success d-block mb-1" style="font-size:2rem"></i>
                <p class="mb-0 fw-bold" style="font-size:13px">Nomor rekening disalin!</p>
            </div>
        </div>
    </div>
</div>

<script>
    // ✅ Validation functions
    function validateNama(nama) {
        if (!nama.trim()) {
            return 'Nama lengkap tidak boleh kosong';
        }
        if (nama.trim().length < 3) {
            return 'Nama minimal 3 karakter';
        }
        return '';
    }

    function validateNoTelp(noTelp) {
        if (!noTelp.trim()) {
            return 'Nomor telepon tidak boleh kosong';
        }
        // Remove spaces and dashes for validation
        const cleaned = noTelp.replace(/[-\s]/g, '');
        if (!/^\d+$/.test(cleaned)) {
            return 'Nomor telepon hanya boleh berisi angka';
        }
        if (cleaned.length < 10) {
            return 'Nomor telepon minimal 10 digit';
        }
        return '';
    }

    function validateAlamat(alamat) {
        if (!alamat.trim()) {
            return 'Alamat tidak boleh kosong';
        }
        if (alamat.trim().length < 3) {
            return 'Alamat minimal 3 karakter';
        }
        return '';
    }

    function validateKota(kota) {
        if (!kota.trim()) {
            return 'Kota/Kabupaten tidak boleh kosong';
        }
        if (kota.trim().length < 3) {
            return 'Kota minimal 3 karakter';
        }
        return '';
    }

    function validateKodePos(kodePos) {
        if (!kodePos.trim()) {
            return 'Kode pos tidak boleh kosong';
        }
        if (!/^\d{5}$/.test(kodePos.trim())) {
            return 'Kode pos harus 5 digit';
        }
        return '';
    }

    function hideValidationAlert() {
        const overlay = document.getElementById('validationOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    function showValidationAlert(errorFields) {
        const overlay = document.getElementById('validationOverlay');
        const errorList = document.getElementById('errorList');

        if (!overlay || !errorList) return;

        // Clear previous errors
        errorList.innerHTML = '';

        // Add error items
        errorFields.forEach(field => {
            const li = document.createElement('li');
            li.textContent = field;
            errorList.appendChild(li);
        });

        // Show overlay
        overlay.style.display = 'flex';
    }

    // Setup on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial metode to transfer
        setMetode('Transfer');

        // Setup button click handler
        const btnBayar = document.getElementById('btnBayar');
        if (btnBayar) {
            btnBayar.addEventListener('click', function(e) {
                // Hide previous alert
                hideValidationAlert();

                // Validate all fields
                const namaError = validateNama(document.getElementById('namaPembeli')?.value || '');
                const telpError = validateNoTelp(document.getElementById('noTelp')?.value || '');
                const alamatError = validateAlamat(document.getElementById('alamat')?.value || '');
                const kotaError = validateKota(document.getElementById('kota')?.value || '');
                const kodePosError = validateKodePos(document.getElementById('kodePos')?.value || '');

                // Collect errors for modal alert
                const errorFields = [];
                if (namaError) errorFields.push(namaError);
                if (telpError) errorFields.push(telpError);
                if (alamatError) errorFields.push(alamatError);
                if (kotaError) errorFields.push(kotaError);
                if (kodePosError) errorFields.push(kodePosError);

                if (errorFields.length > 0) {
                    showValidationAlert(errorFields);
                    e.preventDefault();
                    return false;
                }

                const metode = document.getElementById('metodePembayaran').value;
                processCheckout(e, metode);
            });
        }
    });
</script>