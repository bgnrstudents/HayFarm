<?php
ob_start();
session_start();

$root_dir = dirname(__DIR__, 2);
require_once $root_dir . '/config/database.php';
require_once $root_dir . '/process/models/keranjang.php';
require_once $root_dir . '/process/models/transaksi.php';

$database = new Database();
$db = $database->getConnection();
$keranjang = new Keranjang($db);
$transaksiModel = new Transaksi($db);

// ✅ Cek apakah request AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// ✅ Helper: Response JSON untuk AJAX
function ajax_response($success, $message, $redirect = '')
{
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $success,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit;
}

// ✅ Helper: Redirect untuk non-AJAX
function web_redirect($page, $message = '', $type = 'success')
{
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    if (ob_get_level()) ob_end_clean();
    header('Location: ../../index.php?page=' . $page);
    exit;
}

// ✅ Security: Cek login user (bukan admin!)
if (!isset($_SESSION['login'], $_SESSION['id_user']) || $_SESSION['login'] !== true) {
    if ($isAjax) {
        ajax_response(false, 'Silakan login terlebih dahulu.');
    }
    web_redirect('user/home', 'Silakan login terlebih dahulu.', 'error');
}

// ✅ Hanya terima POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        ajax_response(false, 'Akses tidak valid.');
    }
    web_redirect('user/produk', 'Akses tidak valid.', 'error');
}

$userId = (int) $_SESSION['id_user'];
$source = $_POST['source'] ?? 'direct';

try {
    // Validasi input
    $nama = trim($_POST['nama_pembeli'] ?? '');
    $noTelp = trim($_POST['no_telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $kodePos = trim($_POST['kode_pos'] ?? '');
    $metode = strtolower($_POST['metode_pembayaran'] ?? 'transfer');
    $metode = $metode === 'cod' ? 'cod' : 'transfer';

    if ($nama === '' || $noTelp === '' || $alamat === '' || $kodePos === '') {
        throw new Exception('Data pengiriman wajib dilengkapi.');
    }

    //  Validasi No. HP (regex ketat)
    $noTelp = preg_replace('/[^0-9]/', '', $noTelp);
    if (!preg_match('/^08\d{8,12}$/', $noTelp)) {
        throw new Exception('Nomor telepon tidak valid. Gunakan format 08xx (10-13 digit).');
    }

    // Ambil item yang akan dibeli
    $items = [];
    if ($source === 'cart') {
        $items = $keranjang->getItems($userId);
    } else {
        $productId = (int) ($_POST['id_produk'][0] ?? 0);
        $qty = max(1, (int) ($_POST['jumlah'][0] ?? 1));
        $product = $keranjang->getProdukById($productId);

        if ($product && $product['status_produk'] === 'blm_terjual') {
            $qty = min($qty, max(1, (int) ($product['stok'] ?? 1)));
            $items[] = [
                'id_produk' => (int) $product['id_produk'],
                'jumlah' => $qty,
                'harga' => (float) $product['harga'],
                'sub_total' => (float) $product['harga'] * $qty,
                'nama_produk' => $product['nama_produk'],
            ];
        }
    }

    if (empty($items)) {
        throw new Exception('Tidak ada produk yang dipilih untuk checkout.');
    }

    // Handle upload bukti transfer
    $buktiPath = '';
    if ($metode === 'transfer') {
        if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Bukti transfer wajib diupload.');
        }

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            throw new Exception('Format bukti harus JPG, JPEG, atau PNG.');
        }

        if ($_FILES['bukti_pembayaran']['size'] > 5 * 1024 * 1024) {
            throw new Exception('Ukuran bukti maksimal 5MB.');
        }

        $uploadDir = $root_dir . '/uploads/bukti/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = 'bukti_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (!move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $uploadDir . $filename)) {
            throw new Exception('Gagal mengupload bukti pembayaran.');
        }
        $buktiPath = 'uploads/bukti/' . $filename;
    }

    // ✅ VALIDASI STOK: Cek stok real-time sebelum transaksi
    // $stock_errors = [];
    // foreach ($items as &$item) {
    //     $stmt = $db->prepare("SELECT stok, status_produk FROM data_produk WHERE id_produk = ? FOR UPDATE");
    //     $stmt->bind_param('i', $item['id_produk']);
    //     $stmt->execute();
    //     $produk = $stmt->get_result()->fetch_assoc();

    //     if (!$produk) {
    //         $stock_errors[] = "Produk ID {$item['id_produk']} tidak ditemukan";
    //         continue;
    //     }

    //     if ($produk['status_produk'] !== 'blm_terjual') {
    //         $stock_errors[] = "{$item['nama_produk']} sudah tidak tersedia";
    //         continue;
    //     }

    //     if ((int) $produk['stok'] < $item['jumlah']) {
    //         $stock_errors[] = "Stok {$item['nama_produk']} tidak cukup. Tersedia: {$produk['stok']}, diminta: {$item['jumlah']}";
    //     }

    //     // Update item dengan stok terbaru (untuk perhitungan harga jika perlu)
    //     $item['stok_tersedia'] = (int) $produk['stok'];
    // }

    $stock_errors = [];

    if (!empty($stock_errors)) {
        throw new Exception(implode("<br>", $stock_errors));
    }
    // ✅ Transaction: Buat transaksi + detail + kurangi stok
    $db->begin_transaction();

    $result = $transaksiModel->buatTransaksi($userId, [
        'nama_pembeli' => $nama,
        'no_telp' => $noTelp,
        'alamat' => $alamat,
        'kode_pos' => $kodePos,
        'metode_pembayaran' => $metode,
        'bukti_pembayaran' => $buktiPath,
    ], $items);

    if (!$result['status']) {
        throw new Exception($result['message']);
    }

    // Kosongkan keranjang jika dari cart
    if ($source === 'cart') {
        $transaksiModel->kosongkanKeranjangUser($userId);
        $_SESSION['cart_count'] = 0;
    }

    $db->commit();

    $successMsg = $metode === 'cod'
        ? 'Pesanan COD berhasil dibuat. Silakan hubungi admin via WhatsApp.'
        : 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.';

    if ($isAjax) {
        ajax_response(true, $successMsg, '../../index.php?page=user/riwayat_pesanan');
    } else {
        web_redirect('user/riwayat_pesanan', $successMsg, 'success');
    }
} catch (Exception $e) {
    // Rollback jika ada error
    if (isset($db) && $db instanceof mysqli) {
        $db->rollback();
    }

    // ✅ Error response: AJAX return JSON, non-AJAX redirect
    if ($isAjax) {
        ajax_response(false, $e->getMessage());
    } else {
        web_redirect('user/chekout', $e->getMessage(), 'error');
    }
}
