<?php
// components/header.php
// session_start(); // JANGAN di sini, karena index.php sudah session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HayFarm</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- MAIN CSS (timpa Bootstrap jika perlu) -->
    <link rel="stylesheet" href="public/css/style.css">
    <?php if (!empty($page_css)): ?>
        <link rel="stylesheet" href="public/css/<?php echo $page_css; ?>">
    <?php endif; ?>
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggler = document.querySelector('.navbar-toggler');
            if (toggler) {
                toggler.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            }
        });
    </script>
</body>