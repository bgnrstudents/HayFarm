<?php
session_start();

$page = $_GET['page'] ?? 'user/home';

// 1. WHITELIST semua halaman USER yang boleh diakses
$allowed_pages = [
    // User — dengan navbar & footer
    'user/home',
    'user/produk',
    'user/tentang_kami',

    // User — TANPA navbar & footer
    'user/keranjang',
    'user/riwayat_pesanan',
    'user/chekout',
    'user/detail_produk',
];

if (!in_array($page, $allowed_pages)) {
    $page = 'user/home';
}

// ── 2. CSS per halaman
$page_css = match(true) {
    str_starts_with($page, 'login') || str_starts_with($page, 'register') => 'logregis.css',
    str_starts_with($page, 'user/home')      => 'home.css',
    str_starts_with($page, 'user/produk')    => 'produk.css',
    str_starts_with($page, 'user/keranjang') => 'keranjang.css',
    str_starts_with($page, 'user/chekout') => 'chekout.css',
    str_starts_with($page, 'user/tentang')   => 'tentangkami.css',
    str_starts_with($page, 'user/riwayat_pesanan') => 'riwayat_pesanan.css',
    default                                  => 'style.css',
};

// User yang TIDAK pakai navbar & footer
$user_no_layout_pages = [
    'user/keranjang',
    'user/riwayat_pesanan',
    'user/chekout',
    'user/detail_produk',
];

// ── 3. TENTUKAN JENIS LAYOUT
$is_user_no_layout  = in_array($page, $user_no_layout_pages);
$is_user_with_navbar = !$is_user_no_layout;

include 'components/header.php';

// ── 4. NAVBAR untuk user
if ($is_user_with_navbar) {
    include 'components/navbar.php';
}
// $is_user_no_layout → tidak include navbar/footer

// ── 5. KONTEN HALAMAN
$file = "pages/{$page}.php";
if (file_exists($file)) {
    include $file;
} else {
    echo "<div class='alert alert-danger text-center mt-5'>
            Halaman tidak ditemukan!
          </div>";
}

// ── 6. FOOTER — hanya untuk user dengan navbar
if ($is_user_with_navbar) {
    include 'components/footer.php';
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="public/js/script.js"></script>
</body>

</html>