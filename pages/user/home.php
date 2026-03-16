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
    <!-- NAVBAR -->
    <?php include 'components/navbar.php'; ?>


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
    </section>


    <!-- FOOTER -->
    <?php include 'components/footer.php'; ?>
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