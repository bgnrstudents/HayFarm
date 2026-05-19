<!-- HERO BANNER -->
<div class="hero-tentang">
    <div class="hero-konten">
        <h1>Tentang Kami</h1>
        <p>Mengintegrasikan pendidikan, penelitian, dan produksi peternakan modern.</p>
    </div>
    <div class="scroll-hint">
        <i class="bi bi-chevron-double-down"></i>
    </div>
</div>

<!-- SECTION 1 — TENTANG HAYFARM -->
<section class="section-tentang">
    <div class="container">
        <div class="row align-items-center">

            <!-- TEXT -->
            <div class="col-lg-6">
                <span class="label-kecil">Tentang HayFarm</span>

                <h2 class="judul-besar">
                    TEFA Feedlot dan<br>
                    Sapi Perah
                </h2>

                <p class="teks-deskripsi">
                    Dibangun atas inisiatif Unit Pengelolaan Peternakan Politeknik Negeri Jember,
                    unit ini menjadi laboratorium hidup bagi mahasiswa untuk belajar pengelolaan
                    peternakan dan perawatan sapi secara langsung.
                </p>

                <p class="teks-deskripsi">
                    Melalui konsep <b>Teaching Factory (TEFA)</b>, kami mengintegrasikan pendidikan
                    dan unit bisnis untuk menghadirkan produk berkualitas tinggi dengan standar
                    modern dan inovatif bagi masyarakat.
                </p>
            </div>

            <!-- IMAGE STACK -->
            <div class="col-lg-6 mt-lg-0 mt-5">
                <div class="image-composition">
                    <!-- MAIN IMAGE -->
                    <img src="public/images/bgheader_produk.png" class="img-main">

                    <!-- FLOATING -->
                    <img src="public/images/farel_perah.jpg" class="img img-top-left">
                    <img src="public/images/bghome.png" class="img img-right">
        <img src="public/images/bgheader_produk.png" class="img img-bottom-left">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- MODAL POPUP FOTO -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-foto-content">
            <div class="modal-body p-0 position-relative">
                <button class="tombol-tutup" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img id="modalGambar" src="" alt="Foto HayFarm">
                <div class="caption-modal">
                    <h5 id="modalJudul"></h5>
                    <p id="modalDeskripsi"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2 — VISI MISI -->
<section class="section-visi">
    <div class="container">
        <div class="row align-items-start g-5">

            <div class="col-md-5">
                <div class="visi-card">
                    <div class="visi-icon">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <h3 class="visi-judul">Visi &amp; Misi</h3>
                    <p class="visi-teks">
                        Selain fokus pada produksi dan bisnis, peternakan berpedoman pada sarana
                        pembelajaran, pemberdayaan pemulihan, dan pengelolaan sumber daya lokal.
                        Tidak hanya mengutamakan keuntungan, tetapi juga menyertakan kualitas dan
                        kepedulian terhadap lingkungan melalui budaya pengembangan berkelanjutan.
                    </p>
                </div>
            </div>

            <div class="col-md-2 d-none d-md-flex justify-content-center">
                <div class="garis-tengah"></div>
            </div>

            <div class="col-md-5">
                <div class="visi-card">
                    <div class="visi-icon">
                        <i class="bi bi-building-fill"></i>
                    </div>
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
    </div>
</section>
<script>
    // Modal foto — ambil data dari elemen yang diklik
    document.getElementById('modalFoto').addEventListener('show.bs.modal', function(e) {
        var pemicu = e.relatedTarget;
        if (!pemicu) return;

        var urlGambar = pemicu.dataset.gambar || '';
        var judulFoto = pemicu.dataset.judul || '';
        var deskrip = pemicu.dataset.deskripsi || '';

        var elGambar = document.getElementById('modalGambar');

        elGambar.onerror = function() {
            // Fallback jika gambar tidak ditemukan
            elGambar.src = 'https://placehold.co/800x420/175d2b/ffffff?text=' + encodeURIComponent(judulFoto);
            elGambar.onerror = null;
        };

        elGambar.src = urlGambar;
        document.getElementById('modalJudul').textContent = judulFoto;
        document.getElementById('modalDeskripsi').textContent = deskrip;
    });
</script>
