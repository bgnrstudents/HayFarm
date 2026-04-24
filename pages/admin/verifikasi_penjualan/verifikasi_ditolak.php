<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - HayFarm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f0f2f5; padding: 40px; display: flex; flex-direction: column; align-items: center; }

        .ord-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); display: none; justify-content: center; 
            align-items: center; z-index: 1000; padding: 20px;
        }
        .ord-modal-overlay.active { display: flex; }

        .ord-modal-card {
            background: white; width: 100%; max-width: 440px;
            border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; max-height: 85vh; overflow: hidden;
        }

        .ord-scroll-content { padding: 30px 24px; overflow-y: auto; flex: 1; }
        .ord-scroll-content::-webkit-scrollbar { width: 6px; }
        .ord-scroll-content::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

        .ord-order-id { font-size: 22px; font-weight: 800; color: #111; }
        .ord-status-badge { color: #f11212; font-size: 12px; font-weight: 700; margin-bottom: 20px; }
        .ord-section-title { font-size: 13px; font-weight: 700; color: #374151; margin: 25px 0 15px; text-transform: uppercase; letter-spacing: 0.5px; }

        .ord-info-row { display: flex; gap: 12px; margin-bottom: 15px; align-items: center; }
        .ord-icon-box { width: 36px; height: 36px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; }
        .ord-label { font-size: 11px; color: #9ca3af; display: block; }
        .ord-value { font-size: 14px; color: #1f2937; font-weight: 600; }

        .lb-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; display: none; justify-content: center; align-items: center; }
        .lb-overlay.active { display: flex; }
    </style>
</head>
<body>

        <div class="ord-modal-card">
            <div class="ord-scroll-content">
                
                <div style="padding-left: 5px; margin-bottom: 25px;">
                    <h2 style="font-size: 24px; font-weight: 800; color: #111; margin: 0;">Detail Pesanan</h2>
                    <div style="width: 40px; height: 4px; background: #f11212; border-radius: 2px; margin-top: 6px;"></div>
                </div>

                <div class="ord-order-id">#ORD-2026-001</div>
                <div class="ord-status-badge">Ditolak</div>

                <div class="ord-section-title">Informasi Pelanggan</div>
                <div class="ord-info-row">
                    <div class="ord-icon-box"><i class="fas fa-user"></i></div>
                    <div><span class="ord-label">Nama Lengkap</span><span class="ord-value">Ahmad Ridwan</span></div>
                </div>
                <div class="ord-info-row">
                    <div class="ord-icon-box"><i class="fas fa-envelope"></i></div>
                    <div><span class="ord-label">Email</span><span class="ord-value">ahmad.ridwan@example.com</span></div>
                </div>
                <div class="ord-info-row">
                    <div class="ord-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                    <div><span class="ord-label">Alamat</span><span class="ord-value">Cianjur, Jawa Barat</span></div>
                </div>

                <div class="ord-section-title">Ringkasan Pembayaran</div>
                <div style="border-top: 1px solid #eee; padding-top: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <span style="font-size: 18px; font-weight: 800; color: #111;">Total</span>
                    <span style="font-size: 22px; font-weight: 800; color: #9ca3af; text-decoration: line-through;">
                        Rp 15.250.000
                    </span>
                </div>
                </div>

                <div style="margin-top: 10px;">
                    <button onclick="window.history.back()" 
        style="width: 100%; padding: 14px; border-radius: 10px; border: none; background: #f11212; color: white; font-weight: 700; cursor: pointer; font-size: 14px;">
    Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="lb-overlay" id="lightbox" onclick="this.classList.remove('active')">
        <img id="lbImg" src="" style="max-width: 90%; max-height: 80%; border-radius: 10px;">
    </div>

    <script>
        const modal = document.getElementById('modalDetail');
        const lb = document.getElementById('lightbox');
        const lbImg = document.getElementById('lbImg');

        function openOrderModal() { modal.classList.add('active'); }
        function closeOrderModal() { modal.classList.remove('active'); }

        function openLightbox(src) {
            lbImg.src = src;
            lb.classList.add('active');
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeOrderModal();
        });
    </script>
</body>
</html>