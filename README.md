# HAYFARM

**Sistem Informasi Pendataan dan E-Commerce Tefa Produksi Ternak**

HayFarm adalah sistem berbasis web yang dirancang untuk membantu pengelolaan produksi ternak sekaligus menyediakan fitur penjualan produk hasil ternak secara online. Sistem ini memiliki tiga jenis pengguna utama yaitu **Admin**, **User**, dan **Manager**.

Setiap peran memiliki fitur yang berbeda sesuai kebutuhan operasional peternakan.
----
# Teknologi yang Digunakan

* PHP Native
* MySQL
* HTML
* CSS
* JavaScript

---

# Tujuan Project

Project ini dikembangkan sebagai **Sistem Informasi Pendataan dan E-Commerce Tefa Produksi Ternak** yang dapat membantu:

* Pengelolaan data ternak
* Monitoring kesehatan hewan
* Penjualan produk hasil ternak
* Penyajian laporan bagi manajemen


---

# Struktur Folder Project

Struktur folder pada project ini dirancang agar mudah dipahami oleh developer pemula dan memudahkan pengembangan secara kolaboratif.

```
HAYFARM
│
├── components
├── config
├── pages
│   ├── admin
│   ├── manager
│   └── user
│
├── process
├── public
│   ├── css
│   ├── images
│   └── js
│
├── index.php
├── login.php
├── register.php
├── logout.php
└── README.md
```

---

# Penjelasan Setiap Folder

## 1. components

Folder ini berisi komponen tampilan yang digunakan berulang pada berbagai halaman website.

Contoh isi folder:

* `navbar.php` → navigasi utama website
* `footer.php` → bagian footer halaman
* `sidebar_admin.php` → sidebar untuk dashboard admin
* `sidebar_manager.php` → sidebar untuk dashboard manager

Tujuan penggunaan folder ini adalah agar kode tampilan tidak perlu ditulis berulang pada setiap halaman.

---

# 2. config

Folder ini berisi konfigurasi sistem.

Contoh:

* `database/` → konfigurasi koneksi database MySQL.

File pada folder ini biasanya dipanggil oleh halaman lain ketika membutuhkan koneksi ke database.

---

# 3. pages

Folder ini berisi **halaman tampilan utama sistem** yang akan diakses oleh pengguna.
Folder ini dibagi berdasarkan **role pengguna**.

## pages/admin

Berisi halaman yang dapat diakses oleh Admin.

Fitur Admin:

* Dashboard
* Manajemen Produk

  * Hewan
  * Susu
  * Rumput
* Verifikasi Penjualan
* Data Hewan
* Data Kesehatan Hewan

Contoh file:

```
dashboard.php
verifikasi_penjualan.php
```

Subfolder yang digunakan:

```
data_hewan/
kesehatan_hewan/
produk/
```

---

## pages/manager

Berisi halaman yang digunakan oleh Manager untuk melihat laporan dan monitoring data.

Fitur Manager:

* Dashboard
* Laporan Populasi
* Laporan Kesehatan
* Laporan Transaksi

Contoh file:

```
dashboard.php
lap_populasi.php
lap_kesehatan.php
lap_transaksi.php
```

---

## pages/user

Berisi halaman yang dapat diakses oleh pengguna atau pembeli.

Fitur User:

* Home
* Tentang Kami
* Produk
* Keranjang
* Checkout
* Riwayat Transaksi

Contoh file:

```
home.php
produk.php
keranjang.php
checkout.php
riwayat_pesanan.php
tentang_kami.php
```

---

# 4. process

Folder ini berisi **logika proses sistem (backend)** seperti proses tambah data, edit data, hapus data, login, transaksi, dan lain-lain.

Folder ini dibagi berdasarkan jenis proses.

Contoh struktur:

```
process
│
├── auth
├── data_hewan
├── kesehatan_hewan
├── penjualan
├── produk
│   ├── hewan
│   ├── rumput
│   └── susu
└── transaksi
```

Contoh fungsi proses:

* `login_proses.php` → proses login pengguna
* `register_proses.php` → proses registrasi
* `tambah_hewan_proses.php` → menambahkan data hewan
* `verifikasi_penjualan_proses.php` → memverifikasi transaksi penjualan
* `checkout_proses.php` → proses checkout pembelian

Folder ini tidak berisi tampilan, hanya berisi logika program.

---

# 5. public

Folder ini berisi aset statis yang digunakan oleh website.

Struktur:

```
public
│
├── css
├── js
└── images
```

Penjelasan:

* `css` → file stylesheet website
* `js` → file JavaScript
* `images` → gambar yang digunakan dalam tampilan website

---

# Alur Dasar Sistem

Berikut alur sederhana cara sistem bekerja:

1. User membuka halaman pada folder **pages**
2. Halaman tersebut memanggil **components** untuk layout
3. Jika ada aksi (submit form), maka data dikirim ke folder **process**
4. Folder **process** akan memproses data dan berinteraksi dengan **database**
5. Setelah proses selesai, pengguna diarahkan kembali ke halaman tertentu

---
# Developer Notes

Struktur project dibuat agar:

* Mudah dipahami developer pemula
* Mendukung pengembangan tim
* Memisahkan tampilan dan logika proses
* Mempermudah pengelolaan fitur berdasarkan role pengguna


---

# Kolaborasi Tim Menggunakan Git

Karena project ini dikerjakan oleh beberapa developer, maka digunakan **Git** untuk mengelola perubahan kode.

Manfaat Git:

* menyimpan riwayat perubahan kode
* memudahkan kerja sama tim
* menghindari file tertimpa
* mengetahui siapa yang mengubah kode
* memungkinkan kembali ke versi sebelumnya jika terjadi error

Tanpa Git, project tim biasanya mengalami masalah seperti:

* file tertimpa
* konflik kode
* sulit menggabungkan perubahan dari beberapa developer

---

# Apa itu GitHub?

GitHub adalah tempat menyimpan repository project secara online.

GitHub digunakan untuk:

* menyimpan project secara cloud
* bekerja sama dengan tim
* mengelola perubahan kode
* melakukan review kode

Dengan GitHub semua anggota tim dapat mengakses repository yang sama.

---

# Cara Mengambil Project (Clone Repository)

Clone digunakan untuk **mengambil project dari GitHub ke komputer kita**.

Clone hanya dilakukan **sekali saja** saat pertama kali mengambil project.

Jalankan perintah berikut di terminal:

```
git clone https://github.com/bgnrstudents/HayFarm
```

Masuk ke folder project:

```
cd HayFarm
```

---

# Konsep Branch pada Git

Branch adalah **cabang dari project utama**.

Branch memungkinkan developer mengerjakan fitur masing-masing tanpa mengganggu kode utama.

Contoh konsep branch:

```
        main
         |
   --------------
   |     |     |
 login  ui   produk
```

Contoh branch fitur:

* feature-login
* feature-ui
* feature-ternak
* feature-produk

---

# Apa itu Branch Main?

Branch **main** adalah cabang utama project.

Branch ini berisi:

* kode yang sudah stabil
* fitur yang sudah selesai
* versi project yang siap digunakan

Karena itu developer **tidak diperbolehkan langsung coding di branch main**.

---

# Branch Workflow yang Digunakan

Workflow yang digunakan dalam project ini:

1. mengambil update project
2. membuat branch fitur
3. mengerjakan fitur
4. mengirim perubahan
5. menggabungkan ke main

Diagram workflow:

```
main
 |
 |---- feature-login
 |
 |---- feature-ui
 |
 |---- feature-ternak
 |
 |---- feature-produk
```

---

# Kenapa Harus `git pull origin main` Sebelum Coding?

Perintah ini digunakan untuk **mengambil update terbaru dari repository**.

Jika developer lain sudah menambahkan fitur baru, maka kita harus mengambil update tersebut agar kode tetap sinkron.

Jalankan perintah berikut sebelum mulai coding:

```
git pull origin main
```

---

# Alur Kerja Developer

Berikut langkah kerja yang harus diikuti setiap developer.

## 1. Ambil update terbaru

```
git pull origin main
```

## 2. Buat branch fitur

```
git checkout -b feature-login
```

## 3. Mulai coding

Kerjakan fitur sesuai tugas masing-masing.

Contoh:

* login
* dashboard
* CRUD ternak
* produk
* transaksi

## 4. Simpan perubahan

```
git add .
git commit -m "Menambahkan fitur login"
```

## 5. Kirim perubahan ke GitHub

```
git push origin feature-login
```

---

# Pull Request

Pull Request adalah proses **menggabungkan branch fitur ke branch main**.

Sebelum digabung biasanya kode akan diperiksa terlebih dahulu untuk memastikan:

* fitur berjalan dengan baik
* tidak ada bug
* kode siap digabungkan

---

# Contoh Pembagian Tugas Tim

Jika tim terdiri dari 5 orang, contoh pembagian tugas:

Developer 1
UI dan layout website

Developer 2
Login dan authentication

Developer 3
CRUD data ternak

Developer 4
Produk dan e-commerce

Developer 5
Transaksi dan integrasi sistem

---

# Catatan Penting untuk Semua Anggota Tim

Sebelum mulai coding selalu jalankan:

```
git pull origin main
```

Dan **jangan pernah langsung coding di branch main**.

Gunakan branch fitur masing-masing agar development tetap rapi.

---

# Penutup

Project HayFarm dibuat sebagai latihan kolaborasi software development menggunakan:

* PHP Native
* MySQL
* Git
* GitHub

Dengan workflow ini diharapkan seluruh anggota tim dapat bekerja sama dengan lebih terstruktur sehingga project dapat selesai dengan baik.
