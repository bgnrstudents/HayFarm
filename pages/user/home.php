<!DOCTYPE html>
<html lang="en">

<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TITLE -->
    <title>HayFarm</title>
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="public/css/style.css">
    <!-- BOOTSTRAP CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ICON FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <!-- HOME -->
    <section id="home" class="home-section">
        <div class="container h-100">
            <div class="content-home">
                <div class="text-home">
                    <h3>
                        TEMUKAN TERNAK BERKUALITAS, LANGSUNG DARI SUMBERNYA
                    </h3>
                </div>
                <a href="#" class="btn-home">
                    Lihat Produk <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="container-quality">
            <div class="quality-box d-flex justify-content-center rounded-5 ">

                <div class="item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <p>Ternak Sehat & Terawat</p>
                </div>

                <div class="item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>
                    <p>Data Transparan</p>
                </div>

                <div class="item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <p>Transaksi Aman</p>
                </div>

                <div class="item">
                    <div class="icon-circle">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <p>Langsung dari Sumber</p>
                </div>

            </div>
        </div>
    </section>
    <!-- ABOUT -->
    <section id="home2" class="home2-section">
        <div class="container container-home2">
            <div class="row about d-flex align-items-start mt-5">
                <div class="about-image col-lg-6">
                    <img src="public/images/bghome.png" alt="About Image">
                </div>
                <div class="about-text col-lg-6">
                    <h2 class="mt-lg-0 mt-5">Welcome to Hay Farm</h2>
                    <p>Bertaraf dari inovasi, Lanjutan Peternakan, peternakan kami merupakan pusat pembelajaran dan produksi yang mengedepankan unit bisnis dengan konsep pengembangan, pemeliharaan, dan pengelolaan hewan ternak.</p>
                    <p>Kami fokus hanya mengedepankan produk berkualitas, tetapi juga memberikan pembelajaran bagi mahasiswa dan masyarakat. Dengan pengalaman kami peternakan yang senantiasa, kami terus tingkat proses produksi di lapangan.</p>
                    <div class="con-btn-home2 text-end mt-5">
                        <a href="#" class="btn-home2 text-center">
                            Lihat Selengkapnya <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- PRODUK UNGGULAN -->
    <section id="home3" class="home3-section py-5 mt-5">
        <div class="container  text-center">
            <div class="text-produk">
                <h2>Produk Unggulan</h2>
                <p>Kualitas terbaik dari peternakan modern yang mengintegrasikan pendidikan, riset dan teknologi.</p>
            </div>
            <div class="row d-flex align-items-center justify-content-center g-4 mt-5">

                <div class="col-10 col-lg-4">
                    <div class="card-produk">
                        <img src="public/images/bghome.png" alt="">
                        <div class="card-body text-center">
                            <h5>Sapi Perah</h5>
                            <p>Kami menawarkan sapi perah berkualitas tinggi, termasuk sapi PO (Peranakan Ongole) yang menjadi keunggulan produk kami..</p>
                        </div>
                    </div>
                </div>

                <div class="col-10 col-lg-4">
                    <div class="card-produk">
                        <img src="public/images/bghome.png" alt="">
                        <div class="card-body text-center">
                            <h5>Susu Segar</h5>
                            <p>Produk unggulan yang diproduksi dalam sistem modern untuk kualitas susu yang baik.</p>
                        </div>
                    </div>
                </div>

                <div class="col-10 col-lg-4">
                    <div class="card-produk">
                        <img src="public/images/bghome.png" alt="">
                        <div class="card-body d-flex flex-column align-items-center">
                            <h5>Rumput Segar</h5>
                            <p>Rumput pilihan yang dipanen setiap hari untuk menjamin kesegaran dan kandungan gizi maksimal sebagai sumber energi utama ternak Anda.</p>
                        </div>
                    </div>
                </div>
                 <div class="con-btn-produk text-end mt-5">
                        <a href="#" class="btn-produk text-center">
                            Lihat Selengkapnya <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

            </div>
        </div>
    </section>



    <!-- FOOTER -->
    <!-- BOOTSTRAP JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- MAIN JS -->
    <script src="public/js/script.js"></script>
    <script>
        document.querySelector(".navbar-toggler").addEventListener("click", function() {
            this.classList.toggle("active");
        });
    </script>

</body>

</html>