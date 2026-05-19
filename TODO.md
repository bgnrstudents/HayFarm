# TODO - Perbaikan Halaman Manager: Laporan Transaksi Penjualan

## Rencana Perubahan (sesuai permintaan)
- Fokus hanya pada chart:
  1) “Penjualan Per Jenis Produk” (X label harus nama kategori/jenis produk, bukan angka)
  2) “Trend Penjualan 1 Tahun Terakhir” (judul & data REAL, nilai kosong = 0)
- Jangan ubah:
  - sistem transaksi, query checkout, logika stok, halaman lain, struktur database, styling global

## Langkah Kerja
1. [x] Identifikasi file yang memuat 2 chart: `pages/manager/lap_transaksi.php` dan `public/js/transaksi_manager.js`.
2. [x] Lihat data sumber chart dari backend: `process/models/manager_reports.php` (TransactionReport::getChartData dan trend builder).
3. [x] Update backend chart data “products” pakai label readable kategori (hewan/rumput/susu) + nilai 0 saat tidak terjual (berdasarkan penghitungan transaksi, tanpa hardcode dummy angka selain kategori label).
4. [x] Update frontend chart (transaksi_manager.js):
   - Sumbu X produk ditampilkan sebagai label kategori (bukan angka).
   - Nilai mengambil `pageData.products.labels/values`.
   - Tick Rupiah memakai format `Rp` + ribuan titik (id-ID).
   - Tick axis integer dipaksa tanpa desimal/float untuk menghindari 3.0/3.1 & duplicate.
5. [x] Update judul chart trend dari “6 Bulan Terakhir” menjadi “1 Tahun Terakhir”.
6. [x] Samakan tinggi card kedua chart di `lap_transaksi.php` (menggunakan kelas chart-wrap chart-wrap-lg/sm agar simetris), tanpa mengubah styling global.
7. [x] Validasi (berdasarkan perubahan kode):
   - data kosong tidak pakai dummy selain label kategori
   - guard optional chaining di frontend
   - tick Rupiah & tick integer dipaksa (tanpa float/duplicate)
   - judul trend & tinggi card sudah disamakan

