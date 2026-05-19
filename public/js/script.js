document.addEventListener("DOMContentLoaded", function () {
  const toggler = document.querySelector(".navbar-toggler");
  if (toggler) {
    toggler.addEventListener("click", function () {
      this.classList.toggle("active");
    });
  }

  initHayFarmMotion();
});

function initHayFarmMotion() {
  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  document.body.classList.add("hf-page-ready");

  const loader = document.createElement("div");
  loader.className = "hf-page-loader";
  loader.innerHTML = '<div class="hf-loader-mark" aria-label="Memuat halaman"></div>';
  document.body.appendChild(loader);

  const revealSelectors = [
    ".produk-card",
    ".keranjang-item",
    ".ringkasan-card",
    ".riwayat-card",
    ".order-card",
    ".transaction-card",
    ".katalog-title",
    ".filter-sidebar",
    ".tentang-card",
    ".feature-card",
    ".stat-card",
    ".card"
  ];

  const revealItems = Array.from(document.querySelectorAll(revealSelectors.join(",")))
    .filter((el, index, arr) => arr.indexOf(el) === index);

  revealItems.forEach((el, index) => {
    el.classList.add("hf-reveal");
    if (el.matches(".produk-card, .keranjang-item, .riwayat-card, .order-card, .transaction-card, .card")) {
      el.classList.add("hf-card-motion");
    }
    el.style.setProperty("--hf-delay", `${Math.min(index % 8, 7) * 55}ms`);
  });

  if (prefersReducedMotion || !("IntersectionObserver" in window)) {
    revealItems.forEach((el) => el.classList.add("is-visible"));
  } else {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" },
    );
    revealItems.forEach((el) => observer.observe(el));
  }

  document.addEventListener("click", (event) => {
    const link = event.target.closest("a[href]");
    if (!link || prefersReducedMotion || !shouldAnimateNavigation(link, event)) return;

    event.preventDefault();
    document.body.classList.add("hf-page-leaving");
    loader.classList.add("show");

    setTimeout(() => {
      window.location.href = link.href;
    }, 180);
  });

  window.addEventListener("pageshow", () => {
    loader.classList.remove("show");
    document.body.classList.remove("hf-page-leaving");
    document.body.classList.add("hf-page-ready");
  });
}

function shouldAnimateNavigation(link, event) {
  if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
    return false;
  }

  const href = link.getAttribute("href") || "";
  if (
    href === "" ||
    href.startsWith("#") ||
    href.startsWith("javascript:") ||
    href.startsWith("mailto:") ||
    href.startsWith("tel:") ||
    link.target === "_blank" ||
    link.hasAttribute("download") ||
    link.dataset.bsToggle ||
    link.getAttribute("data-bs-toggle")
  ) {
    return false;
  }

  const url = new URL(link.href, window.location.href);
  if (url.origin !== window.location.origin) return false;
  if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return false;

  return true;
}

async function showDetail(el) {
  const data = el.dataset;
  const escapeHtml = (value) =>
    String(value ?? "-")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");

  const getStatusClass = (statusLabel) =>
    statusLabel === "Sehat"
      ? "bg-success"
      : ((statusLabel || "").includes("Perawatan") || (statusLabel || "").includes("Sakit")
        ? "bg-danger"
        : "bg-warning text-dark");

  const renderRiwayatTable = (riwayat = []) => {
    if (!Array.isArray(riwayat) || riwayat.length === 0) {
      return `
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Diagnosis</th>
            <th>Tindakan</th>
            <th>Catatan</th>
            <th>Petugas</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada riwayat pemeriksaan.</td>
          </tr>
        </tbody>
      `;
    }

    const rows = riwayat.map((item) => {
      const statusLabel = item.status_label || item.kesehatan || "-";
      const statusClass = getStatusClass(statusLabel);

      return `
        <tr>
          <td>${escapeHtml(item.tgl_formatted || item.tgl_pemeriksaan || "-")}</td>
          <td><span class="badge ${statusClass} px-2 py-1">${escapeHtml(statusLabel)}</span></td>
          <td>${escapeHtml(item.diagnosis || "-")}</td>
          <td>${escapeHtml(item.tindakan || "-")}</td>
          <td>${escapeHtml(item.catatan || "-")}</td>
          <td>${escapeHtml(item.petugas || "-")}</td>
        </tr>
      `;
    }).join("");

    return `
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Status</th>
          <th>Diagnosis</th>
          <th>Tindakan</th>
          <th>Catatan</th>
          <th>Petugas</th>
        </tr>
      </thead>
      <tbody>${rows}</tbody>
    `;
  };

  // Isi data statis dulu
  document.getElementById("modal-id").textContent = data.kode || "-";
  document.getElementById("modal-jenis").textContent = data.nama || "-";
  document.getElementById("modal-umur").textContent = data.umur || "-";
  const modalImage = document.getElementById("modal-image");
  if (modalImage) {
    modalImage.onerror = function () {
      this.onerror = null;
      this.src = "public/images/bgheader_produk.png";
    };
    modalImage.src = data.gambar || "public/images/bgheader_produk.png";
  }

  const fallbackStatusClass = getStatusClass(data.kesehatan || "-");
  document.getElementById("modal-status").innerHTML =
    `<span class="badge ${fallbackStatusClass} px-3 py-1 rounded-pill">${escapeHtml(data.kesehatan || "-")}</span>`;
  document.getElementById("modal-riwayat").innerHTML = renderRiwayatTable([
    {
      tgl_pemeriksaan: data.tgl_pemeriksaan || "-",
      status_label: data.kesehatan || "-",
      diagnosis: "-",
      tindakan: "-",
      catatan: data.catatan || "-",
      petugas: "-",
    },
  ]);
  const fallbackCatatan = document.getElementById("modal-catatan");
  if (fallbackCatatan) {
    fallbackCatatan.textContent = data.catatan || "-";
  }

  // ✅ Fetch detail kesehatan via AJAX (fallback kalau dataset kosong)
  if (data.id) {
    try {
      const response = await fetch(
        `process/handlers/get_produk_detail.php?id_produk=${encodeURIComponent(data.id)}`,
      );
      const detail = await response.json();

      if (detail.status && detail.data) {
        // Update field kesehatan dari AJAX
        const kesehatan = detail.data.kesehatan || "Sehat";
        const statusClass = getStatusClass(kesehatan);
        const riwayatKesehatan = Array.isArray(detail.data.riwayat_kesehatan)
          ? detail.data.riwayat_kesehatan
          : [];

        document.getElementById("modal-id").textContent =
          detail.data.kode_hewan || data.kode || "-";
        document.getElementById("modal-status").innerHTML =
          `<span class="badge ${statusClass} px-3 py-1 rounded-pill">${escapeHtml(kesehatan)}</span>`;
        document.getElementById("modal-riwayat").innerHTML = renderRiwayatTable(riwayatKesehatan);
        const catatanEl = document.getElementById("modal-catatan");
        if (catatanEl) {
          catatanEl.textContent = detail.data.catatan || data.catatan || "-";
        }
      }
    } catch (e) {
      console.warn("Gagal fetch detail via AJAX, pakai data dataset");
    }
  }

  new bootstrap.Modal(document.getElementById("detailModal")).show();
}

// ===== CHECKOUT CONFIGURATION =====
const CHECKOUT_CONFIG = {
  ADMIN_WHATSAPP: "6285850030268",
  BANK_ACCOUNT: "1234 5678 90",
  BANK_NAME: "BCA",
  ALLOWED_FILE_TYPES: ["jpg", "jpeg", "png"],
  MAX_FILE_SIZE: 5 * 1024 * 1024, // 5MB
};

// ===== CHECKOUT VALIDATION =====
function validateCheckoutForm() {
  const errors = [];

  // Validate nama
  const nama = document.querySelector('input[name="nama_pembeli"]');
  if (!nama || nama.value.trim() === "") {
    errors.push("Nama lengkap wajib diisi");
  }

  // Validate no_telp
  const noTelp = document.querySelector('input[name="no_telp"]');
  if (!noTelp || noTelp.value.trim() === "") {
    errors.push("No telepon wajib diisi");
  } else if (!/^\d{7,15}$/.test(noTelp.value.replace(/\D/g, ""))) {
    errors.push("No telepon hanya boleh angka (7-15 digit)");
  }

  // Validate alamat
  const alamat = document.querySelector('input[name="alamat"]');
  if (!alamat || alamat.value.trim() === "") {
    errors.push("Alamat pengiriman wajib diisi");
  }

  // Validate kode pos
  const kodePos = document.querySelector('input[name="kode_pos"]');
  if (!kodePos || kodePos.value.trim() === "") {
    errors.push("Kode pos wajib diisi");
  } else if (!/^\d{5}$/.test(kodePos.value.trim())) {
    errors.push("Kode pos harus 5 digit angka");
  }

  // Validate produk tidak kosong (check hidden inputs)
  const produkIds = document.querySelectorAll('input[name="id_produk[]"]');
  if (produkIds.length === 0) {
    errors.push("Tidak ada produk yang dipilih");
  }

  // Validate bukti pembayaran jika transfer
  const metode = document.getElementById("metodePembayaran");
  if (metode && metode.value === "transfer") {
    const buktiFile = document.getElementById("fileBukti");
    if (!buktiFile || !buktiFile.files || buktiFile.files.length === 0) {
      errors.push("Bukti transfer wajib diupload untuk metode Transfer");
    } else {
      const file = buktiFile.files[0];
      const ext = file.name.split(".").pop().toLowerCase();

      if (!CHECKOUT_CONFIG.ALLOWED_FILE_TYPES.includes(ext)) {
        errors.push("Format bukti harus JPG, JPEG, atau PNG");
      }

      if (file.size > CHECKOUT_CONFIG.MAX_FILE_SIZE) {
        errors.push("Ukuran bukti maksimal 5MB");
      }
    }
  }

  return errors;
}

// ✅ Modern validation error display (uses the centered overlay in chekout.php)
function showValidationErrors(errors) {
  const overlay = document.getElementById("validationOverlay");
  const errorList = document.getElementById("errorList");

  if (overlay && errorList) {
    if (errors.length > 0) {
      errorList.innerHTML = "";
      errors.forEach((err) => {
        const li = document.createElement("li");
        li.textContent = err;
        errorList.appendChild(li);
      });
      overlay.style.display = "flex";
    } else {
      overlay.style.display = "none";
    }
    return;
  }

  // Fallback: create toast notification
  if (errors.length > 0) {
    showCheckoutToast(errors.join(" • "), "error");
  }
}

// ✅ Modern toast notification for checkout page
function showCheckoutToast(message, type = "success") {
  // Remove existing toast if any
  const existing = document.getElementById("ckToast");
  if (existing) existing.remove();

  const toast = document.createElement("div");
  toast.id = "ckToast";
  toast.style.cssText = `
    position: fixed; top: 24px; right: 24px; z-index: 10000;
    min-width: 320px; max-width: 440px;
    padding: 16px 20px; border-radius: 14px;
    display: flex; align-items: flex-start; gap: 12px;
    font-family: 'Poppins', sans-serif; font-size: 13px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    animation: ckToastIn 0.35s ease-out;
    ${
      type === "error"
        ? "background: linear-gradient(135deg, #fff5f5 0%, #fee2e2 100%); border: 1px solid #fca5a5; border-left: 4px solid #ef4444; color: #b91c1c;"
        : "background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #86efac; border-left: 4px solid #22c55e; color: #166534;"
    }
  `;

  const icon =
    type === "error"
      ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#b91c1c" stroke-width="2"/><path d="M12 8V12" stroke="#b91c1c" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="16" r="1" fill="#b91c1c"/></svg>'
      : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#166534" stroke-width="2"/><path d="M8 12l3 3 5-6" stroke="#166534" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

  toast.innerHTML = `
    <div style="flex-shrink:0;margin-top:2px">${icon}</div>
    <div style="flex:1;line-height:1.5">${message}</div>
    <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;padding:4px;opacity:0.5;font-size:16px;color:inherit">✕</button>
  `;

  // Inject animation keyframes if not already present
  if (!document.getElementById("ckToastStyle")) {
    const style = document.createElement("style");
    style.id = "ckToastStyle";
    style.textContent = `
      @keyframes ckToastIn { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }
      @keyframes ckToastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(40px); } }
    `;
    document.head.appendChild(style);
  }

  document.body.appendChild(toast);

  // Auto dismiss after 5 seconds
  setTimeout(() => {
    if (toast.parentElement) {
      toast.style.animation = "ckToastOut 0.3s ease-in forwards";
      setTimeout(() => toast.remove(), 300);
    }
  }, 5000);
}

// ===== PAYMENT METHOD SELECTION =====
function setMetode(val) {
  // Reset semua tombol
  document.querySelectorAll(".ck-metode-btn").forEach((btn) => {
    btn.classList.remove("active");
  });

  // Aktifkan yang diklik dengan animasi smooth
  const selectedBtn = document.getElementById("btn" + val);
  if (selectedBtn) {
    selectedBtn.classList.add("active");
  }

  const metodeInput = document.getElementById("metodePembayaran");
  if (metodeInput) {
    metodeInput.value = val === "Cod" ? "cod" : "transfer";
  }

  // Toggle sections dengan smooth fade
  const uploadSection = document.getElementById("uploadSection");
  const panelTransfer = document.getElementById("panelTransfer");
  const codInfo = document.getElementById("codInfoBox");

  if (val === "Cod") {
    // Hide transfer sections
    if (uploadSection) uploadSection.style.display = "none";
    if (panelTransfer) panelTransfer.style.display = "none";

    // Show COD info
    if (codInfo) codInfo.style.display = "block";
  } else {
    // Show transfer sections
    if (uploadSection) uploadSection.style.display = "block";
    if (panelTransfer) panelTransfer.style.display = "block";

    // Hide COD info
    if (codInfo) codInfo.style.display = "none";
  }

  // Update tombol bayar
  const btnBayar = document.getElementById("btnBayar");
  if (!btnBayar) return;

  if (val === "Cod") {
    btnBayar.innerHTML = '<i class="fas fa-handshake"></i> Buat Pesanan COD';
    btnBayar.className = "ck-btn-bayar ck-btn-cod";
    btnBayar.onclick = (e) => processCheckout(e, "cod");
  } else {
    btnBayar.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
    btnBayar.className = "ck-btn-bayar";
    btnBayar.onclick = (e) => processCheckout(e, "transfer");
  }
}

// ===== FILE UPLOAD & PREVIEW =====
function previewBukti(input) {
  const img = document.getElementById("previewImg");
  const fileName = document.getElementById("fileNameDisplay");

  if (input.files && input.files[0]) {
    const file = input.files[0];

    // Validate file type
    const ext = file.name.split(".").pop().toLowerCase();
    if (!CHECKOUT_CONFIG.ALLOWED_FILE_TYPES.includes(ext)) {
      showCheckoutToast(
        "Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.",
        "error",
      );
      input.value = "";
      if (img) img.style.display = "none";
      if (fileName) fileName.textContent = "";
      return;
    }

    // Validate file size
    if (file.size > CHECKOUT_CONFIG.MAX_FILE_SIZE) {
      showCheckoutToast("Ukuran file terlalu besar. Maksimal 5MB.", "error");
      input.value = "";
      if (img) img.style.display = "none";
      if (fileName) fileName.textContent = "";
      return;
    }

    // Show file name
    if (fileName) {
      fileName.textContent = `✓ ${file.name}`;
      fileName.style.color = "#196c33";
    }

    // Show image preview
    const reader = new FileReader();
    reader.onload = (e) => {
      if (img) {
        img.src = e.target.result;
        img.style.display = "block";
      }
    };
    reader.readAsDataURL(file);
  }
}

// ===== COPY TO CLIPBOARD =====
function salinRek() {
  const no = document.getElementById("rekeningNo").innerText.replace(/\s/g, "");
  navigator.clipboard.writeText(no).then(() => {
    const toast = new bootstrap.Modal(document.getElementById("mSalin"));
    toast.show();
    setTimeout(() => toast.hide(), 1600);
  });
}

// ===== WHATSAPP MESSAGE BUILDER =====
function buildWhatsAppMessage(orderData) {
  const { namaUser, totalAmount, produk, metode } = orderData;

  let produktList = produk.map((p) => `${p.nama} (${p.qty})`).join("\n");

  const message = `Saya ingin verifikasi pesanan dari *Hay Farm*

Nama: ${namaUser}
Produk: ${produktList}
Total: Rp ${totalAmount}
Metode: ${metode.toUpperCase()}

Mohon bantuannya untuk memproses pesanan.`;

  return encodeURIComponent(message);
}

// ===== SUCCESS MODALS =====
function showSuccessModal(type, orderData) {
  let modal = document.getElementById("successModal");

  if (!modal) {
    modal = createSuccessModal();
    document.body.appendChild(modal);
  }

  const title = modal.querySelector(".success-title");
  const message = modal.querySelector(".success-message");
  const buttons = modal.querySelector(".success-buttons");

  if (type === "cod") {
    title.innerHTML =
      '<i class="fas fa-check-circle"></i> Pesanan Berhasil Dibuat!';
    message.innerHTML = `
            <p>Terima kasih telah memesan melalui Hay Farm.</p>
            <p><strong>Pesanan Anda sedang menunggu verifikasi dari admin.</strong></p>
            <p style="font-size: 13px; color: #666; margin-top: 12px;">
                Silakan hubungi admin melalui WhatsApp untuk melanjutkan proses verifikasi COD.
            </p>
        `;

    const whatsappLink = `https://wa.me/${CHECKOUT_CONFIG.ADMIN_WHATSAPP}?text=${buildWhatsAppMessage(
      {
        namaUser: document.querySelector('input[name="nama_pembeli"]').value,
        totalAmount: document.querySelector(".ck-total-amount").textContent,
        produk: Array.from(document.querySelectorAll(".ck-produk-nama")).map(
          (el) => ({
            nama: el.textContent,
            qty: el.nextElementSibling.textContent,
          }),
        ),
        metode: "COD",
      },
    )}`;

    buttons.innerHTML = `
            <a href="${whatsappLink}" target="_blank" class="btn-modal btn-modal-primary">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp Admin
            </a>
            <a href="?page=user/riwayat_pesanan" class="btn-modal btn-modal-secondary">
                <i class="fas fa-history"></i> Lihat Riwayat Pesanan
            </a>
        `;
  } else if (type === "transfer") {
    title.innerHTML =
      '<i class="fas fa-check-circle"></i> Bukti Pembayaran Terkirim!';
    message.innerHTML = `
            <p>Terima kasih telah melakukan pembayaran.</p>
            <p><strong>Pesanan Anda sedang menunggu verifikasi dari admin.</strong></p>
            <p style="font-size: 13px; color: #666; margin-top: 12px;">
                Kami akan segera memverifikasi bukti pembayaran Anda. Cek status pesanan di riwayat transaksi.
            </p>
        `;

    buttons.innerHTML = `
            <a href="?page=user/riwayat_pesanan" class="btn-modal btn-modal-primary">
                <i class="fas fa-history"></i> Lihat Riwayat Pesanan
            </a>
        `;
  }

  const bootstrapModal = new bootstrap.Modal(modal);
  bootstrapModal.show();
}

// Create success modal HTML
function createSuccessModal() {
  const modal = document.createElement("div");
  modal.id = "successModal";
  modal.className = "modal fade";
  modal.setAttribute("tabindex", "-1");
  modal.setAttribute("aria-hidden", "true");

  modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ck-success-modal">
                <div class="modal-body">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="success-title"></h2>
                    <div class="success-message"></div>
                    <div class="success-buttons"></div>
                </div>
            </div>
        </div>
    `;

  return modal;
}

// ===== CHECKOUT PROCESS =====
let isProcessingCheckout = false;

function processCheckout(e, methodType) {
  e.preventDefault();

  // ✅ CEK: Jika sedang proses, tolak semua klik
  if (isProcessingCheckout) {
    console.log("Checkout sedang diproses, tunggu...");
    return;
  }

  const btn = document.getElementById("btnBayar");
  const form = btn.closest("form");

  // Validasi form
  const errors = validateCheckoutForm();
  if (errors.length > 0) {
    showValidationErrors(errors);
    return;
  }

  // ✅ SET FLAG: Tandai sedang proses
  isProcessingCheckout = true;

  // Disable tombol
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
  btn.style.opacity = "0.7";
  btn.style.pointerEvents = "none";

  const formData = new FormData(form);

  fetch("process/handlers/transaction.php", {
    method: "POST",
    body: formData,
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => {
      // Cek jika response bukan JSON
      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        throw new Error("Server mengembalikan response yang tidak valid");
      }
      return response.json();
    })
    .then((data) => {
      if (data.status === true) {
        // ✅ Tampilkan modal sukses dulu
        showSuccessModal(methodType, {
          namaUser: document.querySelector('input[name="nama_pembeli"]').value,
          totalAmount: document.querySelector(".ck-total-amount").textContent,
          produk: Array.from(document.querySelectorAll(".ck-produk-nama")).map(
            (el) => ({
              nama: el.textContent,
              qty: el.nextElementSibling.textContent,
            }),
          ),
          metode: methodType.toUpperCase(),
        });

        // ✅ Delay redirect agar modal sempat muncul (2 detik)
        setTimeout(() => {
          window.location.href = "index.php?page=user/riwayat_pesanan";
        }, 2000);
      } else {
        isProcessingCheckout = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
        btn.style.opacity = "1";
        btn.style.pointerEvents = "auto";
        showCheckoutToast(
          data.message || "Terjadi kesalahan saat memproses pesanan.",
          "error",
        );
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      // Enable tombol kembali jika error
      isProcessingCheckout = false;
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
      btn.style.opacity = "1";
      btn.style.pointerEvents = "auto";
      showCheckoutToast("Koneksi error: " + err.message, "error");
    });
}

// Fallback bayar function for old code
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
