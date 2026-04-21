<?php
function isActive($page) {
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}
?>

<div class="sidebar p-3">
    <a href="../../">
        <img src="../../public/images/logo_hayfarm.png" class="logo" alt="Logo HayFarm">
    </a>
    <ul class="menu">
        <li class="<?= isActive('dashboard.php') ?>">
            <a href="../../pages/manager/dashboard.php">
            <i class="fa-solid fa-table-cells-large"></i>    
            Dashboard</a>
        </li>
        <hr>
            <p class="menu-title">LAPORAN</p>
            <li class="<?= isActive('lap_populasi.php') ?>">
                <a href="../../pages/manager/lap_populasi.php">
                <i class="fa-solid fa-clipboard""></i>    
                Laporan Populasi</a>
            </li>
            <li class="<?= isActive('lap_kesehatan.php') ?>">
                <a href="../../pages/manager/lap_kesehatan.php">
                <i class="fa-solid fa-clipboard"></i>    
                Laporan Kesehatan</a>
            </li>
            <li class="<?= isActive('lap_transaksi.php') ?>">
                <a href="../../pages/manager/lap_transaksi.php">
                <i class="fa-solid fa-clipboard"></i>    
                Laporan Transaksi Penjualan</a>
            </li>
        <hr>
        <li class="logout"><a href="../../index.php"><i class="fa-solid fa-power-off" style="color: rgb(151, 151, 151); margin: 5px;"></i>Logout</a></li>
    </ul>
</div>
