<?php
$resolvedManagerName = isset($managerName) && is_string($managerName) ? $managerName : 'Marshanda';
$resolvedManagerRole = isset($managerRole) && is_string($managerRole) ? $managerRole : 'Manager';
$resolvedNotificationCount = isset($notificationCount) ? (int) $notificationCount : 6;
?>
<div class="main-content">
    <div class="topbar">
        <div class="right-topbar">
            <span id="currentDate"></span>

            <div class="user">
                <strong><?= htmlspecialchars($resolvedManagerName, ENT_QUOTES, 'UTF-8') ?></strong><br>
                <small><?= htmlspecialchars($resolvedManagerRole, ENT_QUOTES, 'UTF-8') ?></small>
            </div>
        </div>
    </div>
