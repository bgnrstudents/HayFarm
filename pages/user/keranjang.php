<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../process/models/keranjang.php';

if (!isset($_SESSION['login'], $_SESSION['id_user']) || $_SESSION['login'] !== true) {
    // Ambil URI, hapus leading slash, simpan relatif
    $current_uri = $_SERVER['REQUEST_URI'];
    $relative_url = ltrim($current_uri, '/');

    $_SESSION['redirect_after_login'] = $relative_url;

    // Redirect ke login dengan path relatif yang konsisten
    header('Location: ../../login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$keranjang = new Keranjang($db);

$idUser = (int) $_SESSION['id_user'];
$items = $keranjang->getItems($idUser);
$total = $keranjang->hitungTotal($items);
$_SESSION['cart_count'] = $keranjang->hitungJumlahItem($idUser);

function esc_cart($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<section class="keranjang-section py-5">
    <div class="container">
        <div class=" d-flex gap-3 mb-4">
            <a href="index.php?page=user/produk" class="text-success">
                <i class="fa-solid fa-arrow-left-long fa-2x"></i>
            </a>
            <div>
                <div class="div-line">
                    <h2 class="mb-1 header-keranjang">Keranjang Saya</h2>
                    <span class="line"></span>
                </div>
                <p class="p-keranjang">Semua pembelian anda tercatat dengan transparan</p>
            </div>
        </div>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= ($_SESSION['flash_type'] ?? 'success') === 'error' ? 'danger' : 'success' ?> rounded-4">
                <?= esc_cart($_SESSION['flash_message']) ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7 col-12 mt-lg-5 mt-0">
                <?php if ($items === []): ?>
                    <div class="keranjang-empty text-center p-5 mt-lg-0 mb-lg-0 mb-4">
                        <i class="fa-solid fa-cart-shopping text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold text-success">Keranjang masih kosong</h5>
                        <p class="text-muted mb-4">Pilih produk ternak, susu, atau rumput dari katalog Hay Farm.</p>
                        <a href="index.php?page=user/produk" class="btn btn-success rounded-4 px-4 py-2">Lihat Produk</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <div class="keranjang-item">
                            <img src="<?= esc_cart($item['gambar']) ?>" alt="<?= esc_cart($item['nama_produk']) ?>" class="item-image"
                                onerror="this.onerror=null;this.src='public/images/bgheader_produk.png';">
                            <div class="item-details">
                                <div class="product-header">
                                    <h5 class="product-name"><?= esc_cart($item['nama_produk']) ?></h5>
                                    <form action="process/handlers/cart_handler.php" method="POST">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_detail_keranjang" value="<?= (int) $item['id_detail_keranjang'] ?>">
                                        <button class="delete-icon" title="Hapus item" type="submit">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="bottom-row">
                                    <form class="d-flex align-items-center gap-2 mt-lg-0 mt-3 cart-update-form" action="process/handlers/cart_handler.php" method="POST">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id_detail_keranjang" value="<?= (int) $item['id_detail_keranjang'] ?>">
                                        <div class="cart-qty-control" data-stock="<?= max(1, (int) $item['stok']) ?>">
                                            <div class="quantity-wrapper">
                                                <button class="qty-btn minus" type="button" onclick="ubahJumlahKeranjang(this, -1)">-</button>
                                                <input class="qty-value-input" type="number" name="jumlah" value="<?= (int) $item['jumlah'] ?>" min="1" max="<?= max(1, (int) $item['stok']) ?>" readonly>
                                                <button class="qty-btn plus" type="button" onclick="ubahJumlahKeranjang(this, 1)">+</button>
                                            </div>
                                            <div class="stock-warning" aria-live="polite">
                                                <i class="fas fa-triangle-exclamation"></i>
                                                <span>Stok tersedia hanya <?= max(1, (int) $item['stok']) ?> item</span>
                                            </div>
                                        </div>
                                        <span class="text-muted fw-medium"><?= esc_cart(ucfirst($item['satuan'] ?: 'item')) ?></span>
                                    </form>
                                    <div>
                                        <div class="price"><?= $keranjang->formatRupiah((float) $item['sub_total']) ?></div>
                                        <small class="text-muted"><?= $keranjang->formatRupiah((float) $item['harga']) ?> / <?= esc_cart($item['satuan'] ?: 'item') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-5 col-12 mt-lg-5 mt-0">
                <div class="ringkasan-card p-4 border rounded-4 sticky-top" style="top: 90px;">
                    <h5 class="mb-4">Ringkasan Pembayaran</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Produk</span>
                        <span><?= $keranjang->formatRupiah($total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold text-success"><?= $keranjang->formatRupiah($total) ?></span>
                    </div>

                    <?php if ($items === []): ?>
                        <button class="btn btn-secondary w-100 py-3 rounded-4 fw-bold" disabled>
                            Keranjang Kosong
                        </button>
                    <?php else: ?>
                        <a href="index.php?page=user/chekout&source=cart" class="btn btn-success w-100 py-3 rounded-4 fw-bold">
                            Lanjut Ke Pembayaran
                        </a>
                    <?php endif; ?>

                    <small class="text-muted d-block text-center mt-3">
                        Semua transaksi aman dan terverifikasi
                    </small>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body text-center p-4">
                    <i class="fas fa-trash-alt text-danger fa-3x mb-3"></i>
                    <h5 class="fw-bold mb-2">Hapus Produk?</h5>
                    <p class="text-muted mb-4" id="deleteConfirmText">Yakin ingin menghapus <strong id="deleteProductName"></strong> dari keranjang?</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-danger rounded-pill py-2" id="btnConfirmDelete">Ya, Hapus</button>
                        <button class="btn btn-light rounded-pill py-2 text-muted" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Kecil -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="cartToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i><span id="toastMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
</section>

<script>
    let pendingDeleteId = null;

    function syncCartQuantityState(control) {
        if (!control) return;

        const input = control.querySelector('.qty-value-input');
        const minusBtn = control.querySelector('.qty-btn.minus');
        const plusBtn = control.querySelector('.qty-btn.plus');
        const warning = control.querySelector('.stock-warning');
        if (!input) return;

        const min = parseInt(input.min || '1', 10);
        const max = parseInt(input.max || control.dataset.stock || '1', 10);
        const value = parseInt(input.value || '1', 10);
        const atMin = value <= min;
        const atMax = value >= max;

        if (minusBtn) minusBtn.disabled = atMin;
        if (plusBtn) plusBtn.disabled = atMax;
        if (warning) warning.classList.toggle('show', atMax);
    }

    // ===== UPDATE QUANTITY (AJAX - NO ALERT) =====
    function ubahJumlahKeranjang(button, perubahan) {
        const form = button.closest('.cart-update-form');
        const control = button.closest('.cart-qty-control');
        const input = form.querySelector('.qty-value-input');
        const detailId = form.querySelector('[name="id_detail_keranjang"]').value;
        const min = parseInt(input.min || '1', 10);
        const max = parseInt(input.max || '1', 10);
        const oldValue = parseInt(input.value || '1', 10);
        let value = parseInt(input.value || '1', 10) + perubahan;

        if (value < min) value = min;
        if (value > max) value = max;

        if (value !== oldValue) {
            input.value = value;
            syncCartQuantityState(control);

            // AJAX update - NO redirect, NO alert
            fetch('process/handlers/cart_handler.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'update',
                        id_detail_keranjang: detailId,
                        jumlah: value
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        if (data.stok) {
                            input.max = data.stok;
                            if (control) control.dataset.stock = data.stok;
                        }
                        if (data.jumlah) {
                            input.value = data.jumlah;
                            syncCartQuantityState(control);
                        }
                        // Update UI dynamically
                        const item = form.closest('.keranjang-item');
                        if (data.new_subtotal) {
                            item.querySelector('.price').textContent = data.new_subtotal;
                        }
                        const totalEl = document.querySelector('.ringkasan-card .fw-bold.text-success');
                        if (totalEl && data.new_total) {
                            totalEl.textContent = data.new_total;
                        }
                        // Update cart badge if exists
                        const badge = document.getElementById('cart-badge');
                        if (badge && data.cart_count !== undefined) {
                            badge.textContent = data.cart_count;
                            badge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                        }
                    } else {
                        input.value = oldValue;
                        syncCartQuantityState(control);
                        showToast(data.message || 'Jumlah melebihi stok tersedia.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Update error:', err);
                    input.value = oldValue;
                    syncCartQuantityState(control);
                    showToast('Gagal memperbarui jumlah. Silakan coba lagi.', 'error');
                });
        } else {
            syncCartQuantityState(control);
        }
    }

    // ===== DELETE WITH CONFIRMATION MODAL =====
    document.querySelectorAll('.delete-icon').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const detailId = form.querySelector('[name="id_detail_keranjang"]').value;
            const productName = form.closest('.keranjang-item').querySelector('.product-name').textContent;

            pendingDeleteId = detailId;
            document.getElementById('deleteProductName').textContent = productName;

            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        });
    });

    // Handle confirmed delete
    document.getElementById('btnConfirmDelete')?.addEventListener('click', function() {
        if (!pendingDeleteId) return;

        fetch('process/handlers/cart_handler.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'delete',
                    id_detail_keranjang: pendingDeleteId
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    // Remove item from DOM with animation
                    const item = document.querySelector(`[name="id_detail_keranjang"][value="${pendingDeleteId}"]`)?.closest('.keranjang-item');
                    if (item) {
                        item.style.transition = 'opacity 0.3s, transform 0.3s';
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-20px)';
                        setTimeout(() => item.remove(), 300);
                    }

                    // Update total & badge
                    const totalEl = document.querySelector('.ringkasan-card .fw-bold.text-success');
                    if (totalEl && data.new_total) {
                        totalEl.textContent = data.new_total;
                    }
                    const badge = document.getElementById('cart-badge');
                    if (badge && data.cart_count !== undefined) {
                        badge.textContent = data.cart_count;
                        badge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                    }

                    // Show small toast notification
                    showToast(data.message || 'Produk dihapus');

                    // Check if cart empty → show empty state
                    if (data.is_empty) {
                        setTimeout(() => location.reload(), 500);
                    }
                }
                pendingDeleteId = null;
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'))?.hide();
            })
            .catch(err => {
                console.error('Delete error:', err);
                showToast('Gagal menghapus produk. Silakan coba lagi.', 'error');
                pendingDeleteId = null;
            });
    });

    // Small toast notification (consistent with theme)
    function showToast(message) {
        const toastEl = document.getElementById('cartToast');
        const toastMsg = document.getElementById('toastMessage');
        if (toastEl && toastMsg) {
            toastMsg.textContent = message;
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.cart-qty-control').forEach(syncCartQuantityState);
        // ✅ FIX 1: Cek flag agar script tidak jalan 2x
        if (window._cartProcessed) return;
        window._cartProcessed = true;

        const pendingItem = sessionStorage.getItem('pending_cart_item');

        if (pendingItem) {
            const item = JSON.parse(pendingItem);

            // ✅ FIX 2: Hapus dari sessionStorage SEKARANG (sebelum AJAX)
            sessionStorage.removeItem('pending_cart_item');

            // Show loading overlay
            const loadingOverlay = document.createElement('div');
            loadingOverlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.95);z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;';
            loadingOverlay.innerHTML = `
            <div class="spinner-border text-success" style="width:3rem;height:3rem" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0 fw-bold text-success">Memproses produk...</p>
        `;
            document.body.appendChild(loadingOverlay);

            // Add to cart via AJAX
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('id_produk', item.id_produk);
            formData.append('jumlah', item.jumlah);

            fetch('process/handlers/cart_handler.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    // Remove overlay
                    if (loadingOverlay.parentNode) loadingOverlay.parentNode.removeChild(loadingOverlay);

                    if (data.status) {
                        // ✅ FIX 3: Update badge
                        const badge = document.getElementById('cart-badge');
                        if (badge && data.cart_count !== undefined) {
                            badge.textContent = data.cart_count;
                            badge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                        }

                        // Show toast
                        showToast('Produk berhasil ditambahkan!');

                        // ✅ FIX 4: Reload page setelah delay singkat untuk show items
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan produk');
                    }
                })
                .catch(err => {
                    console.error('Cart error:', err);
                    if (loadingOverlay.parentNode) loadingOverlay.parentNode.removeChild(loadingOverlay);
                    showToast('Gagal: ' + err.message, 'error');

                    // Fallback: reload untuk memastikan data sync
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                });
        }
    });

    // Helper toast function
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('cartToast');
        if (toastEl) {
            const toastBody = toastEl.querySelector('.toast-body');
            if (toastBody) {
                toastBody.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i><span>${message}</span>`;
                toastEl.className = `toast align-items-center text-white border-0 shadow-lg ${type === 'success' ? 'bg-success' : 'bg-danger'}`;
            }
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        } else {
            console.warn(message);
        }
    }
</script>
