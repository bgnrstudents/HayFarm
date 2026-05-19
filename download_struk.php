<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

error_reporting(E_ALL);
ini_set('display_errors', 0);

if (!isset($_SESSION['id_user'])) {
    die("Akses Ditolak. Silakan login terlebih dahulu.");
}

$id_transaksi = (int) ($_GET['id_transaksi'] ?? 0);
if ($id_transaksi <= 0) {
    die("ID Transaksi tidak valid.");
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Ambil data transaksi
    $stmt = $db->prepare("SELECT * FROM transaksi WHERE id_transaksi = ? AND id_user = ?");
    $stmt->bind_param("ii", $id_transaksi, $_SESSION['id_user']);
    $stmt->execute();
    $transaksi = $stmt->get_result()->fetch_assoc();

    if (!$transaksi) {
        die("Transaksi tidak ditemukan atau bukan milik Anda.");
    }

    // Ambil detail produk
    $stmtDetail = $db->prepare("
        SELECT dt.*, dp.nama_produk, dp.jenis_produk, 
               t.kode_hewan, t.berat_badan, t.jenis_hewan
        FROM detail_transaksi dt
        JOIN data_produk dp ON dt.id_produk = dp.id_produk
        LEFT JOIN data_ternak t ON dp.id_hewan = t.id_hewan
        WHERE dt.id_transaksi = ?
    ");
    $stmtDetail->bind_param("i", $id_transaksi);
    $stmtDetail->execute();
    $details = $stmtDetail->get_result()->fetch_all(MYSQLI_ASSOC);
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('d/m/Y H:i:s', strtotime($transaksi['tgl_transaksi'] . ' WIB'));
    $total_formatted = 'Rp ' . number_format((float)$transaksi['total_tagihan'], 0, ',', '.');
    $total_terbilang = terbilang((int)$transaksi['total_tagihan']);

    $metode = strtoupper($transaksi['metode_pembayaran'] === 'transfer' ? 'Transfer Bank' : 'COD');
    $status = ($transaksi['status_transaksi'] === 'telah_dikonfirmasi') ? 'LUNAS' : 'MENUNGGU VERIFIKASI';
    $status_color = ($transaksi['status_transaksi'] === 'telah_dikonfirmasi') ? '#10B981' : '#F59E0B';

    // Generate HTML Struk
    $html = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 15px; background: #fff; color: #333; font-size: 13px; }
            .struk { max-width: 380px; margin: 0 auto; border: 2px solid #166534; border-radius: 12px; padding: 15px; }
            .header { text-align: center; border-bottom: 2px solid #166534; padding-bottom: 10px; margin-bottom: 15px; }
            .logo { width: 70px; height: 70px; margin-bottom: 8px; }
            .brand { font-size: 22px; font-weight: bold; color: #166534; margin: 0; }
            .address { font-size: 11px; color: #444; line-height: 1.3; margin-top: 5px; }
            .date { font-size: 12px; color: #555; margin-top: 8px; }
            .section { margin: 12px 0; }
            .section-title { font-size: 12px; font-weight: bold; color: #166534; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 8px; }
            .row { display: flex; justify-content: space-between; margin: 6px 0; font-size: 13px; }
            .label { color: #555; }
            .value { font-weight: 600; text-align: right; }
            .total-box { background: #ECFDF5; padding: 15px; border-radius: 10px; text-align: center; margin: 15px 0; }
            .total-label { font-size: 13px; color: #166534; }
            .total-amount { font-size: 26px; font-weight: bold; color: #166534; margin: 6px 0; }
            .terbilang { font-size: 11px; color: #15803d; font-style: italic; }
            .footer { text-align: center; margin-top: 20px; font-size: 11px; color: #166534; }
            table { width: 100%; border-collapse: collapse; font-size: 12px; }
            th, td { padding: 6px 0; border-bottom: 1px dashed #ccc; }
            th { text-align: left; color: #444; }
        </style>
    </head>
    <body>
        <div class="struk">
            <div class="header">
                <img src="public/images/logo.png" class="logo" alt="Hay Farm">
                <div class="brand">HAY FARM</div>
                <div class="address">
                    Jalan Mastrip, Kelurahan Sumbersari<br>
                    Kecamatan Sumbersari, Kabupaten Jember<br>
                    Jawa Timur
                </div>
                <div class="date">' . $tanggal . '</div>
            </div>

            <div class="section">
                <div class="section-title">DATA PEMBELI</div>
                <div class="row"><span class="label">Nama : </span><span class="value">' . esc($transaksi['nama_pembeli']) . '</span></div>
                <div class="row"><span class="label">WhatsApp : </span><span class="value">' . esc($transaksi['no_telp']) . '</span></div>
                <div class="row"><span class="label">Alamat : </span><span class="value">' . esc($transaksi['alamat']) . '</span></div>
            </div>

            <div class="section">
                <div class="section-title">DETAIL PRODUK</div>
                <table>';


    foreach ($details as $item) {
        $nama_item = esc($item['nama_produk']);
        if ($item['jenis_produk'] === 'hewan' && !empty($item['kode_hewan'])) {
            $nama_item .= "<br><small>ID: " . esc($item['kode_hewan']) . " | " . (float)$item['berat_badan'] . " Kg</small>";
        }
        $html .= '
                    <tr>
                        <td>' . $nama_item . '</td>
                        <td style="text-align:center;">' . (int)$item['jumlah'] . '</td>
                        <td style="text-align:right;">Rp ' . number_format((float)$item['sub_total'], 0, ',', '.') . '</td>
                    </tr>';
    }

    $html .= '
                </table>
            </div>

            <div class="total-box">
                <div class="total-label">TOTAL PEMBAYARAN</div>
                <div class="total-amount">' . $total_formatted . '</div>
                <div class="terbilang">' . $total_terbilang . ' Rupiah</div>
            </div>

            <div class="row" style="border-top: 1px dashed #ccc; padding-top: 10px;">
                <div><span class="label">Metode Pembayaran</span><br><strong>' . $metode . '</strong></div>
                <div style="text-align:right;"><span class="label">Status</span><br>
                    <span style="color:' . $status_color . '; font-weight:bold;">' . $status . '</span>
                </div>
            </div>

            <div class="footer">
                Terima kasih atas pembelian Anda!<br>
                <small>Silakan simpan struk ini sebagai bukti transaksi resmi Hay Farm.</small>
            </div>
        </div>
    </body>
    </html>';

    // Konfigurasi Dompdf
    $options = new Options();
    $options->setDefaultFont('Arial');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper([0, 0, 380, 700], 'portrait'); // Ukuran struk vertikal
    $dompdf->render();

    // Bersihkan buffer
    if (ob_get_level()) ob_end_clean();

    $filename = "Struk_HayFarm_" . $id_transaksi . ".pdf";
    $dompdf->stream($filename, ["Attachment" => true]);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

function esc($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Fungsi Terbilang
function terbilang($angka)
{
    $angka = abs($angka);
    $baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    if ($angka < 12) return $baca[$angka];
    elseif ($angka < 20) return terbilang($angka - 10) . " Belas";
    elseif ($angka < 100) return terbilang(floor($angka / 10)) . " Puluh" . terbilang($angka % 10);
    elseif ($angka < 200) return "Seratus" . terbilang($angka - 100);
    elseif ($angka < 1000) return terbilang(floor($angka / 100)) . " Ratus" . terbilang($angka % 100);
    elseif ($angka < 2000) return "Seribu" . terbilang($angka - 1000);
    elseif ($angka < 1000000) return terbilang(floor($angka / 1000)) . " Ribu" . terbilang($angka % 1000);
    elseif ($angka < 1000000000) return terbilang(floor($angka / 1000000)) . " Juta" . terbilang($angka % 1000000);
    return "";
}