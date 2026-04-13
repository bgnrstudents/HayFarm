<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
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
                <li><a href="#"><i class="fa-solid fa-table-cells-large" style="color: rgb(151, 151, 151); margin: 3px;"></i> Dashboard</a></li>
                 <li><a href="#"><i class="fa-solid fa-credit-card" style="color: rgb(151, 151, 151); margin: 3px;"></i> Manajemen Produk</a></li>
                  <li class="active"><a href="dashboard.php"> <i class="fa-solid fa-file-circle-check" style="color: rgba(255, 190, 37, 1); margin-right: 2px"></i> Verifikasi Penjualan</a></li>
                  <hr>
                <p class="menu-title">DATA</p>
                <li><a href="#"><i class="fa-solid fa-solid fa-square-poll-vertical" style="color: rgb(151, 151, 151); margin: 3px;"></i> Data Hewan</a></li>
                <li><a href="#"><i class="fa-solid fa-solid fa-heart-pulse" style="color: rgb(151, 151, 151); margin: 3px;"></i> Data Kesehatan Hewan</a></li>
                <hr>
                <li class="logout"><a href="../logout.php"><i class="fa-solid fa-power-off" style="color: rgb(151, 151, 151); margin: 5px;"></i>Logout</a></li>
            </ul>
        </div>


<!-- MAIN -->
<div class="main-content">
    <div class="topbar">
        <div class="right-topbar">
            <span id="currentDate"></span>
            <span class="notif">
                <i class="fa-solid fa-bell" style="color: rgb(25, 108, 51); font-size: 1.25rem;"></i>
            </span>
            <span class="badge">3</span>
            <div class="user">
                <strong>FarelDwi</strong><br>
                <small>Admin</small>
            </div>
        </div>
    </div>
<h4 class="text-3xl font-bold text-gray-800">Dashboard</h4>
<p class="text-gray-500 text-sm">Selamat datang di panel kontrol administrasi peternakan</p>
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