<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

$root_dir = dirname(__DIR__, 2);

// --- KONEKSI DATABASE & MODEL ---
require_once $root_dir . '/config/database.php';
require_once $root_dir . '/process/models/produk.php';

$db_conn = new Database();
$connection = $db_conn->getConnection();

if (!$connection) {
    $_SESSION['flash_type'] = 'error';
    $_SESSION['flash_message'] = 'Koneksi Database Gagal!';
    header("Location: " . $_SERVER['HTTP_HOST'] . "/HayFarm/pages/admin/manajemen_produk.php");
    exit;
}

$produk_model = new Produk($connection);

// --- FUNGSI HELPER REDIRECT ---
function safe_redirect($relative_path)
{
    if (ob_get_level()) {
        ob_end_clean();
    }
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
    $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $relative_path;
    header("Location: " . $url);
    exit;
}

// --- VALIDASI REQUEST METHOD ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    safe_redirect('/HayFarm/pages/admin/manajemen_produk.php');
}

if (!isset($_POST['aksi'])) {
    $_SESSION['flash_type'] = 'error';
    $_SESSION['flash_message'] = 'Aksi tidak ditemukan!';
    safe_redirect('/HayFarm/pages/admin/manajemen_produk.php');
}

try {
    $action = $_POST['aksi'];

    switch ($action) {
        case 'tambah':
            // ✅ Validasi minimal sebelum normalisasi
            if (empty($_POST['jenis_produk']) || !in_array($_POST['jenis_produk'], ['hewan', 'rumput', 'susu'])) {
                throw new Exception("Jenis produk tidak valid.");
            }
            if (empty($_POST['nama_produk'])) {
                throw new Exception("Nama produk wajib diisi.");
            }
            if (!isset($_POST['harga']) || !is_numeric($_POST['harga']) || floatval($_POST['harga']) <= 0) {
                throw new Exception("Harga harus lebih dari 0.");
            }
            if (!isset($_POST['stok']) || !is_numeric($_POST['stok']) || (int)$_POST['stok'] < 0) {
                throw new Exception("Stok harus angka valid dan tidak negatif.");
            }
            // Validasi id_hewan jika jenis_produk = hewan
            if ($_POST['jenis_produk'] === 'hewan' && (empty($_POST['id_hewan']) || !is_numeric($_POST['id_hewan']))) {
                throw new Exception("Produk hewan harus memilih hewan yang valid.");
            }

            // Normalisasi status
            $status_db = $_POST['status_produk'] ?? 'tersedia';

            $data = [
                'jenis_produk'   => $_POST['jenis_produk'],
                'nama_produk'    => $_POST['nama_produk'],
                'id_hewan'       => ($_POST['jenis_produk'] === 'hewan' && !empty($_POST['id_hewan'])) ? (int)$_POST['id_hewan'] : null,
                'harga'          => floatval($_POST['harga']),
                'stok'           => (int)$_POST['stok'],
                'tgl_kadaluarsa' => !empty($_POST['tgl_kadaluarsa']) ? $_POST['tgl_kadaluarsa'] : '2099-12-31',
                'status_produk'  => $status_db,
            ];

            // Set satuan
            if ($data['jenis_produk'] === 'hewan') {
                $data['satuan'] = 'ekor';
                if ($data['stok'] == 0) $data['stok'] = 1;
            } elseif ($data['jenis_produk'] === 'susu') {
                $data['satuan'] = 'liter';
            } elseif ($data['jenis_produk'] === 'rumput') {
                $data['satuan'] = 'ton';
            }

            $result = $produk_model->create($data);
            $_SESSION['flash_type'] = $result['status'] ? 'success' : 'error';
            $_SESSION['flash_message'] = $result['message'];
            break;

        case 'edit':
            $id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("ID Produk tidak valid.");

            $status_ui = $_POST['status_produk'] ?? 'tersedia';

            // ✅ FIX: Tambahkan 'tidak tersedia' (dengan spasi) di match
            $status_db = match (strtolower(trim($status_ui))) {
                'tersedia', 'available', 'blm_terjual' => 'blm_terjual',
                'tidak-tersedia', 'tidak tersedia', 'terjual', 'sold' => 'terjual', // ← TAMBAH INI
                default => 'blm_terjual'
            };

            $old_product = $produk_model->getById($id);
            if (!$old_product) {
                error_log("Produk ID $id tidak ditemukan di DB"); // ✅ Debug log
                throw new Exception("Produk tidak ditemukan.");
            }

            // ✅ VALIDASI KHUSUS HEWAN: Cek apakah sudah terikat transaksi dikonfirmasi
            if ($old_product['jenis_produk'] === 'hewan') {
                $stmt = $connection->prepare("
            SELECT COUNT(*) as c 
            FROM detail_transaksi dt
            JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
            WHERE dt.id_produk = ? 
            AND t.status_transaksi = 'telah_dikonfirmasi'
        ");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $check = $stmt->get_result()->fetch_assoc();

                // 🔒 Jika sudah dikonfirmasi → BLOCK SEMUA EDIT
                if ($check['c'] > 0) {
                    throw new Exception(
                        "Hewan ini sudah terjual & dikonfirmasi. " .
                            "Data tidak dapat diubah untuk menjaga integritas transaksi. " .
                            "Hubungi manager jika diperlukan koreksi data."
                    );
                }
            }

            // ✅ Siapkan data update HANYA field yang dikirim
            $data = [];
            if (isset($_POST['jenis_produk'])) $data['jenis_produk'] = $_POST['jenis_produk'];
            if (isset($_POST['nama_produk'])) $data['nama_produk'] = $_POST['nama_produk'];
            if (isset($_POST['id_hewan']) && $_POST['jenis_produk'] === 'hewan') {
                $data['id_hewan'] = (int)$_POST['id_hewan'];
            }
            if (isset($_POST['harga'])) $data['harga'] = floatval($_POST['harga']);
            if (isset($_POST['stok'])) $data['stok'] = (int)$_POST['stok'];

            // ✅ NORMALISASI tgl_kadaluarsa: hanya susu yang butuh, lainnya default
            if ($_POST['jenis_produk'] === 'susu' && !empty($_POST['tgl_kadaluarsa'])) {
                $data['tgl_kadaluarsa'] = $_POST['tgl_kadaluarsa'];
            } else {
                $data['tgl_kadaluarsa'] = '2099-12-31'; // Default untuk non-susu atau kosong
            }

            $data['status_produk'] = $status_db; // ✅ Kirim Enum DB

            // ✅ Set satuan otomatis berdasarkan jenis_produk
            if (!empty($data['jenis_produk'])) {
                $data['satuan'] = match ($data['jenis_produk']) {
                    'hewan' => 'ekor',
                    'susu' => 'liter',
                    'rumput' => 'ton',
                    default => ''
                };
                // Khusus hewan: stok minimal 1
                if ($data['jenis_produk'] === 'hewan' && isset($data['stok']) && $data['stok'] < 1) {
                    $data['stok'] = 1;
                }
            }

            $result = $produk_model->update($id, $data);

            // ✅ Debug: log result jika gagal
            if (!$result['status']) {
                error_log("Update produk $id gagal: " . $result['message']);
            }

            $_SESSION['flash_type'] = $result['status'] ? 'success' : 'error';
            $_SESSION['flash_message'] = $result['message'];
            break;
        case 'hapus':
            $id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("ID Produk tidak valid.");

            $result = $produk_model->delete($id);

            if ($result['status']) {
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_type'] = 'error';
            }
            $_SESSION['flash_message'] = $result['message'];
            break;

        default:
            throw new Exception("Aksi tidak dikenali.");
    }
} catch (Exception $e) {
    $_SESSION['flash_type'] = 'error';
    $_SESSION['flash_message'] = $e->getMessage();
}

safe_redirect('/HayFarm/pages/admin/manajemen_produk.php');
