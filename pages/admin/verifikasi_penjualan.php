<?php
session_start();

if (!isset($_SESSION['login'], $_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit;
}
require_once '../../config/database.php';
require_once '../../process/models/transaksi.php';

$database = new Database();
$db = $database->getConnection();
$transaksiModel = new Transaksi($db);

// Ambil data dengan OOP
// Ambil parameter filter dari URL (GET)
$filter_status = $_GET['status'] ?? null;
$filter_bulan = $_GET['bulan'] ?? null;
$filter_metode = $_GET['metode'] ?? null;

// Ambil data dengan filter
$dataTransaksi = $transaksiModel->getAllTransaksi($filter_status, $filter_bulan, $filter_metode);
$stats = $transaksiModel->getStatsVerifikasi();

// Helper functions
function rupiah($amount)
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function classStatusTransaksi($status)
{
    return match ($status) {
        'menunggu_verifikasi' => 'wait',
        'telah_dikonfirmasi' => 'ok',
        'dibatalkan' => 'no',
        default => 'wait'
    };
}

function labelStatusTransaksi($status)
{
    return match ($status) {
        'menunggu_verifikasi' => 'Menunggu',
        'telah_dikonfirmasi' => 'Diverifikasi',
        'dibatalkan' => 'Ditolak',
        default => $status
    };
}

// Extract stats untuk view
$totalMenunggu = $stats['menunggu'];
$totalDiverifikasi = $stats['diverifikasi'];
$totalDitolak = $stats['ditolak'];
$totalTerverifikasi = $stats['total_terverifikasi'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Verifikasi Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_verifikasiPenjualan.css?v=2">
</head>

<body>

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/../../components/sidebar_admin.php'; ?>

    <!-- MAIN -->
    <div class="main-content">
        <?php include __DIR__ . '/../../components/navbar_admin.php'; ?>

        <!-- HEADER -->
        <div class="section-header mt-3">
            <h2>Verifikasi Penjualan</h2>
            <p>Review & verifikasi pesanan sebelum dikonfirmasi</p>
        </div>
        <?php
        // Unified Alert Logic (Session + GET)
        $alertType = '';
        $alertMessage = '';

        if (isset($_SESSION['flash_message'])) {
            $alertMessage = $_SESSION['flash_message'];
            $alertType = ($_SESSION['flash_type'] ?? 'success') === 'error' ? 'error' : 'success';
            unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        } elseif (isset($_GET['status'])) {
            if ($_GET['status'] === 'berhasil') {
                $alertType = 'success';
                $alertMessage = 'Status transaksi berhasil diperbarui!';
            } elseif ($_GET['status'] === 'gagal') {
                $alertType = 'error';
                $alertMessage = 'Gagal memperbarui status transaksi. Silakan coba lagi.';
            }
        }

        $alertIcon = match ($alertType) {
            'success' => '<i class="fas fa-check-circle"></i>',
            'error' => '<i class="fas fa-exclamation-circle"></i>',
            default => ''
        };
        ?>

        <?php if ($alertMessage): ?>
            <div class="hayfarm-alert <?= $alertType ?>" id="hayfarmAlert">
                <div class="hayfarm-alert-icon"><?= $alertIcon ?></div>
                <div class="hayfarm-alert-content">
                    <p class="hayfarm-alert-message"><?= htmlspecialchars($alertMessage) ?></p>
                </div>
                <button class="hayfarm-alert-close" onclick="closeHayfarmAlert()" aria-label="Tutup">&times;</button>
            </div>
        <?php endif; ?>

        <!-- FILTER -->
        <div class="filter-box">
            <select id="filterBulan" name="bulan" data-filter="bulan">
                <option value="">Semua Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <select id="filterStatus" name="status" data-filter="status">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi">Menunggu</option>
                <option value="telah_dikonfirmasi">Diverifikasi</option>
                <option value="dibatalkan">Ditolak</option>
            </select>
            <select id="filterMetode" name="metode" data-filter="metode">
                <option value="">Semua Metode</option>
                <option value="transfer">Transfer</option>
                <option value="cod">COD</option>
            </select>
        </div>

        <!-- STATS -->
        <div class="stats">
            <div class="stat-card">
                <h4>Menunggu</h4>
                <h2><?= $totalMenunggu ?> Pesanan</h2>
            </div>
            <div class="stat-card">
                <h4>Diverifikasi</h4>
                <h2><?= $totalDiverifikasi ?> Pesanan</h2>
            </div>
            <div class="stat-card">
                <h4>Ditolak</h4>
                <h2><?= $totalDitolak ?> Pesanan</h2>
            </div>
            <div class="stat-card">
                <h4>Total Terverifikasi</h4>
                <h2><?= rupiah($totalTerverifikasi) ?></h2>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="verifikasiTableBody">
                    <?php if (empty($dataTransaksi)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataTransaksi as $row): ?>
                            <?php
                            $kodePesanan = '#ORD-' . str_pad((string) $row['id_transaksi'], 3, '0', STR_PAD_LEFT);
                            $buktiPembayaran = trim((string) ($row['bukti_pembayaran'] ?? ''));
                            // FIX: Path bukti sesuai dengan upload logic
                            $buktiUrl = $buktiPembayaran !== '' ? '../../' . ltrim($buktiPembayaran, '/') : '';
                            $detailPesanan = [
                                'orderId' => $kodePesanan,
                                'customer' => $row['nama_pembeli'],
                                'phone' => $row['no_telp'] ?: '-',
                                'address' => $row['alamat'] ?: '-',
                                'method' => strtoupper((string) $row['metode_pembayaran']),
                                'total' => rupiah((float) $row['total_tagihan']),
                                'proofName' => $buktiPembayaran ?: 'Bukti pembayaran belum tersedia',
                                'proofUrl' => $buktiUrl,
                            ];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($kodePesanan) ?></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['tgl_transaksi']))) ?></td>
                                <td><?= htmlspecialchars($row['nama_pembeli']) ?></td>
                                <td><?= htmlspecialchars($row['produk'] ?? '-') ?></td>
                                <td><?= htmlspecialchars((string) ($row['jumlah_pembelian'] ?? '')) ?></td>
                                <td><?= rupiah((float) $row['total_tagihan']) ?></td>
                                <td><span class="status <?= classStatusTransaksi($row['status_transaksi']) ?>"><?= labelStatusTransaksi($row['status_transaksi']) ?></span></td>
                                <td>
                                    <?php if ($row['status_transaksi'] === 'menunggu_verifikasi'): ?>
                                        <form id="verifyForm<?= (int) $row['id_transaksi'] ?>"
                                            action="../../process/handlers/verifikasi_handler.php"
                                            method="POST" style="display:inline;">
                                            <input type="hidden" name="id_transaksi" value="<?= (int) $row['id_transaksi'] ?>">
                                            <input type="hidden" name="aksi" id="verifyAction<?= (int) $row['id_transaksi'] ?>" value="verifikasi">
                                            <button
                                                class="btn-verif"
                                                type="button"
                                                data-order='<?= htmlspecialchars(json_encode($detailPesanan), ENT_QUOTES) ?>'
                                                onclick="openPendingFromButton(this, 'verifyForm<?= (int) $row['id_transaksi'] ?>', 'verifyAction<?= (int) $row['id_transaksi'] ?>')">Verifikasi</button>
                                        </form>
                                    <?php elseif ($row['status_transaksi'] === 'telah_dikonfirmasi'): ?>
                                        <button class="eye" type="button" data-order='<?= htmlspecialchars(json_encode($detailPesanan), ENT_QUOTES) ?>' onclick="openVerifiedFromButton(this)"><i class="fa fa-eye"></i></button>
                                    <?php else: ?>
                                        <button class="eye" type="button" data-order='<?= htmlspecialchars(json_encode($detailPesanan), ENT_QUOTES) ?>' onclick="openRejectedFromButton(this)"><i class="fa fa-eye"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination">
                <span>Menampilkan 1-<?= count($dataTransaksi ?? []) ?> dari <?= count($dataTransaksi ?? []) ?> data</span>
                <div>
                    <button class="page-btn">Sebelumnya</button>
                    <button class="page-btn active-page">1</button>
                    <button class="page-btn">Selanjutnya</button>
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL DETAIL PESANAN -->
    <div class="sales-modal-overlay" id="salesModal" onclick="closeSalesModalOutside(event)">
        <div class="sales-modal-card">
            <div class="sales-modal-body">
                <div class="sales-modal-header">
                    <div>
                        <h2 class="sales-modal-title">Detail Pesanan</h2>
                        <p class="sales-modal-subtitle" id="salesSubtitle">Review pesanan pelanggan</p>
                    </div>
                    <button class="sales-modal-close" type="button" onclick="closeSalesModal()">&times;</button>
                </div>

                <div class="sales-order-id" id="salesOrderId">#ORD-2026-001</div>
                <div class="sales-status-badge waiting" id="salesStatusText">Menunggu Verifikasi</div>

                <div class="sales-section-title">Informasi Pelanggan</div>
                <div class="sales-info-row">
                    <div class="sales-icon-box"><i class="fas fa-user"></i></div>
                    <div><span class="sales-label">Nama Lengkap</span><span class="sales-value" id="salesCustomer">Ahmad Ridwan</span></div>
                </div>
                <!-- Email field DIHAPUS karena tidak ada di database -->
                <div class="sales-info-row">
                    <div class="sales-icon-box"><i class="fas fa-phone"></i></div>
                    <div><span class="sales-label">Nomor Telepon</span><span class="sales-value" id="salesPhone">08123456789</span></div>
                </div>
                <div class="sales-info-row">
                    <div class="sales-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                    <div><span class="sales-label">Alamat Pengiriman</span><span class="sales-value" id="salesAddress">Cianjur, Jawa Barat</span></div>
                </div>
                <!-- Setelah row Alamat, tambahkan ini: -->
                <div class="sales-info-row">
                    <div class="sales-icon-box"><i class="fas fa-envelope"></i></div>
                    <div>
                        <span class="sales-label">Email</span>
                        <span class="sales-value" id="salesEmail">-</span>
                    </div>
                </div>

                <!-- Tambahkan section Detail Produk (sebelum sales-summary): -->
                <div class="sales-section-title">Detail Produk</div>
                <div class="sales-products-wrapper" style="margin-bottom: 16px;">
                    <table class="sales-products-table" style="width:100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <th style="text-align:left; padding: 10px; color: #64748b; font-weight: 700;">Produk</th>
                                <th style="text-align:left; padding: 10px; color: #64748b; font-weight: 700;">ID/Kode Hewan</th> <!-- ✅ BARU -->
                                <th style="text-align:center; padding: 10px; color: #64748b; font-weight: 700;">Qty</th>
                                <th style="text-align:right; padding: 10px; color: #64748b; font-weight: 700;">Harga</th>
                                <th style="text-align:right; padding: 10px; color: #64748b; font-weight: 700;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="salesProductsBody">
                            <tr>
                                <td colspan="5" style="padding: 12px; text-align: center; color: #94a3b8;">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="sales-section-title" id="proofTitle">Bukti Transfer</div>
                <div class="sales-proof-card" id="salesProof">
                    <img src="" alt="Bukti transfer">
                    <div>
                        <span class="sales-value">Bukti_Transfer.jpg</span>
                        <span class="sales-label">Klik untuk memperbesar</span>
                    </div>
                </div>

                <div class="sales-section-title">Ringkasan Pembayaran</div>
                <div class="sales-summary">
                    <div class="sales-summary-row">
                        <div class="sales-value"><i class="fas fa-credit-card"></i> Metode Pembayaran</div>
                        <div class="sales-value" id="salesPaymentMethod">Transfer Bank</div>
                    </div>
                    <div class="sales-summary-row sales-total-row">
                        <span class="sales-total-label">Total</span>
                        <span class="sales-total" id="salesTotal">Rp 15.250.000</span>
                    </div>
                </div>

                <div class="sales-actions" id="salesActions"></div>
            </div>
        </div>
    </div>

    <!-- LIGHTBOX GAMBAR -->
    <div class="sales-lightbox" id="salesLightbox" onclick="closeSalesLightbox()">
        <img id="salesLightboxImage" src="" alt="Bukti transfer">
    </div>

    <!-- JS File -->
    <script src="../../public/js/verifikasiPenjualan_admin.js?v=3"></script>

</body>

</html>