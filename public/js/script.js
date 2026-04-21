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

// chekout js
function setMetode(val) {
    // Reset semua tombol
    document.querySelectorAll('.ck-metode-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Aktifkan yang diklik
    document.getElementById('btn' + val).classList.add('active');

    // Sembunyikan/tampilkan upload sesuai metode
    document.getElementById('uploadSection').style.display =
        val === 'Cod' ? 'none' : 'block';

    // Ganti teks tombol bayar
    const btnBayar = document.getElementById('btnBayar');
    if (val === 'Cod') {
        btnBayar.innerHTML = '<i class="fab fa-whatsapp"></i> Konfirmasi via WhatsApp';
        btnBayar.className = 'ck-btn-bayar wa-btn';
    } else {
        btnBayar.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
        btnBayar.className = 'ck-btn-bayar';
    }
}
function pilihWallet(el) {
  document
    .querySelectorAll(".ck-ewallet-btn")
    .forEach((b) => b.classList.remove("active"));
  el.classList.add("active");
}

function previewBukti(input) {
  const img = document.getElementById("previewImg");
  if (input.files && input.files[0]) {
    const r = new FileReader();
    r.onload = (e) => {
      img.src = e.target.result;
      img.style.display = "block";
    };
    r.readAsDataURL(input.files[0]);
  }
}

function salinRek() {
  const no = document.getElementById("rekeningNo").innerText.replace(/\s/g, "");
  navigator.clipboard.writeText(no).then(() => {
    const m = new bootstrap.Modal(document.getElementById("mSalin"));
    m.show();
    setTimeout(() => m.hide(), 1600);
  });
}

function bayar() {
  const btn = document.getElementById("btnBayar");
  const ori = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = ori;
    btn.disabled = false;
    new bootstrap.Modal(document.getElementById("mSukses")).show();
  }, 1500);
}
