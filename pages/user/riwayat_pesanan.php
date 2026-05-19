<?php
// ✅ VALIDASI SESSION KETAT
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../../login.php');
    exit;
}

if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    session_destroy();
    header('Location: ../../login.php?error=session_corrupt');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../process/models/transaksi.php';

$database = new Database();
$db = $database->getConnection();
$transaksi_model = new Transaksi($db);

// ✅ SANITISASI & LOGGING
$idUser = (int) $_SESSION['id_user'];

// Log untuk debugging
error_log("RIWAYAT PESANAN - User ID dari session: " . $idUser);
error_log("RIWAYAT PESANAN - Session data: " . print_r($_SESSION, true));

$transaksiList = $transaksi_model->getTransaksiByUser($idUser);

// Log hasil query
error_log("RIWAYAT PESANAN - Jumlah transaksi ditemukan: " . count($transaksiList));

$idUser = (int) $_SESSION['id_user'];
$transaksiList = $transaksi_model->getTransaksiByUser($idUser);

function formatRupiah($amount)
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function getStatusBadge($status)
{
    $badges = [
        'menunggu_verifikasi' => [
            'class' => 'menunggu',
            'icon' => 'fa-solid fa-clock',
            'text' => 'Menunggu Verifikasi'
        ],
        'telah_dikonfirmasi' => [
            'class' => 'selesai',
            'icon' => 'fa-solid fa-check-circle',
            'text' => 'Telah Dikonfirmasi'
        ],
        'dibatalkan' => [
            'class' => 'dibatalkan',
            'icon' => 'fa-solid fa-times-circle',
            'text' => 'Dibatalkan'
        ]
    ];

    return $badges[$status] ?? [
        'class' => 'menunggu',
        'icon' => 'fa-solid fa-question-circle',
        'text' => $status
    ];
}

function formatDate($date)
{
    $months = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    $parts = explode('-', $date);
    if (count($parts) === 3) {
        return $parts[2] . ' ' . $months[$parts[1]] . ' ' . $parts[0];
    }
    return $date;
}
?>

<section class="riwayat-header">
    <div class="riwayat-header-bg"></div>
    <div class="riwayat-header-gradient"></div>
    <div class="riwayat-header-content container">
        <a href="?page=user/produk" class="btn-kembali">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-center">Riwayat Transaksi</h1>
        <p class="text-center">Lihat dan pantau status pembelian Anda</p>
    </div>
</section>

<div class="riwayat-body">
    <div class="container">

        <!-- TOOLBAR -->
        <div class="riwayat-toolbar">
            <div class="filter-status">
                <label>Status:</label>
                <select class="filter-select" id="filterStatus" onchange="filterKartu()">
                    <option value="semua">Semua</option>
                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                    <option value="telah_dikonfirmasi">Telah Dikonfirmasi</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari produk..."
                    oninput="filterKartu()">
            </div>
        </div>

        <!-- GRID KARTU -->
        <div class="riwayat-grid" id="riwayatGrid">

            <?php if (empty($transaksiList)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-box-open"></i>
                    <p>Belum ada transaksi. Mulai belanja sekarang!</p>
                    <a href="?page=user/produk" class="btn btn-success rounded-4 px-4 py-2" style="margin-top: 12px;">
                        Lihat Produk
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($transaksiList as $t):
                    $status_info = getStatusBadge($t['status_transaksi']);
                    $produkNames = array_map(fn($d) => $d['nama_produk'] ?? 'Produk Tidak Tersedia', $t['detail']);
                    $produkSearch = implode(' ', $produkNames);
                ?>
                    <div class="order-card" data-status="<?= htmlspecialchars($t['status_transaksi']) ?>" data-nama="<?= htmlspecialchars($produkSearch) ?>">
                        <div class="order-card-top">
                            <span class="status-badge <?= htmlspecialchars($status_info['class']) ?>">
                                <i class="<?= htmlspecialchars($status_info['icon']) ?>" style="font-size:10px"></i>
                                <?= htmlspecialchars($status_info['text']) ?>
                            </span>
                            <span class="order-date"><?= formatDate($t['tgl_transaksi']) ?></span>
                        </div>

                        <div class="order-card-mid">
                            <?php if (!empty($t['detail'])):
                                $firstItem = $t['detail'][0];
                            ?>
                                <img src="<?= htmlspecialchars($firstItem['gambar'] ?? 'public/images/rumput.jpg') ?>"
                                    alt="<?= htmlspecialchars($firstItem['nama_produk'] ?? 'Produk') ?>"
                                    class="order-thumb"
                                    onerror="this.onerror=null;this.src='public/images/susu.jpg';">
                            <?php endif; ?>

                            <div class="order-info">
                                <p class="order-nama">
                                    <?php
                                    if (count($t['detail']) === 1) {
                                        echo htmlspecialchars($t['detail'][0]['nama_produk'] ?? 'Produk');
                                    } else {
                                        echo htmlspecialchars($t['detail'][0]['nama_produk'] ?? 'Produk') . ' +' . (count($t['detail']) - 1) . ' produk';
                                    }
                                    ?>
                                </p>

                                <div class="order-items" style="margin-top: 8px; margin-bottom: 12px;">
                                    <?php foreach ($t['detail'] as $detail): ?>
                                        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">
                                            • <?= htmlspecialchars($detail['nama_produk'] ?? 'Produk') ?> (<?= (int)$detail['jumlah'] ?> <?= htmlspecialchars($detail['satuan'] ?? 'item') ?>) - <?= formatRupiah($detail['sub_total']) ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <p class="order-inform">Informasi Pengiriman</p>
                                <div class="order-detail-row">
                                    <span class="dl">Nama</span><span class="dv"><?= htmlspecialchars($t['nama_pembeli']) ?></span>
                                    <span class="dl">Alamat</span><span class="dv"><?= htmlspecialchars($t['alamat']) ?></span>
                                    <span class="dl">Telepon</span><span class="dv"><?= htmlspecialchars($t['no_telp']) ?></span>
                                    <span class="dl">Metode Pembayaran</span>
                                    <span class="dv">
                                        <span style="text-transform: uppercase; font-size: 11px; font-weight: 600;">
                                            <?= $t['metode_pembayaran'] === 'cod' ? 'COD' : 'Transfer Bank' ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN FOOTER KARTU -->
                        <div class="order-card-footer">
                            <div class="order-total">
                                Total Pembayaran
                                <strong><?= formatRupiah($t['total_tagihan']) ?></strong>
                            </div>

                            <!-- ✅ FIX: Tombol Download Struk (Hanya muncul jika Lunas/Dikonfirmasi) -->
                            <?php if ($t['status_transaksi'] === 'telah_dikonfirmasi'): ?>
                                <a href="download_struk.php?id_transaksi=<?= $t['id_transaksi'] ?>"
                                    class="btn-download-struk"
                                    target="_blank"
                                    title="Download Struk">
                                    <i class="fas fa-file-pdf"></i> Download Struk
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div><!-- /riwayat-grid -->
    </div>
</div>

<?php if (!empty($transaksiList)): ?>
    <div class="riwayat-info">
        <div class="container">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Silakan tunggu proses verifikasi admin. Anda akan menerima notifikasi setelah pesanan dikonfirmasi.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function filterKartu() {
        const status = document.getElementById('filterStatus').value;
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#riwayatGrid .order-card');

        let visible = 0;
        cards.forEach(card => {
            const matchStatus = status === 'semua' || card.dataset.status === status;
            const matchSearch = card.dataset.nama.toLowerCase().includes(query);
            const show = matchStatus && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Tampilkan empty state kalau tidak ada hasil
        let empty = document.getElementById('emptyState');
        if (visible === 0) {
            if (!empty) {
                empty = document.createElement('div');
                empty.id = 'emptyState';
                empty.className = 'empty-state';
                empty.innerHTML = `
                <i class="fa-solid fa-box-open"></i>
                <p>Tidak ada transaksi yang ditemukan.</p>`;
                document.getElementById('riwayatGrid').appendChild(empty);
            }
        } else if (empty) {
            empty.remove();
        }
    }
</script>