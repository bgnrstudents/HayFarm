document.getElementById('currentDate').textContent =
    new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

const searchInput = document.getElementById('searchInput');
const tableRows = document.querySelectorAll('#dataTableBody tr');
let activePreviewRecord = null;

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(keyword) ? '' : 'none';
        });
    });
}

function parseRecord(trigger) {
    return JSON.parse(trigger.dataset.record);
}

function openOverlay(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeOverlay(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = 'auto';
}

function closeModalOutside(event, overlayId) {
    if (event.target.id === overlayId) {
        closeOverlay(overlayId);
    }
}

function formatTanggalIndonesia(dateString) {
    return new Date(dateString + 'T00:00:00').toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function hitungUsia(dateString) {
    const lahir = new Date(dateString + 'T00:00:00');
    const hariIni = new Date();
    let tahun = hariIni.getFullYear() - lahir.getFullYear();
    let bulan = hariIni.getMonth() - lahir.getMonth();

    if (bulan < 0) {
        tahun--;
        bulan += 12;
    }

    if (tahun > 0) {
        return `${tahun} Tahun`;
    }

    return `${Math.max(bulan, 1)} Bulan`;
}

function updateStatusButtons(scopeSelector, status) {
    document.querySelectorAll(`${scopeSelector} .health-status-btn`).forEach(button => {
        button.classList.remove('active');
    });

    const targetId = status === 'tidak_produktif' ? `${scopeSelector === '#editOverlay' ? 'edit' : 'tambah'}-btn-tidak-produktif` : `${scopeSelector === '#editOverlay' ? 'edit' : 'tambah'}-btn-produktif`;
    const targetButton = document.getElementById(targetId);
    if (targetButton) {
        targetButton.classList.add('active');
    }
}

function openTambah() {
    document.getElementById('tambahForm').reset();
    updateStatusButtons('#tambahOverlay', 'produktif');
    document.getElementById('tambahStatusValue').value = 'produktif';
    openOverlay('tambahOverlay');
}

function closeTambah() {
    closeOverlay('tambahOverlay');
}

function selectTambahStatus(status, element) {
    document.querySelectorAll('#tambahOverlay .health-status-btn').forEach(button => button.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('tambahStatusValue').value = status;
}

function fillEditForm(record) {
    document.getElementById('editIdHewan').value = record.id || '';
    document.getElementById('editJenisHewan').value = record.jenis || '';
    document.getElementById('editBeratBadan').value = record.berat || '';
    document.getElementById('editJenisKelamin').value = record.kelamin || '';
    document.getElementById('editNoKandang').value = record.kandang || '';
    document.getElementById('editTanggalLahir').value = record.tgl_lahir || '';
    document.getElementById('editStatusValue').value = record.status || 'produktif';

    document.getElementById('editSummaryId').textContent = record.id || '-';
    document.getElementById('editSummaryJenis').textContent = record.jenis || '-';
    document.getElementById('editSummaryUsia').textContent = record.usia || hitungUsia(record.tgl_lahir);
    document.getElementById('editSummaryStatus').textContent = record.status_label || '-';

    updateStatusButtons('#editOverlay', record.status || 'produktif');
}

function openEdit(trigger) {
    const record = parseRecord(trigger);
    fillEditForm(record);
    openOverlay('editOverlay');
}

function closeEdit() {
    closeOverlay('editOverlay');
}

function selectEditStatus(element, value) {
    document.querySelectorAll('#editOverlay .health-status-btn').forEach(button => button.classList.remove('active'));
    element.classList.add('active');
    document.getElementById('editStatusValue').value = value;
    document.getElementById('editSummaryStatus').textContent = value === 'produktif' ? 'Produktif' : 'Tidak Produktif';
}

function fillPreview(record) {
    activePreviewRecord = record;

    document.getElementById('previewIdBadge').textContent = record.id || '-';
    document.getElementById('previewJenis').textContent = record.jenis || '-';
    document.getElementById('previewKelamin').textContent = record.kelamin || '-';
    document.getElementById('previewBerat').textContent = record.berat || '-';
    document.getElementById('previewUsia').textContent = record.usia || hitungUsia(record.tgl_lahir);
    document.getElementById('previewKandang').textContent = record.kandang || '-';
    document.getElementById('previewTanggalLahir').textContent = formatTanggalIndonesia(record.tgl_lahir);
    document.getElementById('previewImage').src = getPreviewImage(record.jenis);

    const statusWrap = document.getElementById('previewStatusWrap');
    const statusElement = document.getElementById('previewStatus');
    const tersedia = record.status === 'produktif';
    statusElement.textContent = tersedia ? 'Tersedia' : 'Tidak Tersedia';
    statusElement.className = `preview-status-text ${tersedia ? '' : 'status-tidak-produktif'}`;
    statusWrap.className = `status-pill-preview ${tersedia ? '' : 'status-tidak-produktif-dot'}`;
}

function getPreviewImage(jenis) {
    const imageMap = {
        'Sapi Perah': 'https://images.unsplash.com/photo-1546445317-29f4545e9d53?q=80&w=400',
        'Sapi PO': 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=400',
        'Kambing': 'https://images.unsplash.com/photo-1524024973431-2ad916746881?q=80&w=400',
        'Domba': 'https://images.unsplash.com/photo-1484557985045-edf25e08da73?q=80&w=400'
    };

    return imageMap[jenis] || imageMap['Sapi Perah'];
}

function openPreview(trigger) {
    const record = parseRecord(trigger);
    fillPreview(record);
    openOverlay('previewOverlay');
}

function closePreview() {
    closeOverlay('previewOverlay');
}

function closePreviewOutside(event) {
    if (event.target.id === 'previewOverlay') {
        closePreview();
    }
}

function openDelete(namaHewan) {
    document.getElementById('deleteTarget').textContent = namaHewan || 'data ini';
    openOverlay('deleteOverlay');
}

function closeDelete() {
    closeOverlay('deleteOverlay');
}

function closeDeleteOutside(event) {
    if (event.target.id === 'deleteOverlay') {
        closeDelete();
    }
}

function confirmDelete() {
    showFlashMessage('Data hewan berhasil dihapus.');
    closeDelete();
}

document.getElementById('tambahForm').addEventListener('submit', function (event) {
    event.preventDefault();
    showFlashMessage('Data hewan berhasil disimpan.');
    closeTambah();
});

document.getElementById('editHewanForm').addEventListener('submit', function (event) {
    event.preventDefault();
    showFlashMessage('Perubahan data hewan berhasil disimpan.');
    closeEdit();
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeTambah();
        closeEdit();
        closePreview();
        closeDelete();
    }
});
