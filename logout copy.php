<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>

        /* === 1. Latar Belakang Gelap (Overlay) === */
        .logout-modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.45); /* Gelap transparan */
            display: flex; justify-content: center; align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease; /* Efek memudar saat muncul */
        }
        
        /* Class ini ditambahkan lewat JS untuk memunculkan modal */
        .logout-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* === 2. Kotak Modal (Card) === */
        .logout-modal-card {
            background: #ffffff;
            width: 440px; /* Lebar optimal untuk modal peringatan */
            max-width: 90vw;
            border-radius: 20px; /* Sudut membulat lembut sesuai gambar */
            padding: 40px; /* Padding dalam yang lapang */
            text-align: center; /* Teks di tengah */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            transform: translateY(20px); /* Efek sedikit naik saat muncul */
            transition: transform 0.3s ease;
        }
        .logout-modal-overlay.active .logout-modal-card {
            transform: translateY(0);
        }

        /* === 3. Ikon Segitiga Peringatan (Kustom) === */
        .logout-icon-area {
            font-size: 72px; /* Ukuran ikon besar */
            color: #f6e088; /* Warna kuning soft sesuai gambar Della */
            margin-bottom: 25px; /* Jarak ke teks */
            display: flex; justify-content: center;
        }

        /* === 4. Teks Pertanyaan (Menebal) === */
        .logout-text {
            font-size: 19px;
            font-weight: 600; /* Medium-bold */
            color: #1e1e1e;
            line-height: 1.5;
            margin-bottom: 35px; /* Jarak ke tombol */
        }

        /* === 5. Area Tombol (Berjejer) === */
        .logout-btn-group {
            display: flex;
            justify-content: center;
            gap: 15px; /* Jarak antar tombol */
        }

        /* === 6. Style Tombol (Kustom) === */
        .btn-modal {
            padding: 11px 0; /* Padding vertikal */
            width: 140px; /* Lebar tombol sama */
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px; /* Sudut tombol membulat */
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            outline: none;
        }

        /* Tombol 'Ya' (Hijaunya Della) */
        .btn-yes {
            background-color: #8fae9b; /* Warna hijau soft Della */
            color: #ffffff;
        }
        .btn-yes:hover {
            background-color: #7c9b88; /* Warna hijau sedikit gelap saat di-hover */
        }

        /* Tombol 'Batal' (Abunya Della) */
        .btn-cancel {
            background-color: #8c8c8c; /* Warna abu Della */
            color: #ffffff;
        }
        .btn-cancel:hover {
            background-color: #797979; /* Warna abu sedikit gelap saat di-hover */
        }

        /* Demo Trigger Button (Hanya untuk demo, Della nggak perlu bagian ini) */

    </style>
</head>
<body>
    <div class="logout-modal-overlay" id="delLogModal" onclick="closeLogModalOutside(event)">
        <div class="logout-modal-card">
            
            <div class="logout-icon-area">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="logout-text">
                Apakah kamu yakin ingin keluar dari akun ini ?
            </div>

            <div class="logout-btn-group">
                <button class="btn-modal btn-yes" onclick="prosesLogout()">Ya</button>
                <button class="btn-modal btn-cancel" onclick="closeLogModal()">Batal</button>
            </div>
            
        </div>
    </div>

    <script>
    const delLogModal = document.getElementById('delLogModal');

    // Buka Pop-up
    function openLogoutModal() {
        delLogModal.classList.add('active');
    }

    // Fungsi Tutup (Namanya sekarang sama dengan yang di tombol Batal)
    function closeLogModal() {
        delLogModal.classList.remove('active');
    }

    // Tutup jika klik di area gelap (Overlay)
    function closeLogModalOutside(event) {
        if (event.target === delLogModal) {
            closeLogModal();
        }
    }

    // Aksi Keluar (Ya)
    function prosesLogout() {
        alert('✅ Kamu berhasil keluar dari akun!');
        closeLogModal();
        window.location.href = "login.php";
    }

    // Tutup dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLogModal();
    });
</script>

</body>
</html>