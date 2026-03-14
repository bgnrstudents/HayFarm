# HayFarm

**Sistem Informasi Pendataan dan E-Commerce Tefa Produksi Ternak**

HayFarm adalah sistem informasi berbasis web yang digunakan untuk membantu pengelolaan data ternak serta penjualan produk ternak secara digital.

Project ini dikembangkan sebagai **project tim** menggunakan:

* PHP Native
* MySQL
* Git
* GitHub

Karena project ini dikerjakan oleh beberapa developer dalam satu tim, maka digunakan **Git dan GitHub** untuk mengelola kode secara kolaboratif.

Dokumen ini bertujuan untuk membantu seluruh anggota tim memahami:

* struktur project
* cara kerja sistem
* workflow kolaborasi menggunakan Git

---

# Konsep Arsitektur Project

Project ini menggunakan konsep **MVC (Model - View - Controller)**.

Tujuan penggunaan MVC:

* membuat struktur kode lebih rapi
* memisahkan logika program dengan tampilan
* memudahkan kerja tim
* memudahkan pengembangan di masa depan

Alur kerja MVC secara sederhana:

```
User membuka website
        │
        ▼
index.php
        │
        ▼
routes
        │
        ▼
Controller
        │
        ▼
Model mengambil data dari database
        │
        ▼
View menampilkan halaman ke user
```

Dengan struktur ini project menjadi:

* lebih rapi
* mudah dipahami
* mudah dikembangkan
* mudah dikerjakan secara tim

---

# Struktur Folder Project

Berikut struktur utama folder pada project **HayFarm**:

```
HayFarm
│
├ app
│ ├ controllers
│ ├ models
│ └ views
│
├ config
│
├ database
│
├ public
│ ├ css
│ ├ js
│ └ images
│
├ routes
│
├ index.php
└ README.md
```

---

# Penjelasan Struktur Folder

## 1. Folder `app`

Folder **app** adalah tempat semua kode utama aplikasi berada.

Di dalamnya terdapat tiga bagian utama:

```
app
 ├ controllers
 ├ models
 └ views
```

Ketiga bagian ini mengikuti konsep **MVC**.

---

## 2. Folder `controllers`

Folder ini berisi file yang mengatur **alur program**.

Controller menerima request dari user, kemudian menentukan data apa yang harus diambil dan halaman apa yang ditampilkan.

Contoh file controller:

```
AuthController.php
TernakController.php
ProdukController.php
TransaksiController.php
```

Tugas controller antara lain:

* memproses login
* mengambil data dari model
* mengirim data ke view
* menampilkan halaman

---

## 3. Folder `models`

Folder **models** berisi kode yang berhubungan langsung dengan **database**.

Semua query database ditulis di dalam folder ini, seperti:

* SELECT
* INSERT
* UPDATE
* DELETE

Contoh file model:

```
UserModel.php
TernakModel.php
ProdukModel.php
TransaksiModel.php
```

Model bertugas mengambil dan mengolah data dari database.

---

## 4. Folder `views`

Folder **views** berisi tampilan yang dilihat oleh user.

Biasanya berisi:

* HTML
* sedikit kode PHP
* layout halaman

Contoh struktur:

```
views
 ├ auth
 │ ├ login.php
 │ └ register.php
 │
 ├ ternak
 │ ├ index.php
 │ ├ create.php
 │ └ edit.php
 │
 └ dashboard.php
```

View hanya bertugas **menampilkan data**, bukan mengolah data.

---

## 5. Folder `config`

Folder ini berisi **konfigurasi sistem**.

Contoh file:

```
database.php
```

File ini digunakan untuk mengatur koneksi ke database.

---

## 6. Folder `database`

Folder ini berisi file database project.

Contoh:

```
hayfarm.sql
```

File ini digunakan agar anggota tim lain dapat meng-import database dengan struktur yang sama.

---

## 7. Folder `public`

Folder **public** berisi file yang digunakan oleh tampilan website.

Contoh isi:

```
public
 ├ css
 ├ js
 └ images
```

Penjelasan:

* **css** → file styling website
* **js** → file javascript
* **images** → gambar yang digunakan dalam website

---

## 8. Folder `routes`

Folder ini berisi pengaturan **routing URL website**.

Contoh file:

```
web.php
```

Contoh routing:

```
/login → AuthController
/dashboard → DashboardController
/ternak → TernakController
```

Routing digunakan untuk menentukan halaman yang harus ditampilkan ketika user membuka URL tertentu.

---

## 9. File `index.php`

File **index.php** adalah **pintu masuk utama aplikasi**.

Semua request dari browser akan masuk terlebih dahulu ke file ini, kemudian diarahkan ke controller yang sesuai.

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
