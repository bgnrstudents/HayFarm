<?php
ob_start();
session_start();

$root_dir = dirname(__DIR__, 2);
require_once $root_dir . '/config/database.php';
require_once $root_dir . '/process/models/keranjang.php';

$database = new Database();
$db = $database->getConnection();
$keranjang = new Keranjang($db);

function cart_is_ajax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function cart_json(array $data): void
{
    if (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function cart_redirect(string $message = '', string $type = 'success'): void
{
    if ($message !== '') {
        $_SESSION['flash_type'] = $type;
        $_SESSION['flash_message'] = $message;
    }

    if (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: ../../index.php?page=user/keranjang');
    exit;
}

if (!isset($_SESSION['login'], $_SESSION['id_user']) || $_SESSION['login'] !== true) {
    if (cart_is_ajax()) {
        cart_json(['status' => false, 'message' => 'Silakan login terlebih dahulu.']);
    }
    cart_redirect('Silakan login terlebih dahulu.', 'error');
}

$userId = (int) $_SESSION['id_user'];
$action = $_POST['action'] ?? '';

try {
    if ($action === 'add') {
        $productId = (int) ($_POST['id_produk'] ?? 0);
        $qty = $keranjang->normalJumlah($_POST['jumlah'] ?? 1);
        $result = $keranjang->tambahItem($userId, $productId, $qty);

        if (!$result['status']) {
            throw new Exception($result['message']);
        }

        $_SESSION['cart_count'] = $result['cart_count'];

        cart_json([
            'status' => true,
            'message' => $result['message'],
            'cart_count' => $_SESSION['cart_count'],
        ]);
    }

    if ($action === 'update') {
        $detailId = (int) ($_POST['id_detail_keranjang'] ?? 0);
        $qty = $keranjang->normalJumlah($_POST['jumlah'] ?? 1);
        $result = $keranjang->updateJumlah($userId, $detailId, $qty);

        if (!$result['status']) {
            throw new Exception($result['message']);
        }

        $_SESSION['cart_count'] = $keranjang->hitungJumlahItem($userId);
        
        // ✅ FIX: Return JSON untuk AJAX, redirect untuk fallback
        if (cart_is_ajax()) {
            $items = $keranjang->getItems($userId);
            $updatedItem = null;
            foreach ($items as $item) {
                if ((int)$item['id_detail_keranjang'] === $detailId) {
                    $updatedItem = $item;
                    break;
                }
            }
            
            cart_json([
                'status' => true,
                'message' => $result['message'],
                'new_subtotal' => $updatedItem ? $keranjang->formatRupiah((float)$updatedItem['sub_total']) : '',
                'new_total' => $keranjang->formatRupiah($keranjang->hitungTotal($items)),
                'cart_count' => $_SESSION['cart_count'],
                'jumlah' => $updatedItem ? (int)$updatedItem['jumlah'] : $qty,
                'stok' => $updatedItem ? max(1, (int)$updatedItem['stok']) : null,
            ]);
        } else {
            cart_redirect($result['message']);
        }
    }

    if ($action === 'delete') {
        $detailId = (int) ($_POST['id_detail_keranjang'] ?? 0);
        $result = $keranjang->hapusItem($userId, $detailId);

        if (!$result['status']) {
            throw new Exception($result['message']);
        }

        $_SESSION['cart_count'] = $keranjang->hitungJumlahItem($userId);
        
        // ✅ FIX: Return JSON untuk AJAX, redirect untuk fallback
        if (cart_is_ajax()) {
            $items = $keranjang->getItems($userId);
            cart_json([
                'status' => true,
                'message' => $result['message'],
                'new_total' => $keranjang->formatRupiah($keranjang->hitungTotal($items)),
                'cart_count' => $_SESSION['cart_count'],
                'is_empty' => empty($items),
            ]);
        } else {
            cart_redirect($result['message']);
        }
    }

    throw new Exception('Aksi keranjang tidak dikenali.');
} catch (Exception $e) {
    if (cart_is_ajax()) {
        cart_json(['status' => false, 'message' => $e->getMessage()]);
    }
    cart_redirect($e->getMessage(), 'error');
}
