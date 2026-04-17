<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    background-color: #f3fcf5;
    color: #374151;
    line-height: 1.5;
    padding: 24px;
}

.container {
    max-width: 920px;
    margin: 0 auto;
}

/* Header */
.header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-left: 8px;
}

.back-button {
    font-size: 20px;
    margin-right: 16px;
    cursor: pointer;
    color: #6b7280;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: 0.2s;
}

.back-button:hover {
    background-color: #e5e7eb;
}

.header-content h1 {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 2px;
}

.header-content p {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

/* Tabs */
.tabs {
    display: flex;
    gap: 32px;
    margin-bottom: 20px;
    padding-left: 8px;
}

.tab {
    padding-bottom: 12px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    color: #6b7280;
    position: relative;
    transition: all 0.2s;
}

.tab:hover {
    color: #111827;
}

.tab.active {
    color: #111827;
    font-weight: 700;
}

.tab.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background-color: #10b981;
    border-radius: 3px 3px 0 0;
}

/* Form Container */
.form-container {
    background: #ffffff;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
}

.form-row {
    display: flex;
    gap: 24px;
    margin-bottom: 24px;
}

.form-group {
    flex: 1;
    margin-bottom: 24px;
}

.form-group.full-width {
    width: 100%;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.required {
    color: #ef4444;
}

.form-input,
.form-select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    color: #111827;
    background-color: #ffffff;
    transition: all 0.2s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.form-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
}

/* --- CSS BARU UNTUK UPLOAD AREA --- */
.upload-wrapper {
    position: relative;
}

.upload-box {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 32px 20px;
    text-align: center;
    background-color: #f9fafb;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 120px;
}

.upload-box:hover {
    border-color: #10b981;
    background-color: #f0fdf4;
}

.upload-icon {
    width: 48px;
    height: 48px;
    color: #9ca3af;
    margin-bottom: 12px;
    transition: all 0.2s;
}

.upload-box:hover .upload-icon {
    color: #10b981;
    transform: translateY(-2px);
}

.upload-text {
    font-size: 14px;
    color: #374151;
    font-weight: 500;
    margin-bottom: 4px;
}

.upload-hint {
    font-size: 12px;
    color: #9ca3af;
}

/* Image Preview */
.image-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    position: relative;
}

.image-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.btn-remove:hover {
    background: #dc2626;
}
/* -------------------------------- */

/* Status Section */
.status-divider {
    height: 1px;
    background-color: #e5e7eb;
    margin-bottom: 16px;
}

.status-options {
    display: flex;
    gap: 16px;
}

.status-option {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid #e5e7eb;
    background-color: #fff;
}

.status-option.available.active {
    background-color: #ecfdf5;
    border-color: #a7f3d0;
    color: #065f46;
}

.status-option.available.active .icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border: 1px solid #065f46;
    border-radius: 50%;
    font-size: 10px;
}

.status-option.unavailable {
    background-color: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}

.status-option.unavailable .icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border: 1px solid #92400e;
    border-radius: 50%;
    font-size: 10px;
}

/* Buttons */
.button-group {
    display: flex;
    gap: 16px;
    margin-top: 24px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel {
    background-color: #ffffff;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    background-color: #f3f4f6;
}

.btn-save {
    background-color: #10b981;
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-save:hover {
    background-color: #059669;
    box-shadow: 0 6px 14px rgba(16, 185, 129, 0.4);
}

/* Tips Container */
.tips-container {
    background-color: #eff6ff;
    border-radius: 12px;
    padding: 20px 24px;
}

.tips-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.bulb-icon {
    font-size: 18px;
}

.tips-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e3a8a;
    margin: 0;
}

.tips-list {
    list-style: none;
    padding-left: 4px;
}

.tips-list li {
    font-size: 14px;
    color: #1e40af;
    margin-bottom: 6px;
    padding-left: 16px;
    position: relative;
}

.tips-list li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #1e40af;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 640px) {
    body {
        padding: 16px;
    }

    .form-row {
        flex-direction: column;
        gap: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .tabs {
        gap: 20px;
        overflow-x: auto;
    }

    .status-options {
        flex-direction: column;
    }

    .button-group {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
    </style>

</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="back-button">←</div>
            <div class="header-content">
                <h1>Tambah Produk</h1>
                <p>Data Produk</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active">Hewan</div>
            <div class="tab">Susu</div>
            <div class="tab">Rumput</div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis Produk <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Rumput">
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Rumput Surga">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Harga <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Rp 20.000.000">
                </div>

                <div class="form-group">
                    <label class="form-label">Stok <span class="required">*</span></label>
                    <select class="form-select">
                        <option value="">Pilih stok</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
            </div>

            <!-- BAGIAN YANG DIUBAH: Upload Foto -->
            <div class="form-group full-width">
                <label class="form-label">Tambah Foto Produk/Hewan</label>
                <div class="upload-wrapper">
                    <!-- Input file tersembunyi -->
                    <input type="file" id="fileInput" hidden accept="image/*" onchange="previewImage(event)">
                    
                    <!-- Area Upload yang bisa diklik -->
                    <div class="upload-box" id="uploadBox" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon">
                            <!-- Icon Upload SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        </div>
                        <p class="upload-text">Klik untuk menambahkan foto</p>
                        <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                    </div>

                    <!-- Area Preview Foto (Muncul setelah upload) -->
                    <div class="image-preview" id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Preview">
                        <button type="button" class="btn-remove" onclick="removeImage()">×</button>
                    </div>
                </div>
            </div>
            <!-- Selesai Bagian Upload -->

            <div class="form-group full-width">
                <label class="form-label">Status Produk <span class="required">*</span></label>
                <div class="status-divider"></div>
                <div class="status-options">
                    <div class="status-option available active">
                        <span class="icon">✓</span>
                        <span>Tersedia</span>
                    </div>
                    <div class="status-option unavailable">
                        <span class="icon">!</span>
                        <span>Tidak Tersedia</span>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button class="btn btn-cancel">Batal</button>
                <button class="btn btn-save">Simpan Data</button>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="tips-container">
            <div class="tips-header">
                <span class="bulb-icon"></span>
                <h3 class="tips-title">Tips Pengisian</h3>
            </div>
            <ul class="tips-list">
                <li>Pastikan harga Hewan sesuai dengan catatan identifikasi ternak</li>
                <li>Pilih status yang sesuai dengan kondisi lapangan</li>
                <li>Catat diagnosa dan tindakan secara detail untuk referensi masa depan</li>
            </ul>
        </div>
    </div>

    <!-- Script sederhana untuk preview foto -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'flex';
                    document.getElementById('uploadBox').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('fileInput').value = "";
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('uploadBox').style.display = 'flex';
        }
    </script>
</body>
</html>