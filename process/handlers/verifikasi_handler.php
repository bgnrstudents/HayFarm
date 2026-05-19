<?php
ob_start();
session_start();

$root_dir = dirname(__DIR__, 2);
require_once $root_dir . '/config/database.php';
require_once $root_dir . '/process/models/transaksi.php';

// Security: Hanya Admin & Manager
if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$transaksiModel = new Transaksi($db);

function safe_redirect($message = '', $type = 'success')
{
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    if (ob_get_level()) ob_end_clean();

    // Redirect langsung ke file admin
    header('Location: ../../pages/admin/verifikasi_penjualan.php');
    exit;
}

// Handle POST untuk verifikasi/tolak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_transaksi = filter_input(INPUT_POST, 'id_transaksi', FILTER_VALIDATE_INT);
        $aksi = $_POST['aksi'] ?? '';

        // DEBUG: Log parameter yang diterima
        error_log("DEBUG verifikasi_handler: id_transaksi=$id_transaksi, aksi=$aksi");

        if (!$id_transaksi) {
            throw new Exception('ID Transaksi tidak valid');
        }

        if ($aksi === 'verifikasi') {
            $result = $transaksiModel->updateStatusTransaksi($id_transaksi, 'telah_dikonfirmasi', $_SESSION['id_user']);
        } elseif ($aksi === 'tolak') {
            $result = $transaksiModel->updateStatusTransaksi($id_transaksi, 'dibatalkan', $_SESSION['id_user']);
        } else {
            throw new Exception('Aksi tidak dikenali: ' . $aksi);
        }

        if ($result['status']) {
            safe_redirect($result['message'], 'success');
        } else {
            throw new Exception($result['message']);
        }
    } catch (Exception $e) {
        safe_redirect($e->getMessage(), 'error');
    }
}

// GET request - redirect back
safe_redirect('Akses tidak valid', 'error');
