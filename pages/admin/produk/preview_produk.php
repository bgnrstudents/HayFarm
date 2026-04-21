<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Preview Produk</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #e8e8e8;
      padding: 40px 20px;
      min-height: 100vh;
    }

    /* CONTAINER - Flexbox untuk layout kartu */
    .container {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
      align-items: flex-start;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* CARD - Diperbaiki agar tidak overlap */
    .card {
      background-color: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      width: 360px;
      min-width: 360px;
      flex-shrink: 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* HEADER */
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 20px;
      background-color: #ffffff;
    }

    .header-title {
      font-size: 16px;
      font-weight: 700;
      color: #333333;
    }

    .header-id {
      background-color: #2a2a2a;
      color: #ffffff;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
    }

    /* CATEGORY */
    .category {
      padding: 0 20px 12px;
      color: #666666;
      font-size: 14px;
    }

    /* IMAGE */
    .card-image {
      width: 100%;
      height: 180px;
      overflow: hidden;
    }

    .card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* DETAIL TITLE */
    .detail-title {
      padding: 16px 20px 12px;
      font-size: 13px;
      font-weight: 700;
      color: #333333;
      letter-spacing: 0.5px;
    }

    /* DETAIL GRID */
    .detail-grid {
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px 20px;
    }

    .detail-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .detail-item label {
      font-size: 12px;
      color: #888888;
      font-weight: 500;
    }

    .detail-item .value {
      font-size: 14px;
      font-weight: 700;
      color: #333333;
    }

    .detail-item.empty {
      visibility: hidden;
    }

    /* STATUS */
    .status-available {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #2ecc40 !important;
      font-weight: 700 !important;
    }

    .dot {
      width: 8px;
      height: 8px;
      background-color: #2ecc40;
      border-radius: 50%;
      display: inline-block;
    }

    /* BUTTON */
    .btn-close {
      display: block;
      width: calc(100% - 40px);
      margin: 20px 20px 20px;
      padding: 14px;
      background-color: #2ecc40;
      color: #ffffff;
      font-size: 15px;
      font-weight: 700;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .btn-close:hover {
      background-color: #27ae35;
    }

    /* RESPONSIVE - Mobile */
    @media (max-width: 1150px) {
      .container {
        flex-direction: column;
        align-items: center;
        gap: 25px;
      }
      .card {
        width: 100%;
        max-width: 360px;
        min-width: auto;
      }
    }
  </style>
</head>
<body>

  <div class="container">

    <!-- CARD 1 - RUMPUT -->
    <div class="card">
      <div class="card-header">
        <span class="header-title">Preview Produk</span>
        <span class="header-id">ID: S-R-001</span>
      </div>
      <p class="category">Rumput</p>
      <div class="card-image">
        <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600" alt="Rumput">
      </div>
      <h3 class="detail-title">DETAIL PRODUK RUMPUT</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <label>Nama Produk</label>
          <p class="value">Rumput</p>
        </div>
        <div class="detail-item">
          <label>Nama Produk</label>
          <p class="value">Rumput Odot</p>
        </div>
        <div class="detail-item">
          <label>Tgl Produksi</label>
          <p class="value">05 Maret 2026</p>
        </div>
        <div class="detail-item">
          <label>Harga</label>
          <p class="value">Rp 2.500 / Kg</p>
        </div>
        <div class="detail-item">
          <label>Stok</label>
          <p class="value">500 Kg</p>
        </div>
        <div class="detail-item">
          <label>Status</label>
          <p class="value status-available">
            <span class="dot"></span> Tersedia
          </p>
        </div>
      </div>
      <button class="btn-close">Tutup Preview</button>
    </div>

    <!-- CARD 2 - HEWAN -->
    <div class="card">
      <div class="card-header">
        <span class="header-title">Preview Produk</span>
        <span class="header-id">ID: S-H-001</span>
      </div>
      <p class="category">Hewan</p>
      <div class="card-image">
        <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600" alt="Sapi Perah">
      </div>
      <h3 class="detail-title">DETAIL PRODUK HEWAN</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <label>Kategori</label>
          <p class="value">Hewan</p>
        </div>
        <div class="detail-item">
          <label>Nama Produk</label>
          <p class="value">Sapi Perah</p>
        </div>
        <div class="detail-item">
          <label>Jumlah</label>
          <p class="value">4 Ekor</p>
        </div>
        <div class="detail-item">
          <label>Harga</label>
          <p class="value">Rp 20.000.000</p>
        </div>
        <div class="detail-item empty"></div>
        <div class="detail-item">
          <label>Status</label>
          <p class="value status-available">
            <span class="dot"></span> Tersedia
          </p>
        </div>
      </div>
      <button class="btn-close">Tutup Preview</button>
    </div>

    <!-- CARD 3 - SUSU (DIPINDAHKAN KE DALAM CONTAINER) -->
    <div class="card">
      <div class="card-header">
        <span class="header-title">Preview Produk</span>
        <span class="header-id">ID: S-S-001</span>
      </div>
      <p class="category">Produk Olahan</p>
      <div class="card-image">
        <img src="https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600" alt="Susu Segar">
      </div>
      <h3 class="detail-title">DETAIL PRODUK SUSU</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <label>Kategori</label>
          <p class="value">Produk Olahan</p>
        </div>
        <div class="detail-item">
          <label>Nama Produk</label>
          <p class="value">Susu Segar</p>
        </div>
        <div class="detail-item">
          <label>Tgl Produksi</label>
          <p class="value">10 Maret 2026</p>
        </div>
        <div class="detail-item">
          <label>Harga</label>
          <p class="value">Rp 15.000 / Liter</p>
        </div>
        <div class="detail-item">
          <label>Stok</label>
          <p class="value">200 Liter</p>
        </div>
        <div class="detail-item">
          <label>Status</label>
          <p class="value status-available">
            <span class="dot"></span> Tersedia
          </p>
        </div>
      </div>
      <button class="btn-close">Tutup Preview</button>
    </div>

  </div> <!-- END CONTAINER -->

</body>
</html>