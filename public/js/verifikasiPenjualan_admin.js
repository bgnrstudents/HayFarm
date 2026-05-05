const salesModal = document.getElementById('salesModal');
const salesActions = document.getElementById('salesActions');
const salesStatusText = document.getElementById('salesStatusText');
const salesTotal = document.getElementById('salesTotal');
const salesProof = document.getElementById('salesProof');
const proofTitle = document.getElementById('proofTitle');
let pendingVerificationFormId = null;
let pendingVerificationActionId = null;

function openSalesModal(status, orderId, customer, total) {
    const config = {
        pending: {
            subtitle: 'Periksa bukti pembayaran sebelum konfirmasi',
            statusText: 'Menunggu Verifikasi',
            statusClass: 'waiting',
            customer: customer || 'Pelanggan',
            email: 'ahmad.ridwan@example.com',
            total: total || 'Rp 0',
            rejected: false,
            showProof: true,
            actions: `
                <button class="sales-btn confirm" type="button" onclick="confirmVerification()">Verifikasi & Konfirmasi</button>
                <button class="sales-btn reject" type="button" onclick="rejectVerification()">Tolak Pesanan</button>
                <button class="sales-btn close" type="button" onclick="closeSalesModal()">Batal</button>
            `
        },
        verified: {
            subtitle: 'Pesanan sudah diverifikasi',
            statusText: 'Diverifikasi',
            statusClass: 'verified',
            customer: 'Siti Rahma',
            email: 'siti.rahma@example.com',
            total: 'Rp 28.500.000',
            rejected: false,
            showProof: true,
            actions: '<button class="sales-btn confirm" type="button" onclick="closeSalesModal()">Tutup</button>'
        },
        rejected: {
            subtitle: 'Pesanan ditolak',
            statusText: 'Ditolak',
            statusClass: 'rejected',
            customer: 'Dewi Lestari',
            email: 'dewi.lestari@example.com',
            total: 'Rp 12.500.000',
            rejected: true,
            showProof: false,
            actions: '<button class="sales-btn reject" type="button" onclick="closeSalesModal()">Tutup</button>'
        }
    }[status];

    document.getElementById('salesOrderId').textContent = orderId || '#ORD';
    document.getElementById('salesSubtitle').textContent = config.subtitle;
    document.getElementById('salesCustomer').textContent = config.customer;
    document.getElementById('salesEmail').textContent = config.email;
    salesStatusText.textContent = config.statusText;
    salesStatusText.className = `sales-status-badge ${config.statusClass}`;
    salesTotal.textContent = config.total;
    salesTotal.className = `sales-total ${config.rejected ? 'rejected' : ''}`;
    salesProof.style.display = config.showProof ? 'flex' : 'none';
    proofTitle.style.display = config.showProof ? 'block' : 'none';
    salesActions.innerHTML = config.actions;
    salesModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openPending(formId, actionId, orderId, customer, total) {
    pendingVerificationFormId = formId || null;
    pendingVerificationActionId = actionId || null;
    openSalesModal('pending', orderId, customer, total);
}

function openVerified() {
    pendingVerificationFormId = null;
    pendingVerificationActionId = null;
    openSalesModal('verified');
}

function openRejected() {
    pendingVerificationFormId = null;
    pendingVerificationActionId = null;
    openSalesModal('rejected');
}

function confirmVerification() {
    submitPendingVerification('verifikasi');
}

function rejectVerification() {
    submitPendingVerification('tolak');
}

function submitPendingVerification(action) {
    const form = pendingVerificationFormId ? document.getElementById(pendingVerificationFormId) : null;
    const actionInput = pendingVerificationActionId ? document.getElementById(pendingVerificationActionId) : null;
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
    salesModal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function closeSalesModalOutside(event) {
    if (event.target.id === 'salesModal') {
        closeSalesModal();
    }
}

function openSalesLightbox(src) {
    document.getElementById('salesLightboxImage').src = src;
    document.getElementById('salesLightbox').classList.add('active');
}

function closeSalesLightbox() {
    document.getElementById('salesLightbox').classList.remove('active');
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeSalesLightbox();
        closeSalesModal();
    }
});

function setupAdminPagination(tbodySelector, paginationSelector, rowsPerPage = 5) {
    const tbody = document.querySelector(tbodySelector);
    const pagination = document.querySelector(paginationSelector);
    if (!tbody || !pagination) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));
    const totalRows = rows.length;
    const totalPages = Math.max(1, Math.ceil(totalRows / rowsPerPage));
    let currentPage = 1;

    const info = pagination.querySelector('span');
    const buttons = pagination.querySelectorAll('.page-btn');
    const previousButton = buttons[0];
    const pageButton = buttons[1];
    const nextButton = buttons[2];

    function renderPage() {
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = index >= startIndex && index < endIndex ? '' : 'none';
        });

        const start = totalRows > 0 ? startIndex + 1 : 0;
        const end = Math.min(endIndex, totalRows);
        if (info) info.textContent = `Menampilkan ${start}-${end} dari ${totalRows} data`;
        if (pageButton) pageButton.textContent = currentPage;
        if (previousButton) previousButton.disabled = currentPage <= 1;
        if (nextButton) nextButton.disabled = currentPage >= totalPages;
    }

    previousButton?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage -= 1;
            renderPage();
        }
    });

    nextButton?.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage += 1;
            renderPage();
        }
    });

    renderPage();
}
