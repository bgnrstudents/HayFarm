<?php include '../../components/header_manager.php'; ?>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar p-3">
            <img src="../../public/images/logo_hayfarm.png" class="logo">
            <ul class="menu">
                <li class="active"><a href="dashboard.php"> <i class="fa-solid fa-table-cells-large" style="color: rgba(255, 190, 37, 1); margin-right: 2px"></i> Dashboard</a></li>
                <hr>
                <p class="menu-title">LAPORAN</p>
                <li><a href="<?php echo '../../pages/manager/lap_populasi.php'; ?>"><i class="fa-solid fa-clipboard" style="color: rgb(151, 151, 151); margin: 3px;"></i> Laporan Populasi</a></li>
                <li><a href="<?php echo '../../pages/manager/lap_kesehatan.php'; ?>"><i class="fa-solid fa-clipboard" style="color: rgb(151, 151, 151); margin: 3px;"></i> Laporan Kesehatan</a></li>
                <li><a href="<?php echo '../../pages/manager/lap_transaksi.php'; ?>"><i class="fa-solid fa-clipboard" style="color: rgb(151, 151, 151); margin: 3px;"></i> Laporan Transaksi Penjualan</a></li>

                <hr>
                <li class="logout"><a href="../../index.php"><i class="fa-solid fa-power-off" style="color: rgb(151, 151, 151); margin: 5px;"></i>Logout</a></li>
            </ul>
        </div>


        <!-- TOPBAR-->
        <div class="main-content">
            <div class="topbar">
                <div class="right-topbar">
                    <span id="currentDate"></span>
                    <span class="notif"><i class="fa-solid fa-bell" style="color: rgb(25, 108, 51); size: 1.25rem;"></i></span>
                    <span class="badge">6</span>
                    </span>
                    <div class="user">
                        <strong>Marshanda</strong><br>
                        <small>Manager</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include '../../components/footer_manager.php'; ?> 