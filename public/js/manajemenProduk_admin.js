const defaultProducts = [];

const products =
  Array.isArray(window.productData) && window.productData.length
    ? window.productData
    : defaultProducts;

let activeFilter = { type: "", status: "" };
let editingId = null;
let pendingDeleteProductId = null;
let currentProductPage = 1;
const rowsPerPage = 5;

document.addEventListener("DOMContentLoaded", () => {
  renderProducts();
  updateStats();

  const search = document.getElementById("tableSearch");
  if (search) {
    search.addEventListener("input", () => {
      currentProductPage = 1;
      renderProducts();
    });
  }

  const filterButton = document.querySelector(".btn-filter");
  if (filterButton) {
    filterButton.addEventListener("click", openFilterModal);
  }
});

function getFilteredProducts() {
  const keyword = (document.getElementById("tableSearch")?.value || "")
    .toLowerCase()
    .trim();

  return products.filter((product) => {
    const matchesKeyword = [
      product.type,
      product.name,
      product.price,
      product.stock,
      product.status,
    ]
      .join(" ")
      .toLowerCase()
      .includes(keyword);
    const matchesType =
      !activeFilter.type || product.type === activeFilter.type;
    const matchesStatus =
      !activeFilter.status || product.status === activeFilter.status;

    return matchesKeyword && matchesType && matchesStatus;
  });
}

function renderProducts() {
  const tbody = document.getElementById("productTableBody");
  if (!tbody) return;

  const rows = getFilteredProducts();
  const totalPages = Math.max(1, Math.ceil(rows.length / rowsPerPage));
  currentProductPage = Math.min(currentProductPage, totalPages);
  const startIndex = (currentProductPage - 1) * rowsPerPage;
  const visibleRows = rows.slice(startIndex, startIndex + rowsPerPage);

  tbody.innerHTML = visibleRows
    .map(
      (product, index) => `
        <tr class="${needsPriceInput(product) ? "needs-price-row" : ""}">
            <td>${startIndex + index + 1}</td>
            <td>${product.type}</td>
            <td>${product.name}</td>
            <td>${formatDate(product.date)}</td>
            <td>${formatDate(product.expiryDate)}</td>
            <td>${needsPriceInput(product) ? `<span class="price-needed">${product.price}</span>` : product.price}</td>
            <td>${product.stock}</td>
            <td><span class="status-badge ${product.status === "Tersedia" ? "status-tersedia" : "status-tidak-tersedia"}">${product.status}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view" type="button" title="Preview" onclick="openPreviewModal(${product.id})"><i class="fa-solid fa-eye"></i></button>
                    <button class="action-btn edit" type="button" title="Edit" onclick="openEditModal(${product.id})"><i class="fa-solid fa-pen"></i></button>
                    <button class="action-btn delete" type="button" title="Hapus" onclick="deleteProduct(${product.id})"><i class="fa-solid fa-trash"></i></button>
                </div>
            </td>
        </tr>
    `,
    )
    .join("");

  if (!rows.length) {
    tbody.innerHTML =
      '<tr><td colspan="9" style="text-align:center;color:#777;">Data produk tidak ditemukan</td></tr>';
  }

  updateProductPagination(rows.length);
}

function updateStats() {
  setText("totalProduk", products.length);
  setText(
    "totalRumput",
    products.filter((product) => product.type === "Rumput").length,
  );
  setText(
    "totalSusu",
    products.filter((product) => product.type === "Susu").length,
  );
  setText(
    "totalHewan",
    products.filter((product) => product.type === "Hewan").length,
  );
}

function updateProductPagination(totalVisible) {
  const info = document.getElementById("productPaginationInfo");
  const pagination = document.querySelector(".product-section .pagination");
  if (!info || !pagination) return;

  const total = totalVisible || 0;
  const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
  const start = total > 0 ? (currentProductPage - 1) * rowsPerPage + 1 : 0;
  const end = total > 0 ? Math.min(currentProductPage * rowsPerPage, total) : 0;
  info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;

  const buttons = pagination.querySelectorAll(".page-btn");
  if (buttons[0]) buttons[0].disabled = currentProductPage <= 1;
  if (buttons[1]) buttons[1].textContent = currentProductPage;
  if (buttons[2]) buttons[2].disabled = currentProductPage >= totalPages;
}

function changeProductPage(direction) {
  const totalPages = Math.max(
    1,
    Math.ceil(getFilteredProducts().length / rowsPerPage),
  );
  currentProductPage = Math.min(
    Math.max(currentProductPage + direction, 1),
    totalPages,
  );
  renderProducts();
}

function setText(id, value) {
  const element = document.getElementById(id);
  if (element) element.textContent = value;
}

function formatDate(dateString) {
  if (!dateString) return "-";

  const isoDate = normalizeDateValue(dateString);
  if (!isoDate) return "-";

  return isoToDisplayDate(isoDate);
}

function openFilterModal() {
  document.getElementById("filterModal")?.classList.add("active");
}

function closeFilterModal() {
  document.getElementById("filterModal")?.classList.remove("active");
}

function applyFilter() {
  const jenis = document.getElementById("filterJenis")?.value || "";
  const status = document.getElementById("filterStatus")?.value || "";

  // Mapping status UI ke parameter backend
  let statusParam = null;
  if (status === "Tersedia") {
    statusParam = "tersedia";
  } else if (status === "Tidak Tersedia") {
    statusParam = "tidak_tersedia";
  }

  // Update URL parameter untuk BOTH status & jenis
  const url = new URL(window.location.href);

  if (statusParam) {
    url.searchParams.set("status_filter", statusParam);
  } else {
    url.searchParams.delete("status_filter");
  }

  // ✅ TAMBAH: Kirim jenis_produk ke server
  if (jenis) {
    url.searchParams.set("jenis_filter", jenis.toLowerCase());
  } else {
    url.searchParams.delete("jenis_filter");
  }

  // Filter jenis produk tetap client-side untuk search bar (optional)
  activeFilter.type = jenis;

  // Reload halaman dengan parameter baru
  window.location.href = url.toString();
}

function resetFilter() {
  activeFilter = { type: "", status: "" };

  // Reset UI selects
  const filterJenis = document.getElementById("filterJenis");
  const filterStatus = document.getElementById("filterStatus");
  if (filterJenis) filterJenis.value = "";
  if (filterStatus) filterStatus.value = "";

  // Reset URL params
  const url = new URL(window.location.href);
  url.searchParams.delete("status_filter");
  url.searchParams.delete("jenis_filter"); // ✅ TAMBAH INI
  window.history.replaceState({}, "", url);

  currentProductPage = 1;
  renderProducts();
}

function openAddModal() {
  const modal = document.getElementById("addProductModal");
  if (!modal) return;

  modal.classList.add("active");

  // Reset semua form ke state awal
  ["hewan", "rumput", "susu"].forEach((type) => {
    resetAddStatus(type); // ✅ TAMBAH INI
    const form = document.getElementById(`add-form-${type}`);
    if (form) form.reset();
  });

  // Buka tab hewan sebagai default
  switchAddTab("hewan");
}

function closeAddModal() {
  document.getElementById("addProductModal")?.classList.remove("active");
}

function switchAddTab(type) {
  switchTabInModal("addProductModal", type, "add-form");
}

function switchEditTab(type) {
  // Switch tab di modal
  const modal = document.getElementById("editModal");
  if (modal) {
    modal.querySelectorAll(".tab").forEach((tab) => {
      tab.classList.toggle("active", tab.dataset.tab === type);
    });
  }

  // Sembunyikan SEMUA form section
  document.querySelectorAll(".form-section").forEach((section) => {
    section.classList.remove("active");
  });

  // Tampilkan HANYA form yang sesuai
  const targetForm = document.getElementById(`edit-form-${type}`);
  if (targetForm) {
    targetForm.classList.add("active");
  }
}

function switchTabInModal(modalId, type, formPrefix) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  // Switch tab active state
  modal.querySelectorAll(".tab").forEach((tab) => {
    tab.classList.toggle("active", tab.dataset.tab === type);
  });

  // Switch form visibility
  modal
    .querySelectorAll(".form-section")
    .forEach((section) => section.classList.remove("active"));
  const targetForm = document.getElementById(`${formPrefix}-${type}`);
  if (targetForm) {
    targetForm.classList.add("active");
  }
}

function handleAddSubmit(event, type) {
  event.preventDefault();

  try {
    const form = event.target;
    const validationMessage = validateProductForm(form, type, "add");
    if (validationMessage) {
      notifyProductMessage(validationMessage, "danger");
      return;
    }

    const product = applyExpiryStatus(readProductForm("add", type));
    const addError = validateProductData(product);
    if (addError) {
      notifyProductMessage(addError, "danger");
      return;
    }

    submitProductToServer("tambah", product);
  } catch (error) {
    notifyProductMessage(getProductErrorMessage(error), "danger");
  }
}

function validateProductForm(form, type, mode = "add") {
  if (!form) {
    return "Form tambah produk tidak ditemukan. Muat ulang halaman lalu coba lagi.";
  }

  if (!["hewan", "rumput", "susu"].includes(type)) {
    return "Jenis produk tidak valid. Pilih tab produk yang tersedia lalu coba lagi.";
  }

  if (!form.checkValidity()) {
    form.reportValidity();
    return "Lengkapi semua kolom wajib dengan format yang benar sebelum menyimpan.";
  }

  const price = priceNumber(getValue(`${mode}-harga-${type}`));
  if (price <= 0) {
    return "Harga produk harus lebih dari Rp 0.";
  }

  const stock = Number(getValue(`${mode}-stok-${type}`));
  if (!Number.isFinite(stock) || stock < 0) {
    return "Stok produk harus berupa angka yang valid.";
  }

  if (type === "hewan" && stock < 1) {
    return "Stok hewan minimal 1 ekor.";
  }

  if (type === "susu") {
    const prodDate = getDateInputIso(`${mode}-tgl-produksi-susu`);
    const expDate = getDateInputIso(`${mode}-tgl-expiry-susu`);

    if (!prodDate) {
      return "Tanggal produksi susu wajib diisi.";
    }
    if (!expDate) {
      return "Tanggal kadaluarsa susu wajib diisi.";
    }
    if (expDate < prodDate) {
      return "Tanggal kadaluarsa harus setelah tanggal produksi.";
    }
    if (expDate < todayIsoDate()) {
      return "Tanggal kadaluarsa tidak boleh di masa lalu.";
    }
  }

  return "";
}

function validateProductData(product) {
  const typeKey = { Hewan: "hewan", Rumput: "rumput", Susu: "susu" }[
    product?.type
  ];

  if (!product?.name || product.name === defaultName(typeKey)) {
    return "Nama produk belum terisi dengan benar.";
  }

  if (
    !product?.type ||
    !product?.price ||
    !product?.stock ||
    !product?.status
  ) {
    return "Data produk belum lengkap. Periksa kembali isian form.";
  }

  return "";
}

function getProductErrorMessage(error) {
  console.error("Gagal menambahkan produk:", error);
  return (
    error?.message ||
    "Produk gagal ditambahkan karena terjadi kesalahan sistem. Silakan coba lagi."
  );
}

function notifyProductMessage(message, type = "success") {
  if (typeof showFlashMessage === "function") {
    showFlashMessage(message, type);
    return;
  }

  alert(message);
}

function openEditModal(id) {
  const product = products.find((item) => item.id === id);
  if (!product) return;

  editingId = id;
  const type = product.type.toLowerCase();

  // Switch ke form yang sesuai
  switchEditTab(type);
  fillEditForm(product, type);
  configureEditRequiredFields(product, type);

  // Reset status buttons sebelum isi form
  resetEditStatus(type); // ✅ TAMBAH INI

  document.getElementById("editModal")?.classList.add("active");
}

function closeEditModal() {
  document.getElementById("editModal")?.classList.remove("active");
  editingId = null;
}

function handleEditSubmit(event, type) {
  event.preventDefault();
  const validationMessage = validateProductForm(event.target, type, "edit");
  if (validationMessage) {
    notifyProductMessage(validationMessage, "danger");
    return;
  }

  const index = products.findIndex((item) => item.id === editingId);
  if (index === -1) return;

  const updatedProduct = applyExpiryStatus({
    ...products[index],
    ...readProductForm("edit", type),
    id: editingId,
  });
  if (priceNumber(updatedProduct.price) > 0) {
    updatedProduct.needs_price = false;
  }
  submitProductToServer("edit", updatedProduct);
}

function readProductForm(mode, type) {
  const label = { hewan: "Hewan", rumput: "Rumput", susu: "Susu" }[type];
  const name = getValue(`${mode}-nama-${type}`) || defaultName(type);
  const price = getValue(`${mode}-harga-${type}`) || "Rp 0";
  const stockValue =
    type === "hewan" ? "1" : getValue(`${mode}-stok-${type}`) || "0";
  const statusRaw = normalizeStatus(getValue(`${mode}-status-${type}`));

  // ✅ FIX: Kirim enum DB langsung, bukan text UI
  const statusDb = statusRaw === "Tersedia" ? "blm_terjual" : "terjual";

  const date =
    type === "susu" ? getDateInputIso(`${mode}-tgl-produksi-susu`) : "";
  const expiryDate =
    type === "susu" ? getDateInputIso(`${mode}-tgl-expiry-susu`) : "";
  const idHewan = type === "hewan" ? getValue(`${mode}-id-hewan`) : "";

  const data = {
    type: label,
    name,
    idHewan,
    date,
    expiryDate,
    price,
    stock: `${stockValue} ${stockUnit(type)}`,
    status_produk: statusDb, // ✅ Ini sudah benar: "blm_terjual" atau "terjual"
    status: statusRaw === "Tersedia" ? "Tersedia" : "Tidak Tersedia",
  };

  if (mode === "add") {
    data.image = defaultImage(type);
  }
  return data;
}

function configureEditRequiredFields(product, type) {
  const form = document.getElementById(`edit-form-${type}`);
  if (!form) return;

  form.querySelectorAll("input, select, textarea").forEach((input) => {
    input.required = false;
  });

  const priceInput = document.getElementById(`edit-harga-${type}`);
  if (priceInput) {
    priceInput.required = true;
  }
}

function fillEditForm(product, type) {
  setValue(`edit-nama-${type}`, product.name);
  if (type === "hewan" && product.idHewan) {
    ensureEditAnimalOption(product);
    setValue(`edit-id-hewan`, product.idHewan);
  }
  setValue(`edit-harga-${type}`, product.price);
  setValue(
    `edit-stok-${type}`,
    type === "hewan" ? 1 : parseInt(product.stock, 10) || "",
  );
  setValue(
    `edit-status-${type}`,
    product.status.toLowerCase().replaceAll(" ", "-"),
  );

  if (type === "susu") {
    setValue("edit-tgl-produksi-susu", normalizeDateValue(product.date));
    setValue("edit-tgl-expiry-susu", addDays(product.date, 7));
  }

  const statusInput = document.getElementById(`edit-status-${type}`);
  const statusWrap = statusInput?.closest(".form-group");
  statusWrap?.querySelectorAll(".status-option").forEach((option) => {
    option.classList.toggle(
      "active",
      option.classList.contains(
        product.status === "Tersedia" ? "available" : "unavailable",
      ),
    );
  });
}

function ensureEditAnimalOption(product) {
  const select = document.getElementById("edit-id-hewan");
  if (!select || !product?.idHewan) return;
  if (product.statusHewan && product.statusHewan !== "tdk_produktif") return;

  const optionValue = String(product.idHewan);
  const exists = Array.from(select.options).some(
    (option) => option.value === optionValue,
  );
  if (exists) return;

  const kodeHewan = product.kodeHewan || `HF-${optionValue.padStart(3, "0")}`;
  const jenisHewan = String(product.jenisHewan || "sapi").replaceAll("_", " ");
  const option = document.createElement("option");
  option.value = optionValue;
  option.textContent = `${kodeHewan} - ${jenisHewan.replace(/\b\w/g, (char) => char.toUpperCase())}`;
  select.appendChild(option);
}

function getValue(id) {
  return document.getElementById(id)?.value.trim() || "";
}

function setValue(id, value) {
  const element = document.getElementById(id);
  if (element) element.value = value;
}

function normalizeStatus(value) {
  return value === "tidak-tersedia" || value === "tidak tersedia"
    ? "Tidak Tersedia"
    : "Tersedia";
}

function applyExpiryStatus(product) {
  if (isExpiredMilkProduct(product)) {
    product.status = "Tidak Tersedia";
  }

  return product;
}

function isExpiredMilkProduct(product) {
  const expiryDate = normalizeDateValue(product?.expiryDate);
  return (
    product?.type === "Susu" &&
    Boolean(expiryDate) &&
    expiryDate < todayIsoDate()
  );
}

function stockUnit(type) {
  return { hewan: "Ekor", rumput: "Kg", susu: "Liter" }[type] || "";
}

function defaultName(type) {
  return {
    hewan: "Produk Hewan",
    rumput: "Produk Rumput",
    susu: "Produk Susu",
  }[type];
}

function defaultImage(type) {
  return {
    hewan: "https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600",
    rumput:
      "https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=600",
    susu: "https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600",
  }[type];
}

function selectAddStatus(element, type) {
  // ✅ Reset semua button status di form type ini
  const container = element.closest(".status-options");
  container.querySelectorAll(".status-option").forEach((option) => {
    option.classList.remove("active");
  });

  // Set button yang diklik jadi active
  element.classList.add("active");

  // Update hidden input
  const inputId = `add-status-${type}`;
  setValue(
    inputId,
    element.classList.contains("available") ? "tersedia" : "tidak-tersedia",
  );
}

function selectEditStatus(element, type) {
  // ✅ Reset semua button status di form type ini
  const container = element.closest(".status-options");
  container.querySelectorAll(".status-option").forEach((option) => {
    option.classList.remove("active");
  });

  // Set button yang diklik jadi active
  element.classList.add("active");

  // Update hidden input
  const inputId = `edit-status-${type}`;
  setValue(
    inputId,
    element.classList.contains("available") ? "tersedia" : "tidak-tersedia",
  );
}

function resetEditStatus(type) {
  const container = document.querySelector(
    `#edit-form-${type} .status-options`,
  );
  if (!container) return;

  // Reset semua button
  container.querySelectorAll(".status-option").forEach((option) => {
    option.classList.remove("active");
  });

  // Set default ke "Tersedia"
  const availableBtn = container.querySelector(".status-option.available");
  if (availableBtn) {
    availableBtn.classList.add("active");
  }

  // Update hidden input
  setValue(`edit-status-${type}`, "tersedia");
}
function resetAddStatus(type) {
  const container = document.querySelector(`#add-form-${type} .status-options`);
  if (!container) return;

  // Reset semua button
  container.querySelectorAll(".status-option").forEach((option) => {
    option.classList.remove("active");
  });

  // Set default ke "Tersedia"
  const availableBtn = container.querySelector(".status-option.available");
  if (availableBtn) {
    availableBtn.classList.add("active");
  }

  // Update hidden input
  setValue(`add-status-${type}`, "tersedia");
}

function selectStatusOption(element, inputId) {
  element.parentElement
    .querySelectorAll(".status-option")
    .forEach((option) => option.classList.remove("active"));
  element.classList.add("active");
  setValue(
    inputId,
    element.classList.contains("available") ? "tersedia" : "tidak-tersedia",
  );
}

function openPreviewModal(id) {
  const product = products.find((item) => item.id === id);
  if (!product) return;

  // Set judul & badge
  setText("previewTitle", `Preview ${product.type}`);
  setText("previewSubtitle", `Detail informasi ${product.type.toLowerCase()}`);

  // Set badge dengan warna dinamis
  const badge = document.getElementById("previewBadge");
  const badgeColors = {
    Hewan: "#175d2b",
    Susu: "#0284c7",
    Rumput: "#16a34a",
  };
  badge.textContent = product.type;
  badge.style.background = badgeColors[product.type] || "#175d2b";

  // Reset semua section (hide dulu)
  document
    .querySelectorAll(
      ".preview-hewan-only, .preview-susu-only, .preview-rumput-only",
    )
    .forEach((el) => (el.style.display = "none"));

  // Isi data umum
  setText("previewProductName", product.name);
  setText("previewProductType", product.type);
  setText("previewProductPrice", product.price);
  setText("previewProductStock", product.stock);
  setText("previewProductStatus", product.status);
  setText("previewDeskripsi", product.deskripsi || "Tidak ada deskripsi.");

  // Render berdasarkan tipe produk
  renderPreviewByType(product);

  // Set status pill dengan class yang benar
  const statusWrap = document.getElementById("previewStatusWrap");
  if (statusWrap) {
    // ✅ JANGAN reset className, cukup hapus class status saja
    statusWrap.classList.remove("available", "unavailable");

    // Tambah class berdasarkan status
    if (product.status === "Tersedia") {
      statusWrap.classList.add("available");
    } else {
      statusWrap.classList.add("unavailable");
    }

    setText("previewProductStatus", product.status);
  }

  // Show modal
  document.getElementById("previewModal")?.classList.add("active");
}

function renderPreviewByType(product) {
  if (product.type === "Hewan") {
    // Tampilkan section hewan
    document
      .querySelectorAll(".preview-hewan-only")
      .forEach((el) => (el.style.display = ""));
    setText(
      "previewHewanId",
      product.idHewan ? `HF-${String(product.idHewan).padStart(3, "0")}` : "-",
    );
    setText("previewNoKandang", product.noKandang || "-");
    setText(
      "previewTglLahir",
      product.tglLahir ? formatDate(product.tglLahir) : "-",
    );
  } else if (product.type === "Susu") {
    // Tampilkan section susu
    document
      .querySelectorAll(".preview-susu-only")
      .forEach((el) => (el.style.display = ""));
    setText("previewProdDate", product.date ? formatDate(product.date) : "-");
    setText(
      "previewExpDate",
      product.expiryDate ? formatDate(product.expiryDate) : "-",
    );
  } else if (product.type === "Rumput") {
    // Tampilkan section rumput
    document
      .querySelectorAll(".preview-rumput-only")
      .forEach((el) => (el.style.display = ""));
  }
}

function setupMilkExpiryAutomation() {
  bindMilkExpiry("add");
  bindMilkExpiry("edit");
}

function bindMilkExpiry(mode) {
  const production = document.getElementById(`${mode}-tgl-produksi-susu`);
  const expiry = document.getElementById(`${mode}-tgl-expiry-susu`);
  if (!production || !expiry) return;

  if (mode === "add" && !production.value) {
    production.value = todayIsoDate();
  }

  production.addEventListener("change", () => syncMilkExpiry(mode));
  production.addEventListener("input", () => syncMilkExpiry(mode));
  syncMilkExpiry(mode);
}

function syncMilkExpiry(mode) {
  const production = document.getElementById(`${mode}-tgl-produksi-susu`);
  const expiry = document.getElementById(`${mode}-tgl-expiry-susu`);
  if (!production || !expiry) return;

  if (mode === "add" && !production.value) {
    production.value = todayIsoDate();
  }

  const productionDate = getDateInputIso(`${mode}-tgl-produksi-susu`);
  expiry.value = productionDate ? addDays(productionDate, 7) : "";
}

function addDays(dateString, days) {
  const isoDate = normalizeDateValue(dateString);
  if (!isoDate) return "";

  const [year, month, day] = isoDate.split("-").map(Number);
  const date = new Date(year, month - 1, day);
  date.setDate(date.getDate() + days);
  return dateToIsoDate(date);
}

function normalizeDateValue(value) {
  const rawValue = String(value || "").trim();
  if (/^\d{4}-\d{2}-\d{2}$/.test(rawValue)) {
    return isValidIsoDate(rawValue) ? rawValue : "";
  }

  const match = rawValue.match(/^(\d{2})-(\d{2})-(\d{4})$/);
  if (!match) return "";

  const [, day, month, year] = match;
  const isoDate = `${year}-${month}-${day}`;
  return isValidIsoDate(isoDate) ? isoDate : "";
}

function getDateInputIso(id) {
  const input = document.getElementById(id);
  if (!input) return "";

  const value = normalizeDateValue(input.value);
  if (value) return value;

  if (
    input.valueAsDate instanceof Date &&
    !Number.isNaN(input.valueAsDate.getTime())
  ) {
    return dateToIsoDate(input.valueAsDate);
  }

  return "";
}

function isoToDisplayDate(value) {
  const isoDate = normalizeDateValue(value);
  if (!isoDate) return "";

  const [year, month, day] = isoDate.split("-");
  return `${day}-${month}-${year}`;
}

function isValidIsoDate(value) {
  const match = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
  if (!match) return false;

  const [, year, month, day] = match.map(Number);
  const date = new Date(year, month - 1, day);
  return (
    date.getFullYear() === year &&
    date.getMonth() === month - 1 &&
    date.getDate() === day
  );
}

function todayIsoDate() {
  return dateToIsoDate(new Date());
}

function dateToIsoDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

function closePreviewModal() {
  const modal = document.getElementById("previewModal");
  if (modal) {
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
  }
}

function formatProductId(product) {
  const prefix =
    { Hewan: "S-H", Rumput: "S-R", Susu: "S-S" }[product.type] || "S-P";
  return `${prefix}-${String(product.id).slice(-3).padStart(3, "0")}`;
}

function deleteProduct(id) {
  const product = products.find((item) => item.id === id);
  if (!product) return;

  pendingDeleteProductId = id;
  setText("deleteProductTarget", `${product.type} - ${product.name}`);
  document.getElementById("deleteProductOverlay")?.classList.add("active");
  // document.body.style.overflow = 'hidden';
}

function closeProductDelete() {
  document.getElementById("deleteProductOverlay")?.classList.remove("active");
  document.body.style.overflow = "auto";
  pendingDeleteProductId = null;
}

function closeProductDeleteOutside(event) {
  if (event.target.id === "deleteProductOverlay") {
    closeProductDelete();
  }
}

function confirmProductDelete() {
  const index = products.findIndex(
    (item) => item.id === pendingDeleteProductId,
  );
  if (index === -1) return;

  if (pendingDeleteProductId < 100000) {
    submitProductToServer("hapus", products[index]);
    return;
  }

  products.splice(index, 1);
  renderProducts();
  updateStats();
  closeProductDelete();
  showFlashMessage("Produk berhasil dihapus.");
}

function formatCurrencyInput(input) {
  const numbers = input.value.replace(/\D/g, "");
  input.value = numbers ? `Rp ${Number(numbers).toLocaleString("id-ID")}` : "";
}

function priceNumber(price) {
  return Number(String(price || "").replace(/\D/g, "")) || 0;
}

function needsPriceInput(product) {
  return Boolean(product.needs_price) && priceNumber(product.price) === 0;
}

function submitProductToServer(action, product) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "../../process/handlers/produk_handler.php";
  form.style.display = "none";

  const fields = {
    aksi: action,
    id_produk: product.id || "",
    jenis_produk: String(product.type || "").toLowerCase(),
    nama_produk: product.name || "",
    id_hewan: product.idHewan || "",
    harga: priceNumber(product.price),
    stok: parseInt(product.stock, 10) || 0,
    tgl_kadaluarsa:
      product.type === "Susu" ? normalizeDateValue(product.expiryDate) : "",
    status_produk: product.status || "Tersedia",
  };

  Object.entries(fields).forEach(([name, value]) => {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
  });

  document.body.appendChild(form);
  form.submit();
}

function exportLaporanProduk() {
  const filtered = getFilteredProducts();
  if (filtered.length === 0) {
    alert("Tidak ada data untuk diekspor. Sesuaikan filter terlebih dahulu.");
    return;
  }

  // 📅 Metadata Laporan
  const now = new Date();
  const timestamp =
    now.toLocaleDateString("id-ID") + " " + now.toLocaleTimeString("id-ID");
  const filterStatus =
    document.getElementById("filterStatus")?.value || "Semua";
  const filterJenis = document.getElementById("filterJenis")?.value || "Semua";

  // 📑 Header Laporan
  const header = [
    "LAPORAN DATA PRODUK - HAY FARM TEFA POLIJE",
    "",
    `Tanggal Cetak,${timestamp}`,
    `Filter Status,${filterStatus}`,
    `Filter Jenis,${filterJenis}`,
    "",
    "No,ID Produk,Jenis Produk,Nama Produk,Harga Satuan,Stok,Satuan,Status,Tgl Kadaluarsa,Nilai Stok",
  ];

  // 📊 Data Rows + Kalkulasi
  let totalNilaiStok = 0;
  const rows = filtered.map((p, i) => {
    const hargaNum = parseFloat(p.price.replace(/\D/g, "")) || 0;
    const stokNum = parseInt(p.stock) || 0;
    const nilaiStok = hargaNum * stokNum;
    totalNilaiStok += nilaiStok;

    // Derive satuan dari field stok
    const satuan = p.stock.includes("Ekor")
      ? "Ekor"
      : p.stock.includes("Liter")
        ? "Liter"
        : "Ton";

    return [
      i + 1,
      `PROD-${String(p.id).padStart(3, "0")}`,
      p.type,
      `"${p.name.replace(/"/g, '""')}"`,
      p.price,
      stokNum,
      satuan,
      p.status,
      p.expiryDate || "-",
      `Rp ${nilaiStok.toLocaleString("id-ID")}`,
    ].join(",");
  });

  // 📉 Footer Ringkasan
  const footer = [
    "",
    `TOTAL ITEM,${filtered.length}`,
    `TOTAL NILAI STOK,"Rp ${totalNilaiStok.toLocaleString("id-ID")}"`,
    "",
    "Generated by Hay Farm System | Data bersifat internal TEFA Produksi Ternak",
  ];

  // 💾 Generate & Download
  const csvContent = [...header, ...rows, ...footer].join("\n");
  // \uFEFF = BOM UTF-8 (biar Excel baca format & huruf correctly)
  const blob = new Blob(["\uFEFF" + csvContent], {
    type: "text/csv;charset=utf-8;",
  });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `Laporan_Produk_HayFarm_${now.toISOString().slice(0, 10)}.csv`;
  link.click();
}
document.addEventListener("click", (event) => {
  if (event.target.classList.contains("modal-overlay")) {
    event.target.classList.remove("active");
    document.body.style.overflow = "auto";
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeAddModal();
    closeEditModal();
    closeFilterModal();
    closePreviewModal();
    closeProductDelete();
  }
});
function handleAddSubmit(event, type) {
  event.preventDefault();

  try {
    const form = event.target;
    const validationMessage = validateProductForm(form, type, "add");
    if (validationMessage) {
      notifyProductMessage(validationMessage, "danger");
      return;
    }

    const product = applyExpiryStatus(readProductForm("add", type));
    const addError = validateProductData(product);
    if (addError) {
      notifyProductMessage(addError, "danger");
      return;
    }

    if (type === "hewan" && product.idHewan) {
      const isDuplicate = products.some((p) => p.idHewan == product.idHewan);
      if (isDuplicate) {
        notifyProductMessage(
          "Hewan ini sudah memiliki produk. Tidak bisa membuat produk duplikat.",
          "danger",
        );
        return;
      }
    }

    submitProductToServer("tambah", product);
  } catch (error) {
    notifyProductMessage(getProductErrorMessage(error), "danger");
  }
}
