# TODO - Business Logic IB (Inseminasi Buatan)

- [ ] Update JavaScript di `public/js/dataKesehatan_admin.js`:
  - [ ] Hitung otomatis `tgl_perkiraan = tgl_ib + 283 hari` saat `tgl_ib` terisi/berubah
  - [ ] Saat `tgl_ib` terisi: set `status_ib` default ke `proses` (jika status masih kosong)
  - [ ] Pastikan `tgl_perkiraan` tetap editable (tidak readonly)

- [ ] Update back-end di `process/handlers/kesehatan_handler.php`:
  - [ ] Enforce alur realistis:
    - [ ] Jika `tgl_ib` ada dan status_ib kosong -> set `proses`
    - [ ] Jika `status_ib=berhasil` -> simpan `tgl_perkiraan` (ambil dari input; jika kosong hitung server-side)
    - [ ] Jika `status_ib=tdk_berhasil` atau `status_ib=proses` -> simpan `tgl_perkiraan = NULL`

- [ ] Testing manual:
  - [ ] Case 1: submit dengan hanya `tgl_ib` terisi -> DB `status_ib=proses` dan `tgl_perkiraan=NULL`
  - [ ] Case 2: update status ke `berhasil` -> DB `tgl_perkiraan` terisi
  - [ ] Case 3: update status ke `tdk_berhasil` -> DB `tgl_perkiraan=NULL`

