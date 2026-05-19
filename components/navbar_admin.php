<?php
// Pastikan session & koneksi DB tersedia
if (session_status() === PHP_SESSION_NONE) session_start();

// Sesuaikan path jika struktur folder berbeda
$db_path = __DIR__ . '/../config/database.php';
if (file_exists($db_path)) {
    require_once $db_path;
    $db = new Database();
    $conn = $db->getConnection();
} else {
    $conn = null;
}

// Hitung transaksi menunggu verifikasi (hanya untuk admin/manager)
$pending_count = 0;
if (isset($_SESSION['role'], $_SESSION['login']) && $_SESSION['login'] === true && in_array($_SESSION['role'], ['admin', 'manager'])) {
    if ($conn) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM transaksi WHERE status_transaksi = 'menunggu_verifikasi'");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $pending_count = (int) ($row['total'] ?? 0);
    }
}
?>
<div class="topbar justify-content-end">
    <div class="topbar-right">
        <span id="currentDate"></span>
        <!-- Link otomatis ke verifikasi jika ada pending -->
        <a class="notif" href="<?= ($pending_count > 0) ? 'verifikasi_penjualan.php' : '#' ?>" aria-label="Notifikasi verifikasi">
            <i class="fa-solid fa-bell" style="color: rgb(25, 108, 51);"></i>
            <?php if ($pending_count > 0): ?>
                <span class="badge"><?= $pending_count ?></span>
            <?php endif; ?>
        </a>
        <div class="user">
            <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong>
            <small><?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'admin')) ?></small>
        </div>
    </div>
</div>

<script>
const dateEl = document.getElementById('currentDate');
if (dateEl) {
    dateEl.textContent = new Date().toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}
</script>