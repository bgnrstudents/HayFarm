<?php
session_start();

$page = $_GET['page'] ?? 'user/home';

// 1. WHITELIST semua halaman yang boleh diakses
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

    // Admin
    'admin/data_hewan/data_hewan',
    'admin/data_hewan/tambah_data_hewan',
    'admin/data_hewan/edit_data_hewan',
    'admin/kesehatan_hewan/data_kesehatan',
    'admin/produk/hewan/tambah_hewan',
    'admin/produk/rumput/tambah_rumput',
    'admin/produk/susu/tambah_susu',

    // Manager
    'manager/data_hewan/data_hewan',
    'manager/kesehatan_hewan/data_kesehatan',
    'manager/produk/hewan/tambah_hewan',
];

if (!in_array($page, $allowed_pages)) {
    $page = 'user/home';
}

// ── 2. CSS per halaman
$page_css = match(true) {
    str_starts_with($page, 'user/home')      => 'home.css',
    str_starts_with($page, 'user/produk')    => 'produk.css',
    str_starts_with($page, 'user/keranjang') => 'keranjang.css',
    str_starts_with($page, 'user/chekout') => 'chekout.css',
    str_starts_with($page, 'user/tentang')   => 'tentangkami.css',
    str_starts_with($page, 'user/riwayat_pesanan') => 'riwayat_pesanan.css',
    str_starts_with($page, 'admin/')         => 'admin.css',
    str_starts_with($page, 'manager/')       => 'manager_sidebar.css',
    default                                  => 'style.css',
};

// ── 3. DEFINISI GRUP HALAMAN
// User yang TIDAK pakai navbar & footer
$user_no_layout_pages = [
    'user/keranjang',
    'user/riwayat_pesanan',
    'user/chekout',
    'user/detail_produk',
];

// Admin pakai sidebar admin
$admin_sidebar_pages = [
    'admin/data_hewan/data_hewan',
    'admin/data_hewan/tambah_data_hewan',
    'admin/data_hewan/edit_data_hewan',
    'admin/kesehatan_hewan/data_kesehatan',
    'admin/produk/hewan/tambah_hewan',
    'admin/produk/rumput/tambah_rumput',
    'admin/produk/susu/tambah_susu',
];

// Manager pakai sidebar manager
$manager_sidebar_pages = [
    'manager/data_hewan/data_hewan',
    'manager/kesehatan_hewan/data_kesehatan',
    'manager/produk/hewan/tambah_hewan',
];

// ── 4. TENTUKAN JENIS LAYOUT
$is_user_no_layout  = in_array($page, $user_no_layout_pages);
$is_admin_sidebar   = in_array($page, $admin_sidebar_pages);
$is_manager_sidebar = in_array($page, $manager_sidebar_pages);

// User biasa = bukan salah satu dari grup di atas
$is_user_with_navbar = !$is_user_no_layout && !$is_admin_sidebar && !$is_manager_sidebar;

include 'components/header.php';

// ── 6. NAVBAR / SIDEBAR sesuai kondisi
if ($is_admin_sidebar) {
    include 'components/sidebar_admin.php';

} elseif ($is_manager_sidebar) {
    include 'components/sidebar_manager.php';

} elseif ($is_user_with_navbar) {
    include 'components/navbar.php';

}
// $is_user_no_layout → tidak include apapun (navbar/sidebar tidak muncul)

// ── 7. KONTEN HALAMAN
$file = "pages/{$page}.php";
if (file_exists($file)) {
    include $file;
} else {
    echo "<div class='alert alert-danger text-center mt-5'>
            Halaman tidak ditemukan!
          </div>";
}

// ── 8. FOOTER — hanya untuk user biasa dengan navbar
if ($is_user_with_navbar) {
    include 'components/footer.php';
}
?>