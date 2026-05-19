# PANDUAN MENTORING PRIVAT: BEDAH ROLE ADMIN - HAY FARM

Selamat belajar! Sekarang kita akan membedah secara mendalam dan menyeluruh **khusus Role Admin** pada proyek **Hay Farm**. Panduan ini dirancang untuk memudahkan Anda memahami arsitektur data, alur bisnis admin, interaksi file, dan memprediksi pertanyaan dosen penguji secara taktis.

---

## 1. GAMBARAN ROLE ADMIN

### A. Tujuan Role Admin
Role Admin bertanggung jawab atas kelancaran operasional harian peternakan dan pengelolaan data (inventori dan medis). Admin memastikan:
1. **Validitas Produk**: Mengelola produk yang dijual (stok, harga, tipe).
2. **Kesehatan & Asal Usul Hewan**: Mencatat asal sapi, rekam medis harian, dan melacak siklus reproduksi Inseminasi Buatan (IB).
3. **Validitas Transaksi**: Meninjau pembayaran transfer bank dari pembeli sebelum mengonfirmasi penjualan dan melepas kepemilikan sapi.

### B. Fitur Utama Admin
* **Dashboard Penjualan & Populasi**: Ringkasan data (populasi sapi perah/PO, total produk, pesanan yang perlu diverifikasi).
* **CRUD Produk Terintegrasi**: Mengontrol produk susu, rumput, dan sapi hidup.
* **CRUD Hewan Ternak**: Pendaftaran sapi baru lengkap dengan kode unik kandang, berat badan, umur, dan foto sapi.
* **CRUD Medis (Kesehatan & Reproduksi)**: Mencatat rekam medis harian dokter hewan, diagnosis, tindakan, dan status program kehamilan sapi (Inseminasi Buatan).
* **Verifikasi Transaksi (Sales Verification)**: Validasi pembayaran dengan detail produk dan foto bukti transfer.

### C. Alur Kerja (Work Flow) Admin
```
[Masuk Sesi (Login)] ──> [Dashboard (Pantau Aktivitas)]
                             │
                             ├─> [Kelola Data Ternak & Rekam Medis (Kesehatan + IB)]
                             ├─> [Kelola Data Produk (Sapi/Susu/Rumput)]
                             └─> [Tinjau Bukti Bayar] ──> [Verifikasi] ──> [Stok Update]
```

---

## 2. DIVERSIFIKASI HALAMAN ADMIN (FRONTEND & BACKEND)

Berikut adalah detail teknis dari setiap halaman admin:

### 1. Dashboard Admin
* **Fungsi**: Halaman pendaratan pertama admin yang menampilkan rangkuman data peternakan secara ringkas.
* **File Terkait**: `pages/admin/dashboard.php`, `process/models/dashboard_admin.php`.
* **Frontend & Backend**:
  * **Backend**: Mengambil statistik menggunakan MySQL query `COUNT` untuk total hewan, produk, dan transaksi menunggu verifikasi.
  * **Frontend**: Menampilkan statistik dalam bentuk kartu info (*stats card*) yang bersih menggunakan framework Bootstrap 5.

### 2. CRUD Produk (`manajemen_produk.php`)
* **Fungsi**: Form input dan tabel modifikasi produk sapi perah, sapi PO, susu, dan rumput.
* **File Terkait**:
  * View: `pages/admin/manajemen_produk.php`
  * Model: `process/models/produk.php`
  * Controller: `process/handlers/produk_handler.php`
* **Query Database**:
  * **Create**: `INSERT INTO data_produk (jenis_produk, nama_produk, id_hewan, harga, stok, tgl_kadaluarsa, status_produk, satuan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)`
  * **Read**: `SELECT p.*, t.kode_hewan FROM data_produk p LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan`
  * **Update**: `UPDATE data_produk SET nama_produk = ?, harga = ?, stok = ?, status_produk = ? WHERE id_produk = ?`
  * **Delete**: `DELETE FROM data_produk WHERE id_produk = ?` (Blokir hapus jika terikat transaksi).

### 3. CRUD Hewan Ternak (`data_hewan.php`)
* **Fungsi**: Pendaftaran sapi baru untuk dipantau siklus hidupnya.
* **File Terkait**:
  * View & Table: `pages/admin/data_hewan.php`
  * Logika Aksi: `pages/admin/data_hewan/tambah_data_hewan.php`, `edit_data_hewan.php`, `hapus_data_hewan.php`
  * Model: `process/models/hewan.php`
* **Query Database**:
  * **Create**: `INSERT INTO data_ternak (kode_hewan, jenis_hewan, berat_badan, jenis_kelamin, no_kandang, tgl_lahir, foto_hewan, status_hewan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)`
  * **Read**: `SELECT * FROM data_ternak WHERE is_deleted = 0 ORDER BY id_hewan DESC`
  * **Delete (Soft Delete)**: `UPDATE data_ternak SET is_deleted = 1, deleted_at = NOW() WHERE id_hewan = ?` (Mengarsipkan data ternak tanpa menghapus fisik agar tidak merusak data transaksi historis).

### 4. CRUD Kesehatan & Reproduksi (`data_kesehatan.php`)
* **Fungsi**: Manajemen diagnosis penyakit sapi harian beserta pelacakan reproduksi (Inseminasi Buatan / Kawin Suntik) jika sapi berjenis kelamin betina.
* **File Terkait**:
  * View: `pages/admin/data_kesehatan.php`
  * Logika Aksi: `pages/admin/kesehatan_hewan/tambah_data_kesehatan.php`, `edit_data_kesehatan.php`
  * Model: `process/models/kesehatan.php`, `process/models/reproduksi.php`
* **Query Database**:
  * **Read**: Menggunakan `LEFT JOIN` antara tabel `data_kesehatan`, `data_ternak`, dan `data_reproduksi` untuk menampilkan laporan terpadu:
    ```sql
    SELECT k.*, t.kode_hewan, r.status_ib, r.tgl_ib 
    FROM data_kesehatan k 
    LEFT JOIN data_ternak t ON k.id_hewan = t.id_hewan 
    LEFT JOIN data_reproduksi r ON k.id_kesehatan = r.id_kesehatan
    WHERE k.is_deleted = 0;
    ```

### 5. Verifikasi Transaksi (`verifikasi_penjualan.php`)
* **Fungsi**: Halaman peninjauan bukti transfer, detail item pesanan, dan konfirmasi/penolakan pesanan.
* **File Terkait**:
  * View: `pages/admin/verifikasi_penjualan.php`
  * JS: `public/js/verifikasiPenjualan_admin.js`
  * AJAX Get Detail: `process/handlers/get_detail_transaksi.php`
  * Controller Update Status: `process/handlers/verifikasi_handler.php`
  * Model: `process/models/transaksi.php`

---

## 3. FLOW OPERASIONAL ADMIN (STEP-BY-STEP)

### A. Alur Penambahan Produk Hewan (Sapi Hidup)
1. **Pra-kondisi**: Sapi yang ingin dijual harus terdaftar di `data_ternak`, memiliki status `tdk_produktif` (karena sapi produktif dilarang dijual demi populasi peternakan), dan belum pernah dijadikan produk lain.
2. Admin masuk ke halaman **Manajemen Produk** -> Klik **Tambah Produk**.
3. Admin memilih Jenis Produk: **Hewan**.
4. Dropdown pilihan Hewan secara dinamis memanggil method `Hewan->getAvailableForProduct()` untuk menampilkan sapi yang memenuhi pra-kondisi.
5. Admin menginput Nama Produk, Harga, dan memilih ID Hewan -> Submit Form.
6. Form POST dikirim ke `produk_handler.php?aksi=tambah`.
7. Backend memproses:
   * Mengatur satuan otomatis menjadi `'ekor'` dan stok otomatis bernilai `1` (karena sapi hidup dijual per ekor).
   * Memanggil model `Produk->create($data)` untuk menyimpan data ke database.

### B. Alur Verifikasi Transaksi (Terima atau Tolak)
1. Pembeli melakukan checkout. Transaksi masuk ke tabel `transaksi` dengan status `menunggu_verifikasi`.
2. Admin masuk ke halaman **Verifikasi Penjualan**. Tampil badge kuning bertuliskan jumlah pesanan menunggu.
3. Admin mengklik tombol **"Verifikasi"** pada baris transaksi target.
4. Fungsi JS `openPendingFromButton` memicu modal pop-up tampil. JS mengirim request AJAX ke `get_detail_transaksi.php?id_transaksi=X` untuk mengambil detail pesanan (email pembeli, nama produk, kode sapi yang dibeli).
5. Admin meninjau kesesuaian nominal struk pembayaran transfer di panel modal.
6. **Skenario A (Diterima)**:
   * Admin klik **"Verifikasi & Konfirmasi"**.
   * Form POST dikirim ke `verifikasi_handler.php` dengan parameter `aksi=verifikasi`.
   * Model `Transaksi->updateStatusTransaksi` dipanggil.
   * Status diubah menjadi `telah_dikonfirmasi` dan stok produk telah dipotong permanen sejak pembeli checkout.
7. **Skenario B (Ditolak)**:
   * Admin klik **"Tolak Pesanan"**.
   * Form POST dikirim ke `verifikasi_handler.php` dengan parameter `aksi=tolak`.
   * Backend mengisolasi database transaction, mengubah status transaksi menjadi `dibatalkan`.
   * Sistem memanggil `restoreStokProduk($id_transaksi)`. Jumlah stok produk di database otomatis bertambah kembali (di-restore) dan status produk dikembalikan menjadi `blm_terjual` jika sebelumnya habis.
   * Transaksi di-commit.

---

## 4. FRONTEND ADMIN (STUDI TAMPILAN)

* **Sidebar & Navbar**: Sidebar admin diletakkan di `components/sidebar_admin.php` menggunakan file CSS `admin_sidebar.css`. Navigasi ini mempermudah admin berpindah halaman dari Dashboard, Produk, Ternak, Kesehatan, hingga Verifikasi.
* **Tabel & Animasi**: Tabel data menggunakan CSS custom (`admin_verifikasiPenjualan.css`) yang rapi dengan efek hover.
* **Lightbox Gambar**: Bukti transfer pembayaran dapat diklik untuk memperbesar gambar menggunakan element popup transparan (`sales-lightbox`) yang digerakkan oleh CSS dan JavaScript.
* **Tampilan Filter**: Filter box di halaman verifikasi menggunakan event listener `change` pada JavaScript yang mendeteksi perubahan dropdown (Status, Bulan, Metode) dan otomatis memuat ulang halaman dengan parameter GET baru untuk menyaring kueri database.

---

## 5. STRUKTUR DATABASE & RELASI KHUSUS ADMIN

Admin mengelola hampir seluruh tabel di database. Berikut adalah relasi kunci yang wajib dipahami:

```
                      +-------------------+
                      |    data_ternak    |
                      +-------------------+
                      | id_hewan (PK)     |<-----+
                      | kode_hewan (UQ)   |      |
                      | status_hewan      |      |
                      +-------------------+      |
                        |               |        |
           (ON DELETE)  |  (ON DELETE)  |        |
           (  CASCADE  )  |  (  CASCADE )  |        |
                        v               v        |
+-------------------+ +-------------------+      |
|  data_kesehatan   | |   data_produk     |      |
+-------------------+ +-------------------+      |
| id_kesehatan(PK)  | | id_produk (PK)    |      |
| id_hewan (FK) ----| | id_hewan (FK) ----|------+
+-------------------+ +-------------------+
        |
        v (1-to-1 / Nullable)
+-------------------+
|  data_reproduksi  |
+-------------------+
| id_reproduksi(PK) |
| id_kesehatan (FK) |
+-------------------+
```

### Aturan Integritas Data (Foreign Key & Constraint):
1. **Relasi Sapi & Rekam Medis**: Tabel `data_kesehatan` terikat ke `data_ternak` via `id_hewan`. Jika data sapi dihapus, seluruh riwayat kesehatannya otomatis terhapus (`ON DELETE CASCADE`).
2. **Relasi Kesehatan & Reproduksi**: Tabel `data_reproduksi` terikat ke `data_kesehatan` via `id_kesehatan`. Ini mempermudah pelacakan karena inseminasi buatan (IB) merupakan bagian dari tindakan kesehatan medis hewan.
3. **Pemberatan Sapi Hidup**: Tabel `data_produk` memiliki foreign key `id_hewan` ke `data_ternak` yang bersifat nullable (hanya diisi jika jenis produk adalah `'hewan'`).

---

## 6. POTENSI BUG & TECHNICAL DEBT PADA FLOW ADMIN

Berikut adalah celah keamanan dan kelemahan program yang bisa Anda sampaikan secara cerdas kepada dosen:

1. **Belum Ada Validasi Dimensi & Ekstensi Gambar Mime-Type di Backend**:
   * *Analisis*: Pada proses unggah gambar sapi (`tambah_data_hewan.php`), sistem memvalidasi file hanya berdasarkan ekstensi nama file (seperti `.png`, `.jpg`).
   * *Bug Potensial*: Hacker bisa mengubah nama file script PHP jahat menjadi `script.php.png`. Jika server mengeksekusinya, server bisa diretas.
   * *Solusi*: "Untuk keamanan ekstra di masa depan, validasi unggahan gambar sebaiknya menggunakan fungsi `mime_content_type()` atau `getimagesize()` untuk memastikan file tersebut benar-benar gambar, bukan script yang disamarkan."
2. **Duplikasi Kode (Code Duplication)**:
   * *Analisis*: Fungsi format mata uang Rupiah didefinisikan ulang di beberapa file (`verifikasi_penjualan.php` mendefinisikan `rupiah()`, sedangkan model keranjang mendefinisikan `formatRupiah()`).
   * *Solusi*: "Ini adalah Technical Debt. Kami berniat menyatukannya ke dalam file helper global agar kode lebih bersih dan terstruktur."
3. **Belum Ada Paging Sisi Database (Server-Side Pagination)**:
   * *Analisis*: Sistem pagination di halaman admin (`public/js/verifikasiPenjualan_admin.js`) membagi halaman secara frontend menggunakan Javascript.
   * *Bug Potensial*: Semua data transaksi dimuat sekaligus dari database. Jika ada 10.000 transaksi, server akan terasa sangat lambat.
   * *Solusi*: "Kami akan memperbarui pagination menggunakan klausa SQL `LIMIT` dan `OFFSET` agar server hanya mengambil data sebanyak yang ingin ditampilkan di layar."

---

## 7. PREDIKSI PERTANYAAN DOSEN & CARA MENJAWAB

### Pertanyaan 1: "Mengapa Anda menggunakan Soft Delete (`is_deleted = 1`) pada data ternak, bukan Hard Delete (`DELETE FROM`)?"
* **Jawaban Anda**:
  > *"Kami menggunakan Soft Delete untuk menjaga integritas data historis peternakan. Jika kami menghapus data ternak secara fisik dari database, maka data transaksi pembelian masa lalu yang mereferensikan ID sapi tersebut akan rusak (karena data sapi yang dibeli hilang). Dengan Soft Delete, kami hanya menyembunyikan sapi tersebut dari daftar aktif, namun riwayat pembelian dan laporan keuangan manager tetap akurat."*

### Pertanyaan 2: "Bagaimana proses sinkronisasi stok dan status produk bekerja ketika transaksi ditolak?"
* **Jawaban Anda**:
  > *"Ketika transaksi ditolak (`aksi=tolak`), control handler menginstruksikan model `Transaksi` untuk menjalankan fungsi `updateStatusTransaksi`. Di dalam blok database transaction, system memicu method `restoreStokProduk($id_transaksi)`. Fungsi ini mengambil seluruh detail item dalam transaksi tersebut, kemudian melakukan query `UPDATE data_produk SET stok = stok + ?, status_produk = 'blm_terjual' WHERE id_produk = ?`. Ini menjamin stok dikembalikan secara aman dan otomatis tersedia kembali di katalog pembeli."*

### Pertanyaan 3: "Mengapa data reproduksi (Inseminasi Buatan) disatukan penginputannya dengan data kesehatan?"
* **Jawaban Anda**:
  > *"Inseminasi Buatan (IB) merupakan bagian dari tindakan medis reproduksi hewan ternak yang memerlukan pemantauan kondisi fisik sapi secara menyeluruh oleh dokter hewan. Dengan menghubungkan tabel `data_reproduksi` langsung ke primary key `id_kesehatan` di tabel `data_kesehatan`, kami dapat mengelompokkan catatan medis harian dan status kehamilan sapi secara terpadu, sehingga rekam medis sapi menjadi lebih rapi dan komprehensif."*

---
Gunakan dokumen bedah role admin ini untuk melengkapi pemahaman Anda. Anda sekarang telah menguasai alur data admin, keterkaitan file, struktur kueri CRUD, hingga strategi menjawab pertanyaan dosen penguji. Sukses besar untuk presentasi Anda besok! 🚀
