// ---- Tanggal ----
document.getElementById('currentDate').textContent =
    new Date().toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'});

// ---- Utility: tutup modal jika klik backdrop ----
function closeModalOutside(event, overlayId) {
    if (event.target.id === overlayId) {
        document.getElementById(overlayId).classList.remove('active');
        document.getElementById(overlayId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ============ MODAL TAMBAH ============
function openTambah() {
    document.getElementById('tambahForm').reset();
    // Reset status buttons
    document.querySelectorAll('#tambahOverlay .health-status-btn').forEach(b =>
        b.classList.remove('active'));
    document.getElementById('tambah-btn-sehat').classList.add('active');
    document.getElementById('tambahStatusValue').value = 'sehat';

    const overlay = document.getElementById('tambahOverlay');
    overlay.style.display = 'flex';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeTambah() {
    const overlay = document.getElementById('tambahOverlay');
    overlay.classList.remove('active');
    overlay.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function pilihStatusTambah(status, elemen) {
    document.querySelectorAll('#tambahOverlay .health-status-btn').forEach(btn =>
        btn.classList.remove('active'));
    elemen.classList.add('active');
    document.getElementById('tambahStatusValue').value = status;
}

function simpanTambah() {
    showFlashMessage('Data berhasil disimpan.');
    closeTambah();
}

function isiPerkiraanLahirOtomatis() {
    const tglIb = document.getElementById('tambahTglIb')?.value;
    const statusIb = document.getElementById('tambahStatusIb')?.value;
    const perkiraan = document.getElementById('tambahPerkiraanLahir');

    if (!tglIb || statusIb !== 'berhasil' || !perkiraan || perkiraan.value) {
        return;
    }

    const tanggal = new Date(`${tglIb}T00:00:00`);
    tanggal.setMonth(tanggal.getMonth() + 9);
    perkiraan.value = tanggal.toISOString().slice(0, 10);
}

document.getElementById('tambahTglIb')?.addEventListener('change', isiPerkiraanLahirOtomatis);
document.getElementById('tambahStatusIb')?.addEventListener('change', isiPerkiraanLahirOtomatis);

// ============ MODAL EDIT ============
function openEdit(trigger) {
    const record = typeof trigger === 'object' && trigger.dataset
        ? JSON.parse(trigger.dataset.record)
        : {};

    document.getElementById('editIdKesehatan').value = record.id || '';
    document.getElementById('editTanggal').value = record.tanggal || '';
    document.getElementById('editHewan').value = record.id_hewan || '';
    document.getElementById('editDiagnosa').value = record.diagnosis || '';
    document.getElementById('editTindakan').value = record.tindakan || '';
    document.getElementById('editKeterangan').value = record.catatan || '';
    document.getElementById('editTglIb').value = record.tgl_ib || '';
    document.getElementById('editIbKe').value = record.ib_ke || '';
    document.getElementById('editStatusReproduksi').value = record.status_reproduksi || 'Hamil';
    document.getElementById('editTglLahir').value = record.tgl_perkiraan || '';
    document.getElementById('editInfoTambahan').value = record.info_tambahan || '';

    // Set status button aktif
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(b =>
        b.classList.remove('active'));
    const statusMap = { sehat: 'edit-btn-sehat', perawatan: 'edit-btn-perawatan', observasi: 'edit-btn-observasi' };
    if (statusMap[record.status]) document.getElementById(statusMap[record.status]).classList.add('active');
    document.getElementById('inputStatusKesehatan').value = record.status || '';

    const kesehatanTab = document.getElementById('kesehatan-tab');
    if (kesehatanTab && window.bootstrap) {
        new bootstrap.Tab(kesehatanTab).show();
    }

    const overlay = document.getElementById('editOverlay');
    overlay.style.display = 'flex';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEdit() {
    const overlay = document.getElementById('editOverlay');
    overlay.classList.remove('active');
    overlay.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function selectStatus(element, value) {
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(btn =>
        btn.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('inputStatusKesehatan').value = value;
}

function simpanEdit() {
    showFlashMessage('Data berhasil disimpan.');
    closeEdit();
}

// ============ MODAL PREVIEW ============
function openPreview() {
    document.getElementById('ringkasanPreview').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closePreview() {
    document.getElementById('ringkasanPreview').classList.remove('active');
    document.body.style.overflow = 'auto';
}
function closePreviewOutside(event) {
    if (event.target.id === 'ringkasanPreview') closePreview();
}

// ============ MODAL DELETE ============
function openDelete(namaHewan) {
    document.getElementById('deleteTarget').textContent = namaHewan || 'data ini';
    document.getElementById('deleteOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeDelete() {
    document.getElementById('deleteOverlay').classList.remove('active');
    document.body.style.overflow = 'auto';
}
function closeDeleteOutside(event) {
    if (event.target.id === 'deleteOverlay') closeDelete();
}
function confirmDelete() {
    showFlashMessage('Data berhasil dihapus.');
    closeDelete();
}

// ESC untuk tutup semua modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTambah(); closeEdit(); closePreview(); closeDelete();
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
