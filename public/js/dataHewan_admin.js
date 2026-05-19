// --- Date & Search Init ---
document.addEventListener("DOMContentLoaded", () => {
  const dateEl = document.getElementById("currentDate");
  if (dateEl) {
    dateEl.textContent = new Date().toLocaleDateString("id-ID", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }

  const searchInput = document.getElementById("searchInput");
  const tableRows = document.querySelectorAll("#dataTableBody tr");

  if (searchInput && tableRows.length > 0) {
    searchInput.addEventListener("input", function () {
      const keyword = this.value.toLowerCase().trim();
      tableRows.forEach((row) => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(keyword) ? "" : "none";
      });
    });
  }
});

// --- Utilities ---
function parseRecord(trigger) {
  if (!trigger || !trigger.dataset.record) return null;
  try {
    return JSON.parse(trigger.dataset.record);
  } catch (e) {
    console.error("Error parsing record data:", e);
    return null;
  }
}

function openOverlay(id) {
  const el = document.getElementById(id);
  if (el) {
    el.classList.add("active"); // Pastikan CSS class 'active' atau 'flex' sesuai CSS kamu
    el.style.display = "flex"; // Fallback inline style
    document.body.style.overflow = "hidden";
  }
}

function closeOverlay(id) {
  const el = document.getElementById(id);
  if (el) {
    el.classList.remove("active");
    el.style.display = "none";
    document.body.style.overflow = "auto";
  }
}

function closeModalOutside(event, overlayId) {
  if (event.target.id === overlayId) {
    closeOverlay(overlayId);
  }
}

function formatTanggalIndonesia(dateString) {
  if (!dateString) return "-";
  return new Date(dateString + "T00:00:00").toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
}

function hitungUsia(dateString) {
  if (!dateString) return "-";
  const lahir = new Date(dateString + "T00:00:00");
  const hariIni = new Date();
  let tahun = hariIni.getFullYear() - lahir.getFullYear();
  let bulan = hariIni.getMonth() - lahir.getMonth();

  if (bulan < 0) {
    tahun--;
    bulan += 12;
  }
  if (tahun > 0) return `${tahun} Tahun`;
  return `${Math.max(bulan, 0)} Bulan`;
}

function resolveAnimalImagePath(path) {
  const raw = String(path || "").trim();
  if (!raw) return "../../public/images/bgheader_produk.png";

  const normalized = raw
    .replace(/\\/g, "/")
    .replace(/^(\.\.\/|\.\/)+/, "")
    .replace(/^\/+/, "");

  if (/^https?:\/\//i.test(normalized)) {
    return normalized;
  }

  if (
    normalized.startsWith("public/") ||
    normalized.startsWith("uploads/")
  ) {
    return `../../${normalized}`;
  }

  return `../../uploads/hewan/${normalized.split("/").pop()}`;
}

// --- Status Button Logic ---
function updateStatusButtons(scopeSelector, status) {
  // Reset all buttons in scope
  document
    .querySelectorAll(`${scopeSelector} .health-status-btn`)
    .forEach((button) => {
      button.classList.remove("active");
    });

  // Determine prefix (edit or tambah)
  const prefix = scopeSelector.includes("edit") ? "edit" : "tambah";

  // Normalize status to match DB enum: 'produktif' or 'tdk_produktif'
  // Handle legacy 'tidak_produktif' just in case
  const normalizedStatus =
    status === "tdk_produktif" || status === "tidak_produktif"
      ? "tdk_produktif"
      : "produktif";

  const suffix =
    normalizedStatus === "tdk_produktif" ? "tidak-produktif" : "produktif";
  const targetId = `${prefix}-btn-${suffix}`;

  const targetButton = document.getElementById(targetId);
  if (targetButton) {
    targetButton.classList.add("active");
  }
}

// --- MODAL: TAMBAH ---
function openTambah() {
  const form = document.getElementById("tambahForm");
  if (form) form.reset();

  updateStatusButtons("#tambahOverlay", "produktif");

  const hiddenStatus = document.getElementById("tambahStatusValue");
  if (hiddenStatus) hiddenStatus.value = "produktif";

  openOverlay("tambahOverlay");
}

function closeTambah() {
  closeOverlay("tambahOverlay");
}

function selectTambahStatus(status, element) {
  document
    .querySelectorAll("#tambahOverlay .health-status-btn")
    .forEach((btn) => btn.classList.remove("active"));
  element.classList.add("active");

  const hiddenStatus = document.getElementById("tambahStatusValue");
  if (hiddenStatus) hiddenStatus.value = status;
}

// --- MODAL: EDIT ---
function fillEditForm(record) {
  if (!record) return;

  // 1. Primary Key (Hidden Input) - KRUSIAL untuk Update/Delete
  const pkInput = document.getElementById("editIdHewanPK");
  if (pkInput) pkInput.value = record.id_hewan || record.id || "";

  // 2. Display Fields
  const kodeInput = document.getElementById("editKodeHewan");
  if (kodeInput) kodeInput.value = record.kode || record.id || "";

  const namaInput = document.getElementById("editNamaHewan");
  if (namaInput) namaInput.value = record.nama || "";

  const jenisInput = document.getElementById("editJenisHewan");
  if (jenisInput) jenisInput.value = record.jenis || "";

  const beratInput = document.getElementById("editBeratBadan");
  if (beratInput) beratInput.value = record.berat || "";

  const kelaminInput = document.getElementById("editJenisKelamin");
  if (kelaminInput) kelaminInput.value = record.kelamin || "";

  const kandangInput = document.getElementById("editNoKandang");
  if (kandangInput) kandangInput.value = record.kandang || "";

  const lahirInput = document.getElementById("editTanggalLahir");
  if (lahirInput) lahirInput.value = record.tgl_lahir || "";

  // 3. Status Handling
  const statusVal = record.status || "produktif";
  const hiddenStatus = document.getElementById("editStatusValue");
  if (hiddenStatus) hiddenStatus.value = statusVal;

  // 4. Summary Strip
  document.getElementById("editSummaryKode").textContent =
    record.kode || record.id || "-";
  document.getElementById("editSummaryJenis").textContent = record.jenis || "-";
  document.getElementById("editSummaryUsia").textContent =
    record.usia || hitungUsia(record.tgl_lahir);

  const statusLabel =
    statusVal === "tdk_produktif" || statusVal === "tidak_produktif"
      ? "Tidak Produktif"
      : "Produktif";
  document.getElementById("editSummaryStatus").textContent = statusLabel;

  // 5. Visual Button State
  updateStatusButtons("#editOverlay", statusVal);
}

function openEdit(trigger) {
  const record = parseRecord(trigger);
  if (record) {
    fillEditForm(record);
    openOverlay("editOverlay");
  }
}

function closeEdit() {
  closeOverlay("editOverlay");
}

function selectEditStatus(element, value) {
  document
    .querySelectorAll("#editOverlay .health-status-btn")
    .forEach((btn) => btn.classList.remove("active"));
  element.classList.add("active");

  const hiddenStatus = document.getElementById("editStatusValue");
  if (hiddenStatus) hiddenStatus.value = value;

  // Update Summary Text
  const summaryEl = document.getElementById("editSummaryStatus");
  if (summaryEl) {
    summaryEl.textContent =
      value === "tdk_produktif" || value === "tidak_produktif"
        ? "Tidak Produktif"
        : "Produktif";
  }
}

// --- MODAL: PREVIEW ---
let activePreviewRecord = null;

function fillPreview(record) {
  if (!record) return;
  activePreviewRecord = record;

  const setText = (id, value, fallback = "-") => {
    const el = document.getElementById(id);
    if (el) el.textContent = value ?? fallback;
  };

  // setText('previewKodeBadge', record.kode, '-');
  setText("previewKodeHewan", record.kode, "-");
  setText(
    "previewJenis",
    record.jenis ? record.jenis.replace("_", " ") : "-",
    "-",
  );
  setText(
    "previewKelamin",
    record.kelamin
      ? record.kelamin.charAt(0).toUpperCase() + record.kelamin.slice(1)
      : "-",
    "-",
  );
  setText("previewBerat", record.berat ? `${record.berat} kg` : "-", "-");
  setText("previewKandang", record.kandang, "-");
  setText(
    "previewTanggalLahir",
    record.tgl_lahir ? formatTanggalIndonesia(record.tgl_lahir) : "-",
    "-",
  );
  setText("previewUsia", record.usia || hitungUsia(record.tgl_lahir), "-");

  // Status pill
  const statusVal = record.status || "produktif";
  const isProductive = statusVal === "produktif";
  setText("previewStatus", isProductive ? "Produktif" : "Tidak Produktif");

  const statusWrap = document.getElementById("previewStatusWrap");
  if (statusWrap) {
    statusWrap.className = `status-pill-preview ${isProductive ? "" : "status-tidak-produktif-dot"}`;
  }

  // Image handling
  const previewImage = document.getElementById("previewImage");
  const container = previewImage?.closest(".preview-image-container");
  const noImageText = container?.querySelector(".no-image-text");

  if (record.foto && previewImage && container) {
    previewImage.src = resolveAnimalImagePath(record.foto);
    previewImage.alt = `Foto ${record.jenis || "hewan"}`;
    previewImage.style.display = "block";
    previewImage.onerror = () => {
      previewImage.onerror = null;
      previewImage.src = "../../public/images/bgheader_produk.png";
      previewImage.style.display = "block";
      if (noImageText) noImageText.style.display = "none";
      container.classList.remove("no-image");
    };
    container.classList.remove("no-image");
    if (noImageText) noImageText.style.display = "none";
  } else {
    if (previewImage) {
      previewImage.removeAttribute("src");
      previewImage.style.display = "none";
    }
    if (noImageText) noImageText.style.display = "block";
    container?.classList.add("no-image");
  }
}

function openPreview(trigger) {
  const record = parseRecord(trigger);
  if (record) {
    fillPreview(record);
    openOverlay("previewOverlay");
  }
}

function closePreview() {
  closeOverlay("previewOverlay");
}

function closePreviewOutside(event) {
  if (event.target.id === "previewOverlay") closePreview();
}

// --- MODAL: DELETE ---
function openDelete(namaHewan, idHewanPK) {
  document.getElementById("deleteTarget").textContent = namaHewan || "data ini";

  // FIX: Gunakan ID PK, bukan Kode Hewan
  const deleteInput = document.getElementById("deleteIdHewanPK");
  if (deleteInput) {
    deleteInput.value = idHewanPK || "";
  }

  openOverlay("deleteOverlay");
}

function closeDelete() {
  closeOverlay("deleteOverlay");
}

function closeDeleteOutside(event) {
  if (event.target.id === "deleteOverlay") closeDelete();
}

// --- FORM SUBMISSION (Loading State) ---
const tambahForm = document.getElementById("tambahForm");
if (tambahForm) {
  tambahForm.addEventListener("submit", function () {
    const btn = this.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      btn.textContent = "Menyimpan...";
    }
  });
}

// const editForm = document.getElementById("editHewanForm");
// if (editForm) {
//   editForm.addEventListener("submit", function () {
//     const btn = this.querySelector('button[type="submit"]');
//     if (btn) {
//       btn.disabled = true;
//       btn.textContent = "Menyimpan...";
//     }
//   });
// }

const deleteForm = document.getElementById("deleteHewanForm");
if (deleteForm) {
  deleteForm.addEventListener("submit", function () {
    const btn = this.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      btn.textContent = "Menghapus...";
    }
  });
}

// --- KEYBOARD SHORTCUT (ESC) ---
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    closeTambah();
    closeEdit();
    closePreview();
    closeDelete();
  }
});

// --- PAGINATION ---
function setupAdminPagination(
  tbodySelector,
  paginationSelector,
  rowsPerPage = 5,
) {
  const tbody = document.querySelector(tbodySelector);
  const pagination = document.querySelector(paginationSelector);
  if (!tbody || !pagination) return;

  const rows = Array.from(tbody.querySelectorAll("tr"));
  const totalRows = rows.length;
  const totalPages = Math.max(1, Math.ceil(totalRows / rowsPerPage));
  let currentPage = 1;

  const info = pagination.querySelector("span");
  const buttons = pagination.querySelectorAll(".page-btn");
  const previousButton = buttons[0];
  const pageButton = buttons[1];
  const nextButton = buttons[2];

  function renderPage() {
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;

    rows.forEach((row, index) => {
      row.style.display = index >= startIndex && index < endIndex ? "" : "none";
    });

    const start = totalRows > 0 ? startIndex + 1 : 0;
    const end = Math.min(endIndex, totalRows);

    if (info)
      info.textContent = `Menampilkan ${start}-${end} dari ${totalRows} data`;
    if (pageButton) pageButton.textContent = currentPage;
    if (previousButton) previousButton.disabled = currentPage <= 1;
    if (nextButton) nextButton.disabled = currentPage >= totalPages;
  }

  previousButton?.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage -= 1;
      renderPage();
    }
  });

  nextButton?.addEventListener("click", () => {
    if (currentPage < totalPages) {
      currentPage += 1;
      renderPage();
    }
  });

  renderPage();
}
// Tambah di <script> bawah file
document.querySelectorAll('input[name="foto_hewan"]').forEach((input) => {
  input.addEventListener("change", function (e) {
    const file = e.target.files[0];

    if (file) {
      const allowed = ["image/jpeg", "image/png", "image/webp"];
      const maxSize = 2 * 1024 * 1024;

      if (!allowed.includes(file.type)) {
        alert("Format foto harus JPG, PNG, atau WebP");
        this.value = "";
        return;
      }

      if (file.size > maxSize) {
        alert("Ukuran foto maksimal 2MB");
        this.value = "";
        return;
      }
    }
  });
});

// Tambah validasi untuk form edit
const editForm = document.getElementById("editHewanForm");
if (editForm) {
  editForm.addEventListener("submit", function (e) {
    const berat = document.getElementById("editBeratBadan").value;
    const tglLahir = document.getElementById("editTanggalLahir").value;

    if (berat && parseFloat(berat) <= 0) {
      e.preventDefault();
      alert("Berat badan harus lebih dari 0");
      return;
    }

    if (tglLahir && new Date(tglLahir) > new Date()) {
      e.preventDefault();
      alert("Tanggal lahir tidak boleh di masa depan");
      return;
    }
  });
}
