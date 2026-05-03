<div class="topbar justify-content-end">
        <div class="topbar-right">
            <span id="currentDate"></span>
            <a class="notif" href="dashboard.php" aria-label="Lihat notifikasi di dashboard">
                <i class="fa-solid fa-bell" style="color: rgb(25, 108, 51);"></i>
                <span class="badge">6</span>
            </a>
            <div class="user">
                <strong>Farel</strong>
                <small>Admin</small>
            </div>
        </div>
    </div>

<script>
const dateEl = document.getElementById('currentDate');
dateEl.textContent = new Date().toLocaleDateString('id-ID',{
    weekday:'long',
    year:'numeric',
    month:'long',
    day:'numeric'
});
</script>