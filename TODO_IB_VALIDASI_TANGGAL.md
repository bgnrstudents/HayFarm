# TODO - Validasi tanggal kesehatan vs tgl lahir hewan (IB)

- [x] Update `process/models/kesehatan.php`:
  - [x] Validasi relasi tanggal di `create()`:
    - [x] Jika `tgl_lahir` terisi, tolak jika `tgl_pemeriksaan < tgl_lahir`.
    - [x] Jika IB disertakan (`tgl_ib`/`tgl_perkiraan`), tolak jika < `tgl_lahir`.
  - [x] Validasi relasi tanggal di `update()`:
    - [x] `tgl_pemeriksaan`, `tgl_ib`, `tgl_perkiraan` tidak boleh lebih awal dari `tgl_lahir`.
- [ ] (Optional) Update front-end untuk UX:
  - [ ] Set atribut `min` untuk `tgl_pemeriksaan`, `tgl_ib`, dan `tgl_perkiraan` berdasarkan `tgl_lahir` saat hewan dipilih.
- [ ] Test manual:
  - [ ] Case: `tgl_pemeriksaan` sebelum `tgl_lahir` => harus gagal.
  - [ ] Case: `tgl_ib` sebelum `tgl_lahir` => harus gagal.
  - [ ] Case: status IB=berhasil dengan `tgl_perkiraan` sebelum `tgl_lahir` => harus gagal.


