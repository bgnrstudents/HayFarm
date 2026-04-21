<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Manajemen Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Nunito', sans-serif; }

.main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 150vh;
    background: linear-gradient(to bottom, #ffffff 0px, #ffffff 80px, #dbe7df 80px, #c9d8cf 40%, #b8c8be 100%);
}

.sidebar {
    width: 250px;
    height: 100vh;
    background: #fff;
    position: fixed;
    padding: 10px;
}

.logo { width: 130px; display: block; margin: 10px auto 20px; }

.menu { list-style: none; }
.menu li { margin-bottom: 10px; }
.menu li a {
    text-decoration: none;
    color: #333;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
}
.menu li a:hover { background: #f2f2f2; }
.menu .active a { background: #175D2B; color: #fff; }
.menu .active a i { color: #ffbe25; }
.menu-title { font-size: 12px; color: #777; margin: 15px 0 5px; }

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 10px 20px;
}

.search-box {
    position: relative;
    width: 300px;
}
.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 14px;
    pointer-events: none;
}
.search-box input {
    width: 100%;
    padding: 8px 12px 8px 35px;
    border-radius: 20px;
    border: none;
    outline: none;
    background: #f1f3f5;
    font-size: 14px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}
#currentDate { font-size: 13px; color: #555; }

.notif {
    position: relative;
    font-size: 16px;
    cursor: pointer;
}
.notif .badge {
    position: absolute;
    top: -6px;
    right: -8px;
    background: red;
    color: white;
    font-size: 10px;
    padding: 3px 5px;
    border-radius: 50%;
}

.user {
    display: flex;
    flex-direction: column;
    font-size: 12px;
    text-align: right;
}
.user strong { font-size: 13px; }

.stats-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-info h3 { font-size: 14px; color: #666; margin-bottom: 8px; font-weight: 600; }
.stat-info .number { font-size: 32px; font-weight: bold; color: #333; }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.stat-icon.produk { background: #e8f5e9; color: #175D2B; }
.stat-icon.rumput { background: #e8f5e9; color: #4CAF50; }
.stat-icon.susu { background: #e3f2fd; color: #2196F3; }
.stat-icon.hewan { background: #fff3e0; color: #FF9800; }

.product-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.section-header { margin-bottom: 20px; }
.section-header h2 { font-size: 18px; color: #333; margin-bottom: 5px; font-weight: 700; }
.section-header p { font-size: 13px; color: #888; }

.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.table-search { flex: 1; max-width: 300px; position: relative; }
.table-search input {
    width: 100%;
    padding: 10px 15px 10px 40px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
}
.table-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.table-actions { display: flex; gap: 10px; }

.btn-filter, .btn-export {
    padding: 10px 20px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
    transition: all 0.3s;
}
.btn-filter:hover, .btn-export:hover { background: #f5f5f5; }

.btn-add {
    padding: 10px 20px;
    background: #175D2B;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-add:hover { background: #145024; }

.product-table { width: 100%; border-collapse: collapse; }
.product-table thead { background: #f8f9fa; }
.product-table th {
    padding: 12px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    border-bottom: 2px solid #e0e0e0;
}
.product-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    color: #333;
}
.product-table tbody tr:hover { background: #f8f9fa; }

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-tersedia { background: #e8f5e9; color: #175D2B; }
.status-tidak-tersedia { background: #ffebee; color: #f44336; }

.action-buttons { display: flex; gap: 8px; }
.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}
.action-btn.view { background: #e3f2fd; color: #2196F3; }
.action-btn.edit { background: #fff3e0; color: #FF9800; }
.action-btn.delete { background: #ffebee; color: #f44336; }
.action-btn:hover { transform: scale(1.1); }


/* Modal Overlay */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
    padding: 20px;
}
.modal-overlay.active { display: flex; }

/* Filter Modal */
.filter-modal {
    background: white;
    border-radius: 12px;
    padding: 30px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}
.filter-modal h3 { font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #333; }
.filter-group { margin-bottom: 20px; }
.filter-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    margin-bottom: 8px;
}
.filter-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    outline: none;
    font-size: 14px;
    background: white;
    cursor: pointer;
}
.filter-group select:focus { border-color: #175D2B; }
.filter-buttons { display: flex; gap: 10px; margin-top: 25px; }
.filter-buttons button {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-filter-apply { background: #175D2B; color: white; }
.btn-filter-apply:hover { background: #145024; }
.btn-filter-reset { background: #e0e0e0; color: #333; }
.btn-filter-reset:hover { background: #d0d0d0; }
.btn-filter-close { background: #f5f5f5; color: #666; }
.btn-filter-close:hover { background: #e0e0e0; }

/* Edit Modal Styles */
.edit-modal {
    background: white;
    border-radius: 16px;
    width: 95%;
    max-width: 920px;
    max-height: 95vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    position: relative;
}
.edit-modal .modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    background: none;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}
.edit-modal .modal-close:hover { background-color: #f3f4f6; color: #333; }

.edit-modal .header {
    display: flex;
    align-items: center;
    padding: 24px 32px 20px;
    border-bottom: 1px solid #e5e7eb;
}
.edit-modal .header h2 { font-size: 20px; font-weight: 700; color: #111827; margin: 0; }
.edit-modal .header p { font-size: 13px; color: #6b7280; margin: 2px 0 0; }

.edit-modal .tabs { 
    display: flex; 
    gap: 32px; 
    padding: 0 32px 20px;
    border-bottom: 1px solid #e5e7eb;
}
.edit-modal .tab {
    padding-bottom: 12px; 
    cursor: pointer; 
    font-size: 15px; 
    font-weight: 500;
    color: #6b7280; 
    position: relative; 
    transition: all 0.2s;
}
.edit-modal .tab:hover { color: #111827; }
.edit-modal .tab.active { color: #111827; font-weight: 700; }
.edit-modal .tab.active::after {
    content: ''; position: absolute; bottom: -1px; left: 0; width: 100%;
    height: 3px; background-color: #10b981; border-radius: 3px 3px 0 0;
}

.edit-modal .form-content { padding: 24px 32px; }
.edit-modal .form-section { display: none; animation: fadeIn 0.3s ease; }
.edit-modal .form-section.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.edit-modal .form-row { display: flex; gap: 24px; margin-bottom: 24px; }
.edit-modal .form-group { flex: 1; margin-bottom: 24px; }
.edit-modal .form-group.full-width { width: 100%; }

.edit-modal .form-label {
    display: block; font-size: 14px; font-weight: 600;
    color: #374151; margin-bottom: 8px;
}
.edit-modal .required { color: #ef4444; }

.edit-modal .form-input, .edit-modal .form-select, .edit-modal .form-textarea {
    width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb;
    border-radius: 10px; font-size: 14px; color: #111827;
    background-color: #ffffff; transition: all 0.2s;
}
.edit-modal .form-input:focus, .edit-modal .form-select:focus, .edit-modal .form-textarea:focus {
    outline: none; border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
.edit-modal .form-select {
    cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    padding-right: 36px;
}
.edit-modal .form-textarea { min-height: 80px; resize: vertical; }

.edit-modal .upload-wrapper { position: relative; }
.edit-modal .upload-box {
    border: 2px dashed #d1d5db; border-radius: 12px; padding: 24px 20px;
    text-align: center; background-color: #f9fafb; cursor: pointer;
    transition: all 0.2s; display: flex; flex-direction: column;
    align-items: center; justify-content: center; min-height: 100px;
}
.edit-modal .upload-box:hover { border-color: #10b981; background-color: #f0fdf4; }
.edit-modal .upload-icon {
    width: 40px; height: 40px; color: #9ca3af; margin-bottom: 10px;
    transition: all 0.2s;
}
.edit-modal .upload-box:hover .upload-icon { color: #10b981; transform: translateY(-2px); }
.edit-modal .upload-text { font-size: 13px; color: #374151; font-weight: 500; margin-bottom: 4px; }
.edit-modal .upload-hint { font-size: 11px; color: #9ca3af; }

.edit-modal .image-preview {
    display: flex; align-items: center; justify-content: center;
    margin-top: 10px; position: relative;
}
.edit-modal .image-preview img {
    max-width: 100%; max-height: 180px; border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.edit-modal .btn-remove {
    position: absolute; top: -8px; right: -8px; background: #ef4444;
    color: white; border: none; border-radius: 50%; width: 24px;
    height: 24px; cursor: pointer; font-size: 16px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.edit-modal .btn-remove:hover { background: #dc2626; }

.edit-modal .status-divider { height: 1px; background-color: #e5e7eb; margin: 16px 0; }
.edit-modal .status-options { display: flex; gap: 16px; }
.edit-modal .status-option {
    flex: 1; display: flex; align-items: center; justify-content: center;
    gap: 10px; padding: 12px 20px; border-radius: 10px; cursor: pointer;
    transition: all 0.2s; font-size: 14px; font-weight: 500;
    border: 1px solid #e5e7eb; background-color: #fff;
}
.edit-modal .status-option.available.active {
    background-color: #ecfdf5; border-color: #a7f3d0; color: #065f46;
}
.edit-modal .status-option.available.active .status-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; background-color: #065f46;
    border-radius: 50%; color: white; font-size: 12px; font-weight: bold;
}
.edit-modal .status-option.unavailable {
    background-color: #fffbeb; border-color: #fde68a; color: #92400e;
}
.edit-modal .status-option.unavailable .status-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; background-color: #92400e;
    border-radius: 50%; color: white; font-size: 12px; font-weight: bold;
}

.edit-modal .button-group { 
    display: flex; 
    gap: 16px; 
    margin-top: 24px; 
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}
.edit-modal .btn {
    padding: 12px 24px; border: none; border-radius: 10px;
    font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.edit-modal .btn-cancel {
    background-color: #ffffff; color: #374151; border: 1px solid #d1d5db;
}
.edit-modal .btn-cancel:hover { background-color: #f3f4f6; }
.edit-modal .btn-save {
    background-color: #10b981; color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.edit-modal .btn-save:hover {
    background-color: #059669;
    box-shadow: 0 6px 14px rgba(16, 185, 129, 0.4);
}

/* Preview Modal Styles */
.preview-modal {
    background: transparent;
    border-radius: 16px;
    width: 95%;
    max-width: 1100px;
    box-shadow: none;
    position: relative;
    padding: 10px;
}

.preview-modal .modal-close {
    position: absolute;
    top: 5px;
    right: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #ffffff;
    background: rgba(0, 0, 0, 0.3);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
    z-index: 10;
}

.preview-modal .modal-close:hover { 
    background-color: rgba(0, 0, 0, 0.6); 
    color: #fff; 
    transform: scale(1.1);
}

.preview-container {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    padding: 20px 10px;
}

.preview-card {
    background-color: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    width: 340px;
    min-width: 340px;
    flex-shrink: 0;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    border: none;
    transition: transform 0.2s ease;
}

.preview-card:hover { transform: translateY(-3px); }

.preview-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    background: linear-gradient(135deg, #175D2B 0%, #1e7a3a 100%);
    border-bottom: none;
}

.preview-card .header-title {
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
}

.preview-card .header-id {
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    backdrop-filter: blur(4px);
}

.preview-card .category {
    padding: 12px 18px 8px;
    color: #175D2B;
    font-size: 13px;
    font-weight: 700;
    background: rgba(23, 93, 43, 0.05);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.preview-card .card-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.preview-card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.preview-card:hover .card-image img { transform: scale(1.03); }

.preview-card .card-image .no-image {
    color: #9ca3af;
    font-size: 48px;
    opacity: 0.6;
}

.preview-card .detail-title {
    padding: 14px 18px 10px;
    font-size: 13px;
    font-weight: 700;
    color: #333333;
    letter-spacing: 0.5px;
    background: #fafafa;
    border-bottom: 1px solid #f0f0f0;
}

.preview-card .detail-grid {
    padding: 14px 18px 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px 16px;
}

.preview-card .detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.preview-card .detail-item label {
    font-size: 11px;
    color: #888888;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.preview-card .detail-item .value {
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
}

.preview-card .detail-item.empty { visibility: hidden; }

.preview-card .status-available {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #175D2B !important;
    font-weight: 700 !important;
    background: rgba(23, 93, 43, 0.1);
    padding: 4px 10px;
    border-radius: 20px;
    width: fit-content;
}

.preview-card .status-unavailable {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #f44336 !important;
    font-weight: 700 !important;
    background: rgba(244, 67, 54, 0.1);
    padding: 4px 10px;
    border-radius: 20px;
    width: fit-content;
}

.preview-card .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(0.9); }
}

.preview-card .status-available .dot { background-color: #175D2B; }
.preview-card .status-unavailable .dot { background-color: #f44336; }

.preview-card .btn-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: calc(100% - 36px);
    margin: 16px auto 18px;
    padding: 12px;
    background: linear-gradient(135deg, #175D2B 0%, #1e7a3a 100%);
    color: #ffffff;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(23, 93, 43, 0.3);
    text-align: center;
}

.preview-card .btn-close:hover {
    background: linear-gradient(135deg, #145024 0%, #175D2B 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(23, 93, 43, 0.4);
}

.preview-card .btn-close:active { transform: translateY(0); }

/* Responsive Preview */
@media (max-width: 768px) {
    .preview-container { flex-direction: column; align-items: center; gap: 20px; }
    .preview-card { width: 100%; max-width: 340px; min-width: auto; }
    .preview-modal .modal-close { color: #333; background: rgba(255, 255, 255, 0.9); }
}

/* Toast Notification */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: 500;
    z-index: 9999;
    animation: slideIn 0.3s ease;
    display: none;
}
.toast.success { background: #175D2B; }
.toast.error { background: #f44336; }
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Responsive */
@media (max-width: 768px) {
    .main-content { margin-left: 0; }
    .sidebar { display: none; }
    .stats-container { grid-template-columns: 1fr; }
    .edit-modal .form-row { flex-direction: column; gap: 0; }
    .edit-modal .form-group { margin-bottom: 20px; }
    .edit-modal .tabs { gap: 20px; overflow-x: auto; padding-bottom: 5px; }
    .edit-modal .status-options { flex-direction: column; }
    .edit-modal .button-group { flex-direction: column; }
    .edit-modal .btn { width: 100%; }
    .preview-container { flex-direction: column; align-items: center; }
    .preview-card { width: 100%; max-width: 340px; min-width: auto; }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="../../public/images/logo_hayfarm.png" class="logo" alt="Logo">

    <ul class="menu">
        <li><a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a></li>
        <li class="active"><a href="manajemen_produk.php"><i class="fa-solid fa-credit-card"></i> Manajemen Produk</a></li>
        <li><a href="#"><i class="fa-solid fa-file-circle-check"></i> Verifikasi Penjualan</a></li>
        <p class="menu-title">DATA</p>
        <li><a href="#"><i class="fa-solid fa-square-poll-vertical"></i> Data Hewan</a></li>
        <li><a href="#"><i class="fa-solid fa-heart-pulse"></i> Data Kesehatan Hewan</a></li>
        <li><a href="#"><i class="fa-solid fa-power-off"></i> Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Pencarian" id="globalSearch">
        </div>
        <div class="topbar-right">
            <span id="currentDate"></span>
            <div class="notif">
                <i class="fa-solid fa-bell" style="color: rgb(25, 108, 51);"></i>
                <span class="badge">6</span>
            </div>
            <div class="user">
                <strong>Farel</strong>
                <small>Admin</small>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk</h3>
                <div class="number" id="totalProduk">0</div>
            </div>
            <div class="stat-icon produk"><i class="fa-solid fa-box"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk Rumput</h3>
                <div class="number" id="totalRumput">0</div>
            </div>
            <div class="stat-icon rumput"><i class="fa-solid fa-seedling"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk Susu</h3>
                <div class="number" id="totalSusu">0</div>
            </div>
            <div class="stat-icon susu"><i class="fa-solid fa-bottle-water"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Produk Hewan</h3>
                <div class="number" id="totalHewan">0</div>
            </div>
            <div class="stat-icon hewan"><i class="fa-solid fa-cow"></i></div>
        </div>
    </div>

    <!-- PRODUCT LIST SECTION -->
    <div class="product-section">
        <div class="section-header">
            <h2>Daftar Produk</h2>
            <p>Manajemen data produk</p>
        </div>

        <div class="table-controls">
            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari produk..." id="tableSearch">
            </div>
            <div class="table-actions">
                <button class="btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
                <button class="btn-export" onclick="exportTableToCSV('produk_data.csv')"><i class="fa-solid fa-download"></i> Export</button>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </button>
            </div>

        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Jenis Produk</th>
                    <th>Nama Produk</th>
                    <th>Tanggal</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody"></tbody>
        </table>
    
    </div>

    <!-- FILTER MODAL -->
    <div class="modal-overlay" id="filterModal">
        <div class="filter-modal">
            <h3>Filter Produk</h3>
            <div class="filter-group">
                <label for="filterJenis">Jenis Produk</label>
                <select id="filterJenis">
                    <option value="">Semua Jenis</option>
                    <option value="Rumput">Rumput</option>
                    <option value="Hewan">Hewan</option>
                    <option value="Susu">Susu</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterStatus">Status</label>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                </select>
            </div>
            <div class="filter-buttons">
                <button class="btn-filter-apply" onclick="applyFilter()">Terapkan</button>
                <button class="btn-filter-reset" onclick="resetFilter()">Reset</button>
                <button class="btn-filter-close" onclick="closeFilterModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal-overlay" id="editModal">
        <div class="edit-modal">
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
            <div class="header">
                <div>
                    <h2>Edit Data Produk</h2>
                    <p>Perbarui informasi produk</p>
                </div>
            </div>
            <div class="tabs">
                <div class="tab active" data-tab="hewan" onclick="switchEditTab('hewan')">Hewan</div>
                <div class="tab" data-tab="rumput" onclick="switchEditTab('rumput')">Rumput</div>
                <div class="tab" data-tab="susu" onclick="switchEditTab('susu')">Susu</div>
            </div>
            <div class="form-content">
                <!-- ===== FORM HEWAN ===== -->
                <form id="edit-form-hewan" class="form-section active" onsubmit="handleEditSubmit(event, 'hewan')">
                    <input type="hidden" id="edit-id-hewan">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Hewan <span class="required">*</span></label>
                            <select class="form-select" id="edit-jenis-hewan" required>
                                <option value="">Pilih jenis hewan</option>
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                                <option value="kerbau">Kerbau</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-nama-hewan" placeholder="Contoh: Sapi Perah FH" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" class="form-input" id="edit-berat-hewan" placeholder="Contoh: 450">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-harga-hewan" placeholder="Rp 20.000.000" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah/Stok <span class="required">*</span></label>
                        <input type="number" class="form-input" id="edit-stok-hewan" placeholder="Contoh: 4" min="1" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Hewan</label>
                        <div class="upload-wrapper">
                            <input type="file" id="edit-file-hewan" class="file-input" hidden accept="image/*" onchange="previewEditImage(event, 'hewan')">
                            <div class="upload-box" onclick="document.getElementById('edit-file-hewan').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="edit-preview-hewan" style="display: none;">
                                <img id="edit-img-hewan" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeEditImage('hewan')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectEditStatus(this, 'hewan')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectEditStatus(this, 'hewan')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-status-hewan" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>

                <!-- ===== FORM RUMPUT ===== -->
                <form id="edit-form-rumput" class="form-section" onsubmit="handleEditSubmit(event, 'rumput')">
                    <input type="hidden" id="edit-id-rumput">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Rumput <span class="required">*</span></label>
                            <select class="form-select" id="edit-jenis-rumput" required>
                                <option value="">Pilih jenis rumput</option>
                                <option value="odot">Rumput Odot</option>
                                <option value="gajah">Rumput Gajah</option>
                                <option value="pakan">Rumput Pakan</option>
                                <option value="lapangan">Rumput Lapangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-nama-rumput" placeholder="Contoh: Rumput Odot Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Kg <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-harga-rumput" placeholder="Rp 2.500" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Kg) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="edit-stok-rumput" placeholder="Contoh: 500" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="edit-file-rumput" class="file-input" hidden accept="image/*" onchange="previewEditImage(event, 'rumput')">
                            <div class="upload-box" onclick="document.getElementById('edit-file-rumput').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="edit-preview-rumput" style="display: none;">
                                <img id="edit-img-rumput" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeEditImage('rumput')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectEditStatus(this, 'rumput')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectEditStatus(this, 'rumput')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-status-rumput" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>

                <!-- ===== FORM SUSU ===== -->
                <form id="edit-form-susu" class="form-section" onsubmit="handleEditSubmit(event, 'susu')">
                    <input type="hidden" id="edit-id-susu">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Susu <span class="required">*</span></label>
                            <select class="form-select" id="edit-jenis-susu" required>
                                <option value="">Pilih jenis susu</option>
                                <option value="segar">Susu Segar</option>
                                <option value="pasteurisasi">Susu Pasteurisasi</option>
                                <option value="uht">Susu UHT</option>
                                <option value="fermentasi">Susu Fermentasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-nama-susu" placeholder="Contoh: Susu Segar Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Produksi <span class="required">*</span></label>
                            <input type="date" class="form-input" id="edit-tgl-produksi-susu" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluwarsa <span class="required">*</span></label>
                            <input type="date" class="form-input" id="edit-tgl-expiry-susu" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Liter <span class="required">*</span></label>
                            <input type="text" class="form-input" id="edit-harga-susu" placeholder="Rp 15.000" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Liter) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="edit-stok-susu" placeholder="Contoh: 200" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="edit-file-susu" class="file-input" hidden accept="image/*" onchange="previewEditImage(event, 'susu')">
                            <div class="upload-box" onclick="document.getElementById('edit-file-susu').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="edit-preview-susu" style="display: none;">
                                <img id="edit-img-susu" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeEditImage('susu')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectEditStatus(this, 'susu')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectEditStatus(this, 'susu')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-status-susu" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PREVIEW MODAL -->
    <div class="modal-overlay" id="previewModal">
        <div class="preview-modal">
            <button class="modal-close" onclick="closePreviewModal()">&times;</button>
            <div class="preview-container" id="previewContainer"></div>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH PRODUK ================= -->
    <div class="modal-overlay" id="addProductModal">
        <div class="edit-modal" style="max-width: 600px;">
            <button class="modal-close" onclick="closeAddModal()">&times;</button>
            <div class="header">
                <div>
                    <h2>Tambah Produk Baru</h2>
                    <p>Isi form di bawah untuk menambahkan produk</p>
                </div>
            </div>
            <div class="tabs">
                <div class="tab active" data-tab="hewan" onclick="switchAddTab('hewan')"> Hewan</div>
                <div class="tab" data-tab="susu" onclick="switchAddTab('susu')"> Susu</div>
                <div class="tab" data-tab="rumput" onclick="switchAddTab('rumput')"> Rumput</div>
            </div>
            <div class="form-content">
                
                <!-- ===== FORM HEWAN ===== -->
                <form id="add-form-hewan" class="form-section active" onsubmit="handleAddSubmit(event, 'hewan')">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Hewan <span class="required">*</span></label>
                            <select class="form-select" id="add-jenis-hewan" required>
                                <option value="">Pilih jenis hewan</option>
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                                <option value="kerbau">Kerbau</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-nama-hewan" placeholder="Contoh: Sapi Perah FH" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" class="form-input" id="add-berat-hewan" placeholder="Contoh: 450">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-hewan" placeholder="Rp 20.000.000" required oninput="formatCurrencyInput(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah/Stok <span class="required">*</span></label>
                        <input type="number" class="form-input" id="add-stok-hewan" placeholder="Contoh: 4" min="1" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Hewan</label>
                        <div class="upload-wrapper">
                            <input type="file" id="add-file-hewan" class="file-input" hidden accept="image/*" onchange="previewAddImage(event, 'hewan')">
                            <div class="upload-box" onclick="document.getElementById('add-file-hewan').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="add-preview-hewan" style="display: none;">
                                <img id="add-img-hewan" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeAddImage('hewan')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectAddStatus(this, 'hewan')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectAddStatus(this, 'hewan')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="add-status-hewan" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Batal</button>
                        <button type="submit" class="btn btn-save"> Simpan Data</button>
                    </div>
                </form>

                <!-- ===== FORM RUMPUT ===== -->
                <form id="add-form-rumput" class="form-section" onsubmit="handleAddSubmit(event, 'rumput')">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Rumput <span class="required">*</span></label>
                            <select class="form-select" id="add-jenis-rumput" required>
                                <option value="">Pilih jenis rumput</option>
                                <option value="odot">Rumput Odot</option>
                                <option value="gajah">Rumput Gajah</option>
                                <option value="pakan">Rumput Pakan</option>
                                <option value="lapangan">Rumput Lapangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-nama-rumput" placeholder="Contoh: Rumput Odot Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Kg <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-rumput" placeholder="Rp 2.500" required oninput="formatCurrencyInput(this)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Kg) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="add-stok-rumput" placeholder="Contoh: 500" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="add-file-rumput" class="file-input" hidden accept="image/*" onchange="previewAddImage(event, 'rumput')">
                            <div class="upload-box" onclick="document.getElementById('add-file-rumput').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="add-preview-rumput" style="display: none;">
                                <img id="add-img-rumput" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeAddImage('rumput')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectAddStatus(this, 'rumput')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectAddStatus(this, 'rumput')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="add-status-rumput" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Batal</button>
                        <button type="submit" class="btn btn-save"> Simpan Data</button>
                    </div>
                </form>

                <!-- ===== FORM SUSU ===== -->
                <form id="add-form-susu" class="form-section" onsubmit="handleAddSubmit(event, 'susu')">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Susu <span class="required">*</span></label>
                            <select class="form-select" id="add-jenis-susu" required>
                                <option value="">Pilih jenis susu</option>
                                <option value="segar">Susu Segar</option>
                                <option value="pasteurisasi">Susu Pasteurisasi</option>
                                <option value="uht">Susu UHT</option>
                                <option value="fermentasi">Susu Fermentasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-nama-susu" placeholder="Contoh: Susu Segar Premium" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Produksi <span class="required">*</span></label>
                            <input type="date" class="form-input" id="add-tgl-produksi-susu" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluwarsa <span class="required">*</span></label>
                            <input type="date" class="form-input" id="add-tgl-expiry-susu" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Harga per Liter <span class="required">*</span></label>
                            <input type="text" class="form-input" id="add-harga-susu" placeholder="Rp 15.000" required oninput="formatCurrencyInput(this)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok (Liter) <span class="required">*</span></label>
                            <input type="number" class="form-input" id="add-stok-susu" placeholder="Contoh: 200" min="0" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Foto Produk</label>
                        <div class="upload-wrapper">
                            <input type="file" id="add-file-susu" class="file-input" hidden accept="image/*" onchange="previewAddImage(event, 'susu')">
                            <div class="upload-box" onclick="document.getElementById('add-file-susu').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <p class="upload-text">Klik untuk menambahkan foto</p>
                                <span class="upload-hint">SVG, PNG, JPG (maks 5MB)</span>
                            </div>
                            <div class="image-preview" id="add-preview-susu" style="display: none;">
                                <img id="add-img-susu" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeAddImage('susu')">×</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div class="status-divider"></div>
                        <div class="status-options">
                            <div class="status-option available active" onclick="selectAddStatus(this, 'susu')">
                                <span class="status-icon">✓</span><span>Tersedia</span>
                            </div>
                            <div class="status-option unavailable" onclick="selectAddStatus(this, 'susu')">
                                <span class="status-icon">✕</span><span>Tidak Tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="add-status-susu" value="tersedia">
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Batal</button>
                        <button type="submit" class="btn btn-save"> Simpan Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

</div>

<script>
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
</script>
</body>
</html>