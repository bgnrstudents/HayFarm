# PANDUAN MENTORING PRIVAT: BEDAH ROLE MANAGER - HAY FARM

Selamat belajar! Sekarang kita akan membedah secara mendalam dan terperinci **Role Manager** pada proyek **Hay Farm**. Peran Manager sangat vital karena berfokus pada **analitik, data agregat, visualisasi grafik (Chart.js), dan pelaporan ekspor (PDF/Dompdf)**. Panduan ini menggunakan gaya bahasa santai namun sarat teknis agar Anda siap menghadapi dosen penguji besok.

---

## 1. GAMBARAN ROLE MANAGER

### A. Tujuan Role Manager
Manager bertindak sebagai pengawas keputusan bisnis peternakan. Berbeda dari Admin yang bekerja di level operasional (CRUD satu-satu), Manager mengamati peternakan dari kacamata **makro** untuk:
1. Memantau kesehatan populasi hewan secara keseluruhan.
2. Menganalisis efektivitas inseminasi buatan (reproduksi sapi).
3. Mengukur profitabilitas penjualan komoditas (sapi, rumput, susu).
4. Mengekspor laporan formal dalam format PDF atau CSV/XLSX sebagai bukti fisik pertanggungjawaban operasional.

### B. Fitur Utama Manager
* **Dashboard Grafik Terpadu (Chart.js)**: Menyajikan grafik populasi (Doughnut Chart), kesehatan (Line Chart), reproduksi (Bar Chart), dan trend omzet penjualan (Line Chart).
* **Laporan Populasi**: Ringkasan jumlah sapi perah dan PO lengkap dengan umur, kandang, dan rekam medisnya.
* **Laporan Medis**: Log kumulatif penanganan medis harian seluruh hewan ternak.
* **Laporan Transaksi Keuangan**: Rekapitulasi transaksi jual beli terverifikasi beserta detail nominal omzet.
* **Ekspor Laporan Formal**: Tombol cetak PDF instan menggunakan library **Dompdf** yang rapi.

---

## 2. HALAMAN MANAGER (DIVERSIFIKASI & FILTERS)

Berikut adalah halaman-halaman yang diakses oleh Manager:

### 1. Dashboard Analitik
* **Fungsi**: Panel utama pemantauan grafik bisnis peternakan.
* **File Terkait**: `pages/manager/index.php`, `public/js/dashboard_manager.js`.
* **Frontend & Backend**:
  * **Backend**: Memanggil method `getChartData()` dari masing-masing jenis objek laporan (`PopulationReport`, `HealthReport`, `TransactionReport`) untuk mengemas data populasi, kesehatan, reproduksi, dan transaksi menjadi array JSON tunggal (`$dashboardChartData`).
  * **Frontend**: Data JSON dicetak langsung ke variabel global Javascript (`window.managerDashboardData`) lalu digambar oleh script `dashboard_manager.js` menggunakan library **Chart.js**.

### 2. Laporan Populasi (`lap_populasi.php`)
* **Fungsi**: Menampilkan daftar tabel seluruh sapi hidup beserta detail pemeriksaan kesehatannya.
* **File Terkait**: `pages/manager/lap_populasi.php`, `process/models/manager_reports.php` (class `PopulationReport`).
* **Hubungan Front-Back**: Manager dapat menyaring data berdasarkan filter **Jenis Hewan (Kategori)**. Filter ini dikirim via GET dan diproses oleh method `applyFilters()` untuk memotong array baris data ternak sebelum di-render ke tabel Bootstrap.

### 3. Laporan Kesehatan (`lap_kesehatan.php`)
* **Fungsi**: Riwayat medis kumulatif pemeriksaan sapi harian.
* **File Terkait**: `pages/manager/lap_kesehatan.php`, `process/models/manager_reports.php` (class `HealthReport`).
* **Hubungan Front-Back**: Backend mengambil log pemeriksaan kesehatan terarsip (`is_deleted = 0`) dan menyajikannya dalam bentuk timeline tabel medis.

### 4. Laporan Transaksi (`lap_transaksi.php`)
* **Fungsi**: Audit finansial dari hasil penjualan peternakan.
* **File Terkait**: `pages/manager/lap_transaksi.php`, `process/models/manager_reports.php` (class `TransactionReport`).
* **Hubungan Front-Back**: Menampilkan detail nominal omzet bersih, metode bayar (COD/Transfer), dan status pembayaran. Terdapat dropdown tahun dinamis yang memicu filter grafik trend penjualan tahunan.

### 5. Detail Hewan Manager (`detail_hewan_manager.php`)
* **Fungsi**: Menampilkan profil lengkap seekor sapi secara detail (umur, berat badan, kandang, riwayat penanganan medis harian, dan siklus kawin suntik/IB).
* **File Terkait**: `pages/manager/detail_hewan_manager.php`, `process/models/manager_reports.php` (class `DetailAnimalReport`).

---

## 3. FLOW DATA MANAGER (STEP-BY-STEP)

Mari kita bedah alur bagaimana data dari database diubah menjadi grafik indah di layar Manager:

```
[Database MySQL] ──(Kueri OOP)──> [Model Report (PHP)] ──(JSON Encode)──> [window.managerDashboardData]
                                                                                      │
                                                                                 (Chart.js)
                                                                                      │
                                                                                      ▼
                                                                           [Grafik Canvas di Browser]
```

### Step 1: Request Halaman & Kueri Analitik
Ketika Manager memuat halaman `pages/manager/index.php`:
1. Sistem menginisialisasi factory: `manager_make_report('populasi')` dan lainnya.
2. Setiap objek laporan melakukan kueri data ke database:
   * **Populasi Sapi**: Diambil dari tabel `data_ternak`.
   * **Pemeriksaan Medis**: Diambil dari tabel `data_kesehatan`.
   * **Siklus Reproduksi**: Diambil dari tabel `data_reproduksi`.
   * **Omzet Penjualan**: Diambil dari tabel `transaksi`.

### Step 2: Pengolahan Data Agregat di Sisi PHP (Backend)
1. PHP mengelompokkan data populasi berdasarkan jenisnya (`manager_count_by_key($animals, 'jenis')`) menghasilkan array: `['Sapi Perah' => X, 'Sapi PO' => Y]`.
2. PHP memproses grafik kesehatan dengan menghitung jumlah kasus pemeriksaan per bulan menggunakan fungsi pembantu `buildLastSixMonthTrend()`.
3. PHP memproses status reproduksi (Inseminasi Buatan) dengan menghitung jumlah status `'berhasil'`, `'proses'`, dan `'tdk_berhasil'` dari tabel reproduksi.
4. PHP mengelompokkan total tagihan (`total_tagihan`) per bulan dari transaksi yang bernilai `'telah_dikonfirmasi'` (Selesai) untuk menghasilkan grafik Trend Penjualan tahunan (`buildYearJanToDecTrend`).

### Step 3: Pengiriman Data ke Frontend via JSON
1. PHP mencetak data agregat tersebut ke dalam struktur Javascript menggunakan helper `manager_json($dashboardChartData)` di akhir baris HTML `index.php`:
   ```html
   <script>
       window.managerDashboardData = {"population": {"labels": ["Sapi Perah", "Sapi PO"], "values": [12, 8]}, ...};
   </script>
   ```

### Step 4: Rendering Grafik Menggunakan Chart.js
1. Browser memuat file `public/js/dashboard_manager.js`.
2. Script membaca variabel global `window.managerDashboardData`.
3. Mengambil context canvas (misalnya `document.getElementById('dashboardStatusChart')`).
4. Menginisialisasi objek `new Chart(ctx, { type: 'doughnut', data: ... })` lengkap dengan palet warna custom peternakan (hijau tua `#198754`, hijau muda, kuning emas, dsb.) dan memunculkan animasi grafik saat halaman dimuat.

---

## 4. SISTEM EKSPOR LAPORAN FORMAL (DOMPDF CONTROLLER)

Bagian ini sering menjadi daya tarik utama penguji. Bagaimana PDF dihasilkan?
1. Manager membuka Laporan Populasi, lalu mengklik tombol **"Ekspor PDF"**.
2. Browser mengirimkan permintaan GET ke file `pages/manager/export_report.php?report=populasi&format=pdf`.
3. File `export_report.php` mendeteksi parameter, memanggil `manager_make_report('populasi', $_GET)` untuk menyaring data yang sedang aktif difilter oleh Manager, dan memanggil `manager_make_exporter('pdf')`.
4. Class `PdfReportExporter` bekerja:
   * Mengatur opsi Dompdf agar mengizinkan pembacaan file lokal (`isRemoteEnabled = true`).
   * **Base64 Image Conversion**: Untuk menghindari bug logo tidak muncul di PDF karena keterbatasan path relatif, backend membaca logo fisik peternakan (`logo2.png`), mengubahnya menjadi string base64 (`base64_encode()`), dan memasukkannya ke dalam inline HTML: `<img src="data:image/png;base64,...">`.
   * Merender HTML dinamis (tabel data + rekam medis sapi).
   * Memanggil `$dompdf->setPaper('A4', 'landscape')` (atau `'portrait'` untuk detail ternak).
   * Menghasilkan file PDF dan memicu browser mengunduh otomatis file dengan nama dinamis, seperti `laporan_populasi_20260519_185913.pdf`.

---

## 5. KUERI-KUERI ANALITIK DATABASE UTAMA MANAGER

Kueri analitik manager dirancang khusus agar efisien dan tidak membebani server database:

### A. Kueri Trend Penjualan Selesai (Omzet Bersih)
Diambil oleh model `Transaksi` melalui method `getAllForReport()`:
```sql
SELECT 
    t.id_transaksi, 
    t.tgl_transaksi, 
    t.total_tagihan, 
    t.metode_pembayaran, 
    t.status_transaksi, 
    t.nama_pembeli,
    GROUP_CONCAT(p.jenis_produk) as jenis_produk,
    GROUP_CONCAT(p.nama_produk SEPARATOR ', ') as produk
FROM transaksi t
LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
LEFT JOIN data_produk p ON dt.id_produk = p.id_produk
GROUP BY t.id_transaksi
ORDER BY t.tgl_transaksi DESC, t.id_transaksi DESC;
```
* **Keunggulan**: Menggunakan `GROUP_CONCAT` untuk mereduksi baris detail produk yang dibeli menjadi satu baris teks string terpisah koma, menghemat memori transfer data database.

---

## 6. POTENSI BUG & ANALISIS KRITIS PADA MODUL MANAGER

Tunjukkan pemahaman kritis Anda dengan menganalisis celah logic berikut di hadapan penguji:

1. **Masalah Aggregation Query (Float Precision Issue)**:
   * *Analisis*: Saat menghitung total omzet finansial, penjumlahan float di MySQL atau PHP terkadang menyisakan angka desimal tidak berujung (misalnya `Rp 15.250.000,000002`).
   * *Solusi*: Di dalam `AbstractManagerReport->buildLastNMonthTrend` (baris 330), sistem memitigasi dengan melakukan pembulatan presisi menggunakan pengecekan integer:
     ```php
     $values[] = (is_float($value) && abs($value - round($value)) < 1e-9) ? (int) round($value) : $value;
     ```
2. **Keterbatasan Chart Tanpa Data (Empty Chart State)**:
   * *Analisis*: Jika Manager memilih filter Tahun atau Bulan di mana peternakan belum memiliki transaksi sama sekali, grafik Chart.js bisa hancur (error Javascript) karena menerima array kosong.
   * *Solusi*: Di model `HealthReport->getChartData()` (baris 782), sistem secara eksplisit mendeteksi jika array data kosong dan langsung mengembalikan struktur objek kosong `{ labels: [], values: [] }` agar Chart.js tetap merender grafik kosong dengan sumbu 0 secara aman tanpa memicu error di konsol browser.

---

## 7. PREDIKSI PERTANYAAN DOSEN & CARA MENJAWAB

### Pertanyaan 1: "Bagaimana cara kerja pengiriman data dari kueri PHP database sehingga bisa digambar menjadi chart dinamis di JavaScript?"
* **Jawaban Anda**:
  > *"Kami memanfaatkan variabel global JavaScript sebagai jembatan data. Di backend PHP, data statistik dihitung lalu dikonversi menjadi format JSON menggunakan helper `manager_json()`. JSON tersebut dicetak ke dalam tag `<script>` pada variabel `window.managerDashboardData`. Ketika file JavaScript `dashboard_manager.js` dimuat oleh browser, script akan langsung membaca data dari variabel global tersebut dan menyuapinya ke library **Chart.js** untuk digambar ke elemen `<canvas>`."*

### Pertanyaan 2: "Mengapa gambar/logo peternakan di laporan PDF terkadang tidak muncul saat menggunakan Dompdf, dan bagaimana Anda mengatasinya?"
* **Jawaban Anda**:
  > *"Dompdf sering mengalami kendala perizinan saat mencoba memuat file gambar menggunakan path relatif atau URL web lokal. Untuk mengatasinya, kami membaca file gambar logo secara fisik di server, kemudian melakukan konversi menjadi data biner Base64 menggunakan fungsi `base64_encode()`. String Base64 ini disisipkan langsung ke tag gambar HTML `<img src="data:image/png;base64,...">`. Dengan begitu, Dompdf dapat merender gambar secara instan langsung dari memori tanpa perlu melakukan request file eksternal."*

### Pertanyaan 3: "Bagaimana Anda membedakan data hewan yang sudah dijual dengan yang masih aktif dalam laporan populasi?"
* **Jawaban Anda**:
  > *"Di laporan populasi (`lap_populasi.php`), kami hanya memuat data dari model `Hewan->getAll()` yang menyaring data ternak dengan kondisi `(is_deleted IS NULL OR is_deleted = 0)`. Jika hewan tersebut sudah terjual dan transaksinya dikonfirmasi, status hewan di tabel data ternak berubah menjadi tidak aktif atau tidak tampil lagi di populasi kandang aktif. Hal ini menjamin laporan populasi hanya menampilkan hewan yang saat ini nyata berada di kandang peternakan."*

---
Gunakan panduan ini sebagai pegangan Anda malam ini. Anda sekarang sudah memahami flow data analitik manager, implementasi Dompdf, integrasi Chart.js, hingga antisipasi pertanyaan kritis dosen. Semoga sukses besar presentasi esok hari! 🚀
