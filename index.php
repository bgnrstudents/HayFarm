<?php
session_start();

$page = $_GET['page'] ?? 'user/home';

$file = 'pages/' . $page . '.php';

// validasi file
if (!file_exists($file)) {
    echo "Halaman tidak ditemukan!";
    exit;
}

// layout
include 'components/navbar.php';

// halaman
include $file;

// footer
include 'components/footer.php';