

// ==================== UTILITIES ====================
const dateEl = document.getElementById('currentDate');
const now = new Date();
dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function generateId() { return '0000' + (Math.floor(Math.random() * 9000) + 1000); }
function formatRupiah(angka) { return 'Rp ' + parseInt(angka).toLocaleString('id-ID'); }
function formatDate(dateString) { if (!dateString) return '-'; return new Date(dateString).toLocaleDateString('id-ID'); }
function getSatuan(jenis) { const s = { 'hewan': 'Ekor', 'susu': 'Liter', 'rumput': 'Kg' }; return s[jenis?.toLowerCase()] || ''; }
function capitalizeFirst(str) { if (!str) return '-'; return str.charAt(0).toUpperCase() + str.slice(1); }

// ==================== LOCAL STORAGE ====================
const STORAGE_KEY = 'hayfarm_products';
function getProducts() { const d = localStorage.getItem(STORAGE_KEY); return d ? JSON.parse(d) : []; }
function saveProducts(p) { localStorage.setItem(STORAGE_KEY, JSON.stringify(p)); }

function addProduct(product) {
    const products = getProducts();
    product.id = generateId();
    product.tanggal = product.tanggal || product.tanggal_produksi || new Date().toISOString().split('T')[0];
    products.unshift(product);
    saveProducts(products);
    return product;
}

function updateProduct(id, updatedData) {
    let products = getProducts();
    const idx = products.findIndex(p => p.id === id);
    if (idx !== -1) { products[idx] = { ...products[idx], ...updatedData }; saveProducts(products); return true; }
    return false;
}

function deleteProduct(id) {
    let products = getProducts();
    products = products.filter(p => p.id !== id);
    saveProducts(products);
}

// ==================== RENDER TABLE ====================
function renderTable(products, searchQuery = '') {
    const tbody = document.getElementById('productTableBody');
    const emptyState = document.getElementById('emptyState');
    const table = document.querySelector('.product-table');
    tbody.innerHTML = '';
    
    let filtered = products;
    if (searchQuery) {
        const q = searchQuery.toLowerCase();
        filtered = products.filter(p => p.nama?.toLowerCase().includes(q) || p.jenis?.toLowerCase().includes(q));
    }
    
    if (filtered.length === 0) { emptyState.style.display = 'block'; table.style.display = 'none'; return; }
    emptyState.style.display = 'none'; table.style.display = 'table';
    
    filtered.forEach(product => {
        const row = document.createElement('tr');
        const statusClass = product.status === 'tersedia' ? 'status-tersedia' : 'status-tidak-tersedia';
        const statusText = product.status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia';
        const jenisDisplay = product.jenis ? capitalizeFirst(product.jenis) : '-';
        row.innerHTML = `
            <td>${product.id}</td><td>${jenisDisplay}</td><td>${product.nama || '-'}</td>
            <td>${formatDate(product.tanggal)}</td><td>${formatRupiah(product.harga)}</td>
            <td>${product.stok} ${getSatuan(product.jenis)}</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td><div class="action-buttons">
                <button class="action-btn view" onclick="openPreviewModal('${product.id}')"><i class="fa-solid fa-eye"></i></button>
                <button class="action-btn edit" onclick="openEditModal('${product.id}')"><i class="fa-solid fa-pen"></i></button>
                <button class="action-btn delete" onclick="handleDelete('${product.id}')"><i class="fa-solid fa-trash"></i></button>
            </div></td>`;
        tbody.appendChild(row);
    });
}

function updateStats() {
    const p = getProducts();
    document.getElementById('totalProduk').textContent = p.length;
    document.getElementById('totalRumput').textContent = p.filter(x => x.jenis === 'rumput').length;
    document.getElementById('totalSusu').textContent = p.filter(x => x.jenis === 'susu').length;
    document.getElementById('totalHewan').textContent = p.filter(x => x.jenis === 'hewan').length;
}

function handleDelete(id) {
    if (confirm('Yakin ingin menghapus produk ini?')) {
        deleteProduct(id); renderTable(getProducts()); updateStats();
        showToast('Produk berhasil dihapus', 'success');
    }
}

// ==================== SEARCH & FILTER ====================
document.getElementById('tableSearch').addEventListener('input', e => renderTable(getProducts(), e.target.value));
document.getElementById('globalSearch').addEventListener('input', e => { document.getElementById('tableSearch').value = e.target.value; renderTable(getProducts(), e.target.value); });

function openFilterModal() { document.getElementById('filterModal').classList.add('active'); }
function closeFilterModal() { document.getElementById('filterModal').classList.remove('active'); }
function applyFilter() {
    const jenis = document.getElementById('filterJenis').value, status = document.getElementById('filterStatus').value;
    let products = getProducts();
    if (jenis) products = products.filter(p => p.jenis?.toLowerCase() === jenis.toLowerCase());
    if (status) products = products.filter(p => p.status === (status === 'Tersedia' ? 'tersedia' : 'tidak-tersedia'));
    renderTable(products, document.getElementById('tableSearch').value); closeFilterModal();
}
function resetFilter() { document.getElementById('filterJenis').value = ''; document.getElementById('filterStatus').value = ''; renderTable(getProducts(), document.getElementById('tableSearch').value); closeFilterModal(); }
document.querySelector('.btn-filter').addEventListener('click', openFilterModal);
document.getElementById('filterModal').addEventListener('click', e => { if (e.target.id === 'filterModal') closeFilterModal(); });

// ==================== EXPORT CSV ====================
function exportTableToCSV(filename) {
    const products = getProducts();
    if (products.length === 0) { showToast('Tidak ada data untuk diexport', 'error'); return; }
    let csv = ['NO,Jenis Produk,Nama Produk,Tanggal,Harga,Stok,Satuan,Status'];
    products.forEach(p => {
        csv.push([p.id, capitalizeFirst(p.jenis), `"${p.nama || ''}"`, formatDate(p.tanggal), p.harga, p.stok, getSatuan(p.jenis), p.status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia'].join(','));
    });
    const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a'); link.href = URL.createObjectURL(blob); link.download = filename; link.click();
    showToast('Data berhasil diexport!', 'success');
}

// ==================== EDIT MODAL ====================
function openEditModal(productId) {
    const products = getProducts(), product = products.find(p => p.id === productId);
    if (!product) { showToast('Produk tidak ditemukan', 'error'); return; }
    document.querySelectorAll('.edit-modal .form-section').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('.edit-modal .tab').forEach(t => t.classList.remove('active'));
    const jenis = product.jenis?.toLowerCase(); switchEditTab(jenis);
    
    if (jenis === 'hewan') {
        document.getElementById('edit-id-hewan').value = product.id;
        document.getElementById('edit-jenis-hewan').value = product.jenis_detail || '';
        document.getElementById('edit-nama-hewan').value = product.nama || '';
        document.getElementById('edit-berat-hewan').value = product.berat || '';
        document.getElementById('edit-harga-hewan').value = product.harga ? formatRupiah(product.harga).replace(/[^0-9,]/g, '').replace(',', '.') : '';
        document.getElementById('edit-stok-hewan').value = product.stok || '';
        document.getElementById('edit-status-hewan').value = product.status || 'tersedia';
        const opts = document.querySelectorAll('#edit-form-hewan .status-option');
        opts.forEach(o => o.classList.remove('active'));
        (product.status === 'tersedia' ? opts[0] : opts[1]).classList.add('active');
        if (product.foto) { document.getElementById('edit-img-hewan').src = product.foto; document.getElementById('edit-preview-hewan').style.display = 'flex'; document.querySelector('#edit-form-hewan .upload-box').style.display = 'none'; }
        else removeEditImage('hewan');
    } else if (jenis === 'rumput') {
        document.getElementById('edit-id-rumput').value = product.id;
        document.getElementById('edit-jenis-rumput').value = product.jenis_detail || '';
        document.getElementById('edit-nama-rumput').value = product.nama || '';
        document.getElementById('edit-harga-rumput').value = product.harga ? formatRupiah(product.harga).replace(/[^0-9,]/g, '').replace(',', '.') : '';
        document.getElementById('edit-stok-rumput').value = product.stok || '';
        document.getElementById('edit-status-rumput').value = product.status || 'tersedia';
        const opts = document.querySelectorAll('#edit-form-rumput .status-option');
        opts.forEach(o => o.classList.remove('active'));
        (product.status === 'tersedia' ? opts[0] : opts[1]).classList.add('active');
        if (product.foto) { document.getElementById('edit-img-rumput').src = product.foto; document.getElementById('edit-preview-rumput').style.display = 'flex'; document.querySelector('#edit-form-rumput .upload-box').style.display = 'none'; }
        else removeEditImage('rumput');
    } else if (jenis === 'susu') {
        document.getElementById('edit-id-susu').value = product.id;
        document.getElementById('edit-jenis-susu').value = product.jenis_detail || '';
        document.getElementById('edit-nama-susu').value = product.nama || '';
        document.getElementById('edit-tgl-produksi-susu').value = product.tanggal_produksi || '';
        document.getElementById('edit-tgl-expiry-susu').value = product.tanggal_expiry || '';
        document.getElementById('edit-harga-susu').value = product.harga ? formatRupiah(product.harga).replace(/[^0-9,]/g, '').replace(',', '.') : '';
        document.getElementById('edit-stok-susu').value = product.stok || '';
        document.getElementById('edit-status-susu').value = product.status || 'tersedia';
        const opts = document.querySelectorAll('#edit-form-susu .status-option');
        opts.forEach(o => o.classList.remove('active'));
        (product.status === 'tersedia' ? opts[0] : opts[1]).classList.add('active');
        if (product.foto) { document.getElementById('edit-img-susu').src = product.foto; document.getElementById('edit-preview-susu').style.display = 'flex'; document.querySelector('#edit-form-susu .upload-box').style.display = 'none'; }
        else removeEditImage('susu');
    }
    document.getElementById('editModal').classList.add('active');
}
function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }
function switchEditTab(tab) {
    document.querySelectorAll('.edit-modal .tab').forEach(t => { t.classList.remove('active'); if(t.dataset.tab===tab) t.classList.add('active'); });
    document.querySelectorAll('.edit-modal .form-section').forEach(f => f.classList.remove('active'));
    document.getElementById(`edit-form-${tab}`).classList.add('active');
}
function previewEditImage(e, type) {
    const file = e.target.files[0]; if(!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById(`edit-img-${type}`).src = ev.target.result;
        document.getElementById(`edit-preview-${type}`).style.display = 'flex';
        document.querySelector(`#edit-form-${type} .upload-box`).style.display = 'none';
    }; reader.readAsDataURL(file);
}
function removeEditImage(type) {
    document.getElementById(`edit-file-${type}`).value = '';
    document.getElementById(`edit-preview-${type}`).style.display = 'none';
    document.querySelector(`#edit-form-${type} .upload-box`).style.display = 'flex';
}
function selectEditStatus(el, type) {
    el.parentElement.querySelectorAll('.status-option').forEach(o => o.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(`edit-status-${type}`).value = el.classList.contains('available') ? 'tersedia' : 'tidak_tersedia';
}
function handleEditSubmit(e, type) {
    e.preventDefault();
    const id = document.getElementById(`edit-id-${type}`).value, status = document.getElementById(`edit-status-${type}`).value;
    const data = { status, updated_at: new Date().toISOString() };
    if(type==='hewan') {
        data.jenis_detail = document.getElementById('edit-jenis-hewan').value;
        data.nama = document.getElementById('edit-nama-hewan').value;
        data.berat = document.getElementById('edit-berat-hewan').value;
        data.harga = parseInt(document.getElementById('edit-harga-hewan').value.replace(/\D/g,''))||0;
        data.stok = parseInt(document.getElementById('edit-stok-hewan').value)||0;
        const img = document.getElementById('edit-img-hewan').src; if(img && !img.includes('placeholder')) data.foto = img;
    } else if(type==='rumput') {
        data.jenis_detail = document.getElementById('edit-jenis-rumput').value;
        data.nama = document.getElementById('edit-nama-rumput').value;
        data.harga = parseInt(document.getElementById('edit-harga-rumput').value.replace(/\D/g,''))||0;
        data.stok = parseInt(document.getElementById('edit-stok-rumput').value)||0;
        const img = document.getElementById('edit-img-rumput').src; if(img && !img.includes('placeholder')) data.foto = img;
    } else if(type==='susu') {
        data.jenis_detail = document.getElementById('edit-jenis-susu').value;
        data.nama = document.getElementById('edit-nama-susu').value;
        data.tanggal_produksi = document.getElementById('edit-tgl-produksi-susu').value;
        data.tanggal_expiry = document.getElementById('edit-tgl-expiry-susu').value;
        data.harga = parseInt(document.getElementById('edit-harga-susu').value.replace(/\D/g,''))||0;
        data.stok = parseInt(document.getElementById('edit-stok-susu').value)||0;
        const img = document.getElementById('edit-img-susu').src; if(img && !img.includes('placeholder')) data.foto = img;
    }
    if(updateProduct(id, data)) { renderTable(getProducts()); updateStats(); closeEditModal(); showToast('Produk berhasil diperbarui!', 'success'); }
    else showToast('Gagal memperbarui produk', 'error');
}
document.getElementById('editModal').addEventListener('click', e => { if(e.target.id==='editModal') closeEditModal(); });

// ==================== PREVIEW MODAL ====================
function openPreviewModal(productId) {
    const products = getProducts(), product = products.find(p => p.id === productId);
    if(!product) { showToast('Produk tidak ditemukan', 'error'); return; }
    const container = document.getElementById('previewContainer'), jenis = product.jenis?.toLowerCase();
    const statusClass = product.status === 'tersedia' ? 'status-available' : 'status-unavailable';
    const statusText = product.status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia';
    const dotColor = product.status === 'tersedia' ? '#175D2B' : '#f44336';
    let html = '';
    const img = product.foto ? `<img src="${product.foto}" alt="${product.nama}">` : '<span class="no-image"><i class="fa-solid fa-image"></i></span>';
    if(jenis==='rumput') {
        html = `<div class="preview-card"><div class="card-header"><span class="header-title">Preview Produk</span><span class="header-id">ID: ${product.id}</span></div>
        <p class="category">${capitalizeFirst(product.jenis)}</p><div class="card-image">${img}</div><h3 class="detail-title">DETAIL PRODUK RUMPUT</h3>
        <div class="detail-grid">
            <div class="detail-item"><label>Kategori</label><p class="value">${capitalizeFirst(product.jenis)}</p></div>
            <div class="detail-item"><label>Nama Produk</label><p class="value">${product.nama||'-'}</p></div>
            <div class="detail-item"><label>Jenis</label><p class="value">${capitalizeFirst(product.jenis_detail)||'-'}</p></div>
            <div class="detail-item"><label>Harga</label><p class="value">${formatRupiah(product.harga)} / Kg</p></div>
            <div class="detail-item"><label>Stok</label><p class="value">${product.stok||0} Kg</p></div>
            <div class="detail-item"><label>Status</label><p class="value ${statusClass}"><span class="dot" style="background:${dotColor}"></span> ${statusText}</p></div>
        </div><button class="btn-close" onclick="closePreviewModal()">Tutup Preview</button></div>`;
    } else if(jenis==='hewan') {
        html = `<div class="preview-card"><div class="card-header"><span class="header-title">Preview Produk</span><span class="header-id">ID: ${product.id}</span></div>
        <p class="category">${capitalizeFirst(product.jenis)}</p><div class="card-image">${img}</div><h3 class="detail-title">DETAIL PRODUK HEWAN</h3>
        <div class="detail-grid">
            <div class="detail-item"><label>Kategori</label><p class="value">${capitalizeFirst(product.jenis)}</p></div>
            <div class="detail-item"><label>Nama Produk</label><p class="value">${product.nama||'-'}</p></div>
            <div class="detail-item"><label>Jenis Hewan</label><p class="value">${capitalizeFirst(product.jenis_detail)||'-'}</p></div>
            <div class="detail-item"><label>Berat</label><p class="value">${product.berat?product.berat+' Kg':'-'}</p></div>
            <div class="detail-item"><label>Harga</label><p class="value">${formatRupiah(product.harga)}</p></div>
            <div class="detail-item"><label>Jumlah</label><p class="value">${product.stok||0} Ekor</p></div>
            <div class="detail-item"><label>Status</label><p class="value ${statusClass}"><span class="dot" style="background:${dotColor}"></span> ${statusText}</p></div>
        </div><button class="btn-close" onclick="closePreviewModal()">Tutup Preview</button></div>`;
    } else if(jenis==='susu') {
        html = `<div class="preview-card"><div class="card-header"><span class="header-title">Preview Produk</span><span class="header-id">ID: ${product.id}</span></div>
        <p class="category">${capitalizeFirst(product.jenis)}</p><div class="card-image">${img}</div><h3 class="detail-title">DETAIL PRODUK SUSU</h3>
        <div class="detail-grid">
            <div class="detail-item"><label>Kategori</label><p class="value">${capitalizeFirst(product.jenis)}</p></div>
            <div class="detail-item"><label>Nama Produk</label><p class="value">${product.nama||'-'}</p></div>
            <div class="detail-item"><label>Jenis Susu</label><p class="value">${capitalizeFirst(product.jenis_detail)||'-'}</p></div>
            <div class="detail-item"><label>Tgl. Produksi</label><p class="value">${formatDate(product.tanggal_produksi)}</p></div>
            <div class="detail-item"><label>Tgl. Kadaluarsa</label><p class="value">${formatDate(product.tanggal_expiry)}</p></div>
            <div class="detail-item"><label>Harga</label><p class="value">${formatRupiah(product.harga)} / Liter</p></div>
            <div class="detail-item"><label>Stok</label><p class="value">${product.stok||0} Liter</p></div>
            <div class="detail-item"><label>Status</label><p class="value ${statusClass}"><span class="dot" style="background:${dotColor}"></span> ${statusText}</p></div>
        </div><button class="btn-close" onclick="closePreviewModal()">Tutup Preview</button></div>`;
    }
    container.innerHTML = html; document.getElementById('previewModal').classList.add('active');
}
function closePreviewModal() { document.getElementById('previewModal').classList.remove('active'); }
document.getElementById('previewModal').addEventListener('click', e => { if(e.target.id==='previewModal') closePreviewModal(); });

// ==================== ADD MODAL ====================
function openAddModal() {
    document.querySelectorAll('#addProductModal .form-section').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('#addProductModal .tab').forEach(t => t.classList.remove('active'));
    switchAddTab('hewan'); resetAddForm('hewan'); resetAddForm('susu'); resetAddForm('rumput');
    document.getElementById('addProductModal').classList.add('active');
}
function closeAddModal() { document.getElementById('addProductModal').classList.remove('active'); }
function switchAddTab(tab) {
    document.querySelectorAll('#addProductModal .tab').forEach(t => { t.classList.remove('active'); if(t.dataset.tab===tab) t.classList.add('active'); });
    document.querySelectorAll('#addProductModal .form-section').forEach(f => f.classList.remove('active'));
    document.getElementById(`add-form-${tab}`).classList.add('active');
}
function resetAddForm(type) {
    document.getElementById(`add-form-${type}`).reset();
    const opts = document.querySelectorAll(`#add-form-${type} .status-option`);
    opts.forEach(o => o.classList.remove('active')); opts[0].classList.add('active');
    document.getElementById(`add-status-${type}`).value = 'tersedia'; removeAddImage(type);
}
function previewAddImage(e, type) {
    const file = e.target.files[0]; if(!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById(`add-img-${type}`).src = ev.target.result;
        document.getElementById(`add-preview-${type}`).style.display = 'flex';
        document.querySelector(`#add-form-${type} .upload-box`).style.display = 'none';
    }; reader.readAsDataURL(file);
}
function removeAddImage(type) {
    document.getElementById(`add-file-${type}`).value = '';
    document.getElementById(`add-preview-${type}`).style.display = 'none';
    document.querySelector(`#add-form-${type} .upload-box`).style.display = 'flex';
}
function selectAddStatus(el, type) {
    el.parentElement.querySelectorAll('.status-option').forEach(o => o.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(`add-status-${type}`).value = el.classList.contains('available') ? 'tersedia' : 'tidak_tersedia';
}
function formatCurrencyInput(input) {
    let v = input.value.replace(/[^0-9]/g,'');
    if(v) { input.dataset.raw = v; input.value = 'Rp ' + parseInt(v).toLocaleString('id-ID'); }
}
function getRawCurrency(input) { return input.dataset.raw || input.value.replace(/[^0-9]/g,''); }

function handleAddSubmit(e, type) {
    e.preventDefault();
    const labels = { 'hewan':'Hewan', 'susu':'Susu', 'rumput':'Rumput' };
    let product = { jenis: type };
    
    if(type==='hewan') {
        const nama = document.getElementById('add-nama-hewan').value.trim(), hargaRaw = getRawCurrency(document.getElementById('add-harga-hewan')), stok = document.getElementById('add-stok-hewan').value, status = document.getElementById('add-status-hewan').value;
        if(!nama || !hargaRaw || !stok) { showToast('Mohon lengkapi semua field yang wajib diisi!', 'error'); return; }
        product.nama = nama; product.harga = parseInt(hargaRaw); product.stok = parseInt(stok); product.status = status;
        product.jenis_detail = document.getElementById('add-jenis-hewan').value;
        product.berat = document.getElementById('add-berat-hewan').value || null;
    } else if(type==='susu') {
        const nama = document.getElementById('add-nama-susu').value.trim(), tglProd = document.getElementById('add-tgl-produksi-susu').value, tglExp = document.getElementById('add-tgl-expiry-susu').value, hargaRaw = getRawCurrency(document.getElementById('add-harga-susu')), stok = document.getElementById('add-stok-susu').value, status = document.getElementById('add-status-susu').value;
        if(!nama || !tglProd || !hargaRaw || !stok) { showToast('Mohon lengkapi semua field yang wajib diisi!', 'error'); return; }
        product.nama = nama; product.tanggal_produksi = tglProd; product.tanggal_expiry = tglExp; product.tanggal = tglProd;
        product.harga = parseInt(hargaRaw); product.stok = parseInt(stok); product.status = status;
        product.jenis_detail = document.getElementById('add-jenis-susu').value;
    } else if(type==='rumput') {
        const nama = document.getElementById('add-nama-rumput').value.trim(), hargaRaw = getRawCurrency(document.getElementById('add-harga-rumput')), stok = document.getElementById('add-stok-rumput').value, status = document.getElementById('add-status-rumput').value;
        if(!nama || !hargaRaw || !stok) { showToast('Mohon lengkapi semua field yang wajib diisi!', 'error'); return; }
        product.nama = nama; product.harga = parseInt(hargaRaw); product.stok = parseInt(stok); product.status = status;
        product.jenis_detail = document.getElementById('add-jenis-rumput').value;
        product.tanggal = new Date().toISOString().split('T')[0];
    }
    
    const img = document.getElementById(`add-img-${type}`).src;
    if(img && !img.includes('placeholder') && img.startsWith('data:')) product.foto = img;
    
    addProduct(product); renderTable(getProducts()); updateStats(); closeAddModal();
    showToast(`Produk ${labels[type]} berhasil ditambahkan!`, 'success');
}
document.getElementById('addProductModal')?.addEventListener('click', e => { if(e.target.id==='addProductModal') closeAddModal(); });

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', () => {
    updateStats(); renderTable(getProducts());
    const last = sessionStorage.getItem('productCount'), curr = getProducts().length;
    if(last && curr > parseInt(last)) showToast('Produk baru berhasil ditambahkan!', 'success');
    sessionStorage.setItem('productCount', curr);
    window.addEventListener('storage', e => { if(e.key===STORAGE_KEY) { updateStats(); renderTable(getProducts()); }});
    document.querySelectorAll('input[placeholder="Rp 0"]').forEach(inp => {
        inp.addEventListener('blur', function() { if(this.value && !this.value.startsWith('Rp')) formatCurrencyInput(this); });
    });
});