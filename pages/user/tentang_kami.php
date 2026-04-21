<?php
// Memanggil Navbar dari folder components

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - HayFarm</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS Animate On Scroll -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =====================================================
           ROOT & BASE
        ===================================================== */
        :root {
            --hijau-tua   : #175d2b;
            --hijau-mid   : #196c33;
            --hijau-muda  : #4caf72;
            --hijau-bg    : #1a5e30;   /* background section visi */
            --teks-gelap  : #1a1a1a;
            --teks-abu    : #555555;
        }

        *  { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            overflow-x: hidden;
        }

        /* =====================================================
           LOADING SCREEN
        ===================================================== */
        #loader {
            position: fixed; inset: 0;
            background: var(--hijau-tua);
            z-index: 9999;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            transition: opacity .55s ease, visibility .55s ease;
        }
        #loader.sembunyikan { opacity: 0; visibility: hidden; }

        .loader-teks {
            font-size: 1.8rem; font-weight: 800;
            color: #fff; letter-spacing: 3px;
            margin-bottom: 20px;
            animation: kedip 1.2s infinite;
        }
        .loader-bar  { width: 180px; height: 4px; background: rgba(255,255,255,.2); border-radius: 99px; overflow: hidden; }
        .loader-isi  { height: 100%; background: var(--hijau-muda); border-radius: 99px; animation: isiBar 1.5s ease forwards; }

        @keyframes isiBar { from { width: 0; } to { width: 100%; } }
        @keyframes kedip  { 0%,100% { opacity:1; } 50% { opacity:.45; } }

        /* =====================================================
           HERO BANNER  — persis Figma (foto farm + teks putih)
        ===================================================== */
        .hero-tentang {
            width: 100%; height: 280px;
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            /* Gambar dari folder public/images — ganti path sesuai project */
            background: url('../../public/images/bghome.png') center/cover no-repeat;
        }

        /* Overlay gelap agar teks terbaca */
        .hero-tentang::before {
            content: '';
            position: absolute; inset: 0;
            background: rgba(8, 32, 12, 0.52);
        }

        /* Partikel mengambang */
        .hero-partikel span {
            position: absolute; display: block;
            border-radius: 50%;
            background: rgba(76, 175, 114, .20);
            animation: terbang linear infinite;
        }
        @keyframes terbang {
            0%   { transform: translateY(110%) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: .5; }
            100% { transform: translateY(-110vh) scale(1.4); opacity: 0; }
        }

        .hero-konten {
            position: relative; z-index: 2;
            animation: masukBawah .9s cubic-bezier(.22,.61,.36,1) both;
            animation-delay: 1.7s;
        }

        .hero-tentang h1 {
            font-size: 2.8rem; font-weight: 800;
            color: #fff; letter-spacing: 5px;
            text-transform: uppercase;
            text-shadow: 0 3px 20px rgba(0,0,0,.45);
        }

        .hero-tentang p {
            color: rgba(255,255,255,.82);
            font-size: .92rem; font-weight: 300;
            letter-spacing: 1.2px; margin-top: 8px;
            animation: masukAtas .9s cubic-bezier(.22,.61,.36,1) both;
            animation-delay: 1.9s;
        }

        .scroll-hint {
            position: absolute; bottom: 16px; left: 50%;
            transform: translateX(-50%); z-index: 3;
            animation: pantul 1.8s infinite;
        }
        .scroll-hint i { font-size: 1.5rem; color: rgba(255,255,255,.5); }

        @keyframes masukBawah { from { opacity:0; transform:translateY(-24px); } to { opacity:1; transform:translateY(0); } }
        @keyframes masukAtas  { from { opacity:0; transform:translateY(24px);  } to { opacity:1; transform:translateY(0); } }
        @keyframes pantul     { 0%,100% { transform:translateX(-50%) translateY(0); } 50% { transform:translateX(-50%) translateY(8px); } }

        /* =====================================================
           SECTION 1  —  TENTANG  (putih, teks kiri, foto kanan)
        ===================================================== */
        .section-tentang {
            padding: 80px 0 70px;
            background: #ffffff;
        }

        /* Label kecil hijau */
        .label-kecil {
            display: inline-block;
            font-size: .75rem; font-weight: 600;
            color: var(--hijau-tua); letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .section-tentang h2 {
            font-size: 2rem; font-weight: 800;
            color: var(--teks-gelap); line-height: 1.2;
            margin-bottom: 18px;
        }

        .section-tentang .teks-deskripsi {
            color: var(--teks-abu);
            font-size: .93rem; line-height: 1.9;
            text-align: justify;
        }

        .section-tentang .teks-deskripsi b { color: var(--hijau-tua); }

        /* ---- Foto tumpuk ---- */
        .foto-tumpuk {
            position: relative;
            height: 480px;
        }

        .foto-item {
            position: absolute;
            border-radius: 18px;
            border: 6px solid #ffffff;
            overflow: hidden;
            box-shadow: 0 16px 44px rgba(0,0,0,.17);
            background-size: cover;
            background-position: center;
            cursor: pointer;
            transition: transform .4s cubic-bezier(.22,.61,.36,1),
                        box-shadow .4s ease;
        }

        /* Overlay hover foto */
        .foto-item .overlay-foto {
            position: absolute; inset: 0;
            background: linear-gradient(0deg, rgba(23,93,43,.88) 0%, transparent 55%);
            opacity: 0;
            transition: opacity .3s ease;
            display: flex; align-items: flex-end; padding: 12px;
        }
        .overlay-foto span {
            color: #fff; font-size: .78rem; font-weight: 600;
        }
        .foto-item:hover .overlay-foto { opacity: 1; }
        .foto-item:hover {
            transform: scale(1.05) translateY(-10px) rotate(-1.2deg);
            box-shadow: 0 28px 68px rgba(0,0,0,.26);
            z-index: 10 !important;
        }

        /* Posisi masing-masing foto */
        .foto-1 {
            width: 275px; height: 182px;
            top: 14px; left: 0; z-index: 2;
            background-image:
                url('../../public/images/sapi_perah.jpg'),
                url('https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?w=600&auto=format');
        }
        .foto-2 {
            width: 238px; height: 308px;
            top: 52px; right: 8px; z-index: 1;
            background-image:
                url('../../public/images/bghome.png'),
                url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600&auto=format');
        }
        .foto-3 {
            width: 308px; height: 198px;
            bottom: 18px; left: 52px; z-index: 3;
            background-image:
                url('../../public/images/domba.jpg'),
                url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=600&auto=format');
        }

        /* =====================================================
           MODAL FOTO (popup saat klik gambar)
        ===================================================== */
        .modal-foto .modal-content {
            background: #081a0c; border: none;
            border-radius: 20px; overflow: hidden;
        }
        .modal-foto .modal-body { padding: 0; position: relative; }
        #modalGambar {
            width: 100%; height: 420px;
            object-fit: cover; display: block;
        }
        .caption-modal {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(0deg, rgba(8,26,12,.96) 0%, transparent 100%);
            padding: 50px 26px 22px; color: #fff;
        }
        .caption-modal h5 { font-size: 1.05rem; font-weight: 700; margin-bottom: 4px; }
        .caption-modal p  { font-size: .82rem; color: rgba(255,255,255,.7); margin: 0; }
        .tombol-tutup {
            position: absolute; top: 12px; right: 12px; z-index: 10;
            background: rgba(255,255,255,.15); border: none; color: #fff;
            border-radius: 50%; width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1rem;
            transition: background .2s; backdrop-filter: blur(6px);
        }
        .tombol-tutup:hover { background: rgba(255,255,255,.3); }

        /* =====================================================
           SECTION 2  —  VISI MISI  (background hijau gelap)
           Persis seperti Figma: hijau tua solid + teks putih
        ===================================================== */
        .section-visi {
            background-color: var(--hijau-bg);   /* hijau gelap solid */
            padding: 80px 0;
            color: #fff;
        }

        .visi-judul {
            font-size: 1.6rem; font-weight: 800;
            color: #ffffff; margin-bottom: 16px;
        }

        .visi-teks {
            color: rgba(255,255,255,.82);
            font-size: .9rem; line-height: 1.9;
            text-align: justify;
        }

        /* Garis pemisah vertikal antar kolom */
        .garis-tengah {
            width: 1px;
            background: rgba(255,255,255,.18);
        }

        /* =====================================================
           BACK TO TOP BUTTON
        ===================================================== */
        #btn-atas {
            position: fixed; bottom: 26px; right: 26px;
            width: 44px; height: 44px;
            background: var(--hijau-tua); color: #fff;
            border: none; border-radius: 50%; font-size: 1.1rem;
            display: none; align-items: center; justify-content: center;
            cursor: pointer; z-index: 998;
            box-shadow: 0 6px 22px rgba(23,93,43,.38);
            transition: transform .2s, box-shadow .2s;
        }
        #btn-atas:hover   { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(23,93,43,.48); }
        #btn-atas.tampil  { display: flex; animation: munculBtn .3s ease; }
        @keyframes munculBtn { from { transform:scale(0); opacity:0; } to { transform:scale(1); opacity:1; } }

        /* =====================================================
           RESPONSIVE
        ===================================================== */
        @media (max-width: 991.98px) {
            .foto-tumpuk { height: 380px; margin-top: 36px; }
            .foto-1 { width: 196px; height: 130px; }
            .foto-2 { width: 172px; height: 226px; right: 0; }
            .foto-3 { width: 218px; height: 142px; left: 18px; }
            .hero-tentang h1 { font-size: 2rem; letter-spacing: 3px; }
            .section-tentang h2 { font-size: 1.65rem; }
            .garis-tengah { display: none; }
        }
        @media (max-width: 575.98px) {
            .foto-tumpuk { height: 272px; }
            .foto-1 { width: 144px; height: 96px; }
            .foto-2 { width: 122px; height: 168px; right: 0; }
            .foto-3 { width: 154px; height: 104px; left: 6px; }
            .hero-tentang { height: 220px; }
            .hero-tentang h1 { font-size: 1.5rem; letter-spacing: 2px; }
        }
    </style>
</head>
<body>

<!-- ============================================================
     LOADING SCREEN
============================================================ -->
<div id="loader">
    <div class="loader-teks">&#127807; HayFarm</div>
    <div class="loader-bar"><div class="loader-isi"></div></div>
</div>

<!-- ============================================================
     HERO BANNER
============================================================ -->
<div class="hero-tentang mt-5">
    <div class="hero-partikel" id="partikelHero"></div>

    <div class="hero-konten px-3">
        <h1>Tentang Kami</h1>
        <p>Mengintegrasikan pendidikan, penelitian, dan produksi peternakan modern.</p>
    </div>

    <div class="scroll-hint">
        <i class="bi bi-chevron-double-down"></i>
    </div>
</div>

<!-- ============================================================
     SECTION 1 — TENTANG HAYFARM
============================================================ -->
<section class="section-tentang">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Kolom teks (kiri) -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="750">
                <span class="label-kecil">Tentang HayFarm</span>
                <h2>TEFA Feedlot dan<br>Sapi Perah</h2>
                <p class="teks-deskripsi">
                    Dibangun atas inisiatif Unit Pengelolaan Peternakan Politeknik Negeri Jember,
                    unit ini menjadi laboratorium hidup bagi mahasiswa untuk belajar pengelolaan
                    peternakan dan perawatan sapi secara langsung.
                </p>
                <p class="teks-deskripsi mt-3">
                    Melalui konsep <b>Teaching Factory (TEFA)</b>, kami mengintegrasikan pendidikan
                    dan unit bisnis untuk menghasilkan produk berkualitas tinggi dengan standar
                    modern dan inovatif bagi masyarakat.
                </p>
            </div>

            <!-- Kolom foto tumpuk (kanan) -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="750" data-aos-delay="120">
                <div class="foto-tumpuk">

                    <!-- Foto 1 — Sapi Perah -->
                    <div class="foto-item foto-1"
                         data-bs-toggle="modal"
                         data-bs-target="#modalFoto"
                         data-gambar="../../public/images/sapi_perah.jpg"
                         data-fallback="https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?w=900&auto=format"
                         data-judul="Sapi Perah HayFarm"
                         data-deskripsi="Koleksi sapi perah unggulan yang dikelola oleh mahasiswa Politeknik Negeri Jember.">
                        <div class="overlay-foto"><span>&#128247; Sapi Perah</span></div>
                    </div>

                    <!-- Foto 2 — Kandang -->
                    <div class="foto-item foto-2"
                         data-bs-toggle="modal"
                         data-bs-target="#modalFoto"
                         data-gambar="../../public/images/bghome.png"
                         data-fallback="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=900&auto=format"
                         data-judul="Kandang Feedlot"
                         data-deskripsi="Kandang modern berkapasitas 30–40 ekor dengan sistem ventilasi dan manajemen pakan terpadu.">
                        <div class="overlay-foto"><span>&#128247; Kandang Feedlot</span></div>
                    </div>

                    <!-- Foto 3 — Domba -->
                    <div class="foto-item foto-3"
                         data-bs-toggle="modal"
                         data-bs-target="#modalFoto"
                         data-gambar="../../public/images/domba.jpg"
                         data-fallback="https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=900&auto=format"
                         data-judul="Domba HayFarm"
                         data-deskripsi="Selain sapi, HayFarm mengelola domba sebagai bagian dari program diversifikasi ternak.">
                        <div class="overlay-foto"><span>&#128247; Domba</span></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     MODAL POPUP FOTO
============================================================ -->
<div class="modal fade modal-foto" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button class="tombol-tutup" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img id="modalGambar" src="" alt="Foto HayFarm">
                <div class="caption-modal">
                    <h5 id="modalJudul"></h5>
                    <p  id="modalDeskripsi"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     SECTION 2 — VISI MISI (background hijau gelap)
============================================================ -->
<section class="section-visi">
    <div class="container">
        <div class="row g-0">

            <!-- Kolom Visi & Misi -->
            <div class="col-md-5 pe-md-5" data-aos="fade-right" data-aos-duration="750">
                <h3 class="visi-judul">Visi &amp; Misi</h3>
                <p class="visi-teks">
                    Selain fokus pada produksi dan bisnis, peternakan berpedoman pada sarana
                    pembelajaran, pemberdayaan pemulihan, dan pengelolaan sumber daya lokal.
                    Hanya mengutamakan keuntungan, tetapi juga menyertakan kualitas dan
                    kepedulian terhadap lingkungan melalui budaya pengembangan berkelanjutan.
                </p>
            </div>

            <!-- Garis pemisah vertikal -->
            <div class="garis-tengah col-md-auto d-none d-md-block mx-4"></div>

            <!-- Kolom Teaching Factory -->
            <div class="col-md-5 ps-md-2" data-aos="fade-left" data-aos-duration="750" data-aos-delay="120">
                <h3 class="visi-judul">Teaching Factory (TEFA)</h3>
                <p class="visi-teks">
                    Sebagai TEFA, kami tidak hanya berfungsi untuk unit bisnis, tetapi juga
                    menjadi tempat pembelajaran, penelitian, dan pengembangan masyarakat.
                    Dengan kapasitas kandang 30–40 ekor sapi, kami mengelola laboratorium
                    hidup bagi mahasiswa untuk belajar dan berprestasi langsung di lapangan.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     TOAST WELCOME
============================================================ -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9000;">
    <div id="toastWelcome"
         class="toast align-items-center border-0"
         role="alert"
         style="background:var(--hijau-tua); color:#fff;">
        <div class="d-flex">
            <div class="toast-body fw-semibold" style="font-size:.85rem;">
                &#127807; Selamat datang di halaman Tentang Kami!
            </div>
            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Back To Top -->
<button id="btn-atas" title="Kembali ke atas"
        onclick="window.scrollTo({top:0, behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- ============================================================
     SCRIPTS
============================================================ -->
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
/* ---- 1. Inisialisasi AOS ---- */
AOS.init({
    duration : 720,
    easing   : 'ease-out-cubic',
    once     : true,
    offset   : 60
});

/* ---- 2. Loading screen hilang setelah halaman load ---- */
window.addEventListener('load', function () {
    setTimeout(function () {
        document.getElementById('loader').classList.add('sembunyikan');

        // Tampilkan toast welcome
        var elToast = document.getElementById('toastWelcome');
        var toast   = new bootstrap.Toast(elToast, { delay: 3800 });
        toast.show();
    }, 1600);
});

/* ---- 3. Partikel mengambang di Hero ---- */
(function buatPartikel() {
    var wrap = document.getElementById('partikelHero');
    for (var i = 0; i < 12; i++) {
        var s  = document.createElement('span');
        var sz = Math.random() * 38 + 10;
        s.style.cssText =
            'width:'  + sz + 'px;'
          + 'height:' + sz + 'px;'
          + 'left:'   + (Math.random() * 100) + '%;'
          + 'bottom:-' + sz + 'px;'
          + 'animation-duration:' + (Math.random() * 12 + 8) + 's;'
          + 'animation-delay:'    + (Math.random() * 6)      + 's;';
        wrap.appendChild(s);
    }
})();

/* ---- 4. Modal foto — isi konten secara dinamis ---- */
document.getElementById('modalFoto').addEventListener('show.bs.modal', function (e) {
    var pemicu = e.relatedTarget;
    if (!pemicu) return;

    var urlGambar  = pemicu.dataset.gambar   || pemicu.dataset.fallback || '';
    var urlFallbck = pemicu.dataset.fallback  || '';
    var judulFoto  = pemicu.dataset.judul     || '';
    var deskrip    = pemicu.dataset.deskripsi || '';

    var elGambar = document.getElementById('modalGambar');

    // Coba load gambar lokal; kalau gagal pakai fallback Unsplash
    elGambar.onerror = function () {
        if (urlFallbck) { elGambar.src = urlFallbck; }
        elGambar.onerror = null;
    };
    elGambar.src = urlGambar;

    document.getElementById('modalJudul').textContent     = judulFoto;
    document.getElementById('modalDeskripsi').textContent = deskrip;
});

/* ---- 5. Back To Top muncul saat scroll ---- */
var btnAtas = document.getElementById('btn-atas');
window.addEventListener('scroll', function () {
    if (window.scrollY > 300) {
        btnAtas.classList.add('tampil');
    } else {
        btnAtas.classList.remove('tampil');
    }
});
</script>

</body>
</html>