<?php ?>
<div class="chekout-page">

    <!-- Topbar -->
    <div class="chekout-topbar">
        <a href="?page=user/produk" class="btn-back">
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

    <div class="chekout-wrap">

        <!-- ════ KIRI ════ -->
        <div>

            <!-- Informasi Pengiriman -->
            <div class="ck-card">
                <h2 class="ck-section-title">
                    <i class="fas fa-map-marker-alt"></i> Informasi Pengiriman
                </h2>

                <div class="ck-mb">
                    <label class="ck-label">Nama Lengkap</label>
                    <div class="ck-input-wrap">
                        <i class="fas fa-user ck-icon"></i>
                        <input type="text" class="ck-input" placeholder="Budi Santoso">
                    </div>
                </div>

                <div class="ck-mb">
                    <label class="ck-label">No Telepon</label>
                    <div class="ck-input-wrap">
                        <i class="fas fa-phone ck-icon"></i>
                        <input type="text" class="ck-input" placeholder="08xx-xxxx-xxxx">
                    </div>
                </div>

                <div class="ck-mb">
                    <label class="ck-label">Alamat Pengiriman</label>
                    <div class="ck-input-wrap">
                        <i class="fas fa-map-marker-alt ck-icon"></i>
                        <input type="text" class="ck-input" placeholder="Jl. Nama Jalan No. X">
                    </div>
                </div>

                <div class="ck-row2 ck-mb">
                    <div>
                        <label class="ck-label">Kota / Kabupaten</label>
                        <div class="ck-input-wrap">
                            <i class="fas fa-city ck-icon"></i>
                            <input type="text" class="ck-input" placeholder="Jember">
                        </div>
                    </div>
                    <div>
                        <label class="ck-label">Kode Pos</label>
                        <div class="ck-input-wrap">
                            <i class="fas fa-map-pin ck-icon"></i>
                            <input type="text" class="ck-input" placeholder="68125">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <!-- Metode Pembayaran -->
            <div class="ck-card">
                <h2 class="ck-section-title">
                    <i class="fas fa-credit-card"></i> Metode Pembayaran
                </h2>

                <div class="ck-metode-grid">
                    <div class="ck-metode-btn active" id="btnTransfer" onclick="setMetode('Transfer')">
                        <i class="fas fa-university"></i>
                        Transfer Bank
                    </div>
                    <div class="ck-metode-btn" id="btnCod" onclick="setMetode('Cod')">
                        <i class="fas fa-handshake"></i>
                        COD
                    </div>
                </div>


            <!-- Panel Transfer -->
            <div id="panelTransfer">
                <div class="ck-rek-box">
                    <div>
                        <div class="ck-rek-bank">BCA – a.n. Hay Farms Indonesia</div>
                        <div class="ck-rek-no" id="rekeningNo">1234 5678 90</div>
                    </div>
                    <button class="ck-btn-salin" onclick="salinRek()">Salin</button>
                </div>
            </div>

        </div>

    </div>
    <!-- /kiri -->

    <!-- ════ KANAN: Summary ════ -->
    <div class="ck-summary-card">

        <p class="ck-summary-title">Ringkasan Pesanan</p>

        <div class="ck-produk-row">
            <img class="ck-produk-img"
                src="public/images/bgheader_produk.png" alt="Sapi Perah">
            <div>
                <div class="ck-produk-nama">Sapi Perah FH</div>
                <div class="ck-produk-varian">Betina · 4 Tahun · Sehat</div>
                <div class="ck-produk-harga">Rp 10.000.000</div>
            </div>
        </div>



        <div class="ck-total-row">
            <span class="ck-total-label">Total Tagihan</span>
            <span class="ck-total-amount">Rp 10.050.000</span>
        </div>

        <!-- Upload bukti-->
        <div id="uploadSection">
            <p class="ck-upload-title">Bukti Pembayaran</p>
            <label class="ck-upload-box" for="fileBukti">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Klik untuk upload bukti transfer</span>
                <span style="font-size:11px;color:#bbb">JPG / PNG / WEBP · Maks 5MB</span>
                <input type="file" id="fileBukti" accept="image/*" onchange="previewBukti(this)">
            </label>
            <img id="previewImg" class="ck-preview-img" src="#" alt="Preview">
        </div>

        <button class="ck-btn-bayar" id="btnBayar" onclick="bayar()">
            <i class="fas fa-lock"></i> Bayar Sekarang
        </button>

        <!-- <div class="ck-trust">
            <div class="ck-trust-item"><i class="fas fa-shield-alt"></i> SSL Aman</div>
            <div class="ck-trust-item"><i class="fas fa-check-circle"></i> Terverifikasi</div>
            <div class="ck-trust-item"><i class="fas fa-clock"></i> 24 Jam</div>
        </div> -->

    </div>
</div>
</div>


<!-- Modal Sukses -->
<div class="modal fade" id="mSukses" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0"
                style="background:var(--color-secondary);color:#fff;padding:16px 20px">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    style="filter:invert(1)"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div style="width:60px;height:60px;border-radius:50%;background:#e8f5e9;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    <i class="fas fa-check-circle"
                        style="font-size:28px;color:var(--color-secondary)"></i>
                </div>
                <h5 class="fw-bold mb-1">Pesanan Diterima!</h5>
                <p class="text-muted mb-0" style="font-size:13px">
                    Tim kami akan memverifikasi pembayaran Anda<br>dalam 1×24 jam kerja.
                </p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4 gap-2">
                <a href="?page=user/riwayat_pesanan" class="btn text-white px-4"
                    style="background:var(--color-secondary);border-radius:50px;font-weight:700">
                    Lihat Pesanan
                </a>
                <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal"
                    style="border-radius:50px;font-weight:700">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Salin -->
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