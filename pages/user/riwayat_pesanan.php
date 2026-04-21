<?php ?>
<section class="riwayat-header">
    <div class="riwayat-header-bg"></div>
    <div class="riwayat-header-gradient"></div>
    <div class="riwayat-header-content">
        <div class="container">
            <a href="?page=user/produk" class="btn-kembali">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <h1>Riwayat Transaksi</h1>
            <p>Lihat dan pantau status pembelian Anda</p>
        </div>
    </div>
</section>

<div class="riwayat-body">
    <div class="container">

        <!-- TOOLBAR -->
        <div class="riwayat-toolbar">
            <div class="filter-status">
                <label>Status:</label>
                <select class="filter-select" id="filterStatus" onchange="filterKartu()">
                    <option value="semua">Semua</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari nama ternak..."
                    oninput="filterKartu()">
            </div>
        </div>

        <!-- GRID KARTU -->
        <div class="riwayat-grid" id="riwayatGrid">

            <!-- Kartu 1 -->
            <div class="order-card" data-status="menunggu" data-nama="Sapi Perah E4">
                <div class="order-card-top">
                    <span class="status-badge menunggu">
                        <i class="fa-solid fa-clock" style="font-size:10px"></i> Menunggu Konfirmasi
                    </span>
                    <span class="order-date">12 April 2026</span>
                </div>
                <div class="order-card-mid">
                    <img src="public/images/bghome.png" alt="Sapi Perah" class="order-thumb">
                    <div class="order-info">
                        <p class="order-nama">Sapi Perah E4</p>
                        <div class="order-detail-row">
                            <span class="dl">Informasi Pengiriman</span><span class="dv"></span>
                            <span class="dl">Tanggal</span><span class="dv">12 April 2026</span>
                            <span class="dl">Nama</span><span class="dv">Budi Santoso</span>
                            <span class="dl">Alamat</span><span class="dv">Jl. Mawar No.12, Jember</span>
                            <span class="dl">No. HP</span><span class="dv">+62 812 3456 7890</span>
                        </div>
                    </div>
                </div>
                <div class="order-card-footer">
                    <div class="order-total">
                        Total Pembayaran
                        <strong>Rp 15.000.000</strong>
                    </div>
                    <a href="#" class="btn-detail">
                        Detail <i class="fa-solid fa-arrow-right" style="font-size:10px"></i>
                    </a>
                </div>
            </div>

 

        </div><!-- /riwayat-grid -->
    </div>
</div>

<script>
function filterKartu() {
    const status = document.getElementById('filterStatus').value;
    const query  = document.getElementById('searchInput').value.toLowerCase().trim();
    const cards  = document.querySelectorAll('#riwayatGrid .order-card');

    let visible = 0;
    cards.forEach(card => {
        const matchStatus = status === 'semua' || card.dataset.status === status;
        const matchSearch = card.dataset.nama.toLowerCase().includes(query);
        const show = matchStatus && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    // Tampilkan empty state kalau tidak ada hasil
    let empty = document.getElementById('emptyState');
    if (visible === 0) {
        if (!empty) {
            empty = document.createElement('div');
            empty.id = 'emptyState';
            empty.className = 'empty-state';
            empty.innerHTML = `
                <i class="fa-solid fa-box-open"></i>
                <p>Tidak ada transaksi yang ditemukan.</p>`;
            document.getElementById('riwayatGrid').appendChild(empty);
        }
    } else if (empty) {
        empty.remove();
    }
}
</script>