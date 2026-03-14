# HayFarm
Sistem Informasi Peternakan Hay Farm
# HayFarm Project Structure

Dokumen ini menjelaskan struktur folder pada project **HayFarm** agar semua anggota tim memahami fungsi setiap bagian dari project.

Project ini menggunakan konsep **MVC (Model - View - Controller)** supaya kode lebih rapi, mudah dibaca, dan mudah dikerjakan secara tim.

---

# Gambaran Struktur Folder

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

---

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
