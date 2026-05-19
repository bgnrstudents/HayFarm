<?php
// components/navbar.php
$navbarCartCount = (int) ($_SESSION['cart_count'] ?? 0);

if (isset($_SESSION['login'], $_SESSION['id_user']) && $_SESSION['login'] === true) {
  $keranjangModel = __DIR__ . '/../process/models/keranjang.php';
  $dbConfig = __DIR__ . '/../config/database.php';

  if (file_exists($keranjangModel) && file_exists($dbConfig)) {
    require_once $dbConfig;
    require_once $keranjangModel;

    if (isset($db) && $db instanceof mysqli) {
      $keranjangNavbar = new Keranjang($db);
      $navbarCartCount = $keranjangNavbar->hitungJumlahItem((int) $_SESSION['id_user']);
      $_SESSION['cart_count'] = $navbarCartCount;
    }
  }
}
?>
<nav class="navbar navbar-expand-lg px-lg-5 px-md-4 px-3">
  <div class="container-fluid">
    <!-- LOGO -->
    <a class="navbar-brand" href="index.php">
      <img src="public/images/logo/logo2.png" style="height:52px;" alt="HayFarm">
    </a>

    <div class="d-flex align-items-center gap-3 order-lg-last">
      <!-- RIGHT SIDE ACTIONS (Always visible) -->
      <div class="d-flex gap-2 justify-content-center align-items-center">
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
          <a href="index.php?page=user/keranjang" id="cart-nav" class="position-relative text-dark me-1 text-decoration-none">
            <i class="fas fa-shopping-cart fa-lg"></i>
            <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; <?= $navbarCartCount > 0 ? '' : 'display:none;' ?>">
              <?= $navbarCartCount ?>
            </span>
          </a>

          <!-- Profile Icon -->
          <div class="dropdown profile-dropdown">
            <button class="btn profile-btn p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle fa-lg"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end profile-card position-absolute" aria-labelledby="profileDropdown">
              <!-- User Info -->
              <li class="profile-header">
                <div class="profile-icon-large">
                  <i class="fas fa-user-circle"></i>
                </div>
                <h6 class="profile-name"><?= htmlspecialchars($_SESSION['username']) ?></h6>
                <small class="profile-email"><?= htmlspecialchars($_SESSION['email']) ?></small>
              </li>

              <!-- Menu Items -->
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="index.php?page=user/riwayat_pesanan">
                  <i class="fas fa-receipt"></i> Riwayat Transaksi
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="index.php?page=hubungi_kami">
                  <i class="fab fa-whatsapp"></i> Hubungi Kami
                </a>
              </li>

              <!-- Logout -->
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item logout-btn" href="logout.php">
                  LOG OUT
                </a>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <div class="d-none d-lg-flex gap-2">
            <a href="login.php" class="btn btn-login px-4">Login</a>
            <a href="register.php" class="btn btn-register px-4">Register</a>
          </div>
          <!-- Mobile Login Icon -->
          <a href="login.php" class="d-lg-none text-decoration-none ">
            <i class="fas fa-sign-in-alt fa-lg"></i>
          </a>
        <?php endif; ?>
      </div>

      <button class="navbar-toggler border-0 p-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="hamburger"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- MENU -->
      <ul class="navbar-nav mx-auto text-center gap-3 gap-lg-1 mt-3 mt-lg-0 mb-lg-0 mb-3">
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
    </div>
  </div>
</nav>
