# HayFarm
Sistem Informasi Peternakan Hay Farm
# HayFarm Project Structure

Dokumen ini menjelaskan struktur folder pada project **HayFarm** agar semua anggota tim memahami fungsi setiap bagian dari project.

Project ini menggunakan konsep **MVC (Model - View - Controller)** supaya kode lebih rapi, mudah dibaca, dan mudah dikerjakan secara tim.

----------------------------
# Penjelasaan Gambaran Struktur Folder

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

# 1. Folder `app`

Folder **app** adalah tempat semua kode utama aplikasi berada.

Di dalamnya ada tiga bagian utama:

```
app
 ├ controllers
 ├ models
 └ views
```

Ketiga folder ini mengikuti konsep **MVC**.

---

# 2. Folder `controllers`

Folder ini berisi file yang mengatur **alur program**.

Controller menerima request dari user, lalu menentukan data apa yang diambil dan halaman apa yang ditampilkan.

Contoh isi folder:

```
AuthController.php
TernakController.php
ProdukController.php
TransaksiController.php
```

Contoh tugas controller:

* memproses login
* mengambil data ternak
* menampilkan halaman dashboard

---

# 3. Folder `models`

Folder **models** berisi kode yang berhubungan langsung dengan **database**.

Semua query seperti:

* SELECT
* INSERT
* UPDATE
* DELETE

ditulis di dalam folder ini.

Contoh file model:

```
UserModel.php
TernakModel.php
ProdukModel.php
TransaksiModel.php
```

Model bertugas mengambil dan mengolah data dari database.

---

# 4. Folder `views`

Folder **views** berisi tampilan yang dilihat oleh user.

Biasanya berisi:

* HTML
* sedikit kode PHP
* layout halaman

Contoh isi folder:

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

# 5. Folder `config`

Folder ini berisi **pengaturan sistem**.

Contoh file:

```
database.php
```

File ini digunakan untuk membuat koneksi ke database.

---

# 6. Folder `database`

Folder ini berisi file database project.

Contoh:

```
hayfarm.sql
```

File ini digunakan agar anggota tim lain bisa meng-import database dengan struktur yang sama.

---

# 7. Folder `public`

Folder **public** berisi file untuk tampilan website.

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
* **images** → gambar yang digunakan di website

---

# 8. Folder `routes`

Folder ini berisi file yang mengatur **alamat URL website**.

Contoh file:

```
web.php
```

Contoh routing:

```
/login → AuthController
/ternak → TernakController
/dashboard → DashboardController
```

Routing membantu menentukan halaman mana yang harus ditampilkan ketika user membuka URL tertentu.

---

# 9. File `index.php`

File **index.php** adalah pintu masuk utama aplikasi.

Semua request dari browser akan masuk terlebih dahulu ke file ini, lalu diarahkan ke controller yang sesuai.

---

# 10. File `README.md`

File ini berisi dokumentasi project seperti:

* penjelasan struktur folder
* cara menjalankan project
* cara meng-import database

README membantu anggota tim memahami project dengan lebih cepat.

------------------------------------------------------------------
# penjelasan konsep → cara clone → branch workflow → aturan tim → workflow developer
# Alur Kerja Sistem
Cara kerja aplikasi secara sederhana:

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

Dengan struktur ini, project menjadi:

* lebih rapi
* mudah dipahami
* mudah dikerjakan secara tim
* mudah dikembangkan ke depan.
# HAY FARM

Sistem Informasi Pendataan dan E-Commerce Tefa Produksi Ternak

Project ini dibuat sebagai project tim menggunakan **PHP Native** dan **MySQL** untuk membantu pengelolaan data ternak serta penjualan produk ternak secara digital.

Karena project ini dikerjakan oleh **beberapa developer dalam satu tim**, maka kita menggunakan **Git** dan **GitHub** untuk mengelola kode secara kolaboratif.

---

# Kenapa Kita Menggunakan Git?

Git digunakan untuk membantu tim dalam mengelola kode program.

Manfaat Git dalam project tim:

* Menyimpan riwayat perubahan kode
* Memudahkan kerja sama tim
* Menghindari file saling tertimpa
* Memudahkan melihat siapa yang mengubah kode
* Memungkinkan kita kembali ke versi sebelumnya jika terjadi error

Tanpa Git biasanya project tim akan mengalami masalah seperti:

* file tertimpa oleh developer lain
* sulit mengetahui perubahan kode
* sulit menggabungkan kode dari beberapa developer

---

# Apa itu GitHub?

GitHub adalah tempat menyimpan repository project secara online.

GitHub digunakan untuk:

* menyimpan project secara cloud
* bekerja sama dengan tim
* mengelola perubahan kode
* melakukan review kode

Dengan GitHub semua anggota tim bisa mengakses repository yang sama.

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

Sekarang project sudah ada di komputer kalian.

---

# Apa itu Branch?

Branch adalah **cabang dari project utama**.

Bayangkan project seperti pohon.

```
        main
         |
   --------------
   |     |     |
 login  ui   produk
```

Branch digunakan agar setiap developer bisa mengerjakan fitur masing-masing **tanpa mengganggu kode utama**.

Contoh branch:

* feature-login
* feature-ui
* feature-ternak
* feature-produk

Dengan branch, setiap developer bisa bekerja secara terpisah.

---

# Apa itu Branch Main?

Branch **main** adalah cabang utama dari project.

Branch ini berisi:

* kode yang sudah stabil
* kode yang sudah selesai
* versi project yang siap digunakan

Karena itu developer **tidak boleh langsung coding di branch main**.

Branch main harus dijaga agar selalu stabil.

---

# Apa itu Branch Workflow?

Branch workflow adalah **cara kerja tim dalam menggunakan branch**.

Workflow yang kita gunakan adalah:

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

Setiap developer bekerja di branch masing-masing.

---

# Kenapa Harus Menggunakan Branch?

Branch digunakan untuk:

* memisahkan pekerjaan developer
* menghindari konflik kode
* menjaga branch main tetap stabil
* memudahkan penggabungan fitur

Jika semua developer coding di branch main maka:

* kode bisa rusak
* file bisa tertimpa
* sulit mengetahui perubahan

---

# Kenapa Harus `git pull origin main` Sebelum Coding?

Perintah ini digunakan untuk **mengambil update terbaru dari repository**.

Contoh kasus:

Developer A menambahkan fitur login.

Jika developer B tidak melakukan `git pull`, maka:

* developer B tidak mendapatkan update terbaru
* bisa terjadi konflik saat menggabungkan kode

Karena itu **sebelum mulai coding selalu jalankan:**

```
git pull origin main
```

Tujuannya agar kita bekerja pada **versi kode terbaru**.

---

# Alur Kerja Developer

Setiap developer harus mengikuti langkah berikut.

## 1. Ambil update terbaru

```
git pull origin main
```

---

## 2. Buat branch fitur

Contoh membuat branch login:

```
git checkout -b feature-login
```

Sekarang kalian bekerja di branch tersebut.

---

## 3. Mulai coding

Kerjakan fitur sesuai tugas masing-masing.

Contoh:

* halaman login
* dashboard
* CRUD ternak
* produk
* transaksi

---

## 4. Simpan perubahan

Tambahkan perubahan:

```
git add .
```

Commit perubahan:

```
git commit -m "Menambahkan fitur login"
```

Commit berfungsi untuk menyimpan perubahan kode ke dalam Git.

---

## 5. Kirim perubahan ke GitHub

```
git push origin feature-login
```

Branch akan muncul di repository.

---

# Apa itu Pull Request?

Pull Request adalah proses **menggabungkan branch fitur ke branch main**.

Sebelum digabung biasanya kode akan diperiksa terlebih dahulu.

Tujuan Pull Request:

* memastikan kode berjalan dengan baik
* menghindari bug
* memastikan fitur sudah selesai

Setelah disetujui, branch fitur akan digabungkan ke branch main.

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

Dengan pembagian ini setiap developer fokus pada fitur masing-masing.

---

# Catatan Penting untuk Semua Anggota Tim

Selalu lakukan:

```
git pull origin main
```

sebelum mulai coding.

Dan **jangan pernah langsung coding di branch main**.

Gunakan branch fitur masing-masing.

---

# Penutup

Project ini dibuat sebagai latihan kolaborasi software development menggunakan:

* PHP Native
* MySQL
* Git
* GitHub

Dengan workflow ini diharapkan semua anggota tim dapat bekerja sama dengan rapi dan project dapat selesai tepat waktu.
