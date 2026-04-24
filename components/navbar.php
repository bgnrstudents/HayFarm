<!-- components/navbar.php -->
<nav class="navbar navbar-expand-lg px-lg-5 px-md-4 px-3">
  <div class="container-fluid">
    <!-- LOGO -->
    <a class="navbar-brand" href="index.php">
      <img src="../public/images/logo/logo2.png" style="height:52px;" alt="HayFarm">
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="hamburger"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <!-- MENU -->
      <ul class="navbar-nav mx-auto text-center gap-3 gap-lg-1 mb-lg-0 mb-3">
        <li class="nav-item">
          <a class="nav-link <?= ($page ?? '') == 'user/home' ? 'active' : '' ?>" 
             href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($page ?? '') == 'user/tentang_kami' ? 'active' : '' ?>" 
             href="index.php?page=user/tentang_kami">Tentang Kami</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($page ?? '') == 'user/produk' ? 'active' : '' ?>" 
             href="index.php?page=user/produk">Produk</a>
        </li>
      </ul>

      <!-- RIGHT SIDE -->
      <div class="d-flex gap-2 justify-content-center mb-3 mb-lg-0">
        <?php if(isset($_SESSION['id_user'])): ?>
          <a href="index.php?page=user/keranjang" class="btn btn-outline-success px-4">
            <i class="fas fa-shopping-cart"></i> Keranjang
          </a>
          <a href="#" class="btn btn-login px-4">Hi, <?= htmlspecialchars($_SESSION['username']) ?></a>
          <a href="logout.php" class="btn btn-danger px-4">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-login px-4">Login</a>
          <a href="register.php" class="btn btn-register px-4">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>