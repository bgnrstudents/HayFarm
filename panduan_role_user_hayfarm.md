# PANDUAN MENTORING PRIVAT: BEDAH ROLE USER (PEMBELI) - HAY FARM

Selamat datang kembali! Sesuai permintaan Anda, kita akan membedah **khusus Role User (Pembeli)** secara mendalam, step-by-step. Panduan ini menggunakan bahasa yang santai namun tetap teknis agar Anda bisa menjelaskan dengan sangat matang dan lancar di hadapan dosen penguji besok.

---

## 1. GAMBARAN ROLE USER (PEMBELI)

### A. Tujuan Role User
Role Pembeli dirancang untuk memfasilitasi transaksi jual beli produk peternakan secara langsung (D2C - *Direct to Consumer*). Pembeli dapat:
* Mengamati produk peternakan (sapi perah, sapi PO, susu segar, dan rumput gajah) secara transparan.
* Melihat rekam medis (riwayat kesehatan & catatan dokter) sapi hidup sebelum dibeli untuk menjamin kualitas.
* Memesan dan membayar secara fleksibel (Transfer Bank dengan verifikasi manual atau Cash On Delivery).

### B. Fitur Utama User
1. **Katalog Produk Dinamis**: Menampilkan produk berdasarkan ketersediaannya di database (hanya status `blm_terjual` yang tampil).
2. **Detail Kesehatan Ternak (Modal Popup)**: Menampilkan data medis sapi real-time menggunakan AJAX.
3. **Keranjang Belanja Real-Time (AJAX)**: Menambahkan, memperbarui kuantitas, atau menghapus item tanpa *page reload*.
4. **Checkout Terintegrasi**: Mengisi alamat pengiriman dan mengunggah bukti pembayaran (untuk transfer).
5. **Riwayat Pemesanan & Status Tracking**: Memantau apakah pesanan berstatus *menunggu verifikasi*, *dikonfirmasi*, atau *dibatalkan*.

### C. Alur Besar (Flow) User
```
[Daftar Akun] ──> [Masuk Sesi (Login)] ──> [Jelajahi Produk & Kesehatan Sapi] 
                                                        │
                                             (Tambah ke Keranjang)
                                                        │
                                                        ▼
[Riwayat Transaksi] <── [Kirim Form Checkout] <── [Review di Keranjang]
```

---

## 2. DIVERSIFIKASI HALAMAN USER (FRONTEND & BACKEND)

Di bawah ini adalah pemetaan halaman yang diakses oleh pembeli:

### 1. Halaman Utama (Home)
* **Fungsi**: Landing page untuk mengenalkan brand "Hay Farm", kelebihan peternakan, dan produk unggulan.
* **File Terkait**: `pages/user/home.php` (tampilan), `index.php` (router).
* **Hubungan Front & Back**: Statis sebagian besar, namun menu di navbar mendeteksi apakah pembeli sudah login atau belum untuk menampilkan tombol "Login" atau "Logout".

### 2. Katalog Produk
* **Fungsi**: Menampilkan seluruh komoditas yang siap dijual beserta filter harga, kategori, dan status.
* **File Terkait**: `pages/user/produk.php`, `process/models/produk.php`.
* **Hubungan Front & Back**:
  * **Backend**: Mengambil data produk dari database menggunakan model `Produk->getAllForUserView()`.
  * **Frontend**: Menerima array data, merender card produk dinamis (susu, rumput, sapi) dengan CSS custom (`produk.css`), memproses filter dinamis dengan Javascript, dan menangani interaksi tombol "Beli" atau "Keranjang" menggunakan AJAX.

### 3. Detail Produk / Hewan
* **Fungsi**: Menampilkan detail rekam medis hewan ternak sebelum dibeli (ID ternak, umur, jenis kelamin, riwayat pemeriksaan, catatan dokter).
* **File Terkait**: `public/js/script.js` (fungsi `showDetail`), `process/handlers/get_produk_detail.php` (AJAX handler).
* **Hubungan Front & Back**: Ketika tombol "Detail" diklik, JS memicu fungsi `showDetail(this)`, mengirim request AJAX fetch ke `get_produk_detail.php?id_produk=X`. Handler membalas dengan format JSON yang berisi riwayat kesehatan dari database, lalu JS merender tabel data tersebut ke dalam Modal Bootstrap secara instan tanpa memuat ulang halaman.

### 4. Keranjang Belanja (Cart)
* **Fungsi**: Tempat penampungan sementara produk pilihan pembeli sebelum dibeli.
* **File Terkait**: `pages/user/keranjang.php`, `process/models/keranjang.php`, `process/handlers/cart_handler.php` (AJAX Processor).
* **Hubungan Front & Back**: Setiap perubahan jumlah barang (input quantity) dikirim via AJAX POST ke `cart_handler.php`. Handler memanggil model `Keranjang->updateJumlah()`, memvalidasi stok di database, dan mengembalikan JSON berisi total harga terbaru untuk diperbarui langsung di tampilan.

### 5. Checkout
* **Fungsi**: Form pengisian alamat pengiriman, pemilihan metode pembayaran (Transfer/COD), dan unggah bukti transfer.
* **File Terkait**: `pages/user/chekout.php`, `process/handlers/transaction.php`.
* **Hubungan Front & Back**: Form dikirim menggunakan AJAX ke `transaction.php`. Backend memproses file gambar bukti transfer, memulai transaksi database, memotong stok, mengosongkan keranjang pembeli, dan mengembalikan JSON sukses untuk mengarahkan pengguna ke halaman riwayat pesanan.

### 6. Riwayat Transaksi
* **Fungsi**: Menampilkan daftar belanjaan masa lalu pembeli lengkap dengan badge status verifikasi admin dan link kontak admin.
* **File Terkait**: `pages/user/riwayat_pesanan.php`, `process/models/transaksi.php`.
* **Hubungan Front & Back**: Backend mengambil data transaksi milik user (`$_SESSION['id_user']`) lewat kueri `getTransaksiByUser()`, meng-render daftar card riwayat pesanan, dan menampilkan detail produk yang telah dibeli beserta rekam medisnya saat dibeli.

### 7. Login & Register
* **Fungsi**: Masuk sesi dan pendaftaran pembeli baru.
* **File Terkait**: `login.php`, `register.php`, `process/auth/Auth.php`.
* **Hubungan Front & Back**: Form POST diverifikasi oleh class `Auth`. Jika register berhasil, dialihkan ke login. Jika login berhasil, data user dimasukkan ke session dan dialihkan ke `index.php`.

---

## 3. FLOW STEP-BY-STEP (ALUR DATA & AKSI USER)

Mari kita bayangkan skenario pembeli bernama **Budi** yang ingin membeli susu segar:

### Step 1: Login/Register
1. Budi mengisi form login di `login.php` -> Submit POST.
2. Backend (`Auth->login`) memverifikasi email dan password (menggunakan `password_verify`).
3. Jika benar, system memanggil `session_regenerate_id(true)` (keamanan sesi) dan menyimpan `$_SESSION['role'] = 'pembeli'`, `$_SESSION['login'] = true`, `$_SESSION['id_user'] = Budi_ID`.
4. Budi diarahkan ke halaman utama `index.php`.

### Step 2: Melihat Produk & Detail Medis
1. Budi masuk ke menu Produk (`index.php?page=user/produk`).
2. Tampilan mengambil daftar produk komoditas peternakan yang siap dijual (`status_produk = 'blm_terjual'`).
3. Budi tertarik pada **Sapi PO 01**. Ia mengklik tombol **"Detail"**.
4. JavaScript (`public/js/script.js -> showDetail`) mengirim request AJAX ke `get_produk_detail.php?id_produk=Sapi_PO_ID`.
5. Database mengecek tabel `data_kesehatan` dan `data_reproduksi` untuk sapi tersebut, mengembalikannya sebagai JSON, dan JS menampilkannya di modal popup secara dinamis.

### Step 3: Tambah Keranjang Belanja
1. Budi memilih kuantitas (misal 5 liter Susu Segar) lalu klik **"Tambah ke Keranjang"**.
2. Javascript menangkap event klik, menampilkan **Confirm Overlay modal** (*"Apakah Anda ingin memasukkan..."*).
3. Budi klik **"Ya, Tambahkan"**.
4. Request AJAX POST dikirim ke `process/handlers/cart_handler.php` dengan parameter `action=add`, `id_produk`, dan `jumlah`.
5. Backend memanggil model `Keranjang->tambahItem()`. Sistem memeriksa stok susu segar di database:
   * Jika stok mencukupi, item dimasukkan ke tabel `detail_keranjang` (atau jumlahnya bertambah jika sudah ada) dan mengembalikan respons `{status: true, cart_count: X}`.
   * Nilai `$_SESSION['cart_count']` diperbarui di backend.
6. Frontend menerima JSON sukses, memperbarui angka badge keranjang di navbar, dan memunculkan **Toast Notification** berwarna hijau di pojok kanan bawah.

### Step 4: Checkout & Transaksi
1. Budi mengklik ikon keranjang belanja -> dialihkan ke `index.php?page=user/keranjang`.
2. Di halaman keranjang, Budi mereview belanjaannya. Ia mengklik **"Lanjut ke Checkout"**.
3. Budi diarahkan ke halaman `chekout.php?source=cart`. Halaman ini mengunci item belanjaan Budi.
4. Budi mengisi form Alamat, Telepon, dan Kode Pos.
5. Budi memilih metode **"Transfer Bank"**.
6. Sistem menampilkan informasi rekening BCA Hay Farm dan menampilkan input file bukti transfer.
7. Budi memfoto bukti struk ATM dan mengunggahnya (`fileBukti`).
8. Budi mengklik **"Bayar Sekarang"**.

### Step 5: Proses Transaksi di Backend & Pengurangan Stok
1. Form data dikirim via JavaScript `processCheckout` ke `process/handlers/transaction.php`.
2. Backend memvalidasi input:
   * Memastikan no HP valid (regex `^08\d{8,12}$`).
   * Memeriksa keberadaan file struk, membatasi format file (`jpg/jpeg/png`) dan ukuran file (maksimal 5MB).
   * Memindahkan file terunggah ke `/uploads/bukti/` dengan nama acak unik (menghindari penimpaan file).
3. Backend memanggil `Database->begin_transaction()` (Mulai transaksi database terisolasi).
4. Model `Transaksi->buatTransaksi()` melakukan perulangan pada setiap barang belanjaan Budi:
   * Melakukan kueri SQL `SELECT stok FROM data_produk WHERE id_produk = ? FOR UPDATE` (Mengunci baris stok agar tidak dibeli orang lain saat itu juga).
   * Memverifikasi stok real-time di database.
   * Mengurangi stok produk: `stok = stok - jumlah`. Jika stok hasil pengurangan bernilai `0`, status produk diubah menjadi `'terjual'`.
   * Memasukkan data transaksi ke tabel `transaksi` (mendapatkan `id_transaksi`).
   * Memasukkan rincian barang ke tabel `detail_transaksi`.
5. Backend memanggil `Transaksi->kosongkanKeranjangUser()` untuk menghapus item belanjaan Budi di keranjang belanja.
6. Backend memanggil `$db->commit()` untuk menyimpan seluruh transaksi secara permanen ke harddisk server. (Jika di tengah jalan ada error, otomatis `$db->rollback()` untuk mengembalikan data seperti semula).
7. Frontend menerima respons JSON sukses, memunculkan **Modal Sukses** (*"Bukti Pembayaran Terkirim!"*), dan mengalihkan Budi ke halaman Riwayat Pesanan dengan status pesanan: **Menunggu Verifikasi**.

---

## 4. FRONTEND USER (STUDI DESAIN & INTERAKSI)

### A. Struktur Halaman User
Semua halaman user menggunakan layout modular:
1. `components/header.php`: Berisi tag `<head>`, viewport, Google Fonts (Nunito & Poppins), ikon FontAwesome, dan stylesheet CSS.
2. `components/navbar.php`: Navigasi utama dengan tombol home, produk, tentang kami, ikon keranjang belanja dinamis (badge angka jumlah barang), dan nama user/tombol logout.
3. `pages/user/{halaman}.php`: Konten utama halaman.
4. `components/footer.php`: Kaki halaman peternakan.

### B. Bootstrap & Kustomisasi CSS
Sistem menggunakan **Bootstrap 5.3.3** untuk tata letak responsif (*grid system*: `row`, `col-lg-3`, `col-md-12`). Kustomisasi CSS diletakkan pada:
* `public/css/style.css` (Gaya global).
* `public/css/produk.css` (Visual khusus halaman katalog produk).
* `public/css/keranjang.css` & `public/css/chekout.css`.

**Desain Premium yang Diterapkan**:
* **Glassmorphism / Backdrop Filter**: Digunakan pada panel confirm overlay (`backdrop-filter: blur(5px)`).
* **Color Harmony**: Dominasi warna hijau alam (`#196c33`), hijau muda (`#f1f9f4`), dan teks gelap (`#1a1a1a`) untuk membangun citra peternakan modern yang bersih dan segar.
* **Micro-Animations (Transisi Smooth)**:
  * Animasi tombol hover (zoom tipis / perubahan warna bertahap).
  * Animasi bounce/pop pada badge angka keranjang di navbar saat barang ditambahkan (`badge-pop`).
  * Efek memudar (*fade-in reveal*) pada card produk menggunakan JavaScript `IntersectionObserver` (`public/js/script.js -> initHayFarmMotion`).

---

## 5. BACKEND USER (LOGIKA KODE & ALUR DATA)

### A. Session User
Session memegang peran penting dalam mengamankan alur data belanja.
Variabel session yang dipakai pembeli:
* `$_SESSION['login']` (boolean): Flag penunjuk status login.
* `$_SESSION['id_user']` (int): Menyimpan ID pengguna untuk relasi query (tambah keranjang, checkout, riwayat).
* `$_SESSION['role']` (string): Harus bernilai `'pembeli'` untuk membedakannya dengan admin/manager.
* `$_SESSION['cart_count']` (int): Angka keranjang belanja yang ditampilkan di navbar badge.

### B. Kueri-Kueri Database Utama Milik User

#### 1. Query Mengambil Produk dan Detail Kesehatan Terbaru (Catalog View)
Diambil di `pages/user/produk.php` lewat fungsi `getAllForUserView()` di model `Produk.php`:
```sql
SELECT 
    p.*, 
    t.foto_hewan, t.kode_hewan, t.tgl_lahir, t.no_kandang, t.jenis_hewan, t.jenis_kelamin,
    k.status_kesehatan AS status_kesehatan_terakhir,
    k.tgl_pemeriksaan AS tgl_pemeriksaan_terakhir,
    k.catatan AS catatan_kesehatan_terakhir
FROM data_produk p
LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan
LEFT JOIN data_kesehatan k ON k.id_kesehatan = (
    SELECT id_kesehatan 
    FROM data_kesehatan 
    WHERE id_hewan = t.id_hewan 
    ORDER BY tgl_pemeriksaan DESC, id_kesehatan DESC 
    LIMIT 1
)
WHERE p.status_produk = 'blm_terjual'
  AND (p.jenis_produk <> 'hewan' OR t.jenis_hewan IN ('sapi_perah', 'sapi_po'));
```
* **Mengapa query ini istimewa?** Karena query ini menggunakan **Subquery** berkinerja tinggi untuk mengambil data pemeriksaan kesehatan *terakhir* milik sapi tersebut secara otomatis agar pembeli melihat kondisi medis terupdate.

#### 2. Query Mengunci Stok Real-Time saat Checkout (`SELECT FOR UPDATE`)
Eksekusi query ini dilakukan di dalam metode `buatTransaksi()` pada file `process/models/transaksi.php`:
```sql
SELECT stok, status_produk 
FROM data_produk 
WHERE id_produk = ? 
FOR UPDATE;
```
* **Fungsi**: Meminta MySQL mengunci baris produk ini. Pengguna lain tidak bisa membeli atau mengedit stok produk ini sampai transaksi pembayaran Budi selesai diproses.

#### 3. Query Pengurangan Stok
Dipanggil di model `Transaksi.php`:
```sql
UPDATE data_produk
SET stok = GREATEST(stok - ?, 0),
    status_produk = IF(GREATEST(stok - ?, 0) <= 0, 'terjual', 'blm_terjual')
WHERE id_produk = ?;
```
* **Fungsi**: Mengurangi stok produk di database dan mencegah stok bernilai negatif menggunakan fungsi `GREATEST(stok - ?, 0)`. Jika stok mencapai 0, otomatis mengubah status produk menjadi `'terjual'`.

---

## 6. SINKRONISASI FILE (BAGAIMANA FILE SALING BERHUBUNGAN)

Berikut peta interaksi file saat pembeli melakukan transaksi:

```
                  [ index.php (Router) ]
                            │ (include)
                            ▼
               [ pages/user/chekout.php ]  <──(AJAX)──> [ public/js/script.js ]
                            │                                     │
                     (Submit Form POST)                     (Fetch Request)
                            │                                     │
                            ▼                                     ▼
            [ process/handlers/transaction.php ] <────────────────┘
                            │ (Panggil Object & Method)
                            ▼
          [ process/models/transaksi.php (OOP) ]
                            │ (Gunakan Koneksi)
                            ▼
              [ config/database.php (OOP) ]
```

---

## 7. SKEMA TABEL DATABASE YANG DIAKSES USER

Aktivitas pembeli berpusat pada 5 tabel berikut:

1. **`user`**: Menyimpan data login Budi (`id_user`, `username`, `email`, `password` bcrypt).
2. **`keranjang`**: Menyimpan ID keranjang belanja aktif milik user.
   * `id_keranjang` (PK), `id_user` (FK ke tabel `user`).
3. **`detail_keranjang`**: Item-item produk di dalam keranjang belanja.
   * `id_detail_keranjang` (PK), `id_keranjang` (FK), `id_produk` (FK ke `data_produk`), `jumlah` (Qty), `harga` (Snapshot harga), `sub_total`.
4. **`transaksi`**: Header pemesanan.
   * `id_transaksi` (PK), `id_user` (FK), `nama_pembeli`, `no_telp`, `alamat`, `metode_pembayaran`, `bukti_pembayaran` (path file), `tgl_transaksi`, `total_tagihan`, `status_transaksi`.
5. **`detail_transaksi`**: Rincian produk yang dibeli (snapshot permanen).
   * `id_detail_transaksi` (PK), `id_transaksi` (FK), `id_produk` (FK), `jumlah`, `harga`, `sub_total`.

---

## 8. POTENSI BUG & TECHNICAL DEBT PADA FLOW USER

Dosen penguji sangat menyukai analisis kritis. Anda bisa menunjukkan pemahaman mendalam dengan menjelaskan celah berikut:

1. **Race Condition pada Checkout Tanpa Lock di Handler**:
   * *Analisis*: Meskipun model `Transaksi` menggunakan `FOR UPDATE` di SQL, handler `transaction.php` sempat menaruh validasi stok di luar transaksi database (baris 134-158 yang dicentang komentar).
   * *Solusi*: "Kami menonaktifkan pengecekan stok di level handler dan memusatkannya langsung di dalam blok transaksi database (`begin_transaction`) pada model `Transaksi->buatTransaksi()`. Hal ini menjamin pengecekan stok terlindung oleh penguncian data (*row locking*) MySQL."
2. **Format Validasi Nomor Telepon**:
   * *Analisis*: Regex validasi nomor telepon di backend (`process/handlers/transaction.php`) sangat ketat: `/^08\d{8,12}$/`.
   * *Bug Potensial*: Jika pembeli memasukkan nomor telepon dengan format internasional `+628...` atau memisahkan dengan tanda hubung `0812-3456-7890`, sistem akan menolak transaksi.
   * *Solusi*: Di backend, handler membersihkan karakter non-angka terlebih dahulu: `$noTelp = preg_replace('/[^0-9]/', '', $noTelp)` sebelum divalidasi oleh regex.
3. **Pemberihan Keranjang Belanja**:
   * *Analisis*: Jika pembeli melakukan checkout langsung (*Direct Buy* / klik tombol "Beli" di satu produk), sistem akan langsung mengarahkan ke halaman checkout.
   * *Bug Potensial*: Jika proses selesai, kode di `transaction.php` hanya menghapus isi keranjang belanja pembeli (`kosongkanKeranjangUser`) jika parameter `source === 'cart'`. Ini sudah benar agar barang di keranjang tidak hilang secara tidak sengaja ketika pembeli melakukan pembelian langsung untuk produk lain.

---

## 9. PERSIAPAN PERTANYAAN DOSEN (PREDIKSI & CARA MENJAWAB)

### Pertanyaan 1: "Bagaimana cara sistem Anda menjaga agar stok barang tetap konsisten ketika ada dua orang membeli barang yang sama secara bersamaan?"
* **Jawaban Anda**:
  > *"Di backend, kami menggunakan mekanisme **Database Transaction** yang didukung oleh **MySQL InnoDB**. Di dalam method `buatTransaksi` pada file `transaksi.php`, sebelum mengubah stok produk, kami memanggil perintah `SELECT stok FROM data_produk WHERE id_produk = ? FOR UPDATE`. Perintah `FOR UPDATE` ini mengunci baris data produk tersebut agar pembeli lain tidak bisa mengubah stok sampai transaksi pembeli pertama selesai (`COMMIT` atau `ROLLBACK`). Dengan begitu, tidak akan terjadi stok bernilai negatif atau penjualan melebihi kapasitas."*

### Pertanyaan 2: "Mengapa Anda menggunakan AJAX untuk menambahkan produk ke keranjang belanja?"
* **Jawaban Anda**:
  > *"Penggunaan AJAX (Asynchronous JavaScript and XML) bertujuan untuk meningkatkan kenyamanan pembeli (User Experience). Dengan AJAX, data produk dikirim ke backend (`cart_handler.php`) di latar belakang. Browser tidak perlu memuat ulang seluruh halaman katalog produk. Pengguna tetap berada di halaman yang sama, badge angka keranjang di navbar langsung terupdate secara dinamis, dan sistem langsung menampilkan Toast Notification sukses."*

### Pertanyaan 3: "Bagaimana sistem membedakan pembeli yang belum login dan sudah login saat mengklik tombol Beli?"
* **Jawaban Anda**:
  > *"Di file `pages/user/produk.php`, kami mengecek variabel sesi pembeli dengan kode `isset($_SESSION['login'])`. Status login ini kami lempar ke variabel JavaScript `isLoggedIn`. Jika pembeli belum login dan mengklik tombol Beli, fungsi Javascript `handleOrder` akan membatalkan pengiriman data, menutup konfirmasi, dan langsung menampilkan modal popup `loginPromptModal` yang meminta pengguna untuk login terlebih dahulu. Informasi produk yang ingin dibeli disimpan sementara di `sessionStorage` browser agar setelah login berhasil, pembeli dapat otomatis melanjutkan proses belanjanya tanpa memilih ulang."*

### Pertanyaan 4: "Bagaimana proses verifikasi bukti transfer pembayaran dilakukan?"
* **Jawaban Anda**:
  > *"Saat checkout dengan metode transfer, pembeli wajib mengunggah file struk. File tersebut dikirim ke server lewat handler `transaction.php` dan divalidasi tipe ekstensinya (hanya JPG, JPEG, PNG) serta ukuran maksimalnya (5MB) demi keamanan server dari file berbahaya. File disimpan di folder `uploads/bukti/` dengan nama yang diacak menggunakan `bin2hex(random_bytes(4))` untuk menghindari bentrokan nama file. Status transaksi pembeli diatur menjadi 'menunggu_verifikasi'. Admin kemudian meninjau bukti tersebut di halaman dashboard admin untuk mengonfirmasi atau membatalkan pesanan."*

---
Gunakan dokumen panduan role user ini untuk memperdalam pemahaman Anda malam ini. Anda sudah mengetahui struktur kode asli, keterkaitan file frontend-backend, hingga mitigasi celah keamanan aplikasi Anda. Sukses untuk presentasinya besok! 🚀
