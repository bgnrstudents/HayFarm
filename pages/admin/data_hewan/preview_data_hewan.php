<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Hewan HayFarm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }

    
        .btn-simpan-trigger:hover { background: #00695c; transform: translateY(-2px); }

        /* === 2. MODAL OVERLAY (Background Gelap) === */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); 
            display: none; /* Sembunyi secara default */
            justify-content: center; align-items: center; 
            z-index: 1000; padding: 20px;
            backdrop-filter: blur(3px);
        }
        .modal-overlay.active { display: flex; }

        /* === 3. CARD PREVIEW (Modal Box) === */
        .preview-card {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid #e2e8f0;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Detail Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px; }
        .id-badge { background: #1e293b; color: white; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; }

        /* Area Gambar */
        .image-container {
            width: 100%; height: 190px; margin: 20px 0;
            background: #fdfdfd; border: 1.5px dashed #cbd5e1;
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .image-container img { width: 100%; height: 100%; object-fit: cover; }

        /* Grid Informasi */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin: 15px 0 25px; }
        .info-box { background: #f8fafc; padding: 12px 16px; border-radius: 12px; border: 1px solid #f1f5f9; }
        .label { font-size: 10px; color: #94a3b8; display: block; margin-bottom: 4px; text-transform: uppercase; font-weight: 700; }
        .value { font-size: 14px; color: #1e293b; font-weight: 700; }

        /* Status & Button */
        .status-pill { display: flex; align-items: center; gap: 6px; }
        .dot { width: 8px; height: 8px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 8px rgba(34, 197, 94, 0.5); }

        .btn-confirm {
            width: 100%; padding: 16px; border-radius: 14px; border: none;
            background: #00cc44; color: white; font-weight: 700;
            cursor: pointer; font-size: 16px; box-shadow: 0 4px 14px rgba(0, 204, 68, 0.3);
            transition: 0.3s;
        }
        .btn-confirm:hover { background: #00b33c; transform: translateY(-2px); }
    </style>
</head>
<body>

        <div class="preview-card">
            <div class="header">
                <div>
                    <h2 style="font-size: 22px; font-weight: 800; color: #111;">Preview Hewan</h2>
                    <p style="font-size: 13px; color: #64748b;">Data hewan ternak aktif</p>
                </div>
                <div class="id-badge">ID:001</div>
            </div>

            <div class="image-container">
                <img src="https://images.unsplash.com/photo-1546445317-29f4545e9d53?q=80&w=400" alt="Sapi">
            </div>

            <p style="font-size: 11px; font-weight: 800; color: #94a3b8; letter-spacing: 1px; margin-bottom: 12px;">INFORMASI TERNAK</p>
            
            <div class="info-grid">
                <div class="info-box"><span class="label">Jenis Hewan</span><span class="value">Sapi Perah</span></div>
                <div class="info-box"><span class="label">Berat Badan</span><span class="value">150 (Kg)</span></div>
                <div class="info-box"><span class="label">Tanggal Lahir</span><span class="value">05 Maret 2026</span></div>
                <div class="info-box"><span class="label">Usia</span><span class="value">5 Tahun</span></div>
                <div class="info-box"><span class="label">No Kandang</span><span class="value">01</span></div>
                <div class="info-box">
                    <span class="label">Status</span>
                    <div class="status-pill">
                        <div class="dot"></div>
                        <span style="font-size: 14px; color: #22c55e; font-weight: 700;">Tersedia</span>
                    </div>
                </div>
            </div>

            <button class="btn-confirm" onclick="closePreview()">Tutup Preview</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalPreview');

        function openPreview() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Kunci scroll belakang
        }

        function closePreview() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto'; // Aktifkan scroll lagi
        }

        // Tutup jika klik area hitam di luar card
        window.onclick = function(event) {
            if (event.target == modal) {
                closePreview();
            }
        }
    </script>

</body>
</html>