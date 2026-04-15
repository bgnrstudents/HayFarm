<?php
// Jika ingin memanggil navbar, aktifkan baris di bawah ini:
// include '../../components/navbar.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - HayFarm</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            overflow-x: hidden; 
            background-color: #ffffff;
        }
        
        /* 1. Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                     url('../../public/images/bghome.png') no-repeat center center;
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        /* 2. Photo Grid (Efek Bertumpuk Sesuai Gambar) */
        .img-container { 
            position: relative; 
            height: 480px; 
            margin-top: 20px;
        }

        .img-item { 
            position: absolute; 
            border-radius: 15px; 
            overflow: hidden; 
            border: 5px solid white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            background-size: cover;
            background-position: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }

        /* Animasi Hover (Saat Mouse Diatas Foto) */
        .img-item:hover {
            transform: scale(1.05) translateY(-10px);
            z-index: 10 !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        /* Posisi Foto (Disesuaikan 100% dengan Screenshot) */
        .img-1 { width: 60%; height: 180px; top: 10px; left: 0; z-index: 2; 
                 background-image: url('../../public/images/sapi_perah.jpg'); }
        
        .img-2 { width: 55%; height: 220px; top: 90px; right: 5%; z-index: 1; 
                 background-image: url('../../public/images/bghome.png'); }
        
        .img-3 { width: 45%; height: 130px; top: 180px; right: -10px; z-index: 3; 
                 background-image: url('../../public/images/sapi.jpg'); }
        
        .img-4 { width: 50%; height: 160px; bottom: 50px; left: 5%; z-index: 4; 
                 background-image: url('../../public/images/domba.jpg'); }

        /* Kelas untuk Animasi Klik via JS */
        .img-clicked {
            transform: scale(0.92) !important;
            filter: brightness(1.1);
        }

        /* 3. Visi Misi Section */
        .section-visi-misi {
            position: relative;
            background: url('../../assets/images/bghlogin.png') no-repeat center center; 
            background-size: cover;
            padding: 100px 0;
            color: white;
            overflow: hidden;
        }

        .section-visi-misi::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(23, 93, 43, 0.88); 
            z-index: 1;
        }

        .section-visi-misi .container {
            position: relative;
            z-index: 2;
        }

        .title-line {
            display: inline-block;
            border-bottom: 3px solid white;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .text-justify {
            text-align: justify;
            line-height: 1.8;
        }
        
        .text-green-brand { color: #2e7d32; }
    </style>
</head>
<body>

    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold text-uppercase">Tentang Kami</h1>
            <p class="lead">Mengintegrasikan pendidikan, penelitian, dan produksi peternakan modern.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h6 class="text-green-brand fw-bold">Tentang HayFarm</h6>
                    <h2 class="fw-bold mb-4">TEFA Feedlot dan Sapi Perah</h2>
                    <p class="text-muted text-justify">
                        Dibangun atas inisiatif Unit Pengelolaan Peternakan Politeknik Negeri Jember, unit ini menjadi laboratorium hidup bagi mahasiswa untuk belajar pengelolaan peternakan dan perawatan sapi secara langsung.
                    </p>
                    <p class="text-muted text-justify">
                        Melalui konsep Teaching Factory (TEFA), kami mengintegrasikan pendidikan dan unit bisnis untuk menghasilkan produk berkualitas unggul dengan standar modern dan inovatif bagi masyarakat.
                    </p>
                </div>
                
                <div class="col-lg-6">
                    <div class="img-container">
                        <div class="img-item img-1"></div>
                        <div class="img-item img-2"></div>
                        <div class="img-item img-3"></div>
                        <div class="img-item img-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-visi-misi">
        <div class="container">
            <div class="row gx-5">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="title-line">Visi & Misi</h2>
                    <p class="text-justify">
                        Selain fokus pada produksi dan bisnis, peternakan berpedoman sebagai sarana pembelajaran, pemberdayaan, pemulihan, dan pengelolaan sumber daya lokal. Tidak hanya mengutamakan keuntungan, tetapi juga menjaga kualitas dan kepedulian terhadap lingkungan melalui budaya pengembangan berkelanjutan.
                    </p>
                </div>

                <div class="col-md-6">
                    <h2 class="title-line">Teaching Factory (TEFA)</h2>
                    <p class="text-justify">
                        Sebagai TEFA, kami tidak hanya berfungsi untuk unit bisnis, tetapi juga menjadi tempat pembelajaran, penelitian, dan pengembangan masyarakat. Dengan kapasitas kandang 30-40 ekor sapi, kami mengelola laboratorium hidup bagi mahasiswa untuk belajar dan berprestasi langsung di lapangan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Main JS untuk Interaksi Foto
        const images = document.querySelectorAll('.img-item');

        images.forEach(img => {
            // Animasi saat ditekan (mousedown)
            img.addEventListener('mousedown', function() {
                this.classList.add('img-clicked');
            });

            // Animasi saat dilepas (mouseup)
            img.addEventListener('mouseup', function() {
                this.classList.remove('img-clicked');
                
                // Efek tambahan: goyang sedikit setelah klik
                this.style.transform = 'scale(1.05) rotate(3deg)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });

            // Pastikan kelas dihapus jika mouse ditarik keluar saat menekan
            img.addEventListener('mouseleave', function() {
                this.classList.remove('img-clicked');
            });
        });
    </script>
</body>
</html>