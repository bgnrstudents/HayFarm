<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit;
}
$resolvedPageTitle = isset($pageTitle) && is_string($pageTitle) ? $pageTitle : 'Dashboard Manager';
$resolvedBodyClass = isset($bodyClass) && is_string($bodyClass) ? $bodyClass : 'manager-page';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager | <?= htmlspecialchars($resolvedPageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/manager/manager_sidebar.css">
    <link rel="stylesheet" href="../../public/css/manager/manager_mainContent.css">
    <link rel="stylesheet" href="../../public/css/manager/manager_detailHewan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>

<body class="<?= htmlspecialchars($resolvedBodyClass, ENT_QUOTES, 'UTF-8') ?>">
    <div class="app-shell">