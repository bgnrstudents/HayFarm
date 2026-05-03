const salesModal = document.getElementById('salesModal');
const salesActions = document.getElementById('salesActions');
const salesStatusText = document.getElementById('salesStatusText');
const salesTotal = document.getElementById('salesTotal');
const salesProof = document.getElementById('salesProof');
const proofTitle = document.getElementById('proofTitle');

function openSalesModal(status) {
    const config = {
        pending: {
            subtitle: 'Periksa bukti pembayaran sebelum konfirmasi',
            statusText: 'Menunggu Verifikasi',
            statusClass: 'waiting',
            customer: 'Ahmad Ridwan',
            email: 'ahmad.ridwan@example.com',
            total: 'Rp 15.250.000',
            rejected: false,
            showProof: true,
            actions: `
                <button class="sales-btn confirm" type="button" onclick="confirmVerification()">Verifikasi & Konfirmasi</button>
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

function openPending() {
    openSalesModal('pending');
}

function openVerified() {
    openSalesModal('verified');
}

function openRejected() {
    openSalesModal('rejected');
}

function confirmVerification() {
    showFlashMessage('Pesanan berhasil diverifikasi.');
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
