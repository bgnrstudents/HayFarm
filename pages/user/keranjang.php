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

        <div class="row g-4">


            <div class="col-lg-7 col-12 mt-lg-5 mt-0">
                <div class="keranjang-item">
                    <img src="public/images/bgheader_produk.png" alt="Sapi Perah" class="item-image">
                    <div class="item-details">
                        <!-- Header: Nama Produk + Ikon Hapus -->
                        <div class="product-header">
                            <h5 class="product-name">Sapi Perah</h5>
                            <button class="delete-icon" title="Hapus item">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <!-- Bottom row: quantity + satuan dan harga -->
                        <div class="bottom-row">
                            <div class="d-flex align-items-center gap-2 mt-lg-0 mt-3">
                                <div class="quantity-wrapper">
                                    <button class="qty-btn minus">-</button>
                                    <span class="qty-value">1</span>
                                    <button class="qty-btn plus">+</button>
                                </div>
                                <span class="text-muted fw-medium">Ekor</span>
                            </div>
                            <div class="price">Rp 20.000</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-12 mt-lg-5 mt-0">
                <div class="ringkasan-card p-4 border rounded-4 sticky-top" style="top: 90px;">
                    <h5 class="mb-4">Ringkasan Pembayaran</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Produk</span>
                        <span>Rp 1.000.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold text-success">Rp 1.000.000</span>
                    </div>

                    <button class="btn btn-success w-100 py-3 rounded-4 fw-bold">
                        Lanjut Ke Pembayaran
                    </button>

                    <small class="text-muted d-block text-center mt-3">
                        Semua transaksi aman dan terverifikasi
                    </small>
                </div>
            </div>

        </div>
    </div>
</section>