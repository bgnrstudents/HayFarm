<?php ?>

<style>
/* ── Menggunakan variabel dari style.css project ── */

.chekout-page {
    background: #f4f6f4;
    min-height: 100vh;
    font-family: var(--font-poppins);
}

/* ── Topbar ── */
.chekout-topbar {
    background: var(--color-primary);
    border-bottom: 2px solid #e5e7eb;
    padding: 14px 28px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.chekout-topbar .btn-back {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f0f7f2;
    color: var(--color-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 15px;
    flex-shrink: 0;
    transition: background .2s;
}
.chekout-topbar .btn-back:hover { background: #d8edde; }

.chekout-topbar h1 {
    font-family: var(--font-serif);
    font-size: 20px;
    font-weight: 700;
    color: var(--color-secondary);
    font-style: italic;
    margin: 0;
}

/* Step indicator */
.chekout-steps {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 6px;
}

.ck-step {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #aaa;
    font-family: var(--font-poppins);
}

.ck-step-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid currentColor;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
}

.ck-step.active { color: var(--color-secondary); }
.ck-step.active .ck-step-num,
.ck-step.done   .ck-step-num {
    background: var(--color-secondary);
    border-color: var(--color-secondary);
    color: #fff;
}
.ck-step.done { color: var(--color-secondary); }

.ck-step-line { width: 28px; height: 2px; background: #e5e7eb; border-radius: 2px; }
.ck-step-line.done { background: var(--color-secondary); }

/* ── Main wrapper: 2 kolom ── */
.chekout-wrap {
    max-width: 920px;
    margin: 28px auto;
    padding: 0 20px 60px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
    align-items: start;
}

/* ── Kartu generik ── */
.ck-card {
    background: var(--color-primary);
    border-radius: 16px;
    box-shadow: 0 2px 14px rgba(0,0,0,.07);
    border: 1px solid #eee;
    padding: 24px;
    margin-bottom: 20px;
}
.ck-card:last-child { margin-bottom: 0; }

/* ── Section title ── */
.ck-section-title {
    font-family: var(--font-serif);
    font-size: 17px;
    font-weight: 700;
    color: var(--color-secondary);
    font-style: italic;
    margin: 0 0 18px;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Fields ── */
.ck-row2 { display: grid; grid-template-columns: 1fr 130px; gap: 14px; }
.ck-mb   { margin-bottom: 14px; }
.ck-mb:last-child { margin-bottom: 0; }

.ck-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #888;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 6px;
}

.ck-input-wrap { position: relative; }

.ck-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #bbb;
    font-size: 13px;
    pointer-events: none;
    transition: color .2s;
}

.ck-input {
    width: 100%;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    padding: 11px 14px 11px 36px;
    font-size: 13px;
    font-family: var(--font-poppins);
    color: #1a1a1a;
    background: var(--color-primary);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

.ck-input:focus { border-color: var(--color-secondary); box-shadow: 0 0 0 3px rgba(25,108,51,.1); }
.ck-input-wrap:focus-within .ck-icon { color: var(--color-secondary); }
.ck-input::placeholder { color: #ccc; }

/* Toggle simpan alamat */
.ck-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    margin-top: 14px;
    border-top: 1px solid #f0f0f0;
}

.ck-toggle-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #444;
}

.ck-toggle-label i { color: var(--color-secondary); font-size: 15px; }
.ck-toggle-label small { display: block; font-size: 11px; color: #aaa; font-weight: 400; }

.ck-toggle {
    width: 42px; height: 23px;
    background: #ddd; border-radius: 20px;
    position: relative; cursor: pointer;
    transition: background .3s; flex-shrink: 0;
}
.ck-toggle.on { background: var(--color-secondary); }

.ck-toggle-thumb {
    width: 17px; height: 17px;
    background: #fff; border-radius: 50%;
    position: absolute; top: 3px; left: 3px;
    transition: left .3s;
    box-shadow: 0 1px 4px rgba(0,0,0,.15);
}
.ck-toggle.on .ck-toggle-thumb { left: 22px; }

/* ── Metode Pembayaran ── */
.ck-metode-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 16px;
}

.ck-metode-btn {
    border: 1.5px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    background: var(--color-primary);
    font-size: 12px;
    font-weight: 700;
    font-family: var(--font-poppins);
    color: #666;
    transition: all .2s;
    user-select: none;
}

.ck-metode-btn i { font-size: 20px; }
.ck-metode-btn:hover { border-color: var(--color-secondary); background: #f0f7f2; }
.ck-metode-btn.active {
    background: var(--color-secondary);
    color: #fff;
    border-color: var(--color-secondary);
    box-shadow: 0 4px 14px rgba(25,108,51,.25);
}

/* Rekening box */
.ck-rek-box {
    background: #f0f7f2;
    border: 1px solid rgba(25,108,51,.2);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.ck-rek-bank { font-size: 11px; color: #888; font-weight: 600; margin-bottom: 3px; }
.ck-rek-no   { font-size: 20px; font-weight: 800; color: var(--color-secondary); letter-spacing: 1.5px; }

.ck-btn-salin {
    background: var(--color-secondary);
    color: #fff; border: none;
    border-radius: 8px; padding: 8px 18px;
    font-size: 12px; font-weight: 700;
    font-family: var(--font-poppins);
    cursor: pointer; transition: background .2s;
    flex-shrink: 0;
}
.ck-btn-salin:hover { background: var(--color-secondary2); }

/* E-wallet */
.ck-ewallet-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}

.ck-ewallet-btn {
    border: 1.5px solid #e0e0e0;
    border-radius: 8px; padding: 8px 4px;
    font-size: 11px; font-weight: 700;
    font-family: var(--font-poppins);
    color: #555; background: #fff;
    cursor: pointer; transition: all .2s;
    text-align: center;
}
.ck-ewallet-btn:hover,
.ck-ewallet-btn.active { border-color: var(--color-secondary); color: var(--color-secondary); background: #f0f7f2; }

/* ── Ringkasan Pesanan (kanan) ── */
.ck-summary-card {
    background: var(--color-primary);
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.09);
    border: 1px solid #eee;
    padding: 24px;
    position: sticky;
    top: 80px;
}

.ck-summary-title {
    font-family: var(--font-serif);
    font-size: 17px; font-weight: 700;
    color: var(--color-secondary);
    font-style: italic;
    margin: 0 0 16px;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #f0f0f0;
}

.ck-produk-row {
    display: flex; align-items: center;
    gap: 12px; padding-bottom: 14px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 14px;
}

.ck-produk-img {
    width: 72px; height: 60px;
    border-radius: 10px; object-fit: cover;
    flex-shrink: 0; background: #eee;
}

.ck-produk-nama   { font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; }
.ck-produk-varian { font-size: 11px; color: #888; margin-bottom: 3px; }
.ck-produk-harga  { font-size: 13px; font-weight: 700; color: var(--color-secondary); }

/* Breakdown */
.ck-brow {
    display: flex; justify-content: space-between;
    align-items: center; font-size: 13px; padding: 5px 0;
}
.ck-brow .bl { color: #888; }
.ck-brow .bv { font-weight: 600; color: #1a1a1a; }
.ck-brow .bv.free { color: var(--color-secondary); }

.ck-total-row {
    display: flex; justify-content: space-between;
    align-items: center; padding-top: 12px;
    border-top: 2px solid #eee; margin: 8px 0 18px;
}

.ck-total-label {
    font-family: var(--font-serif);
    font-size: 15px; font-weight: 700;
    color: #1a1a1a; font-style: italic;
}

.ck-total-amount {
    font-size: 20px; font-weight: 800;
    color: var(--color-secondary); letter-spacing: -.3px;
}

/* Upload bukti */
.ck-upload-title {
    font-size: 11px; font-weight: 700;
    color: #888; text-transform: uppercase;
    letter-spacing: .6px; margin-bottom: 8px;
}

.ck-upload-box {
    border: 1.5px dashed #ccc;
    border-radius: 10px; padding: 16px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 6px; background: #fafafa;
    cursor: pointer; transition: border-color .2s, background .2s;
    margin-bottom: 14px; text-align: center;
}
.ck-upload-box:hover { border-color: var(--color-secondary); background: #f0f7f2; }
.ck-upload-box i { font-size: 22px; color: #ccc; }
.ck-upload-box:hover i { color: var(--color-secondary); }
.ck-upload-box span { font-size: 12px; color: #aaa; }
.ck-upload-box input[type="file"] { display: none; }

.ck-preview-img {
    width: 100%; max-height: 100px; object-fit: cover;
    border-radius: 8px; margin-bottom: 14px; display: none;
}

/* CTA */
.ck-btn-bayar {
    width: 100%; background: var(--color-secondary);
    color: #fff; border: none; border-radius: 12px;
    padding: 14px; font-size: 15px; font-weight: 700;
    font-family: var(--font-poppins); cursor: pointer;
    transition: background .2s, transform .1s, box-shadow .2s;
    display: flex; align-items: center; justify-content: center;
    gap: 8px; box-shadow: 0 4px 18px rgba(25,108,51,.3);
}
.ck-btn-bayar:hover {
    background: var(--color-secondary2);
    transform: translateY(-1px);
    box-shadow: 0 6px 22px rgba(25,108,51,.35);
}
.ck-btn-bayar:active { transform: translateY(0); }
.ck-btn-bayar.wa-btn { background: #25D366; box-shadow: 0 4px 18px rgba(37,211,102,.3); }
.ck-btn-bayar.wa-btn:hover { background: #1da851; }

/* Trust */
.ck-trust {
    display: flex; align-items: center;
    justify-content: center; gap: 14px; margin-top: 12px;
}
.ck-trust-item { display: flex; align-items: center; gap: 4px; font-size: 11px; color: #aaa; }
.ck-trust-item i { color: var(--color-secondary); font-size: 11px; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .chekout-wrap {
        grid-template-columns: 1fr;
        padding: 0 14px 60px;
        margin-top: 20px;
    }
    .ck-summary-card { position: static; }
    .chekout-steps   { display: none; }
}

@media (max-width: 480px) {
    .chekout-topbar { padding: 12px 16px; }
    .chekout-topbar h1 { font-size: 16px; }
    .ck-card         { padding: 16px; }
    .ck-summary-card { padding: 16px; }
    .ck-row2         { grid-template-columns: 1fr; }
    .ck-ewallet-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<!-- =====================================================
     HTML
====================================================== -->
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

                <div class="ck-mb">
                    <label class="ck-label">
                        Catatan Kurir
                        <span style="color:#bbb;font-weight:400;text-transform:none;letter-spacing:0"> (opsional)</span>
                    </label>
                    <div class="ck-input-wrap">
                        <i class="fas fa-sticky-note ck-icon" style="top:13px;transform:none"></i>
                        <input type="text" class="ck-input" placeholder="Titip di depan pintu...">
                    </div>
                </div>

                <div class="ck-toggle-row">
                    <div class="ck-toggle-label">
                        <i class="fas fa-bookmark"></i>
                        <div>
                            Simpan Alamat Ini
                            <small>Untuk pembelian berikutnya</small>
                        </div>
                    </div>
                    <div class="ck-toggle on" onclick="this.classList.toggle('on')">
                        <div class="ck-toggle-thumb"></div>
                    </div>
                </div>
            </div>

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
                    <div class="ck-metode-btn" id="btnEwallet" onclick="setMetode('Ewallet')">
                        <i class="fas fa-wallet"></i>
                        E-Wallet
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

                <!-- Panel E-Wallet -->
                <div id="panelEwallet" style="display:none">
                    <div class="ck-ewallet-grid">
                        <button class="ck-ewallet-btn" onclick="pilihWallet(this)">GoPay</button>
                        <button class="ck-ewallet-btn" onclick="pilihWallet(this)">OVO</button>
                        <button class="ck-ewallet-btn" onclick="pilihWallet(this)">Dana</button>
                        <button class="ck-ewallet-btn" onclick="pilihWallet(this)">ShopeePay</button>
                    </div>
                </div>

                <!-- Panel COD -->
                <div id="panelCod" style="display:none">
                    <div class="ck-rek-box">
                        <div>
                            <div class="ck-rek-bank">Bayar langsung saat barang tiba</div>
                            <div style="font-size:13px;color:#555;font-weight:600;margin-top:2px">
                                Kurir menghubungi 1 hari sebelum pengiriman
                            </div>
                        </div>
                        <i class="fas fa-truck" style="font-size:22px;color:var(--color-secondary);flex-shrink:0"></i>
                    </div>
                </div>
            </div>

        </div><!-- /kiri -->

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

            <div class="ck-brow">
                <span class="bl">Harga Produk</span>
                <span class="bv">Rp 10.000.000</span>
            </div>
            <div class="ck-brow">
                <span class="bl">Ongkos Kirim</span>
                <span class="bv free">Gratis</span>
            </div>
            <div class="ck-brow">
                <span class="bl">Biaya Penanganan</span>
                <span class="bv">Rp 50.000</span>
            </div>

            <div class="ck-total-row">
                <span class="ck-total-label">Total Tagihan</span>
                <span class="ck-total-amount">Rp 10.050.000</span>
            </div>

            <!-- Upload bukti (hanya transfer) -->
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

            <div class="ck-trust">
                <div class="ck-trust-item"><i class="fas fa-shield-alt"></i> SSL Aman</div>
                <div class="ck-trust-item"><i class="fas fa-check-circle"></i> Terverifikasi</div>
                <div class="ck-trust-item"><i class="fas fa-clock"></i> 24 Jam</div>
            </div>

        </div><!-- /summary -->

    </div><!-- /chekout-wrap -->
</div><!-- /chekout-page -->


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


<script>
function setMetode(val) {
    ['Transfer','Ewallet','Cod'].forEach(m => {
        document.getElementById('btn'   + m).classList.remove('active');
        document.getElementById('panel' + m).style.display = 'none';
    });
    document.getElementById('btn'   + val).classList.add('active');
    document.getElementById('panel' + val).style.display = 'block';

    document.getElementById('uploadSection').style.display =
        val === 'Transfer' ? 'block' : 'none';

    const btn = document.getElementById('btnBayar');
    if (val === 'Cod') {
        btn.innerHTML = '<i class="fab fa-whatsapp"></i> Konfirmasi via WhatsApp';
        btn.className = 'ck-btn-bayar wa-btn';
    } else {
        btn.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
        btn.className = 'ck-btn-bayar';
    }
}

function pilihWallet(el) {
    document.querySelectorAll('.ck-ewallet-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

function previewBukti(input) {
    const img = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
        r.readAsDataURL(input.files[0]);
    }
}

function salinRek() {
    const no = document.getElementById('rekeningNo').innerText.replace(/\s/g, '');
    navigator.clipboard.writeText(no).then(() => {
        const m = new bootstrap.Modal(document.getElementById('mSalin'));
        m.show();
        setTimeout(() => m.hide(), 1600);
    });
}

function bayar() {
    const btn = document.getElementById('btnBayar');
    const ori = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled  = true;
    setTimeout(() => {
        btn.innerHTML = ori;
        btn.disabled  = false;
        new bootstrap.Modal(document.getElementById('mSukses')).show();
    }, 1500);
}
</script>