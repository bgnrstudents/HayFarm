<?php
ob_start();

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

$root_dir = dirname(__DIR__, 2);

// --- KONEKSI DATABASE & MODEL ---
require_once $root_dir . '/config/database.php';
require_once $root_dir . '/process/models/hewan.php';

$db_conn = new Database();
$connection = $db_conn->getConnection();

if (!$connection) {
    $_SESSION['flash_type'] = 'error';
    $_SESSION['flash_message'] = 'Koneksi Database Gagal!';
    header("Location: " . $_SERVER['HTTP_HOST'] . "/HayFarm/pages/admin/data_hewan.php");
    exit;
}

$hewan_model = new Hewan($connection);

// --- FUNGSI HELPER REDIRECT ---
function safe_redirect($relative_path)
{
    // Bersihkan buffer output sebelum mengirim header
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
    safe_redirect('/HayFarm/pages/admin/data_hewan.php');
}

// --- VALIDASI ACTION ---
if (!isset($_POST['action'])) {
    $_SESSION['flash_type'] = 'error';
    $_SESSION['flash_message'] = 'Aksi tidak ditemukan!';
    safe_redirect('/HayFarm/pages/admin/data_hewan.php');
}

// --- PROSES LOGIKA ---
try {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':

            // Ambil data TANPA validasi (biar model yang handle)
            $data = [
                'kode_hewan'    => strtoupper(trim($_POST['kode_hewan'] ?? '')),
                'jenis_hewan'   => $_POST['jenis_hewan'] ?? '',
                'berat_badan'   => $_POST['berat_badan'] ?? 0,
                'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
                'no_kandang'    => strtoupper(trim($_POST['no_kandang'] ?? '')),
                'tgl_lahir'     => $_POST['tgl_lahir'] ?? '',
                'status_hewan'  => $_POST['status_hewan'] ?? 'produktif',
                'foto_hewan'    => ''
            ];

            // Handle upload (tetap di handler karena ini urusan file)
            // Ganti bagian handle upload dengan ini:
            if (isset($_FILES['foto_hewan']) && $_FILES['foto_hewan']['error'] === 0) {
                $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['foto_hewan']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed_ext, true)) {
                    throw new Exception('Format foto tidak didukung. Gunakan JPG, PNG, atau WebP');
                }

                if ($_FILES['foto_hewan']['size'] > 2 * 1024 * 1024) {
                    throw new Exception('Ukuran foto maksimal 2MB');
                }

                // Validasi MIME type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['foto_hewan']['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
                    throw new Exception('File tidak valid. Pastikan ini adalah gambar');
                }

                $upload_dir = $root_dir . '/uploads/hewan/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $new_filename = 'hewan_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $target_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['foto_hewan']['tmp_name'], $target_path)) {
                    $data['foto_hewan'] = 'uploads/hewan/' . $new_filename;
                } else {
                    throw new Exception('Gagal mengupload foto');
                }
            }

            // Panggil model → BIAR MODEL YANG VALIDASI
            $result = $hewan_model->create($data);

            if ($result['status']) {
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_type'] = 'error';
            }

            $_SESSION['flash_message'] = $result['message'];

            break;

        case 'update':
            // 1. Validasi ID
            $id = filter_input(INPUT_POST, 'id_hewan', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("ID Hewan tidak valid.");

            // 2. Persiapkan Data (hanya field yang dikirim form)
            $data = [];
            if (isset($_POST['kode_hewan'])) $data['kode_hewan'] = strtoupper(trim($_POST['kode_hewan']));
            if (isset($_POST['jenis_hewan'])) $data['jenis_hewan'] = $_POST['jenis_hewan'];
            if (isset($_POST['berat_badan'])) $data['berat_badan'] = floatval($_POST['berat_badan']);
            if (isset($_POST['jenis_kelamin'])) $data['jenis_kelamin'] = $_POST['jenis_kelamin'];
            if (isset($_POST['no_kandang'])) $data['no_kandang'] = strtoupper(trim($_POST['no_kandang']));
            if (isset($_POST['tgl_lahir'])) $data['tgl_lahir'] = $_POST['tgl_lahir'];
            if (isset($_POST['status_hewan'])) {
                $data['status_hewan'] = ($_POST['status_hewan'] === 'tdk_produktif') ? 'tdk_produktif' : 'produktif';
            }

            // 3. Handle Upload Foto (Jika user ganti foto)
            if (isset($_FILES['foto_hewan']) && $_FILES['foto_hewan']['error'] === 0) {
                $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['foto_hewan']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowed_ext) && $_FILES['foto_hewan']['size'] <= 2000000) {
                    $upload_dir = $root_dir . '/uploads/hewan/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                    $new_filename = 'hewan_' . time() . '_' . uniqid() . '.' . $ext;
                    $target_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['foto_hewan']['tmp_name'], $target_path)) {
                        $data['foto_hewan'] = 'uploads/hewan/' . $new_filename;
                    }
                }
            }

            // 4. Panggil Model
            $result = $hewan_model->update($id, $data);

            if ($result['status']) {
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = $result['message'];
            } else {
                throw new Exception($result['message']);
            }
            break;

        case 'delete':
            $id = filter_input(INPUT_POST, 'id_hewan', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("ID Hewan tidak valid.");

            // Cek apakah hewan ini sudah pernah dibeli (di transaksi)
            $stmt_check = $connection->prepare("
                SELECT COUNT(*) as c FROM detail_transaksi dt
                JOIN data_produk p ON dt.id_produk = p.id_produk
                WHERE p.id_hewan = ?
            ");
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            $is_sold = $stmt_check->get_result()->fetch_assoc()['c'] > 0;

            // MULAI TRANSACTION
            $connection->begin_transaction();
            try {
                // 1. Soft Delete Reproduksi (Riwayat IB)
                $stmt = $connection->prepare("
                    UPDATE data_reproduksi 
                    SET is_deleted = 1, deleted_at = NOW() 
                    WHERE id_hewan = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                // 2. Soft Delete Kesehatan (Riwayat Medis)
                $stmt = $connection->prepare("
                    UPDATE data_kesehatan 
                    SET is_deleted = 1, deleted_at = NOW() 
                    WHERE id_hewan = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                // 3. Update Status Produk
                $stmt = $connection->prepare("
                    UPDATE data_produk 
                    SET status_produk = 'terjual' 
                    WHERE id_hewan = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                // 4. Soft Delete Hewan (Induk)
                $stmt = $connection->prepare("
                    UPDATE data_ternak 
                    SET is_deleted = 1, deleted_at = NOW() 
                    WHERE id_hewan = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                $connection->commit();

                $msg = $is_sold
                    ? "Hewan sudah terjual. Data berhasil diarsipkan untuk riwayat."
                    : "Data hewan berhasil diarsipkan.";

                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = $msg;
            } catch (Exception $e) {
                $connection->rollback();
                throw new Exception("Gagal mengarsipkan data: " . $e->getMessage());
            }
            break;
    }
} catch (Exception $e) {
    $_SESSION['flash_type'] = 'error';
    $_SESSION['flash_message'] = $e->getMessage();
}

// --- REDIRECT AKHIR ---
safe_redirect('/HayFarm/pages/admin/data_hewan.php');
