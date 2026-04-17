<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - MP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-green: #1a8a4a;
            --primary-green-hover: #157a40;
            --light-green-bg: #e8f5e9;
            --light-green-border: #c8e6c9;
            --success-green: #4caf50;
            --warning-orange: #ff9800;
            --warning-orange-bg: #fff3e0;
            --warning-orange-border: #ffe0b2;
            --success-green-bg: #e8f5e9;
            --success-green-border: #c8e6c9;
            --text-dark: #333;
            --text-muted: #888;
            --bg-light: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 30px 20px;
        }

        .page-container {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* Header */
        .page-header {
            display: flex;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
        }

        .btn-back {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-dark);
            cursor: pointer;
            padding: 4px 8px 4px 0;
            margin-right: 12px;
            display: flex;
            align-items: center;
        }

        .btn-back:hover {
            color: var(--primary-green);
        }

        .header-title h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .header-title .breadcrumb {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
        }

        .header-title .breadcrumb a {
            color: var(--primary-green);
            text-decoration: none;
        }

        /* Tabs */
        .tab-nav {
            display: flex;
            background: var(--light-green-bg);
            padding: 0;
            border-bottom: 1px solid var(--light-green-border);
        }

        .tab-nav .tab-item {
            flex: 1;
            text-align: center;
        }

        .tab-nav .tab-link {
            display: block;
            padding: 14px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #666;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-nav .tab-link:hover {
            color: var(--primary-green);
        }

        .tab-nav .tab-link.active {
            color: var(--primary-green);
            font-weight: 600;
            border-bottom-color: var(--primary-green);
            background: rgba(255, 255, 255, 0.5);
        }

        /* Tab Content */
        .tab-content-area {
            padding: 24px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* Form Box */
        .form-box {
            background: #f8fdf8;
            border: 1px solid #e0f0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-row:last-child {
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-group label .required {
            color: #e74c3c;
            margin-left: 2px;
        }

        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            color: var(--text-dark);
            transition: border-color 0.3s;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(26, 138, 74, 0.1);
        }

        .form-control-custom::placeholder {
            color: #bbb;
        }

        select.form-control-custom {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 10px;
            padding-right: 36px;
        }

        /* Upload Area */
        .upload-area {
            border: 2px dashed #d0e0d0;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #fafcfa;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: var(--primary-green);
            background: #f0faf0;
        }

        .upload-area .upload-icon {
            font-size: 28px;
            color: #bbb;
            margin-bottom: 8px;
        }

        .upload-area .upload-text {
            font-size: 12px;
            color: #999;
        }

        .upload-area .upload-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--primary-green);
        }

        /* Status Section */
        .status-section {
            margin-bottom: 24px;
        }

        .status-section .status-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 10px;
            display: block;
        }

        .status-section .status-label .required {
            color: #e74c3c;
        }

        .status-options {
            display: flex;
            gap: 12px;
        }

        .status-option {
            flex: 1;
        }

        .status-option input[type="radio"] {
            display: none;
        }

        .status-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            border: 2px solid #eee;
        }

        .status-option label .status-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #fff;
        }

        /* Tersedia - Green */
        .status-option.terseleksi input:checked + label {
            background: var(--success-green-bg);
            border-color: var(--success-green-border);
            color: var(--success-green);
        }

        .status-option.terseleksi label .status-icon {
            background: var(--success-green);
        }

        /* Tidak Tersedia - Orange */
        .status-option.tidak-terseleksi input:checked + label {
            background: var(--warning-orange-bg);
            border-color: var(--warning-orange-border);
            color: var(--warning-orange);
        }

        .status-option.tidak-terseleksi label .status-icon {
            background: var(--warning-orange);
        }

        /* Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .btn-cancel {
            padding: 10px 24px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            color: #666;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        .btn-save {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            background: var(--primary-green);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-save:hover {
            background: var(--primary-green-hover);
            box-shadow: 0 4px 12px rgba(26, 138, 74, 0.3);
        }

        /* Tips Section */
        .tips-section {
            background: #f0f4ff;
            border: 1px solid #d0d8f0;
            border-radius: 10px;
            padding: 16px 20px;
        }

        .tips-section .tips-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tips-section ul {
            margin: 0;
            padding-left: 18px;
        }

        .tips-section ul li {
            font-size: 12px;
            color: #666;
            margin-bottom: 6px;
            line-height: 1.6;
        }

        .tips-section ul li:last-child {
            margin-bottom: 0;
        }

        /* Preview Image */
        .upload-preview {
            position: relative;
            width: 100%;
            max-height: 150px;
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        .upload-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-preview .remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            body {
                padding: 0;
            }

            .page-container {
                border-radius: 0;
                max-width: 100%;
                min-height: 100vh;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .tab-content-area {
                padding: 16px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tab-pane.active {
            animation: fadeIn 0.3s ease;
        }

        /* Tooltip */
        .form-group .info-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #999;
            font-size: 10px;
            text-align: center;
            line-height: 16px;
            cursor: help;
            margin-left: 4px;
        }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Header -->
        <div class="page-header">
            <button class="btn-back" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-title">
                <h5>Tambah Produk</h5>
                <nav class="breadcrumb">
                    <a href="#">Data Produk</a>
                </nav>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button class="tab-link active" data-tab="hewan">
                Hewan
            </button>
            <button class="tab-link" data-tab="susu">
                Susu
            </button>
            <button class="tab-link" data-tab="rumput">
                Rumput
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content-area">

            <!-- ========== TAB: HEWAN ========== -->
            <div class="tab-pane active" id="tab-hewan">
                <div class="form-box">
                    <!-- Row 1: Jenis Produk & Nama Produk -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Jenis Produk <span class="required">*</span></label>
                            <select class="form-control-custom" id="jenisProdukHewan">
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                                <option value="kerbau">Kerbau</option>
                                <option value="ayam">Ayam</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" placeholder="Rumput Gajah" id="namaProdukHewan">
                        </div>
                    </div>

                    <!-- Row 2: Harga & Stok -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Harga <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" placeholder="Rp 0" value="Rp 20.000.000" id="hargaHewan">
                        </div>
                        <div class="form-group">
                            <label>Stok <span class="required">*</span></label>
                            <select class="form-control-custom" id="stokHewan">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Foto -->
                    <div class="form-group" style="margin-top: 16px;">
                        <label>Tambah Foto Produk/Hewan</label>
                        <div class="upload-area" onclick="document.getElementById('fotoHewan').click()">
                            <div class="upload-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="upload-text">Klik atau seret gambar ke sini</div>
                            <div class="upload-label" style="margin-top: 4px;">Upload Foto</div>
                        </div>
                        <input type="file" id="fotoHewan" accept="image/*" style="display: none;" onchange="previewImage(this, 'previewHewan')">
                        <div class="upload-preview" id="previewHewan">
                            <img src="" alt="Preview" id="imgHewan">
                            <button class="remove-btn" onclick="removeImage('fotoHewan', 'previewHewan')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Status Produk -->
                <div class="status-section">
                    <label class="status-label">Status Produk <span class="required">*</span></label>
                    <div class="status-options">
                        <div class="status-option terseleksi">
                            <input type="radio" name="statusHewan" id="statusHewanTersedia" value="tersedia" checked>
                            <label for="statusHewanTersedia">
                                <span class="status-icon"><i class="fas fa-check"></i></span>
                                Tersedia
                            </label>
                        </div>
                        <div class="status-option tidak-terseleksi">
                            <input type="radio" name="statusHewan" id="statusHewanTidakTersedia" value="tidak_tersedia">
                            <label for="statusHewanTidakTersedia">
                                <span class="status-icon"><i class="fas fa-times"></i></span>
                                Tidak Tersedia
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-cancel" onclick="resetForm('hewan')">Batal</button>
                    <button class="btn-save" onclick="saveData('hewan')">
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>

                <!-- Tips -->
                <div class="tips-section">
                    <div class="tips-title">
                        <i class="fas fa-lightbulb"></i>
                        Tips Pengisian
                    </div>
                    <ul>
                        <li>Pastikan setiap hewan sesuai dengan standar kesehatan ternak</li>
                        <li>Input data yang sesuai dengan kondisi lapangan</li>
                        <li>Gambar hewan yang baik membantu meningkatkan kualitas penjualan</li>
                    </ul>
                </div>
            </div>

            <!-- ========== TAB: SUSU ========== -->
            <div class="tab-pane" id="tab-susu">
                <div class="form-box">
                    <!-- Row 1: Nama Produk & Tanggal Produksi -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" placeholder="Masukkan nama produk" id="namaProdukSusu">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Produksi Produk <span class="required">*</span></label>
                            <input type="date" class="form-control-custom" id="tanggalSusu">
                        </div>
                    </div>

                    <!-- Row 2: Harga & Stok -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Harga <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" placeholder="Rp 0" value="Rp 20.000" id="hargaSusu">
                        </div>
                        <div class="form-group">
                            <label>Stok <span class="required">*</span></label>
                            <select class="form-control-custom" id="stokSusu">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Foto -->
                    <div class="form-group" style="margin-top: 16px;">
                        <label>Tambah Foto Produk/Hewan</label>
                        <div class="upload-area" onclick="document.getElementById('fotoSusu').click()">
                            <div class="upload-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="upload-text">Klik atau seret gambar ke sini</div>
                            <div class="upload-label" style="margin-top: 4px;">Upload Foto</div>
                        </div>
                        <input type="file" id="fotoSusu" accept="image/*" style="display: none;" onchange="previewImage(this, 'previewSusu')">
                        <div class="upload-preview" id="previewSusu">
                            <img src="" alt="Preview" id="imgSusu">
                            <button class="remove-btn" onclick="removeImage('fotoSusu', 'previewSusu')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Status Produk -->
                <div class="status-section">
                    <label class="status-label">Status Produk <span class="required">*</span></label>
                    <div class="status-options">
                        <div class="status-option terseleksi">
                            <input type="radio" name="statusSusu" id="statusSusuTersedia" value="tersedia" checked>
                            <label for="statusSusuTersedia">
                                <span class="status-icon"><i class="fas fa-check"></i></span>
                                Tersedia
                            </label>
                        </div>
                        <div class="status-option tidak-terseleksi">
                            <input type="radio" name="statusSusu" id="statusSusuTidakTersedia" value="tidak_tersedia">
                            <label for="statusSusuTidakTersedia">
                                <span class="status-icon"><i class="fas fa-times"></i></span>
                                Tidak Tersedia
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-cancel" onclick="resetForm('susu')">Batal</button>
                    <button class="btn-save" onclick="saveData('susu')">
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>

                <!-- Tips -->
                <div class="tips-section">
                    <div class="tips-title">
                        <i class="fas fa-lightbulb"></i>
                        Tips Pengisian
                    </div>
                    <ul>
                        <li>Pastikan harga sesuai dengan standar kualitas susu</li>
                        <li>Input tanggal yang sesuai dengan kondisi lapangan</li>
                        <li>Gambar produk yang baik akan meningkatkan kualitas penjualan</li>
                    </ul>
                </div>
            </div>

            <!-- ========== TAB: RUMPUT ========== -->
            <div class="tab-pane" id="tab-rumput">
                <div class="form-box">
                    <!-- Row 1: Nama Produk & Tanggal Produksi -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" placeholder="Rumput Gajah" id="namaProdukRumput">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Produksi Produk <span class="required">*</span></label>
                            <input type="date" class="form-control-custom" id="tanggalRumput">
                        </div>
                    </div>

                    <!-- Row 2: Harga & Stok -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Harga <span class="required">*</span></label>
                            <input type="text" class="form-control-custom" placeholder="Rp 0" value="Rp 20.000.000" id="hargaRumput">
                        </div>
                        <div class="form-group">
                            <label>Stok <span class="required">*</span></label>
                            <select class="form-control-custom" id="stokRumput">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Foto -->
                    <div class="form-group" style="margin-top: 16px;">
                        <label>Tambah Foto Produk/Hewan</label>
                        <div class="upload-area" onclick="document.getElementById('fotoRumput').click()">
                            <div class="upload-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="upload-text">Klik atau seret gambar ke sini</div>
                            <div class="upload-label" style="margin-top: 4px;">Upload Foto</div>
                        </div>
                        <input type="file" id="fotoRumput" accept="image/*" style="display: none;" onchange="previewImage(this, 'previewRumput')">
                        <div class="upload-preview" id="previewRumput">
                            <img src="" alt="Preview" id="imgRumput">
                            <button class="remove-btn" onclick="removeImage('fotoRumput', 'previewRumput')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Status Produk -->
                <div class="status-section">
                    <label class="status-label">Status Produk <span class="required">*</span></label>
                    <div class="status-options">
                        <div class="status-option terseleksi">
                            <input type="radio" name="statusRumput" id="statusRumputTersedia" value="tersedia" checked>
                            <label for="statusRumputTersedia">
                                <span class="status-icon"><i class="fas fa-check"></i></span>
                                Tersedia
                            </label>
                        </div>
                        <div class="status-option tidak-terseleksi">
                            <input type="radio" name="statusRumput" id="statusRumputTidakTersedia" value="tidak_tersedia">
                            <label for="statusRumputTidakTersedia">
                                <span class="status-icon"><i class="fas fa-times"></i></span>
                                Tidak Tersedia
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-cancel" onclick="resetForm('rumput')">Batal</button>
                    <button class="btn-save" onclick="saveData('rumput')">
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>

                <!-- Tips -->
                <div class="tips-section">
                    <div class="tips-title">
                        <i class="fas fa-lightbulb"></i>
                        Tips Pengisian
                    </div>
                    <ul>
                        <li>Pastikan harga sesuai dengan kualitas dan kuantitas rumput</li>
                        <li>Input data yang sesuai dengan kondisi lapangan</li>
                        <li>Gambar rumput yang baik akan membantu meningkatkan kualitas penjualan</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==================== TAB SWITCHING ====================
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.addEventListener('click', function () {
                // Remove active from all tabs
                document.querySelectorAll('.tab-link').forEach(t => t.classList.remove('active'));
                // Add active to clicked tab
                this.classList.add('active');

                // Hide all panes
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

                // Show target pane
                const targetTab = this.getAttribute('data-tab');
                document.getElementById('tab-' + targetTab).classList.add('active');
            });
        });

        // ==================== IMAGE PREVIEW ====================
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById(previewId);
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                    preview.style.display = 'block';
                    // Hide upload area
                    input.parentElement.querySelector('.upload-area').style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // ==================== REMOVE IMAGE ====================
        function removeImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const uploadArea = input.parentElement.querySelector('.upload-area');

            input.value = '';
            preview.style.display = 'none';
            preview.querySelector('img').src = '';
            uploadArea.style.display = 'flex';
        }

        // ==================== FORMAT CURRENCY ====================
        document.querySelectorAll('input[placeholder="Rp 0"]').forEach(input => {
            input.addEventListener('input', function () {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value) {
                    this.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
                }
            });
        });

        // ==================== SAVE DATA ====================
        function saveData(type) {
            const labels = {
                'hewan': 'Hewan',
                'susu': 'Susu',
                'rumput': 'Rumput'
            };

            // Collect form data
            let data = {};
            const prefix = type;

            if (type === 'hewan') {
                data = {
                    jenis: document.getElementById('jenisProdukHewan').value,
                    nama: document.getElementById('namaProdukHewan').value,
                    harga: document.getElementById('hargaHewan').value,
                    stok: document.getElementById('stokHewan').value,
                    status: document.querySelector('input[name="statusHewan"]:checked')?.value
                };

                // Validation
                if (!data.jenis || !data.nama || !data.harga || !data.stok) {
                    showNotification('Mohon lengkapi semua field yang wajib diisi!', 'error');
                    return;
                }
            } else if (type === 'susu') {
                data = {
                    nama: document.getElementById('namaProdukSusu').value,
                    tanggal: document.getElementById('tanggalSusu').value,
                    harga: document.getElementById('hargaSusu').value,
                    stok: document.getElementById('stokSusu').value,
                    status: document.querySelector('input[name="statusSusu"]:checked')?.value
                };

                if (!data.nama || !data.tanggal || !data.harga || !data.stok) {
                    showNotification('Mohon lengkapi semua field yang wajib diisi!', 'error');
                    return;
                }
            } else if (type === 'rumput') {
                data = {
                    nama: document.getElementById('namaProdukRumput').value,
                    tanggal: document.getElementById('tanggalRumput').value,
                    harga: document.getElementById('hargaRumput').value,
                    stok: document.getElementById('stokRumput').value,
                    status: document.querySelector('input[name="statusRumput"]:checked')?.value
                };

                if (!data.nama || !data.tanggal || !data.harga || !data.stok) {
                    showNotification('Mohon lengkapi semua field yang wajib diisi!', 'error');
                    return;
                }
            }

            console.log('Data ' + labels[type] + ':', data);
            showNotification('Data ' + labels[type] + ' berhasil disimpan!', 'success');
        }

        // ==================== RESET FORM ====================
        function resetForm(type) {
            if (!confirm('Yakin ingin membatalkan? Semua data akan direset.')) return;

            if (type === 'hewan') {
                document.getElementById('jenisProdukHewan').selectedIndex = 0;
                document.getElementById('namaProdukHewan').value = '';
                document.getElementById('hargaHewan').value = 'Rp 20.000.000';
                document.getElementById('stokHewan').selectedIndex = 0;
                removeImage('fotoHewan', 'previewHewan');
                document.getElementById('statusHewanTersedia').checked = true;
            } else if (type === 'susu') {
                document.getElementById('namaProdukSusu').value = '';
                document.getElementById('tanggalSusu').value = '';
                document.getElementById('hargaSusu').value = 'Rp 20.000';
                document.getElementById('stokSusu').selectedIndex = 0;
                removeImage('fotoSusu', 'previewSusu');
                document.getElementById('statusSusuTersedia').checked = true;
            } else if (type === 'rumput') {
                document.getElementById('namaProdukRumput').value = '';
                document.getElementById('tanggalRumput').value = '';
                document.getElementById('hargaRumput').value = 'Rp 20.000.000';
                document.getElementById('stokRumput').selectedIndex = 0;
                removeImage('fotoRumput', 'previewRumput');
                document.getElementById('statusRumputTersedia').checked = true;
            }
        }

        // ==================== NOTIFICATION ====================
        function showNotification(message, type) {
            // Remove existing notification
            const existing = document.querySelector('.custom-notification');
            if (existing) existing.remove();

            const notification = document.createElement('div');
            notification.className = 'custom-notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 14px 24px;
                border-radius: 10px;
                font-family: 'Poppins', sans-serif;
                font-size: 14px;
                font-weight: 500;
                z-index: 9999;
                animation: slideInRight 0.4s ease;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 350px;
            `;

            if (type === 'success') {
                notification.style.background = '#e8f5e9';
                notification.style.color = '#2e7d32';
                notification.style.border = '1px solid #a5d6a7';
                notification.innerHTML = `<i class="fas fa-check-circle" style="font-size:18px;"></i> ${message}`;
            } else {
                notification.style.background = '#ffebee';
                notification.style.color = '#c62828';
                notification.style.border = '1px solid #ef9a9a';
                notification.innerHTML = `<i class="fas fa-exclamation-circle" style="font-size:18px;"></i> ${message}`;
            }

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.4s ease';
                setTimeout(() => notification.remove(), 400);
            }, 3000);
        }

        // Add notification animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // ==================== DRAG & DROP ====================
        document.querySelectorAll('.upload-area').forEach(area => {
            area.addEventListener('dragover', function (e) {
                e.preventDefault();
                this.style.borderColor = '#1a8a4a';
                this.style.background = '#f0faf0';
            });

            area.addEventListener('dragleave', function (e) {
                e.preventDefault();
                this.style.borderColor = '#d0e0d0';
                this.style.background = '#fafcfa';
            });

            area.addEventListener('drop', function (e) {
                e.preventDefault();
                this.style.borderColor = '#d0e0d0';
                this.style.background = '#fafcfa';

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = this.parentElement.querySelector('input[type="file"]');
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
</body>
</html>