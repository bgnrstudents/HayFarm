document.addEventListener("DOMContentLoaded", function () {
  // DOM Elements
  const salesModal = document.getElementById("salesModal");
  const salesActions = document.getElementById("salesActions");
  const salesStatusText = document.getElementById("salesStatusText");
  const salesTotal = document.getElementById("salesTotal");
  const salesProof = document.getElementById("salesProof");
  const proofTitle = document.getElementById("proofTitle");
  const salesPaymentMethod = document.getElementById("salesPaymentMethod");

  let pendingVerificationFormId = null;
  let pendingVerificationActionId = null;

  // ===== HELPER FUNCTIONS =====
  function readOrderData(button) {
    try {
      return JSON.parse(button?.dataset?.order || "{}");
    } catch (error) {
      return {};
    }
  }

  // Helper: Format Rupiah
  function formatRupiah(amount) {
    return "Rp " + new Intl.NumberFormat("id-ID").format(amount);
  }

  // Fungsi fetch detail via AJAX
  async function fetchTransactionDetails(id_transaksi) {
    try {
      const response = await fetch(
        `../../process/handlers/get_detail_transaksi.php?id_transaksi=${id_transaksi}`,
      );
      const result = await response.json();
      return result;
    } catch (error) {
      console.error("Gagal fetch detail transaksi:", error);
      return { status: false };
    }
  }

  // Update openSalesModal untuk handle AJAX
  async function openSalesModal(status, orderData = {}) {
    const config = {
      pending: {
        subtitle: "Periksa bukti pembayaran sebelum konfirmasi",
        statusText: "Menunggu Verifikasi",
        statusClass: "waiting",
        rejected: false,
        showProof: true,
        actions: `
                <button class="sales-btn confirm" type="button">Verifikasi & Konfirmasi</button>
                <button class="sales-btn reject" type="button">Tolak Pesanan</button>
                <button class="sales-btn close" type="button">Batal</button>
            `,
      },
      verified: {
        subtitle: "Pesanan sudah diverifikasi",
        statusText: "Diverifikasi",
        statusClass: "verified",
        rejected: false,
        showProof: true,
        actions:
          '<button class="sales-btn confirm" type="button">Tutup</button>',
      },
      rejected: {
        subtitle: "Pesanan ditolak",
        statusText: "Ditolak",
        statusClass: "rejected",
        rejected: true,
        showProof: false,
        actions:
          '<button class="sales-btn reject" type="button">Tutup</button>',
      },
    }[status];

    // Isi data dasar dari orderData (yang sudah ada)
    const proofUrl = orderData.proofUrl || "";
    const proofName = orderData.proofName || "Bukti pembayaran belum tersedia";

    const el = (id) => document.getElementById(id);

    if (el("salesOrderId"))
      el("salesOrderId").textContent = orderData.orderId || "#ORD";
    if (el("salesSubtitle")) el("salesSubtitle").textContent = config.subtitle;
    if (el("salesCustomer"))
      el("salesCustomer").textContent = orderData.customer || "Pelanggan";
    if (el("salesPhone")) el("salesPhone").textContent = orderData.phone || "-";
    if (el("salesAddress"))
      el("salesAddress").textContent = orderData.address || "-";

    if (salesStatusText) {
      salesStatusText.textContent = config.statusText;
      salesStatusText.className = `sales-status-badge ${config.statusClass}`;
    }
    if (salesTotal) {
      salesTotal.textContent = orderData.total || "Rp 0";
      salesTotal.className = `sales-total ${config.rejected ? "rejected" : ""}`;
    }
    if (salesPaymentMethod) {
      salesPaymentMethod.textContent = orderData.method || "-";
    }
    if (salesProof) {
      salesProof.style.display = config.showProof && proofUrl ? "flex" : "none";
      if (proofUrl) {
        salesProof.onclick = () => openSalesLightbox(proofUrl);
        const img = salesProof.querySelector("img");
        if (img) {
          img.src = proofUrl;
          img.alt = proofName;
        }
        const valueSpan = salesProof.querySelector(".sales-value");
        if (valueSpan) valueSpan.textContent = proofName;
      } else {
        salesProof.onclick = null;
      }
    }
    if (proofTitle) {
      proofTitle.style.display = config.showProof ? "block" : "none";
    }

    // ✅ FETCH DETAIL LENGKAP VIA AJAX (Email + Produk)
    const orderId = orderData.orderId || "";
    const idTransaksi = orderId.replace("#ORD-", ""); // Extract ID from #ORD-065

    if (idTransaksi && !isNaN(idTransaksi)) {
      // Tampilkan loading state
      if (el("salesEmail")) el("salesEmail").textContent = "Memuat...";
      const productsBody = document.getElementById("salesProductsBody");
      if (productsBody) {
        productsBody.innerHTML =
          '<tr><td colspan="4" style="padding: 12px; text-align: center; color: #94a3b8;">Memuat data produk...</td></tr>';
      }

      const detailResult = await fetchTransactionDetails(parseInt(idTransaksi));

      if (detailResult.status && detailResult.data) {
        const data = detailResult.data;

        // Isi Email
        if (el("salesEmail")) el("salesEmail").textContent = data.email || "-";

        // Isi Tabel Produk
        if (productsBody) {
          if (data.products && data.products.length > 0) {
            productsBody.innerHTML = data.products
              .map((p) => {
                // Tampilkan Kode Hewan kalau ada, fallback ke ID Hewan, atau "-" kalau produk non-hewan
                const animalInfo = p.kode_hewan
                  ? `<span style="color:#16a34a; font-weight:700;">${p.kode_hewan}</span>`
                  : p.id_hewan
                    ? `ID: ${p.id_hewan}`
                    : '<span style="color:#94a3b8;">-</span>';

                return `
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 10px; color: #1f2937; font-weight: 600;">${p.nama_produk}</td>
            <td style="padding: 10px; font-size: 13px;">${animalInfo}</td>
            <td style="padding: 10px; text-align: center; color: #64748b;">${p.jumlah} ${p.satuan}</td>
            <td style="padding: 10px; text-align: right; color: #64748b;">${formatRupiah(p.harga)}</td>
            <td style="padding: 10px; text-align: right; color: #1f2937; font-weight: 700;">${formatRupiah(p.sub_total)}</td>
        </tr>
    `;
              })
              .join("");
          } else {
            productsBody.innerHTML =
              '<tr><td colspan="4" style="padding: 12px; text-align: center; color: #94a3b8;">Tidak ada produk</td></tr>';
          }
        }
      } else {
        // Fallback jika gagal fetch
        if (el("salesEmail")) el("salesEmail").textContent = "-";
        if (productsBody) {
          productsBody.innerHTML =
            '<tr><td colspan="4" style="padding: 12px; text-align: center; color: #ef4444;">Gagal memuat detail</td></tr>';
        }
      }
    }

    // Actions buttons
    if (salesActions) {
      salesActions.innerHTML = config.actions;

      const confirmBtn = salesActions.querySelector(".sales-btn.confirm");
      const rejectBtn = salesActions.querySelector(".sales-btn.reject");
      const closeBtn = salesActions.querySelector(".sales-btn.close");

      if (confirmBtn) confirmBtn.onclick = confirmVerification;
      if (rejectBtn) rejectBtn.onclick = rejectVerification;
      if (closeBtn) closeBtn.onclick = closeSalesModal;
    }

    // Show modal
    if (salesModal) {
      salesModal.classList.add("active");
      document.body.style.overflow = "hidden";
    }
  }

  // ===== PUBLIC FUNCTIONS (Exposed to window) =====
  function openPendingFromButton(button, formId, actionId) {
    pendingVerificationFormId = formId || null;
    pendingVerificationActionId = actionId || null;
    openSalesModal("pending", readOrderData(button));
  }

  function openVerifiedFromButton(button) {
    pendingVerificationFormId = null;
    pendingVerificationActionId = null;
    openSalesModal("verified", readOrderData(button));
  }

  function openRejectedFromButton(button) {
    pendingVerificationFormId = null;
    pendingVerificationActionId = null;
    openSalesModal("rejected", readOrderData(button));
  }

  function confirmVerification() {
    submitPendingVerification("verifikasi");
  }

  function rejectVerification() {
    submitPendingVerification("tolak");
  }

  function submitPendingVerification(action) {
    const form = pendingVerificationFormId
      ? document.getElementById(pendingVerificationFormId)
      : null;
    const actionInput = pendingVerificationActionId
      ? document.getElementById(pendingVerificationActionId)
      : null;
    if (form) {
      if (actionInput) {
        actionInput.value = action;
      }
      form.submit();
      return;
    }
    closeSalesModal();
  }

  function closeSalesModal() {
    if (salesModal) {
      salesModal.classList.remove("active");
    }
    document.body.style.overflow = "auto";
  }

  function closeSalesModalOutside(event) {
    if (event.target && event.target.id === "salesModal") {
      closeSalesModal();
    }
  }

  function openSalesLightbox(src) {
    const lightboxImg = document.getElementById("salesLightboxImage");
    const lightbox = document.getElementById("salesLightbox");
    if (lightboxImg) lightboxImg.src = src;
    if (lightbox) lightbox.classList.add("active");
  }

  function closeSalesLightbox() {
    const lightbox = document.getElementById("salesLightbox");
    if (lightbox) lightbox.classList.remove("active");
  }

  // ===== EXPOSE TO GLOBAL SCOPE (for inline onclick) =====
  window.readOrderData = readOrderData;
  window.openSalesModal = openSalesModal;
  window.openPendingFromButton = openPendingFromButton;
  window.openVerifiedFromButton = openVerifiedFromButton;
  window.openRejectedFromButton = openRejectedFromButton;
  window.confirmVerification = confirmVerification;
  window.rejectVerification = rejectVerification;
  window.submitPendingVerification = submitPendingVerification;
  window.closeSalesModal = closeSalesModal;
  window.closeSalesModalOutside = closeSalesModalOutside;
  window.openSalesLightbox = openSalesLightbox;
  window.closeSalesLightbox = closeSalesLightbox;

  // ===== PAGINATION =====
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
        row.style.display =
          index >= startIndex && index < endIndex ? "" : "none";
      });
      const start = totalRows > 0 ? startIndex + 1 : 0;
      const end = Math.min(endIndex, totalRows);
      if (info)
        info.textContent = `Menampilkan ${start}-${end} dari ${totalRows} data`;
      if (pageButton) pageButton.textContent = currentPage;
      if (previousButton) previousButton.disabled = currentPage <= 1;
      if (nextButton) nextButton.disabled = currentPage >= totalPages;
    }

    if (previousButton)
      previousButton.addEventListener("click", () => {
        if (currentPage > 1) {
          currentPage -= 1;
          renderPage();
        }
      });
    if (nextButton)
      nextButton.addEventListener("click", () => {
        if (currentPage < totalPages) {
          currentPage += 1;
          renderPage();
        }
      });
    renderPage();
  }

  setupAdminPagination("#verifikasiTableBody", ".table-box .pagination", 5);

  // Keyboard handler
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeSalesLightbox();
      closeSalesModal();
    }
  });

  // Auto-dismiss alert after 4 seconds
  function closeHayfarmAlert() {
    const alert = document.getElementById("hayfarmAlert");
    if (alert) {
      alert.style.animation = "hayfarmSlideDown 0.3s ease-in reverse";
      setTimeout(() => alert.remove(), 280);
    }
  }

  // Auto close after 4s
  setTimeout(() => {
    const alert = document.getElementById("hayfarmAlert");
    if (alert) closeHayfarmAlert();
  }, 4000);

  // ===== FILTER HANDLER =====
  function applyFilters() {
    const status = document.getElementById("filterStatus")?.value || "";
    const bulan = document.getElementById("filterBulan")?.value || "";
    const metode = document.getElementById("filterMetode")?.value || "";

    // Build query string
    const params = new URLSearchParams();
    if (status) params.append("status", status);
    if (bulan) params.append("bulan", bulan);
    if (metode) params.append("metode", metode);

    // Redirect dengan parameter filter (maintain current page)
    const baseUrl = window.location.pathname;
    window.location.href = `${baseUrl}?${params.toString()}`;
  }

  // Attach event listeners ke semua filter select
  ["filterStatus", "filterBulan", "filterMetode"].forEach((id) => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener("change", applyFilters);
    }
  });

  // Sync select values with URL params on load
  window.addEventListener("load", () => {
    const urlParams = new URLSearchParams(window.location.search);
    ["status", "bulan", "metode"].forEach((key) => {
      const value = urlParams.get(key);
      const select = document.getElementById(
        `filter${key.charAt(0).toUpperCase() + key.slice(1)}`,
      );
      if (select && value) {
        select.value = value;
      }
    });
  });
});
