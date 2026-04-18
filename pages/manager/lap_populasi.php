<?php include '../../components/header_manager.php'; ?>
<?php include '../../components/sidebar_manager.php'; ?>
<div class="main-content-populasi">
    <h1 class="h3 mb-0">Laporan Populasi Ternak</h1>

    <div class="filter-container">
        <select id="month">
            <option>Februari</option>
            <option>Maret</option>
            <option>April</option>
        </select>

        <select id="year">
            <option>2026</option>
            <option>2025</option>
            <option>2024</option>
        </select>

        <select id="animal">
            <option>Semua Hewan</option>
            <option>Sapi</option>
            <option>Kambing</option>
            <option>Ayam</option>
        </select>

        <button class="btn export-pdf" onclick="exportPDF()">
            📄 Export PDF
        </button>

        <button class="btn export-excel" onclick="exportExcel()">
            📊 Export Excel
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Hewan</th>
                    <th>Jumlah Populasi</th>
                    <th>Perubahan Bulan Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Sapi</td>
                    <td>150</td>
                    <td>+10</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Kambing</td>
                    <td>200</td>
                    <td>-5</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Ayam</td>
                    <td>500</td>
                    <td>+20</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
