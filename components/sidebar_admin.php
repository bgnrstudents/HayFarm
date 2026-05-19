<?php
$current = basename($_SERVER['PHP_SELF']);
$loginUrl = '../../logout.php';
$topMenu = [
    'dashboard.php' => ['icon' => 'fa-table-cells-large', 'label' => 'Dashboard'],
    'manajemen_produk.php' => ['icon' => 'fa-credit-card', 'label' => 'Manajemen Produk'],
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

<div class="flash-message-container" id="flashMessageContainer" aria-live="polite" aria-atomic="true"></div>

<style>
    .flash-message-container {
        position: fixed;
        top: 22px;
        right: 22px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        z-index: 20000;
        pointer-events: none;
    }

    .flash-message {
        min-width: 280px;
        max-width: min(420px, calc(100vw - 32px));
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid #16a34a;
        border-radius: 12px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        color: #1f2937;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        opacity: 0;
        pointer-events: auto;
        transform: translateX(18px);
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .flash-message.show {
        opacity: 1;
        transform: translateX(0);
    }

    .flash-message.hide {
        opacity: 0;
        transform: translateX(18px);
    }

    .flash-message.success { border-left-color: #16a34a; }
    .flash-message.danger { border-left-color: #ef4444; }
    .flash-message.warning { border-left-color: #f59e0b; }
    .flash-message.info { border-left-color: #3b82f6; }

    .flash-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 28px;
        font-size: 13px;
        color: #ffffff;
        background: #16a34a;
        font-weight: 800;
    }

    .flash-message.danger .flash-icon { background: #ef4444; }
    .flash-message.warning .flash-icon { background: #f59e0b; }
    .flash-message.info .flash-icon { background: #3b82f6; }

    .flash-body { flex: 1; min-width: 0; }
    .flash-title { font-size: 14px; font-weight: 800; margin-bottom: 2px; color: #111827; }
    .flash-text { font-size: 13px; line-height: 1.45; color: #4b5563; margin: 0; }

    .flash-close {
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        padding: 0;
    }

    @media (max-width: 767px) {
        .flash-message-container { top: 14px; right: 14px; left: 14px; }
        .flash-message { width: 100%; min-width: 0; }
    }
</style>

<script>
function showFlashMessage(message, type = 'success', options = {}) {
        const container = document.getElementById('flashMessageContainer');
        if (!container) return;

        // Normalisasi type agar kompatibel dengan seluruh handler/page
        if (type === 'error') type = 'danger';
        if (type === 'fail') type = 'danger';

        const titles = { success: 'Berhasil', danger: 'Terjadi Kesalahan', warning: 'Perhatian', info: 'Info' };
        const icons = { success: 'check', danger: 'x', warning: '!', info: 'i' };
        const flash = document.createElement('div');

        flash.className = `flash-message ${type}`;
        flash.innerHTML = `
            <span class="flash-icon">${icons[type] || icons.success}</span>
            <div class="flash-body">
                <div class="flash-title">${options.title || titles[type] || titles.success}</div>
                <p class="flash-text">${message}</p>
            </div>
            <button class="flash-close" type="button" aria-label="Tutup">&times;</button>
        `;


        container.appendChild(flash);
        requestAnimationFrame(() => flash.classList.add('show'));

        const close = () => {
            flash.classList.add('hide');
            flash.classList.remove('show');
            window.setTimeout(() => flash.remove(), 250);
        };

        flash.querySelector('.flash-close').addEventListener('click', close);
        window.setTimeout(close, options.duration || 2600);
    }

    function flashThen(message, callback, type = 'success', delay = 900) {
        showFlashMessage(message, type);
        window.setTimeout(callback, delay);
    }
</script>
