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

// ============ MODAL EDIT ============
function openEdit(id, tanggal, jenis, status, diagnosa, tindakan, keterangan) {
    // Isi field
    document.getElementById('editIdKesehatan').value = id || '';
    document.getElementById('editTanggal').value = tanggal || '';
    document.getElementById('editJenis').value = jenis || '';
    document.getElementById('editDiagnosa').value = diagnosa || '';
    document.getElementById('editTindakan').value = tindakan || '';
    document.getElementById('editKeterangan').value = keterangan || '';

    // Set status button aktif
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(b =>
        b.classList.remove('active'));
    const statusMap = { sehat: 'edit-btn-sehat', perawatan: 'edit-btn-perawatan', observasi: 'edit-btn-observasi' };
    if (statusMap[status]) document.getElementById(statusMap[status]).classList.add('active');
    document.getElementById('inputStatusKesehatan').value = status || '';

    // Reset tab ke Kesehatan
    const tab = new bootstrap.Tab(document.getElementById('kesehatan-tab'));
    tab.show();

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
