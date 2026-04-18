<?php ?>

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
    <div class="container py-5">
        <h2 class="katalog-title text-center mb-5">Katalog Produk</h2>

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
                    aria-controls="filterSidebar"
                >
                    <i class="fas fa-sliders-h me-2"></i>
                    Filter Produk
                    <i class="fas fa-chevron-down ms-auto filter-chevron"></i>
                </button>

                <div class="collapse d-lg-block" id="filterSidebar">
                    <div class="filter-sidebar p-4 rounded-4">

                        <h5 class="filter-heading d-none d-lg-flex">
                            <i class="fas fa-filter me-2"></i> Filter Produk
                        </h5>

                        <!-- Jenis Hewan -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-label">Jenis Hewan</h6>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw1" checked>
                                <label class="form-check-label" for="fw1">Sapi Perah</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw2">
                                <label class="form-check-label" for="fw2">Sapi PO</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw3">
                                <label class="form-check-label" for="fw3">Kambing</label>
                            </div>
                            <div class="form-check filter-check">
                                <input class="form-check-input" type="checkbox" id="fw4">
                                <label class="form-check-label" for="fw4">Domba</label>
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

                        <div class="logo-sidebar mt-4 text-center">
                            <img src="public/images/logo_hayfarm.png" alt="Logo HayFarm" class="img-fluid" style="max-width: 160px; opacity: 0.75;">
                        </div>

                    </div>
                </div>
            </div>

            <!-- ===== PRODUCT GRID ===== -->
            <div class="col-lg-9 col-md-12">
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 g-3 g-md-4">

                    <!-- Card 1 -->
                    <div class="col">
                        <div class="produk-card h-100">
                            <div class="card-img-wrap position-relative">
                                <img src="public/images/bgheader_produk.png" alt="Sapi Perah" class="card-img-top">
                                <a href="#" class="detail-badge text-decoration-none">Detail</a>
                            </div>
                            <div class="card-body-custom">
                                <h5 class="card-product-name">Sapi Perah</h5>
                                <p class="card-price">Rp 10.000.000</p>
                                <p class="card-desc">Sapi perah terbaik sepanjang masa karena diproduksi di POLIJE</p>
                            </div>
                            <div class="card-footer-custom d-flex align-items-center gap-2">
                                <button class="btn btn-beli flex-grow-1">Beli</button>
                                <a href="#" class="cart-icon" title="Tambah ke keranjang">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col">
                        <div class="produk-card h-100">
                            <div class="card-img-wrap position-relative">
                                <img src="public/images/bgheader_produk.png" alt="Sapi PO" class="card-img-top">
                                <a href="#" class="detail-badge text-decoration-none">Detail</a>
                            </div>
                            <div class="card-body-custom">
                                <h5 class="card-product-name">Sapi PO</h5>
                                <p class="card-price">Rp 8.500.000</p>
                                <p class="card-desc">Sapi PO unggul dari bibit pilihan peternakan Hay Farms</p>
                            </div>
                            <div class="card-footer-custom d-flex align-items-center gap-2">
                                <button class="btn btn-beli flex-grow-1">Beli</button>
                                <a href="#" class="cart-icon" title="Tambah ke keranjang">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col">
                        <div class="produk-card h-100">
                            <div class="card-img-wrap position-relative">
                                <img src="public/images/bgheader_produk.png" alt="Kambing" class="card-img-top">
                                 <a href="#" class="detail-badge text-decoration-none">Detail</a>
                            </div>
                            <div class="card-body-custom">
                                <h5 class="card-product-name">Kambing Etawa</h5>
                                <p class="card-price">Rp 3.200.000</p>
                                <p class="card-desc">Kambing Etawa berkualitas tinggi, cocok untuk peternakan susu</p>
                            </div>
                            <div class="card-footer-custom d-flex align-items-center gap-2">
                                <button class="btn btn-beli flex-grow-1">Beli</button>
                                <a href="?page=user/keranjang" class="cart-icon" title="Tambah ke keranjang">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>