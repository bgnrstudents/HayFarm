const defaultProducts = [
    {
        id: 1,
        type: 'Hewan',
        name: 'Sapi Perah FH',
        date: '2026-03-10',
        price: 'Rp 20.000.000',
        stock: '4 Ekor',
        status: 'Tersedia',
        image: 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600'
    },
    {
        id: 2,
        type: 'Rumput',
        name: 'Rumput Odot Premium',
        date: '2026-03-12',
        price: 'Rp 2.500',
        stock: '500 Kg',
        status: 'Tersedia',
        image: 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=600'
    },
    {
        id: 3,
        type: 'Susu',
        name: 'Susu Segar Premium',
        date: '2026-03-14',
        price: 'Rp 15.000',
        stock: '200 Liter',
        status: 'Tersedia',
        image: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600'
    }
];

const products = Array.isArray(window.productData) && window.productData.length
    ? window.productData
    : defaultProducts;
products.forEach(applyExpiryStatus);

let activeFilter = { type: '', status: '' };
let editingId = null;
let pendingDeleteProductId = null;
let currentProductPage = 1;
const rowsPerPage = 5;

document.addEventListener('DOMContentLoaded', () => {
    renderProducts();
    updateStats();
    setupMilkExpiryAutomation();

    const search = document.getElementById('tableSearch');
    if (search) {
        search.addEventListener('input', () => {
            currentProductPage = 1;
            renderProducts();
        });
    }

    const filterButton = document.querySelector('.btn-filter');
    if (filterButton) {
        filterButton.addEventListener('click', openFilterModal);
    }
});

function getFilteredProducts() {
    const keyword = (document.getElementById('tableSearch')?.value || '').toLowerCase().trim();

    return products.filter(product => {
        const matchesKeyword = [product.type, product.name, product.price, product.stock, product.status]
            .join(' ')
            .toLowerCase()
            .includes(keyword);
        const matchesType = !activeFilter.type || product.type === activeFilter.type;
        const matchesStatus = !activeFilter.status || product.status === activeFilter.status;

        return matchesKeyword && matchesType && matchesStatus;
    });
}

function renderProducts() {
    const tbody = document.getElementById('productTableBody');
    if (!tbody) return;

    const rows = getFilteredProducts();
    const totalPages = Math.max(1, Math.ceil(rows.length / rowsPerPage));
    currentProductPage = Math.min(currentProductPage, totalPages);
    const startIndex = (currentProductPage - 1) * rowsPerPage;
    const visibleRows = rows.slice(startIndex, startIndex + rowsPerPage);

    tbody.innerHTML = visibleRows.map((product, index) => `
        <tr class="${needsPriceInput(product) ? 'needs-price-row' : ''}">
            <td>${startIndex + index + 1}</td>
            <td>${product.type}</td>
            <td>${product.name}</td>
            <td>${formatDate(product.date)}</td>
            <td>${formatDate(product.expiryDate)}</td>
            <td>${needsPriceInput(product) ? `<span class="price-needed">${product.price}</span>` : product.price}</td>
            <td>${product.stock}</td>
            <td><span class="status-badge ${product.status === 'Tersedia' ? 'status-tersedia' : 'status-tidak-tersedia'}">${product.status}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view" type="button" title="Preview" onclick="openPreviewModal(${product.id})"><i class="fa-solid fa-eye"></i></button>
                    <button class="action-btn edit" type="button" title="Edit" onclick="openEditModal(${product.id})"><i class="fa-solid fa-pen"></i></button>
                    <button class="action-btn delete" type="button" title="Hapus" onclick="deleteProduct(${product.id})"><i class="fa-solid fa-trash"></i></button>
                </div>
            </td>
        </tr>
    `).join('');

    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#777;">Data produk tidak ditemukan</td></tr>';
    }

    updateProductPagination(rows.length);
}

function updateStats() {
    setText('totalProduk', products.length);
    setText('totalRumput', products.filter(product => product.type === 'Rumput').length);
    setText('totalSusu', products.filter(product => product.type === 'Susu').length);
    setText('totalHewan', products.filter(product => product.type === 'Hewan').length);
}

function updateProductPagination(totalVisible) {
    const info = document.getElementById('productPaginationInfo');
    const pagination = document.querySelector('.product-section .pagination');
    if (!info || !pagination) return;

    const total = totalVisible || 0;
    const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
    const start = total > 0 ? ((currentProductPage - 1) * rowsPerPage) + 1 : 0;
    const end = total > 0 ? Math.min(currentProductPage * rowsPerPage, total) : 0;
    info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;

    const buttons = pagination.querySelectorAll('.page-btn');
    if (buttons[0]) buttons[0].disabled = currentProductPage <= 1;
    if (buttons[1]) buttons[1].textContent = currentProductPage;
    if (buttons[2]) buttons[2].disabled = currentProductPage >= totalPages;
}

function changeProductPage(direction) {
    const totalPages = Math.max(1, Math.ceil(getFilteredProducts().length / rowsPerPage));
    currentProductPage = Math.min(Math.max(currentProductPage + direction, 1), totalPages);
    renderProducts();
}

function setText(id, value) {
    const element = document.getElementById(id);
    if (element) element.textContent = value;
}

function formatDate(dateString) {
    if (!dateString) return '-';

    const isoDate = normalizeDateValue(dateString);
    if (!isoDate) return '-';

    return isoToDisplayDate(isoDate);
}

function openFilterModal() {
    document.getElementById('filterModal')?.classList.add('active');
}

function closeFilterModal() {
    document.getElementById('filterModal')?.classList.remove('active');
}

function applyFilter() {
    activeFilter = {
        type: document.getElementById('filterJenis')?.value || '',
        status: document.getElementById('filterStatus')?.value || ''
    };
    currentProductPage = 1;
    renderProducts();
    closeFilterModal();
}

function resetFilter() {
    activeFilter = { type: '', status: '' };
    if (document.getElementById('filterJenis')) document.getElementById('filterJenis').value = '';
    if (document.getElementById('filterStatus')) document.getElementById('filterStatus').value = '';
    currentProductPage = 1;
    renderProducts();
}

function openAddModal() {
    document.getElementById('addProductModal')?.classList.add('active');
    switchAddTab('hewan');
    syncMilkExpiry('add');
}

function closeAddModal() {
    document.getElementById('addProductModal')?.classList.remove('active');
}

function switchAddTab(type) {
    switchTabInModal('addProductModal', type, 'add-form');
    if (type === 'susu') {
        syncMilkExpiry('add');
    }
}

function switchEditTab(type) {
    switchTabInModal('editModal', type, 'edit-form');
    if (type === 'susu') {
        syncMilkExpiry('edit');
    }
}

function switchTabInModal(modalId, type, formPrefix) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.querySelectorAll('.tab').forEach(tab => {
        tab.classList.toggle('active', tab.dataset.tab === type);
    });
    modal.querySelectorAll('.form-section').forEach(section => section.classList.remove('active'));
    document.getElementById(`${formPrefix}-${type}`)?.classList.add('active');
}

function handleAddSubmit(event, type) {
    event.preventDefault();

    try {
        const form = event.target;
        const validationMessage = validateProductForm(form, type, 'add');
        if (validationMessage) {
            notifyProductMessage(validationMessage, 'danger');
            return;
        }

        const product = applyExpiryStatus(readProductForm('add', type));
        const addError = validateProductData(product);
        if (addError) {
            notifyProductMessage(addError, 'danger');
            return;
        }

        submitProductToServer('tambah', product);
    } catch (error) {
        notifyProductMessage(getProductErrorMessage(error), 'danger');
    }
}

function validateProductForm(form, type, mode = 'add') {
    if (!form) {
        return 'Form tambah produk tidak ditemukan. Muat ulang halaman lalu coba lagi.';
    }

    if (!['hewan', 'rumput', 'susu'].includes(type)) {
        return 'Jenis produk tidak valid. Pilih tab produk yang tersedia lalu coba lagi.';
    }

    if (type === 'susu') {
        syncMilkExpiry(mode);
    }

    if (!form.checkValidity()) {
        form.reportValidity();
        return 'Lengkapi semua kolom wajib dengan format yang benar sebelum menyimpan.';
    }

    const price = priceNumber(getValue(`${mode}-harga-${type}`));
    if (price <= 0) {
        return 'Harga produk harus lebih dari Rp 0.';
    }

    const stock = Number(getValue(`${mode}-stok-${type}`));
    if (!Number.isFinite(stock) || stock < 0) {
        return 'Stok produk harus berupa angka yang valid.';
    }

    if (type === 'hewan' && stock < 1) {
        return 'Stok hewan minimal 1 ekor.';
    }

    if (type === 'susu' && !getDateInputIso(`${mode}-tgl-produksi-susu`)) {
        return 'Tanggal produksi susu belum valid. Pilih tanggal dari kalender.';
    }

    if (type === 'susu' && !getDateInputIso(`${mode}-tgl-expiry-susu`)) {
        return 'Tanggal kadaluwarsa susu belum valid. Pilih tanggal produksi ulang agar kadaluwarsa terisi otomatis.';
    }

    return '';
}

function validateProductData(product) {
    const typeKey = { Hewan: 'hewan', Rumput: 'rumput', Susu: 'susu' }[product?.type];

    if (!product?.name || product.name === defaultName(typeKey)) {
        return 'Nama produk belum terisi dengan benar.';
    }

    if (!product?.type || !product?.price || !product?.stock || !product?.status) {
        return 'Data produk belum lengkap. Periksa kembali isian form.';
    }

    return '';
}

function resetAddStatus(type) {
    const statusInput = document.getElementById(`add-status-${type}`);
    const statusWrap = statusInput?.closest('.form-group');
    if (statusInput) statusInput.value = 'tersedia';
    statusWrap?.querySelectorAll('.status-option').forEach(option => {
        option.classList.toggle('active', option.classList.contains('available'));
    });
}

function getProductErrorMessage(error) {
    console.error('Gagal menambahkan produk:', error);
    return error?.message || 'Produk gagal ditambahkan karena terjadi kesalahan sistem. Silakan coba lagi.';
}

function notifyProductMessage(message, type = 'success') {
    if (typeof showFlashMessage === 'function') {
        showFlashMessage(message, type);
        return;
    }

    alert(message);
}

function openEditModal(id) {
    const product = products.find(item => item.id === id);
    if (!product) return;

    editingId = id;
    const type = product.type.toLowerCase();
    switchEditTab(type);
    fillEditForm(product, type);
    configureEditRequiredFields(product, type);
    if (type === 'susu') {
        syncMilkExpiry('edit');
    }
    document.getElementById('editModal')?.classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal')?.classList.remove('active');
    editingId = null;
}

function handleEditSubmit(event, type) {
    event.preventDefault();
    const validationMessage = validateProductForm(event.target, type, 'edit');
    if (validationMessage) {
        notifyProductMessage(validationMessage, 'danger');
        return;
    }

    const index = products.findIndex(item => item.id === editingId);
    if (index === -1) return;

    const updatedProduct = applyExpiryStatus({ ...products[index], ...readProductForm('edit', type), id: editingId });
    if (priceNumber(updatedProduct.price) > 0) {
        updatedProduct.needs_price = false;
    }
    submitProductToServer('edit', updatedProduct);
}

function readProductForm(mode, type) {
    const label = { hewan: 'Hewan', rumput: 'Rumput', susu: 'Susu' }[type];
    const name = getValue(`${mode}-nama-${type}`) || defaultName(type);
    const price = getValue(`${mode}-harga-${type}`) || 'Rp 0';
    const stockValue = type === 'hewan' ? '1' : (getValue(`${mode}-stok-${type}`) || '0');
    const status = normalizeStatus(getValue(`${mode}-status-${type}`));
    const date = type === 'susu' ? getDateInputIso(`${mode}-tgl-produksi-susu`) : '';
    const expiryDate = type === 'susu' ? getDateInputIso(`${mode}-tgl-expiry-susu`) : '';

    return {
        type: label,
        name,
        date,
        expiryDate,
        price,
        stock: `${stockValue} ${stockUnit(type)}`,
        status,
        image: defaultImage(type)
    };
}

function configureEditRequiredFields(product, type) {
    const form = document.getElementById(`edit-form-${type}`);
    if (!form) return;

    form.querySelectorAll('input, select, textarea').forEach(input => {
        input.required = false;
    });

    const priceInput = document.getElementById(`edit-harga-${type}`);
    if (priceInput) {
        priceInput.required = true;
    }
}

function fillEditForm(product, type) {
    setValue(`edit-nama-${type}`, product.name);
    setValue(`edit-harga-${type}`, product.price);
    setValue(`edit-stok-${type}`, type === 'hewan' ? 1 : (parseInt(product.stock, 10) || ''));
    setValue(`edit-status-${type}`, product.status.toLowerCase().replaceAll(' ', '-'));

    if (type === 'susu') {
        setValue('edit-tgl-produksi-susu', normalizeDateValue(product.date));
        setValue('edit-tgl-expiry-susu', addDays(product.date, 7));
    }

    const statusInput = document.getElementById(`edit-status-${type}`);
    const statusWrap = statusInput?.closest('.form-group');
    statusWrap?.querySelectorAll('.status-option').forEach(option => {
        option.classList.toggle('active', option.classList.contains(product.status === 'Tersedia' ? 'available' : 'unavailable'));
    });
}

function getValue(id) {
    return document.getElementById(id)?.value.trim() || '';
}

function setValue(id, value) {
    const element = document.getElementById(id);
    if (element) element.value = value;
}

function normalizeStatus(value) {
    return value === 'tidak-tersedia' || value === 'tidak tersedia' ? 'Tidak Tersedia' : 'Tersedia';
}

function applyExpiryStatus(product) {
    if (isExpiredMilkProduct(product)) {
        product.status = 'Tidak Tersedia';
    }

    return product;
}

function isExpiredMilkProduct(product) {
    const expiryDate = normalizeDateValue(product?.expiryDate);
    return product?.type === 'Susu' && Boolean(expiryDate) && expiryDate < todayIsoDate();
}

function stockUnit(type) {
    return { hewan: 'Ekor', rumput: 'Kg', susu: 'Liter' }[type] || '';
}

function defaultName(type) {
    return { hewan: 'Produk Hewan', rumput: 'Produk Rumput', susu: 'Produk Susu' }[type];
}

function defaultImage(type) {
    return {
        hewan: 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600',
        rumput: 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=600',
        susu: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600'
    }[type];
}

function selectAddStatus(element, type) {
    selectStatusOption(element, `add-status-${type}`);
}

function selectEditStatus(element, type) {
    selectStatusOption(element, `edit-status-${type}`);
}

function selectStatusOption(element, inputId) {
    element.parentElement.querySelectorAll('.status-option').forEach(option => option.classList.remove('active'));
    element.classList.add('active');
    setValue(inputId, element.classList.contains('available') ? 'tersedia' : 'tidak-tersedia');
}

function openPreviewModal(id) {
    const product = products.find(item => item.id === id);
    if (!product) return;

    setText('previewTitle', `Preview ${product.type}`);
    setText('previewSubtitle', 'Data produk aktif');
    setText('previewProductId', `ID: ${formatProductId(product)}`);
    setText('previewSectionTitle', `INFORMASI ${product.type.toUpperCase()}`);
    setText('previewProductType', product.type);
    setText('previewProductName', product.name);
    setText('previewProductPrice', product.price);
    setText('previewProductStatus', product.status);
    renderPreviewByType(product);

    const statusWrap = document.getElementById('previewStatusWrap');
    if (statusWrap) {
        statusWrap.classList.toggle('unavailable', product.status !== 'Tersedia');
    }

    document.getElementById('previewModal')?.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function renderPreviewByType(product) {
    const isMilk = product.type === 'Susu';
    const showStock = isMilk;
    const dateBox = document.querySelector('.preview-date-info');
    const expiryBox = document.querySelector('.preview-expiry-info');
    const stockBox = document.querySelector('.preview-stock-info');

    if (dateBox) dateBox.style.display = isMilk ? '' : 'none';
    if (expiryBox) expiryBox.style.display = isMilk ? '' : 'none';
    if (stockBox) stockBox.style.display = showStock ? '' : 'none';

    setText('previewProductDateLabel', 'Tanggal Produksi');
    setText('previewProductDate', isMilk ? formatDate(product.date) : '-');
    setText('previewProductExpiry', isMilk ? formatDate(product.expiryDate) : '-');
    setText('previewProductStock', showStock ? product.stock : '-');
}

function setupMilkExpiryAutomation() {
    bindMilkExpiry('add');
    bindMilkExpiry('edit');
}

function bindMilkExpiry(mode) {
    const production = document.getElementById(`${mode}-tgl-produksi-susu`);
    const expiry = document.getElementById(`${mode}-tgl-expiry-susu`);
    if (!production || !expiry) return;

    if (mode === 'add' && !production.value) {
        production.value = todayIsoDate();
    }

    production.addEventListener('change', () => syncMilkExpiry(mode));
    production.addEventListener('input', () => syncMilkExpiry(mode));
    syncMilkExpiry(mode);
}

function syncMilkExpiry(mode) {
    const production = document.getElementById(`${mode}-tgl-produksi-susu`);
    const expiry = document.getElementById(`${mode}-tgl-expiry-susu`);
    if (!production || !expiry) return;

    if (mode === 'add' && !production.value) {
        production.value = todayIsoDate();
    }

    const productionDate = getDateInputIso(`${mode}-tgl-produksi-susu`);
    expiry.value = productionDate ? addDays(productionDate, 7) : '';
}

function addDays(dateString, days) {
    const isoDate = normalizeDateValue(dateString);
    if (!isoDate) return '';

    const [year, month, day] = isoDate.split('-').map(Number);
    const date = new Date(year, month - 1, day);
    date.setDate(date.getDate() + days);
    return dateToIsoDate(date);
}

function normalizeDateValue(value) {
    const rawValue = String(value || '').trim();
    if (/^\d{4}-\d{2}-\d{2}$/.test(rawValue)) {
        return isValidIsoDate(rawValue) ? rawValue : '';
    }

    const match = rawValue.match(/^(\d{2})-(\d{2})-(\d{4})$/);
    if (!match) return '';

    const [, day, month, year] = match;
    const isoDate = `${year}-${month}-${day}`;
    return isValidIsoDate(isoDate) ? isoDate : '';
}

function getDateInputIso(id) {
    const input = document.getElementById(id);
    if (!input) return '';

    const value = normalizeDateValue(input.value);
    if (value) return value;

    if (input.valueAsDate instanceof Date && !Number.isNaN(input.valueAsDate.getTime())) {
        return dateToIsoDate(input.valueAsDate);
    }

    return '';
}

function isoToDisplayDate(value) {
    const isoDate = normalizeDateValue(value);
    if (!isoDate) return '';

    const [year, month, day] = isoDate.split('-');
    return `${day}-${month}-${year}`;
}

function isValidIsoDate(value) {
    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!match) return false;

    const [, year, month, day] = match.map(Number);
    const date = new Date(year, month - 1, day);
    return date.getFullYear() === year
        && date.getMonth() === month - 1
        && date.getDate() === day;
}

function todayIsoDate() {
    return dateToIsoDate(new Date());
}

function dateToIsoDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function closePreviewModal() {
    document.getElementById('previewModal')?.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function formatProductId(product) {
    const prefix = { Hewan: 'S-H', Rumput: 'S-R', Susu: 'S-S' }[product.type] || 'S-P';
    return `${prefix}-${String(product.id).slice(-3).padStart(3, '0')}`;
}

function deleteProduct(id) {
    const product = products.find(item => item.id === id);
    if (!product) return;

    pendingDeleteProductId = id;
    setText('deleteProductTarget', `${product.type} - ${product.name}`);
    document.getElementById('deleteProductOverlay')?.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProductDelete() {
    document.getElementById('deleteProductOverlay')?.classList.remove('active');
    document.body.style.overflow = 'auto';
    pendingDeleteProductId = null;
}

function closeProductDeleteOutside(event) {
    if (event.target.id === 'deleteProductOverlay') {
        closeProductDelete();
    }
}

function confirmProductDelete() {
    const index = products.findIndex(item => item.id === pendingDeleteProductId);
    if (index === -1) return;

    if (pendingDeleteProductId < 100000) {
        submitProductToServer('hapus', products[index]);
        return;
    }

    products.splice(index, 1);
    renderProducts();
    updateStats();
    closeProductDelete();
    showFlashMessage('Produk berhasil dihapus.');
}

function formatCurrencyInput(input) {
    const numbers = input.value.replace(/\D/g, '');
    input.value = numbers ? `Rp ${Number(numbers).toLocaleString('id-ID')}` : '';
}

function priceNumber(price) {
    return Number(String(price || '').replace(/\D/g, '')) || 0;
}

function needsPriceInput(product) {
    return Boolean(product.needs_price) && priceNumber(product.price) === 0;
}

function submitProductToServer(action, product) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'manajemen_produk.php';
    form.style.display = 'none';

    const fields = {
        aksi: action,
        id_produk: product.id || '',
        jenis_produk: String(product.type || '').toLowerCase(),
        nama_produk: product.name || '',
        harga: priceNumber(product.price),
        stok: parseInt(product.stock, 10) || 0,
        tgl_kadaluarsa: product.type === 'Susu' ? normalizeDateValue(product.expiryDate) : '',
        status_produk: product.status || 'Tersedia'
    };

    Object.entries(fields).forEach(([name, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

function exportTableToCSV(filename) {
    const rows = getFilteredProducts();
    const csvRows = [
        ['Jenis Produk', 'Nama Produk', 'Tanggal Produksi', 'Tanggal Kadaluwarsa', 'Harga', 'Stok', 'Status'],
        ...rows.map(product => [product.type, product.name, product.date, product.expiryDate, product.price, product.stock, product.status])
    ];
    const csv = csvRows.map(row => row.map(cell => `"${String(cell).replaceAll('"', '""')}"`).join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    URL.revokeObjectURL(link.href);
    showFlashMessage('Data produk berhasil diekspor.');
}

document.addEventListener('click', event => {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
        closeAddModal();
        closeEditModal();
        closeFilterModal();
        closePreviewModal();
        closeProductDelete();
    }
});
