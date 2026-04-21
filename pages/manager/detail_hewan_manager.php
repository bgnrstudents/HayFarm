<?php include '../../components/header_manager.php'; ?>
<?php include '../../components/sidebar_manager.php'; ?>
<?php include '../../components/topbar_manager.php'; ?>

<div class="content-wrapper detail-wrapper">
    <!-- PAGE HEADER -->
    <div class="detail-page-header">
        <div class="back-title">
            <a href="javascript:history.back()">&#8592;</a>
            Detail Hewan Ternak
        </div>
        <button class="btn btn-success d-flex align-items-center gap-2" onclick="exportPDF()">
            <i class="fa fa-file-pdf"></i> Export PDF
        </button>
    </div>

    <!-- TOP SECTION -->
    <div class="detail-top-section">

        <!-- Card Info Hewan -->
        <div class="card-info-hewan">
            <img src="../../public/images/bgheader_produk.png" alt="Foto Hewan">
            <div class="info-grid">
                <span class="info-label">ID Ternak</span>
                <span class="info-value">: 0004</span>
                <span class="info-label">Jenis</span>
                <span class="info-value">: Sapi</span>
                <span class="info-label">Umur</span>
                <span class="info-value">: 7 Tahun</span>
                <span class="info-label">Lokasi</span>
                <span class="info-value">: Kandang 4</span>
                <span class="info-label">Status</span>
                <span class="info-value">: <span class="badge-sehat">Sehat</span></span>
            </div>
        </div>

        <!-- Stats Kanan -->
        <div class="detail-stats-col">
            <div class="card-pemeriksaan-terakhir shadow-sm">
                <small>Pemeriksaan Terakhir</small>
                <div class="tanggal">20 Feb 2026</div>
            </div>
            <div class="stats-row">
                <div class="card-stat shadow-sm">
                    <small>Total Pemeriksaan</small>
                    <div class="stat-number">8</div>
                    <div class="stat-sub">Tahun Ini</div>
                </div>
                <div class="card-stat shadow-sm">
                    <small>Total Vaksinasi</small>
                    <div class="stat-number">5</div>
                    <div class="stat-sub">Tahun Ini</div>
                </div>
            </div>
        </div>

    </div>

    <!-- BOTTOM SECTION -->
    <div class="detail-bottom-section">

        <!-- Tabel Kiri -->
        <div class="detail-tables-col">

            <!-- Riwayat Reproduksi -->
            <div class="card-table shadow-sm">
                <h6>Riwayat Reproduksi</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal IB</th>
                                <th>Petugas</th>
                                <th>Hasil</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>20 Feb 2026</td>
                                <td>Drh. Andi</td>
                                <td>Berhasil</td>
                                <td>Bunting</td>
                            </tr>
                            <tr>
                                <td>2 Des 2025</td>
                                <td>Drh. Wiwin</td>
                                <td>Tidak Berhasil</td>
                                <td>IB Ke-1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Riwayat Pemeriksaan Lengkap -->
            <div class="card-table shadow-sm">
                <h6>Riwayat Pemeriksaan Lengkap</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Diagnosis</th>
                                <th>Tindakan</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>20 Feb 2026</td>
                                <td>Sehat</td>
                                <td>Pemeriksaan Rutin</td>
                                <td>Drh. Andi</td>
                            </tr>
                            <tr>
                                <td>12 Feb 2026</td>
                                <td>Infeksi Ringan</td>
                                <td>Antibiotik</td>
                                <td>Drh. Wiwin</td>
                            </tr>
                            <tr>
                                <td>2 Feb 2026</td>
                                <td>Demam</td>
                                <td>Monitoring</td>
                                <td>Drh. Andi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Catatan Medis Kanan -->
        <div class="card-catatan shadow-sm">
            <h6>Catatan Medis</h6>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                mollit anim id est laborum.
            </p>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="detail-footer">
        Terakhir Update : 25 Februari 2026
    </div>

</div>

<script>
    function exportPDF() {
        alert('Export PDF - ID Ternak: 0004');
    }
</script>

<?php include '../../components/footer_manager.php'; ?>