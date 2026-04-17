<?php
// index.php
session_start();

$page = $_GET['page'] ?? 'user/home';

$allowed_pages = [
    'user/home',
    'user/produk',
    'user/tentang_kami',
    'user/keranjang',
    'user/riwayat'
];

if (!in_array($page, $allowed_pages)) {
    $page = 'user/home'; // fallback aman
}

// index.php
$page = $_GET['page'] ?? 'user/home';

// Tentukan file CSS tambahan berdasarkan halaman
$page_css = '';
if ($page == 'user/home') {
    $page_css = 'home.css';
} elseif ($page == 'user/produk') {
    $page_css = 'produk.css';
} elseif ($page == 'user/keranjang') {
    $page_css = 'keranjang.css';
} elseif ($page == 'admin/dashboard') {
    $page_css = 'admin.css';
} elseif ($page == 'manager/dashboard') {
    $page_css = 'manager.css';
}


include 'components/header.php';
include 'components/navbar.php';

$file = "pages/{$page}.php";
if (file_exists($file)) {
    include $file;
} else {
    echo "<div class='alert alert-danger text-center mt-5'>Halaman tidak ditemukan!</div>";
}

include 'components/footer.php';
?>