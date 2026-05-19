/**
 * ============================================
 * HAYFARM - Data Kesehatan & Reproduksi Admin
 * File: public/js/dataKesehatan_admin.js
 * Version: 5.0 (FINAL FIX)
 * ============================================
 */

// =====================================================
// 📦 SECTION 1: UTILITIES & HELPERS
// =====================================================

function parseRecord(trigger) {
  if (!trigger?.dataset?.record) return null;
  try {
    return JSON.parse(trigger.dataset.record);
  } catch (e) {
    console.error("Parse error:", e);
    return null;
  }
}

function formatTanggal(dateString) {
  if (!dateString) return "-";
  return new Date(dateString + "T00:00:00").toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}

function labelStatusKesehatan(status) {
  return (
    {
      sehat: "Sehat",
      observasi: "Dalam Observasi",
      perawatan: "Dalam Perawatan",
      dalam_observasi: "Dalam Observasi",
      dalam_perawatan: "Dalam Perawatan",
    }[status] || "-"
  );
}

function classStatusKesehatan(status) {
  return (
    {
      sehat: "status-sehat",
      observasi: "status-observasi",
      perawatan: "status-perawatan",
      dalam_observasi: "status-observasi",
      dalam_perawatan: "status-perawatan",
    }[status] || ""
  );
}

function labelStatusIB(status) {
  if (!status) return "-";
  return (
    {
      berhasil: "Berhasil",
      proses: "Proses IB",
      tidak_berhasil: "Tidak Berhasil",
    }[status] || "Tidak Diketahui"
  );
}

// =====================================================
// 🎭 SECTION 2: MODAL OVERLAY CONTROLS
// =====================================================

function openOverlay(id) {
  const el = document.getElementById(id);
  if (el) {
    el.classList.add("active");
    el.style.display = "flex";
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
  if (event.target.id === overlayId) closeOverlay(overlayId);
}

// =====================================================
// 🎨 SECTION 3: STATUS BUTTON HANDLERS
// =====================================================

function updateStatusButtons(scopeSelector, status) {
  document
    .querySelectorAll(`${scopeSelector} .health-status-btn`)
    .forEach((btn) => btn.classList.remove("active"));
  const prefix = scopeSelector.includes("edit") ? "edit" : "tambah";
  const targetId = `${prefix}-btn-${status.replace("_", "-")}`;
  const targetBtn = document.getElementById(targetId);
  if (targetBtn) targetBtn.classList.add("active");
}

// =====================================================
// ➕ SECTION 4: MODAL TAMBAH (CREATE)
// =====================================================

function openTambah() {
  document.getElementById("tambahForm")?.reset();
  updateStatusButtons("#tambahOverlay", "sehat");
  document.getElementById("tambahStatusValue").value = "sehat";
  updateFieldRequirements("sehat", "tambah");
  openOverlay("tambahOverlay");
}

function closeTambah() {
  closeOverlay("tambahOverlay");
}

function pilihStatusTambah(status, element) {
  updateStatusButtons("#tambahOverlay", status);
  document.getElementById("tambahStatusValue").value = status;
  updateFieldRequirements(status, "tambah");
}

// =====================================================
// ✏️ SECTION 5: MODAL EDIT (UPDATE) - ✅ FIXED
// =====================================================

function openEdit(trigger) {
  const record = parseRecord(trigger);
  if (!record) {
    alert("Gagal memuat data edit.");
    return;
  }
  fillEditForm(record);
  openOverlay("editOverlay");
}

function closeEdit() {
  closeOverlay("editOverlay");
}

function fillEditForm(record) {
  if (!record) return;

  // ✅ Cari animal SEKALI saja
  const animal = window.animalsData?.find(
    (a) => String(a.id_hewan) === String(record.id_hewan),
  );

  // Isi hidden input id_hewan
  document.getElementById("editIdKesehatanPK").value =
    record.id_kesehatan || "";
  document.getElementById("editIdHewan").value = record.id_hewan || "";

  // Isi display nama hewan
  const hewanDisplay = animal
    ? `${animal.kode_hewan} - ${animal.jenis_hewan
        .split("_")
        .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
        .join(" ")}`
    : "Data hewan tidak ditemukan";
  document.getElementById("editHewanDisplay").value = hewanDisplay;

  // Isi field kesehatan
  document.getElementById("editTanggalPemeriksaan").value =
    record.tgl_pemeriksaan || "";
  document.getElementById("editDiagnosis").value = record.diagnosis || "";
  document.getElementById("editTindakan").value = record.tindakan || "";
  document.getElementById("editCatatan").value = record.catatan || "";

  // Isi field IB
  document.getElementById("editTglIb").value = record.tgl_ib || "";
  document.getElementById("editIbKe").value = record.ib_ke || "";
  document.getElementById("editTglPerkiraan").value =
    record.tgl_perkiraan || "";
  document.getElementById("editStatusIb").value = record.status_ib || "";

  // ✅ Isi No Kandang (pakai animal yang sudah dicari)
  const editKandangEl = document.getElementById("editNoKandang");
  if (editKandangEl && animal) {
    editKandangEl.value = animal.no_kandang || "-";
  }

  // Status Kesehatan Button
  const status = record.status_kesehatan || "sehat";
  document.getElementById("editStatusValue").value = status;
  document
    .querySelectorAll("#editOverlay .health-status-btn")
    .forEach((btn) => btn.classList.remove("active"));

  let btnId = "edit-btn-sehat";
  if (status === "dalam_observasi" || status === "observasi")
    btnId = "edit-btn-observasi";
  if (status === "dalam_perawatan" || status === "perawatan")
    btnId = "edit-btn-perawatan";

  const activeBtn = document.getElementById(btnId);
  if (activeBtn) activeBtn.classList.add("active");

  updateFieldRequirements(status, "edit");

  // ✅ Trigger visibility section IB (langsung panggil, tidak via change event)
  setTimeout(() => {
    checkReproduksiVisibility(
      "editOverlay",
      "editIdHewan",
      "edit-section-reproduksi",
    );
  }, 150);
}

function pilihStatusEdit(status, element) {
  document
    .querySelectorAll("#editOverlay .health-status-btn")
    .forEach((btn) => btn.classList.remove("active"));
  element.classList.add("active");
  document.getElementById("editStatusValue").value = status;
  updateFieldRequirements(status, "edit");
}

// =====================================================
// 👁️ SECTION 6: MODAL PREVIEW
// =====================================================

function fillPreview(record) {
  if (!record) return;
  const setText = (id, val, fallback = "-") => {
    const el = document.getElementById(id);
    if (el) el.textContent = val ?? fallback;
  };

  setText("previewKodeHewan", record.kode_hewan);
  setText("previewJenis", record.jenis_hewan);
  setText(
    "previewTanggal",
    record.tgl_pemeriksaan ? formatTanggal(record.tgl_pemeriksaan) : "-",
  );
  setText("previewDiagnosis", record.diagnosis);
  setText("previewTindakan", record.tindakan);
  setText("previewCatatan", record.catatan || "-");

  const statusEl = document.getElementById("previewStatus");
  if (statusEl) {
    statusEl.textContent = labelStatusKesehatan(record.status_kesehatan);
    statusEl.className = `status-pill ${classStatusKesehatan(record.status_kesehatan)}`;
  }

  setText("previewTglIb", record.tgl_ib ? formatTanggal(record.tgl_ib) : "-");
  setText("previewIbKe", record.ib_ke || "-");
  setText(
    "previewTglPerkiraan",
    record.tgl_perkiraan ? formatTanggal(record.tgl_perkiraan) : "-",
  );
  setText("previewStatusIb", labelStatusIB(record.status_ib));
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
function closePreviewOutside(e) {
  if (e.target.id === "previewOverlay") closePreview();
}

// =====================================================
// 🗑️ SECTION 7: MODAL DELETE
// =====================================================

function openDelete(label, idKesehatan) {
  document.getElementById("deleteTarget").textContent = label || "data ini";
  document.getElementById("deleteIdKesehatanPK").value = idKesehatan || "";
  openOverlay("deleteOverlay");
}

function closeDelete() {
  closeOverlay("deleteOverlay");
}
function closeDeleteOutside(e) {
  if (e.target.id === "deleteOverlay") closeDelete();
}

// =====================================================
// ✅ SECTION 8: FORM VALIDATION
// =====================================================

function validateKesehatanForm(formId, statusValue) {
  const form = document.getElementById(formId);
  if (!form) return true;

  const statusInputId =
    formId === "editKesehatanForm" ? "editStatusValue" : "tambahStatusValue";
  const status = statusValue || document.getElementById(statusInputId)?.value || "sehat";
  const diagnosis = form.querySelector('[name="diagnosis"]');
  const tindakan = form.querySelector('[name="tindakan"]');

  if (status === "sehat") {
    if (diagnosis) {
      diagnosis.required = false;
      diagnosis.setCustomValidity("");
    }
    if (tindakan) {
      tindakan.required = false;
      tindakan.setCustomValidity("");
    }
  }

  if (status !== "sehat") {
    if (!diagnosis?.value.trim()) {
      alert(
        'Diagnosis wajib diisi untuk status "' +
          labelStatusKesehatan(status) +
          '".',
      );
      diagnosis?.focus();
      return false;
    }
    if (!tindakan?.value.trim()) {
      alert(
        'Tindakan wajib diisi untuk status "' +
          labelStatusKesehatan(status) +
          '".',
      );
      tindakan?.focus();
      return false;
    }
  }

  if (diagnosis) {
    diagnosis.required = status !== "sehat";
    diagnosis.placeholder = status === "sehat" ? "Opsional" : "Wajib diisi";
  }
  if (tindakan) {
    tindakan.required = status !== "sehat";
    tindakan.placeholder = status === "sehat" ? "Opsional" : "Wajib diisi";
  }
  return true;
}

function updateFieldRequirements(status, formPrefix) {
  const diagnosis = document.querySelector(
    `#${formPrefix}Overlay [name="diagnosis"]`,
  );
  const tindakan = document.querySelector(
    `#${formPrefix}Overlay [name="tindakan"]`,
  );
  const requiredSpan = document.querySelector(
    `#${formPrefix}Overlay #diagnosis-required`,
  );
  const diagnosisRequired = document.getElementById(
    `${formPrefix}-diagnosis-required`,
  );
  const tindakanRequired = document.getElementById(
    `${formPrefix}-tindakan-required`,
  );

  const isRequired = status !== "sehat";
  const placeholder = isRequired ? "Wajib diisi" : "Opsional";

  if (diagnosis) {
    diagnosis.required = isRequired;
    diagnosis.placeholder = placeholder;
    diagnosis.style.borderColor =
      isRequired && !diagnosis.value ? "#ef4444" : "";
  }
  if (tindakan) {
    tindakan.required = isRequired;
    tindakan.placeholder = placeholder;
    tindakan.style.borderColor = isRequired && !tindakan.value ? "#ef4444" : "";
  }
  if (requiredSpan) {
    requiredSpan.style.display = isRequired ? "inline" : "none";
  }
  if (diagnosisRequired) {
    diagnosisRequired.style.display = isRequired ? "inline" : "none";
  }
  if (tindakanRequired) {
    tindakanRequired.style.display = isRequired ? "inline" : "none";
  }
}

// =====================================================
// 🐄 SECTION 9: ANIMAL INFO DISPLAY
// =====================================================

function formatJenisHewan(jenis) {
  if (!jenis) return "-";
  return jenis
    .split("_")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");
}

function formatKelamin(kelamin) {
  if (!kelamin) return "-";
  return kelamin.charAt(0).toUpperCase() + kelamin.slice(1);
}

function updateAnimalInfo(selectElement, formPrefix) {
  const selectedId = selectElement.value;
  const animal = window.animalsData?.find(
    (h) => String(h.id_hewan) === String(selectedId),
  );
  if (!animal) return;

  const jenisEl = document.getElementById(`${formPrefix}JenisSummary`);
  const kelaminEl = document.getElementById(`${formPrefix}KelaminSummary`);
  if (jenisEl) jenisEl.textContent = formatJenisHewan(animal.jenis_hewan);
  if (kelaminEl) kelaminEl.textContent = formatKelamin(animal.jenis_kelamin);

  const kandangEl = document.getElementById(`${formPrefix}NoKandang`);
  if (kandangEl) kandangEl.value = animal.no_kandang || "-";
}

// =====================================================
// 🔄 SECTION 10: REPRODUKSI VISIBILITY - ✅ FIXED
// =====================================================

function checkReproduksiVisibility(modalId, selectId, sectionId = null) {
  const select = document.getElementById(selectId);

  // ✅ Auto-detect sectionId jika tidak dikirim
  const defaultSectionId =
    modalId === "tambahOverlay"
      ? "tambah-section-reproduksi"
      : "edit-section-reproduksi";
  const section = document.getElementById(sectionId || defaultSectionId);

  if (!select || !section) return;

  const selectedId = select.value.trim();
  if (!selectedId) {
    section.style.display = "none";
    return;
  }

  const animal = window.animalsData?.find(
    (h) => String(h.id_hewan) === String(selectedId),
  );

  const isBreedingEligible =
    animal &&
    (animal.jenis_hewan === "sapi_perah" || animal.jenis_hewan === "sapi_po") &&
    animal.jenis_kelamin === "betina";

  if (isBreedingEligible) {
    section.style.display = "block";
  } else {
    section.style.display = "none";
    section
      .querySelectorAll("input, select")
      .forEach((input) => (input.value = ""));
  }
}

// =====================================================
// ⚙️ SECTION 11: EVENT LISTENERS & INIT - ✅ FIXED
// =====================================================

function initEventListeners() {
  // Form Submission: Loading State
  ["tambahForm", "editKesehatanForm", "deleteKesehatanForm"].forEach(
    (formId) => {
      const form = document.getElementById(formId);
      if (form) {
        form.addEventListener("submit", function () {
          const btn = this.querySelector('button[type="submit"]');
          if (btn) {
            btn.disabled = true;
            btn.textContent = "Memproses...";
          }
        });
      }
    },
  );

  // Form Validation Hook
  ["tambahForm", "editKesehatanForm"].forEach((formId) => {
    const form = document.getElementById(formId);
    if (form) {
      form.addEventListener("submit", function (e) {
        const statusInputId =
          formId === "editKesehatanForm" ? "editStatusValue" : "tambahStatusValue";
        const statusValue = document.getElementById(statusInputId)?.value || "sehat";
        if (!validateKesehatanForm(formId, statusValue)) e.preventDefault();
      });
    }
  });

  // Dropdown Hewan: Update Info & Reproduksi Visibility
  const tambahSelect = document.getElementById("tambahIdHewan");
  if (tambahSelect) {
    tambahSelect.addEventListener("change", function () {
      updateAnimalInfo(this, "tambah"); // ✅ Pakai fungsi baru
      checkReproduksiVisibility(
        "tambahOverlay",
        "tambahIdHewan",
        "tambah-section-reproduksi",
      ); // ✅ Lengkap
    });
  }

  // Modal Edit: Tidak ada dropdown, jadi tidak perlu event listener change

  // Keyboard: ESC to Close Modals
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeTambah();
      closeEdit();
      closePreview();
      closeDelete();
    }
  });

  // Initial Setup: Reproduksi Visibility
  checkReproduksiVisibility(
    "tambahOverlay",
    "tambahIdHewan",
    "tambah-section-reproduksi",
  );
  checkReproduksiVisibility(
    "editOverlay",
    "editIdHewan",
    "edit-section-reproduksi",
  );
}

// =====================================================
// 📄 SECTION 12: PAGINATION
// =====================================================

function setupAdminPagination(
  tbodySelector,
  paginationSelector,
  rowsPerPage = 5,
) {
  const tbody = document.querySelector(tbodySelector);
  const pagination = document.querySelector(paginationSelector);
  if (!tbody || !pagination) return;

  const rows = Array.from(tbody.querySelectorAll("tr"));
  const total = rows.length;
  const pages = Math.max(1, Math.ceil(total / rowsPerPage));
  let page = 1;

  const [prevBtn, pageBtn, nextBtn] = pagination.querySelectorAll(".page-btn");
  const info = pagination.querySelector("span");

  function render() {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    rows.forEach((row, i) => {
      row.style.display = i >= start && i < end ? "" : "none";
    });
    if (info)
      info.textContent = `Menampilkan ${total ? start + 1 : 0}-${Math.min(end, total)} dari ${total} data`;
    if (pageBtn) pageBtn.textContent = page;
    if (prevBtn) prevBtn.disabled = page <= 1;
    if (nextBtn) nextBtn.disabled = page >= pages;
  }

  prevBtn?.addEventListener("click", () => {
    if (page > 1) {
      page--;
      render();
    }
  });
  nextBtn?.addEventListener("click", () => {
    if (page < pages) {
      page++;
      render();
    }
  });
  render();
}

// =====================================================
// 🚀 SECTION 13: OVERRIDES & BOOTSTRAP - ✅ FIXED
// =====================================================

const originalOpenTambah = openTambah;
openTambah = function () {
  originalOpenTambah();
  setTimeout(() => {
    checkReproduksiVisibility(
      "tambahOverlay",
      "tambahIdHewan",
      "tambah-section-reproduksi",
    );
    updateFieldRequirements("sehat", "tambah");
  }, 100);
};

const originalOpenEdit = openEdit;
openEdit = function (trigger) {
  originalOpenEdit(trigger);
  setTimeout(() => {
    checkReproduksiVisibility(
      "editOverlay",
      "editIdHewan",
      "edit-section-reproduksi",
    );
  }, 100);
};

const originalPilihStatusTambah = pilihStatusTambah;
pilihStatusTambah = function (status, element) {
  originalPilihStatusTambah(status, element);
  updateFieldRequirements(status, "tambah");
};

const originalPilihStatusEdit = pilihStatusEdit;
pilihStatusEdit = function (status, element) {
  originalPilihStatusEdit(status, element);
  updateFieldRequirements(status, "edit");
};

// ✅ DOM Ready: Init Everything - SATU KALI SAJA
document.addEventListener("DOMContentLoaded", function () {
  initEventListeners();
});

// Tambah di <script> bawah file data_kesehatan.php
function pilihStatus(status, el) {
  // Update UI button
  document
    .querySelectorAll(".health-status-btn")
    .forEach((btn) => btn.classList.remove("active"));
  el.classList.add("active");
  document.getElementById("statusValue").value = status;

  // Conditional required: diagnosis & tindakan wajib jika bukan 'sehat'
  const required = status !== "sehat";
  document.getElementById("diagRequired").style.display = required
    ? "inline"
    : "none";
  document.getElementById("tindakRequired").style.display = required
    ? "inline"
    : "none";
  document.getElementById("inputDiagnosis").required = required;
  document.getElementById("inputTindakan").required = required;
}
