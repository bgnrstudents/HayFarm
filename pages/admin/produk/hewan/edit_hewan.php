<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <style>
        /* 1. Background Halaman Utama (Hijau Muda) */
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f3fcf5; /* Warna Hijau Muda */
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #374151;
            line-height: 1.5;
            padding: 40px 24px;
        }

        .container { max-width: 920px; margin: 0 auto; }

        /* 2. Container Utama (Kartu Putih Besar) */
        .form-container {
            background: #ffffff; /* Background PUTIH untuk Header & Form */
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        /* 3. Header Styling (Bagian Atas Kartu) */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb; /* Garis pemisah tipis */
        }

        .back-button {
            font-size: 20px; margin-right: 16px; cursor: pointer; color: #6b7280;
            width: 32px; height: 32px; display: flex; align-items: center;
            justify-content: center; border-radius: 50%; transition: 0.2s;
        }
        .back-button:hover { background-color: #f3f4f6; }
        .header-content h1 { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 2px; }
        .header-content p { font-size: 14px; color: #6b7280; font-weight: 500; }

        /* Tabs */
        .tabs { display: flex; gap: 32px; margin-bottom: 24px; }
        .tab {
            padding-bottom: 12px; cursor: pointer; font-size: 16px; font-weight: 500;
            color: #6b7280; position: relative; transition: all 0.2s;
        }
        .tab:hover { color: #111827; }
        .tab.active { color: #111827; font-weight: 700; }
        .tab.active::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%;
            height: 3px; background-color: #10b981; border-radius: 3px 3px 0 0;
        }

        /* Form Sections */
        .form-section { display: none; animation: fadeIn 0.3s ease; }
        .form-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .form-row { display: flex; gap: 24px; margin-bottom: 24px; }
        .form-group { flex: 1; margin-bottom: 24px; }
        .form-group.full-width { width: 100%; }

        .form-label {
            display: block; font-size: 14px; font-weight: 600;
            color: #374151; margin-bottom: 8px;
        }
        .required { color: #ef4444; }

        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb;
            border-radius: 10px; font-size: 14px; color: #111827;
            background-color: #ffffff; transition: all 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .form-select {
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 36px;
        }
        .form-textarea { min-height: 80px; resize: vertical; }

        /* Upload Area */
        .upload-wrapper { position: relative; }
        .upload-box {
            border: 2px dashed #d1d5db; border-radius: 12px; padding: 32px 20px;
            text-align: center; background-color: #f9fafb; cursor: pointer;
            transition: all 0.2s; display: flex; flex-direction: column;
            align-items: center; justify-content: center; min-height: 120px;
        }
        .upload-box:hover { border-color: #10b981; background-color: #f0fdf4; }
        .upload-icon {
            width: 48px; height: 48px; color: #9ca3af; margin-bottom: 12px;
            transition: all 0.2s;
        }
        .upload-box:hover .upload-icon { color: #10b981; transform: translateY(-2px); }
        .upload-text { font-size: 14px; color: #374151; font-weight: 500; margin-bottom: 4px; }
        .upload-hint { font-size: 12px; color: #9ca3af; }

        .image-preview {
            display: flex; align-items: center; justify-content: center;
            margin-top: 10px; position: relative;
        }
        .image-preview img {
            max-width: 100%; max-height: 200px; border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-remove {
            position: absolute; top: -8px; right: -8px; background: #ef4444;
            color: white; border: none; border-radius: 50%; width: 24px;
            height: 24px; cursor: pointer; font-size: 16px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .btn-remove:hover { background: #dc2626; }

        /* Status Section */
        .status-divider { height: 1px; background-color: #e5e7eb; margin-bottom: 16px; }
        .status-options { display: flex; gap: 16px; }
        .status-option {
            flex: 1; display: flex; align-items: center; justify-content: center;
            gap: 10px; padding: 14px 20px; border-radius: 10px; cursor: pointer;
            transition: all 0.2s; font-size: 14px; font-weight: 500;
            border: 1px solid #e5e7eb; background-color: #fff;
        }
        /* Style untuk Status Tersedia (Hijau) */
        .status-option.available.active {
            background-color: #ecfdf5; border-color: #a7f3d0; color: #065f46;
        }
        .status-option.available.active .status-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 20px; height: 20px; background-color: #065f46;
            border-radius: 50%; color: white; font-size: 12px; font-weight: bold;
        }
        /* Style untuk Status Tidak Tersedia (Kuning/Coklat) */
        .status-option.unavailable {
            background-color: #fffbeb; border-color: #fde68a; color: #92400e;
        }
        .status-option.unavailable .status-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 20px; height: 20px; background-color: #92400e;
            border-radius: 50%; color: white; font-size: 12px; font-weight: bold;
        }

        /* Buttons */
        .button-group { display: flex; gap: 16px; margin-top: 24px; }
        .btn {
            padding: 12px 24px; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .btn-cancel {
            background-color: #ffffff; color: #374151; border: 1px solid #d1d5db;
        }
        .btn-cancel:hover { background-color: #f3f4f6; }
        .btn-save {
            background-color: #10b981; color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .btn-save:hover {
            background-color: #059669;
            box-shadow: 0 6px 14px rgba(16, 185, 129, 0.4);
        }

        /* Tips Container (Di luar kotak putih) */
        .tips-container {
            background-color: #eff6ff; border-radius: 12px;
            padding: 20px 24px;
        }
        .tips-header { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
        .tips-title { font-size: 16px; font-weight: 700; color: #1e3a8a; margin: 0; }
        .tips-list { list-style: none; padding-left: 4px; }
        .tips-list li {
            font-size: 14px; color: #1e40af; margin-bottom: 6px;
            padding-left: 16px; position: relative;
        }
        .tips-list li:before {
            content: "•"; position: absolute; left: 0;
            color: #1e40af; font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 640px) {
            body { padding: 20px 16px; }
            .form-row { flex-direction: column; gap: 0; }
            .form-group { margin-bottom: 20px; }
            .tabs { gap: 20px; overflow-x: auto; padding-bottom: 5px; }
            .status-options { flex-direction: column; }
            .button-group { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- SATU KARTU PUTIH BESAR (Mencakup Header & Form) -->
        <div class="form-container">
            
            <!-- HEADER (Sekarang di dalam kotak putih) -->
            <div class="header">
                <div class="back-button" onclick="window.history.back()">←</div>
                <div class="header-content">
                    <h1>Edit Data Produk</h1>
                    <p>Data Produk</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" data-tab="hewan" onclick="switchTab('hewan')">Hewan</div>
                <div class="tab" data-tab="rumput" onclick="switchTab('rumput')">Rumput</div>
                <div class="tab" data-tab="susu" onclick="switchTab('susu')">Susu</div>
            </div>
            
            <!-- ===== FORM HEWAN ===== -->
            <form id="form-hewan" class="form-section active" onsubmit="handleSubmit(event, 'hewan')">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Hewan <span class="required">*</span></label>
                        <select class="form-select" required>
                            <option value="">Pilih jenis hewan</option>
                            <option value="sapi">Sapi</option>
                            <option value="kambing">Kambing</option>
                            <option value="domba">Domba</option>
                            <option value="kerbau">Kerbau</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Produk <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Contoh: Sapi Perah FH" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Berat Badan (kg)</label>
                        <input type="number" class="form-input" placeholder="Contoh: 450">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Rp 20.000.000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah/Stok <span class="required">*</span></label>
                    <input type="number" class="form-input" placeholder="Contoh: 4" min="1" required>
                </div>

                <!-- Upload Foto -->
                <div class="form-group full-width">
                    <label class="form-label">Foto Hewan</label>
                    <div class="upload-wrapper">
                        <input type="file" id="file-hewan" class="file-input" hidden accept="image/*" onchange="previewImage(event, 'hewan')">
                        <div class="upload-box" onclick="document.getElementById('file-hewan').click()">
                            <div class="upload-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p class="upload-text">Klik untuk menambahkan foto</p>
                            <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                        </div>
                        <div class="image-preview" id="preview-hewan" style="display: none;">
                            <img id="img-hewan" src="" alt="Preview">
                            <button type="button" class="btn-remove" onclick="removeImage('hewan')">×</button>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group full-width">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <div class="status-divider"></div>
                    <div class="status-options">
                        <div class="status-option available active" onclick="selectStatus(this, 'hewan')">
                            <span class="status-icon">✓</span><span>Tersedia</span>
                        </div>
                        <div class="status-option unavailable" onclick="selectStatus(this, 'hewan')">
                            <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                        </div>
                    </div>
                    <input type="hidden" id="status-hewan" value="tersedia">
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-cancel" onclick="resetForm('hewan')">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan Data</button>
                </div>
            </form>

            <!-- ===== FORM RUMPUT ===== -->
            <form id="form-rumput" class="form-section" onsubmit="handleSubmit(event, 'rumput')">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Rumput <span class="required">*</span></label>
                        <select class="form-select" required>
                            <option value="">Pilih jenis rumput</option>
                            <option value="odot">Rumput Odot</option>
                            <option value="gajah">Rumput Gajah</option>
                            <option value="pakan">Rumput Pakan</option>
                            <option value="lapangan">Rumput Lapangan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Produk <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Contoh: Rumput Odot Premium" required>
                    </div>
                </div>

                <!-- TANGGAL PRODUKSI SUDAH DIHAPUS SESUAI PERMINTAAN -->

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga per Kg <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Rp 2.500" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok (Kg) <span class="required">*</span></label>
                        <input type="number" class="form-input" placeholder="Contoh: 500" min="0" required>
                    </div>
                </div>

                <!-- Upload Foto -->
                <div class="form-group full-width">
                    <label class="form-label">Foto Produk</label>
                    <div class="upload-wrapper">
                        <input type="file" id="file-rumput" class="file-input" hidden accept="image/*" onchange="previewImage(event, 'rumput')">
                        <div class="upload-box" onclick="document.getElementById('file-rumput').click()">
                            <div class="upload-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p class="upload-text">Klik untuk menambahkan foto</p>
                            <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                        </div>
                        <div class="image-preview" id="preview-rumput" style="display: none;">
                            <img id="img-rumput" src="" alt="Preview">
                            <button type="button" class="btn-remove" onclick="removeImage('rumput')">×</button>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group full-width">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <div class="status-divider"></div>
                    <div class="status-options">
                        <div class="status-option available active" onclick="selectStatus(this, 'rumput')">
                            <span class="status-icon">✓</span><span>Tersedia</span>
                        </div>
                        <div class="status-option unavailable" onclick="selectStatus(this, 'rumput')">
                            <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                        </div>
                    </div>
                    <input type="hidden" id="status-rumput" value="tersedia">
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-cancel" onclick="resetForm('rumput')">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan Data</button>
                </div>
            </form>

            <!-- ===== FORM SUSU ===== -->
            <form id="form-susu" class="form-section" onsubmit="handleSubmit(event, 'susu')">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Susu <span class="required">*</span></label>
                        <select class="form-select" required>
                            <option value="">Pilih jenis susu</option>
                            <option value="segar">Susu Segar</option>
                            <option value="pasteurisasi">Susu Pasteurisasi</option>
                            <option value="uht">Susu UHT</option>
                            <option value="fermentasi">Susu Fermentasi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Produk <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Contoh: Susu Segar Premium" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Produksi <span class="required">*</span></label>
                        <input type="date" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Kadaluarsa <span class="required">*</span></label>
                        <input type="date" class="form-input" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga per Liter <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Rp 15.000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok (Liter) <span class="required">*</span></label>
                        <input type="number" class="form-input" placeholder="Contoh: 200" min="0" required>
                    </div>
                </div>

                <!-- Upload Foto -->
                <div class="form-group full-width">
                    <label class="form-label">Foto Produk</label>
                    <div class="upload-wrapper">
                        <input type="file" id="file-susu" class="file-input" hidden accept="image/*" onchange="previewImage(event, 'susu')">
                        <div class="upload-box" onclick="document.getElementById('file-susu').click()">
                            <div class="upload-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p class="upload-text">Klik untuk menambahkan foto</p>
                            <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                        </div>
                        <div class="image-preview" id="preview-susu" style="display: none;">
                            <img id="img-susu" src="" alt="Preview">
                            <button type="button" class="btn-remove" onclick="removeImage('susu')">×</button>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group full-width">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <div class="status-divider"></div>
                    <div class="status-options">
                        <div class="status-option available active" onclick="selectStatus(this, 'susu')">
                            <span class="status-icon">✓</span><span>Tersedia</span>
                        </div>
                        <div class="status-option unavailable" onclick="selectStatus(this, 'susu')">
                            <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                        </div>
                    </div>
                    <input type="hidden" id="status-susu" value="tersedia">
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-cancel" onclick="resetForm('susu')">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan Data</button>
                </div>
            </form>

        </div> <!-- End Form Container (Putih) -->

        <!-- Tips Section (Di luar kotak putih) -->
        <div class="tips-container">
            <div class="tips-header">
                <h3 class="tips-title" id="tips-title">Tips Pengisian - Hewan</h3>
            </div>
            <ul class="tips-list" id="tips-list">
                <li>Pastikan harga sesuai dengan catatan identifikasi ternak</li>
                <li>Pilih status yang sesuai dengan kondisi kesehatan hewan</li>
                <li>Lampirkan foto yang jelas untuk memudahkan identifikasi</li>
            </ul>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const tipsData = {
            hewan: {
                title: "Tips Pengisian - Hewan",
                list: [
                    "Pastikan harga sesuai dengan catatan identifikasi ternak",
                    "Pilih status yang sesuai dengan kondisi kesehatan hewan",
                    "Lampirkan foto yang jelas untuk memudahkan identifikasi"
                ]
            },
            rumput: {
                title: "Tips Pengisian - Rumput",
                list: [
                    "Foto kondisi rumput membantu pembeli menilai kualitas",
                    "Pastikan stok diperbarui secara berkala",
                    "Cantumkan harga yang kompetitif"
                ]
            },
            susu: {
                title: "Tips Pengisian - Susu",
                list: [
                    "Perhatikan tanggal kadaluarsa untuk produk susu",
                    "Sebutkan suhu penyimpanan yang direkomendasikan",
                    "Informasi harga harus sesuai dengan kualitas produk"
                ]
            }
        };

        function switchTab(tabName) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
                if(tab.dataset.tab === tabName) tab.classList.add('active');
            });
            document.querySelectorAll('.form-section').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById(`form-${tabName}`).classList.add('active');
            updateTips(tabName);
        }

        function updateTips(tabName) {
            const tips = tipsData[tabName];
            document.getElementById('tips-title').textContent = tips.title;
            const listEl = document.getElementById('tips-list');
            listEl.innerHTML = tips.list.map(tip => `<li>${tip}</li>`).join('');
        }

        function previewImage(event, type) {
            const file = event.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`img-${type}`).src = e.target.result;
                    document.getElementById(`preview-${type}`).style.display = 'flex';
                    document.querySelector(`#form-${type} .upload-box`).style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage(type) {
            document.getElementById(`file-${type}`).value = "";
            document.getElementById(`preview-${type}`).style.display = 'none';
            document.querySelector(`#form-${type} .upload-box`).style.display = 'flex';
        }

        function selectStatus(element, type) {
            const parent = element.parentElement;
            parent.querySelectorAll('.status-option').forEach(opt => opt.classList.remove('active'));
            element.classList.add('active');
            const status = element.classList.contains('available') ? 'tersedia' : 'tidak-tersedia';
            document.getElementById(`status-${type}`).value = status;
        }

        function resetForm(type) {
            if(confirm('Apakah Anda yakin ingin membatalkan? Data yang belum disimpan akan hilang.')) {
                document.getElementById(`form-${type}`).reset();
                removeImage(type);
                const statusOpts = document.querySelectorAll(`#form-${type} .status-option`);
                statusOpts[0].classList.add('active');
                statusOpts[1].classList.remove('active');
                document.getElementById(`status-${type}`).value = 'tersedia';
            }
        }

        function handleSubmit(event, type) {
            event.preventDefault();
            const formData = { type: type, status: document.getElementById(`status-${type}`).value };
            const btn = event.target.querySelector('.btn-save');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Menyimpan...';
            btn.disabled = true;
            setTimeout(() => {
                alert(`Data ${type.toUpperCase()} berhasil disimpan!\nStatus: ${formData.status}`);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateTips('hewan');
        });
    </script>
</body>
</html>