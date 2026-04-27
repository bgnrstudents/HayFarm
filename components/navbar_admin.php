<div class="navbar  d-flex align-items-center justify-content-between">
    
    <div class="topbar-right">
        <span id="currentDate"></span>
        <div class="notif">
            <i class="fa fa-bell"></i>
            <span class="badge">6</span>
        </div>
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