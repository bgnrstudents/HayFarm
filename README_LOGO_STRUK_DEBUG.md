Cek penyebab logo struk tidak tampil:

1) download_struk.php saat ini mencoba load file ini:
- public/images/logo.png (real path: __DIR__/public/images/logo.png)

2) Di project terdapat gambar logo lain seperti:
- public/images/logo_hayfarm.png

3) Jika file public/images/logo.png tidak ada, maka fallback ke 'public/images/logo.png' tidak akan bisa diakses oleh Dompdf.

4) Perbaikan ideal:
- Pakai path yang benar dan/atau embed base64 (supaya Dompdf tidak perlu akses remote/local).

5) Setelah patch, testing dengan buka download_struk.php?id_transaksi=... dan pastikan PDF mengandung image.

