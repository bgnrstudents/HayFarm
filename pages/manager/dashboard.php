<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/manager_sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- nunito -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet"></style>
</head>
<body>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar p-3">
            <img src="../../public/images/logo_hayfarm.png" class="logo">
            <ul class="menu">
                <li class="active"><a href="dashboard.php"> <i class="fa-solid fa-table-cells-large" style="color: rgba(255, 190, 37, 1); margin-right: 2px"></i> Dashboard</a></li>
                <hr>
                <p class="menu-title">LAPORAN</p>
                <li><a href="#"><i class="fa-solid fa-clipboard" style="color: rgb(151, 151, 151); margin: 3px;"></i> Laporan Populasi</a></li>
                <li><a href="#"><i class="fa-solid fa-clipboard" style="color: rgb(151, 151, 151); margin: 3px;"></i> Laporan Kesehatan</a></li>
                <li><a href="#"><i class="fa-solid fa-clipboard" style="color: rgb(151, 151, 151); margin: 3px;"></i> Laporan Transaksi Penjualan</a></li>

                <hr>
                <li class="logout"><a href="../logout.php"><i class="fa-solid fa-power-off" style="color: rgb(151, 151, 151); margin: 5px;"></i>Logout</a></li>
            </ul>
        </div>


        <!-- MAIN -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    // Set current date in Indonesian format
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const now = new Date();
        dateEl.textContent = now.toLocaleDateString('id-ID', opts);
    }
</script>
</body>
</html>