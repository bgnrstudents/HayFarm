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

let activeFilter = { type: '', status: '' };
let editingId = null;
let pendingDeleteProductId = null;
let currentProductPage = 1;
const rowsPerPage = 5;

document.addEventListener('DOMContentLoaded', () => {
    renderProducts();
    updateStats();

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
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#777;">Data produk tidak ditemukan</td></tr>';
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
    return new Date(`${dateString}T00:00:00`).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
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
}

function closeAddModal() {
    document.getElementById('addProductModal')?.classList.remove('active');
}

function switchAddTab(type) {
    switchTabInModal('addProductModal', type, 'add-form');
}

function switchEditTab(type) {
    switchTabInModal('editModal', type, 'edit-form');
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

    const product = readProductForm('add', type);
    product.id = Date.now();
    products.unshift(product);

    renderProducts();
    updateStats();
    closeAddModal();
    event.target.reset();
    removeAddImage(type);
    showFlashMessage('Produk baru berhasil ditambahkan.');
}

function openEditModal(id) {
    const product = products.find(item => item.id === id);
    if (!product) return;

    editingId = id;
    const type = product.type.toLowerCase();
    switchEditTab(type);
    fillEditForm(product, type);
    configureEditRequiredFields(product, type);
    document.getElementById('editModal')?.classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal')?.classList.remove('active');
    editingId = null;
}

function handleEditSubmit(event, type) {
    event.preventDefault();
    const index = products.findIndex(item => item.id === editingId);
    if (index === -1) return;

    const updatedProduct = { ...products[index], ...readProductForm('edit', type), id: editingId };
    if (priceNumber(updatedProduct.price) > 0) {
        updatedProduct.needs_price = false;
    }
    products[index] = updatedProduct;
    renderProducts();
    updateStats();
    closeEditModal();
    showFlashMessage('Perubahan produk berhasil disimpan.');
}

function readProductForm(mode, type) {
    const label = { hewan: 'Hewan', rumput: 'Rumput', susu: 'Susu' }[type];
    const name = getValue(`${mode}-nama-${type}`) || defaultName(type);
    const price = getValue(`${mode}-harga-${type}`) || 'Rp 0';
    const stockValue = getValue(`${mode}-stok-${type}`) || '0';
    const status = normalizeStatus(getValue(`${mode}-status-${type}`));
    const date = getValue(`${mode}-tgl-produksi-${type}`) || new Date().toISOString().slice(0, 10);

    return {
        type: label,
        name,
        date,
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
    setValue(`edit-stok-${type}`, parseInt(product.stock, 10) || '');
    setValue(`edit-status-${type}`, product.status.toLowerCase().replaceAll(' ', '-'));

    if (type === 'susu') {
        setValue('edit-tgl-produksi-susu', product.date);
        setValue('edit-tgl-expiry-susu', product.date);
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

function previewAddImage(event, type) {
    previewImage(event, `add-preview-${type}`, `add-img-${type}`);
}

function previewEditImage(event, type) {
    previewImage(event, `edit-preview-${type}`, `edit-img-${type}`);
}

function previewImage(event, previewId, imageId) {
    const file = event.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById(previewId);
        const image = document.getElementById(imageId);
        if (preview && image) {
            image.src = e.target.result;
            preview.style.display = 'flex';
        }
    };
    reader.readAsDataURL(file);
}

function removeAddImage(type) {
    removeImage(`add-file-${type}`, `add-preview-${type}`, `add-img-${type}`);
}

function removeEditImage(type) {
    removeImage(`edit-file-${type}`, `edit-preview-${type}`, `edit-img-${type}`);
}

function removeImage(fileId, previewId, imageId) {
    const file = document.getElementById(fileId);
    const preview = document.getElementById(previewId);
    const image = document.getElementById(imageId);
    if (file) file.value = '';
    if (image) image.src = '';
    if (preview) preview.style.display = 'none';
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
    setText('previewProductDate', formatDate(product.date));
    setText('previewProductPrice', product.price);
    setText('previewProductStock', product.stock);
    setText('previewProductStatus', product.status);

    const image = document.getElementById('previewProductImage');
    if (image) {
        image.src = product.image || defaultImage(product.type.toLowerCase());
        image.alt = product.name;
    }

    const statusWrap = document.getElementById('previewStatusWrap');
    if (statusWrap) {
        statusWrap.classList.toggle('unavailable', product.status !== 'Tersedia');
    }

    document.getElementById('previewModal')?.classList.add('active');
    document.body.style.overflow = 'hidden';
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

function exportTableToCSV(filename) {
    const rows = getFilteredProducts();
    const csvRows = [
        ['Jenis Produk', 'Nama Produk', 'Tanggal', 'Harga', 'Stok', 'Status'],
        ...rows.map(product => [product.type, product.name, product.date, product.price, product.stock, product.status])
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
