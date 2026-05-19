# Panduan Migrasi safe_redirect & Hosting InfinityFree

Dokumen ini berisi template kode baru untuk fungsi `safe_redirect` serta daftar file dan bagian baris kode mana saja yang perlu diganti setelah Anda melakukan `git pull`.

---

## 1. Template Baru Fungsi `safe_redirect` (Universal & Smart)

Fungsi `safe_redirect` baru ini dirancang agar **otomatis mendeteksi lingkungan server**. Fungsi ini akan:
- Mengizinkan redirect dengan path relatif (seperti `../../pages/admin/...`).
- Menghapus prefiks `/HayFarm` secara otomatis jika dijalankan di hosting (karena hosting biasanya berjalan di root `/`, sedangkan local Laragon berjalan di subfolder `/HayFarm`).

```php
function safe_redirect($path)
{
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // 1. Jika path bertipe relatif (tidak diawali /), biarkan browser menangani secara native
    if (strpos($path, '/') !== 0) {
        header("Location: " . $path);
        exit;
    }

    // 2. Jika path absolut, deteksi subfolder proyek secara dinamis
    $script_name = $_SERVER['SCRIPT_NAME'];
    $handler_pos = strpos($script_name, '/process/handlers/');
    $base_path = '';
    if ($handler_pos !== false) {
        $base_path = substr($script_name, 0, $handler_pos);
    }

    // 3. Hilangkan folder local '/HayFarm' jika hosting tidak menggunakannya
    if (strpos($path, '/HayFarm') === 0) {
        $path = substr($path, strlen('/HayFarm'));
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
    $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $base_path . $path;
    header("Location: " . $url);
    exit;
}
```

---

## 2. Bagian Kode yang Harus Diganti pada Masing-Masing File

Setelah Anda melakukan `pull` dari teman Anda, lakukan pencarian dan penggantian di 3 file berikut:

### File 1: `process/handlers/kesehatan_handler.php`

1. **Ganti definisi fungsi `safe_redirect` lama** (biasanya di sekitar baris 18) dengan **Template Baru** di atas.
2. **Ganti pemanggilan redirect di baris pemeriksaan request POST (atas)**:
   * **Lama**:
     ```php
     if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
         safe_redirect('/HayFarm/pages/admin/data_kesehatan.php');
     }
     ```
   * **Baru**:
     ```php
     if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
         safe_redirect('../../pages/admin/data_kesehatan.php');
     }
     ```
3. **Ganti pemanggilan redirect di akhir file**:
   * **Lama**:
     ```php
     safe_redirect('/HayFarm/pages/admin/data_kesehatan.php');
     ```
   * **Baru**:
     ```php
     safe_redirect('../../pages/admin/data_kesehatan.php');
     ```

---

### File 2: `process/handlers/produk_handler.php`

1. **Pindahkan definisi fungsi `safe_redirect` ke baris paling atas** (tepat di bawah `$root_dir = dirname(__DIR__, 2);`) menggunakan **Template Baru** di atas. Hal ini agar fungsi `safe_redirect` dapat dipanggil sejak awal jika koneksi database gagal.
2. **Ganti blok penanganan error koneksi database**:
   * **Lama**:
     ```php
     if (!$connection) {
         $_SESSION['flash_type'] = 'error';
         $_SESSION['flash_message'] = 'Koneksi Database Gagal!';
         header("Location: " . $_SERVER['HTTP_HOST'] . "/HayFarm/pages/admin/manajemen_produk.php");
         exit;
     }
     ```
   * **Baru**:
     ```php
     if (!$connection) {
         $_SESSION['flash_type'] = 'error';
         $_SESSION['flash_message'] = 'Koneksi Database Gagal!';
         safe_redirect('../../pages/admin/manajemen_produk.php');
     }
     ```
3. **Hapus definisi fungsi `safe_redirect` lama** yang berada di tengah file (biasanya setelah `$produk_model`).
4. **Ganti semua pemanggilan `safe_redirect` lama** menjadi path relatif:
   * **Lama**: `safe_redirect('/HayFarm/pages/admin/manajemen_produk.php');`
   * **Baru**: `safe_redirect('../../pages/admin/manajemen_produk.php');`

---

### File 3: `process/handlers/hewan_handler.php`

1. **Pindahkan definisi fungsi `safe_redirect` ke baris paling atas** (tepat di bawah `$root_dir = dirname(__DIR__, 2);`) menggunakan **Template Baru** di atas.
2. **Ganti blok penanganan error koneksi database**:
   * **Lama**:
     ```php
     if (!$connection) {
         $_SESSION['flash_type'] = 'error';
         $_SESSION['flash_message'] = 'Koneksi Database Gagal!';
         header("Location: " . $_SERVER['HTTP_HOST'] . "/HayFarm/pages/admin/data_hewan.php");
         exit;
     }
     ```
   * **Baru**:
     ```php
     if (!$connection) {
         $_SESSION['flash_type'] = 'error';
         $_SESSION['flash_message'] = 'Koneksi Database Gagal!';
         safe_redirect('../../pages/admin/data_hewan.php');
     }
     ```
3. **Hapus definisi fungsi `safe_redirect` lama** yang berada di tengah file.
4. **Ganti semua pemanggilan `safe_redirect` lama** menjadi path relatif:
   * **Lama**: `safe_redirect('/HayFarm/pages/admin/data_hewan.php');`
   * **Baru**: `safe_redirect('../../pages/admin/data_hewan.php');`

---

## 3. Tambahan: Konfigurasi Database saat Hosting (`config/database.php`)

Agar Anda tidak perlu mengedit file `config/database.php` setiap kali melakukan upload ke hosting atau ditarik ke local, Anda bisa menggunakan template database pintar yang mendeteksi host otomatis berikut:

```php
<?php
class Database
{
    private string $host;
    private string $user;
    private string $pass;
    private string $db;
    public mysqli $conn;

    public function __construct()
    {
        // Deteksi secara otomatis jika berjalan di Local Laragon (localhost)
        if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
            $this->host = "localhost";
            $this->user = "root";
            $this->pass = "";
            $this->db   = "hayfarm";
        } else {
            // SETELAN PRODUCTION / HOSTING INFINITYFREE
            $this->host = "sqlXXX.infinityfree.com"; // Sesuaikan dengan MySQL Host dari cPanel Anda
            $this->user = "epiz_XXXXXXXX";           // Sesuaikan dengan Username cPanel Anda
            $this->pass = "PasswordAnda";            // Sesuaikan dengan Password cPanel Anda
            $this->db   = "epiz_XXXXXXXX_hayfarm";   // Sesuaikan dengan Nama DB di cPanel Anda
        }

        mysqli_report(MYSQLI_REPORT_OFF);
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_errno) {
            die('Koneksi database gagal: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}

if (!isset($db) || !($db instanceof mysqli)) {
    $database = new Database();
    $db = $database->getConnection();
}
?>
```
Dengan template database ini, Anda cukup menyetel kredensial hosting sekali saja di bagian `else`, dan file ini akan otomatis memilih kredensial yang tepat sesuai tempat kode dijalankan.
