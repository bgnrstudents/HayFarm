document.addEventListener("DOMContentLoaded", function () {
  const toggler = document.querySelector(".navbar-toggler");
  if (toggler) {
    toggler.addEventListener("click", function () {
      this.classList.toggle("active");
    });
  }
});

function showDetail(id) {
  document.getElementById("modal-id").textContent = "0004";
  document.getElementById("modal-jenis").textContent = "Sapi Perah";
  document.getElementById("modal-umur").textContent = "7 Tahun";
  document.getElementById("modal-lokasi").textContent = "Kandang 4";
  document.getElementById("modal-status").innerHTML =
    '<span class="badge bg-success px-3 py-1 rounded-pill">Sehat</span>';

  document.getElementById("modal-riwayat").innerHTML = `
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Kondisi</th>
                <th>Tindakan</th>
                <th>Dokter</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>20 Feb 2026</td><td>Sehat</td><td>Pemeriksaan Rutin</td><td>Drh. Andi</td></tr>
            <tr><td>12 Feb 2026</td><td>Infeksi Ringan</td><td>Antibiotik</td><td>Drh. Wiwin</td></tr>
        </tbody>`;

  document.getElementById("modal-catatan").innerHTML =
    "Ternak ini dalam kondisi baik dan rutin diberi vitamin. Perlu pemeriksaan ulang bulan depan.";

  new bootstrap.Modal(document.getElementById("detailModal")).show();
}
