<?php
$current = basename($_SERVER['PHP_SELF']);
$loginUrl = '/HayFarm/login.php';
$topMenu = [
    'index.php' => ['icon' => 'fa-table-cells-large', 'label' => 'Dashboard'],
    'produk.php' => ['icon' => 'fa-credit-card', 'label' => 'Manajemen Produk'],
    'verifikasi_penjualan.php' => ['icon' => 'fa-file-circle-check', 'label' => 'Verifikasi Penjualan'],
];
$bottomMenu = [
    'data_hewan.php' => ['icon' => 'fa-square-poll-vertical', 'label' => 'Data Hewan'],
    'data_kesehatan.php' => ['icon' => 'fa-heart-pulse', 'label' => 'Data Kesehatan Hewan'],
];
?>
<div class="sidebar">
    <img src="../../public/images/logo/logo2.png" alt="Logo Hay Farm" class="logo">

    <ul class="menu">
        <?php foreach ($topMenu as $file => $item): ?>
            <li class="<?php echo $current === $file ? 'active' : ''; ?>">
                <a href="<?php echo $file; ?>">
                    <i class="fa-solid <?php echo $item['icon']; ?>"></i>
                    <?php echo $item['label']; ?>
                </a>
            </li>
        <?php endforeach; ?>

        <p class="menu-title">DATA</p>

        <?php foreach ($bottomMenu as $file => $item): ?>
            <li class="<?php echo $current === $file ? 'active' : ''; ?>">
                <a href="<?php echo $file; ?>">
                    <i class="fa-solid <?php echo $item['icon']; ?>"></i>
                    <?php echo $item['label']; ?>
                </a>
            </li>
        <?php endforeach; ?>

        <li>
            <a href="#" onclick="openLogoutModal(event)">
                <i class="fa-solid fa-power-off"></i>
                Logout
            </a>
        </li>
    </ul>
</div>

<style>
    .logout-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .logout-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .logout-modal-card {
        background: #ffffff;
        width: 440px;
        max-width: 90vw;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        position: relative;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }

    .logout-modal-overlay.active .logout-modal-card {
        transform: translateY(0);
    }

    .logout-icon-area {
        font-size: 72px;
        color: #f6e088;
        margin-bottom: 25px;
        display: flex;
        justify-content: center;
    }

    .logout-text {
        font-size: 19px;
        font-weight: 600;
        color: #1e1e1e;
        line-height: 1.5;
        margin-bottom: 35px;
    }

    .logout-btn-group {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .btn-modal {
        padding: 11px 0;
        width: 140px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .btn-yes {
        background-color: #8fae9b;
        color: #ffffff;
    }

    .btn-yes:hover {
        background-color: #7c9b88;
    }

    .btn-cancel {
        background-color: #8c8c8c;
        color: #ffffff;
    }

    .btn-cancel:hover {
        background-color: #797979;
    }
</style>

<div class="logout-modal-overlay" id="delLogModal" onclick="closeLogModalOutside(event)">
    <div class="logout-modal-card">
        <div class="logout-icon-area">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div class="logout-text">
            Apakah kamu yakin ingin keluar dari akun ini ?
        </div>

        <div class="logout-btn-group">
            <button class="btn-modal btn-yes" type="button" onclick="prosesLogout()">Ya</button>
            <button class="btn-modal btn-cancel" type="button" onclick="closeLogModal()">Batal</button>
        </div>
    </div>
</div>

<script>
    const delLogModal = document.getElementById('delLogModal');

    function openLogoutModal(event) {
        if (event) {
            event.preventDefault();
        }
        delLogModal.classList.add('active');
    }

    function closeLogModal() {
        delLogModal.classList.remove('active');
    }

    function closeLogModalOutside(event) {
        if (event.target === delLogModal) {
            closeLogModal();
        }
    }

    function prosesLogout() {
        window.location.href = <?php echo json_encode($loginUrl); ?>;
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogModal();
        }
    });
</script>