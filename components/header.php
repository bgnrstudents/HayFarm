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
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/images/logo/logo2.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- MAIN CSS (timpa Bootstrap jika perlu) -->
    <link rel="stylesheet" href="public/css/style.css">
    <?php if (!empty($page_css)): ?>
        <?php $cssVersion = file_exists(__DIR__ . '/../public/css/' . $page_css) ? filemtime(__DIR__ . '/../public/css/' . $page_css) : time(); ?>
        <link rel="stylesheet" href="public/css/<?php echo $page_css; ?>?v=<?php echo $cssVersion; ?>">
    <?php endif; ?>
</head>

<body>
    <!-- <div id="preloader">
        <div class="loader"></div>
    </div> -->
