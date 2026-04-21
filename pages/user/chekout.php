<?php
declare(strict_types=1);
 
// ── Autoloader sederhana (ganti dengan Composer di production) ──
spl_autoload_register(function (string $class): void {
    $base = __DIR__ . '/../src/';
    $file = $base . str_replace(['HayFarm\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($file)) require_once $file;
});
 
use HayFarm\Exceptions\ValidationException;
use HayFarm\Exceptions\PaymentException;
use HayFarm\Exceptions\UploadException;
use HayFarm\Models\Product;
use HayFarm\Services\ValidationService;
use HayFarm\Services\UploadService;
use HayFarm\Services\OrderService;
use HayFarm\Services\Payment\TransferPaymentHandler;
use HayFarm\Services\Payment\CODPaymentHandler;
use HayFarm\Storage\SessionOrderStorage;
 
// ── Bootstrap ──
if (session_status() === PHP_SESSION_NONE) session_start();
 
// ── Konfigurasi ──
$waNumber     = '6281234567890';
$rekeningNo   = '1234 5678 90';
$rekeningBank = 'BCA – a.n. Hay Farms Indonesia';
 
// ── Dependency Injection / Composition Root ──
$product = new Product(
    id:       'SAPI-001',
    name:     'Sapi Perah',
    price:    10_000,
    imageUrl: 'images/farel_perah.jpg',
    stock:    50,
);
 
$storage        = new SessionOrderStorage();
$uploadService  = new UploadService('uploads/');
$orderService   = new OrderService($storage);
 
$orderService->registerPaymentHandler(new TransferPaymentHandler($uploadService));
$orderService->registerPaymentHandler(new CODPaymentHandler($waNumber));
 
$validator = new ValidationService();
 
// ── State ──
$msgType = '';
$errMsg  = '';
$waLink  = '#';
$old     = [];
 
// ── Proses Form ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = ValidationService::sanitize($_POST['nama']     ?? '');
    $telp   = ValidationService::sanitize($_POST['telepon']  ?? '');
    $alamat = ValidationService::sanitize($_POST['alamat']   ?? '');
    $pos    = ValidationService::sanitize($_POST['kode_pos'] ?? '');
    $metode = $_POST['metode_pembayaran'] ?? '';
 
    try {
        $validator->validate([
            'nama'    => $nama,
            'telepon' => $telp,
            'alamat'  => $alamat,
            'kode_pos'=> $pos,
            'metode'  => $metode,
        ]);
 
        $result = $orderService->createOrder(
            customerData: [
                'nama'    => $nama,
                'telepon' => $telp,
                'alamat'  => $alamat,
                'kode_pos'=> $pos,
                'metode'  => $metode,
            ],
            product:     $product,
            paymentData: ['file' => $_FILES['bukti_pembayaran'] ?? null],
        );
 
        $msgType = $metode;
        $waLink  = $result['wa_link'] ?? '#';
        $old     = [];
 
    } catch (ValidationException $e) {
        $msgType = 'error';
        $errMsg  = implode('<br>', $e->getErrors());
        $old     = $_POST;
 
    } catch (UploadException | PaymentException $e) {
        $msgType = 'error';
        $errMsg  = $e->getMessage();
        $old     = $_POST;
    }
}
 
$fmtTotal = $product->getFormattedPrice();
$fmtHarga = $product->getFormattedPrice();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pesanan – HayFarm</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,600;0,700;0,800;1,700&display=swap" rel="stylesheet">
<style>
/* ── (CSS tidak berubah dari versi sebelumnya) ── */
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Nunito',sans-serif;background:#f4f6f4;color:#1a1a1a;min-height:100vh;}
.page-top{background:#fff;border-bottom:2px solid #e0e0e0;padding:14px 24px;display:flex;align-items:center;gap:10px;}
.page-top .back-arrow{font-size:18px;color:#1a5c38;cursor:pointer;font-weight:800;}
.page-top h1{font-size:20px;font-weight:800;color:#1a5c38;font-style:italic;text-decoration:underline;text-underline-offset:3px;margin:0;}
.page-top .search-box{margin-left:auto;border:1.5px solid #d0d0d0;border-radius:8px;padding:6px 14px;font-size:13px;font-family:'Nunito',sans-serif;width:260px;outline:none;color:#555;}
.page-top .search-box:focus{border-color:#1a5c38;}
.checkout-wrap{max-width:820px;margin:28px auto;padding:0 16px 40px;}
.checkout-card{background:#fff;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.07);padding:28px;display:grid;grid-template-columns:1fr 260px;gap:24px;align-items:start;}
.field-label{font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:6px;display:block;}
.field-input{width:100%;border:1.5px solid #d8d8d8;border-radius:10px;padding:10px 14px 10px 36px;font-size:13px;font-family:'Nunito',sans-serif;color:#1a1a1a;background:#fff;transition:border-color .2s,box-shadow .2s;outline:none;}
.field-input:focus{border-color:#1a5c38;box-shadow:0 0 0 3px rgba(26,92,56,.1);}
.input-wrap{position:relative;}
.input-wrap .fi{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#aaa;font-size:14px;}
.row2{display:grid;grid-template-columns:1fr 140px;gap:12px;}
.metode-title{font-size:15px;font-weight:800;color:#1a5c38;font-style:italic;margin:20px 0 12px;}
.metode-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.metode-btn{border:1.5px solid #d0d0d0;border-radius:12px;padding:14px 10px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;cursor:pointer;background:#fff;font-size:14px;font-weight:700;color:#555;transition:all .2s;user-select:none;}
.metode-btn i{font-size:22px;}
.metode-btn:hover{border-color:#1a5c38;}
.metode-btn.active{background:#1a5c38;color:#fff;border-color:#1a5c38;}
.rek-bar{margin-top:14px;background:#fff;border:1.5px solid #d0d0d0;border-radius:10px;padding:10px 16px;display:flex;align-items:center;justify-content:space-between;}
.rek-bar .rek-bank{font-size:10px;color:#888;font-weight:600;display:block;margin-bottom:2px;}
.rek-bar .rek-no{font-size:20px;font-weight:800;color:#1a1a1a;letter-spacing:1px;}
.btn-salin{background:#1a5c38;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:13px;font-weight:700;font-family:'Nunito',sans-serif;cursor:pointer;transition:background .2s;flex-shrink:0;}
.btn-salin:hover{background:#144a2d;}
.tagihan-title{font-size:15px;font-weight:800;color:#1a5c38;font-style:italic;margin-bottom:14px;}
.produk-row{display:flex;align-items:center;gap:12px;margin-bottom:14px;}
.produk-img{width:80px;height:64px;border-radius:10px;object-fit:cover;background:#eee;flex-shrink:0;}
.produk-nama{font-size:14px;font-weight:800;color:#1a1a1a;}
.produk-harga{font-size:13px;font-weight:600;color:#555;margin-top:2px;}
.total-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-top:1.5px solid #eee;margin-bottom:16px;}
.total-row .tl,.total-row .tv{font-size:14px;font-weight:800;color:#1a1a1a;}
.upload-title{font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:8px;}
.upload-box{border:1.5px solid #d0d0d0;border-radius:10px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;background:#fff;cursor:pointer;}
.upload-box .utext{font-size:13px;color:#aaa;display:flex;align-items:center;gap:8px;}
.btn-upload{background:#1a5c38;color:#fff;border:none;border-radius:7px;padding:7px 18px;font-size:13px;font-weight:700;font-family:'Nunito',sans-serif;cursor:pointer;flex-shrink:0;}
.upload-box input[type="file"]{display:none;}
.preview-img{width:100%;max-height:110px;object-fit:cover;border-radius:8px;margin-top:8px;display:none;}
.btn-kirim{width:100%;background:#1a5c38;color:#fff;border:none;border-radius:10px;padding:13px;font-size:15px;font-weight:800;font-family:'Nunito',sans-serif;cursor:pointer;margin-top:14px;transition:background .2s;display:flex;align-items:center;justify-content:center;gap:8px;}
.btn-kirim:hover{background:#144a2d;}
.btn-kirim.wa-btn{background:#25D366;}
.btn-kirim.wa-btn:hover{background:#1da851;}
.mb12{margin-bottom:12px;}
.sicon{width:56px;height:56px;border-radius:50%;background:#e8f5e9;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
.sicon i{font-size:26px;color:#1a5c38;}
@media(max-width:700px){.checkout-card{grid-template-columns:1fr;}.row2{grid-template-columns:1fr 1fr;}}
</style>
</head>
<body>
 
<div class="page-top">
  <span class="back-arrow"><i class="bi bi-arrow-left"></i></span>
  <h1>Informasi pengiriman</h1>
  <input type="text" class="search-box" placeholder="">
</div>
 
<div class="checkout-wrap">
  <form method="POST" enctype="multipart/form-data" id="mainForm">
    <input type="hidden" name="metode_pembayaran" id="metodeHidden" value="transfer">
 
    <div class="checkout-card">
      <!-- KIRI -->
      <div class="form-section">
        <div class="mb12">
          <label class="field-label">Nama</label>
          <div class="input-wrap">
            <i class="bi bi-person fi"></i>
            <input type="text" name="nama" class="field-input" placeholder="Farel"
              value="<?= htmlspecialchars($old['nama'] ?? '') ?>">
          </div>
        </div>
        <div class="mb12">
          <label class="field-label">No Telepon</label>
          <div class="input-wrap">
            <i class="bi bi-telephone fi"></i>
            <input type="text" name="telepon" class="field-input" placeholder="+62-124-930834"
              value="<?= htmlspecialchars($old['telepon'] ?? '') ?>">
          </div>
        </div>
        <div class="row2 mb12">
          <div>
            <label class="field-label">Alamat Pengiriman</label>
            <div class="input-wrap">
              <i class="bi bi-geo-alt fi"></i>
              <input type="text" name="alamat" class="field-input" placeholder="JL. Sirotul Mustaqim"
                value="<?= htmlspecialchars($old['alamat'] ?? '') ?>">
            </div>
          </div>
          <div>
            <label class="field-label">Kode Pos</label>
            <div class="input-wrap">
              <i class="bi bi-mailbox fi"></i>
              <input type="text" name="kode_pos" class="field-input" placeholder="369948"
                value="<?= htmlspecialchars($old['kode_pos'] ?? '') ?>">
            </div>
          </div>
        </div>
 
        <p class="metode-title">Metode Pembayaran</p>
        <div class="metode-grid">
          <div class="metode-btn active" id="btnTransfer" onclick="setMetode('transfer')">
            <i class="bi bi-credit-card-2-front"></i> Transfer
          </div>
          <div class="metode-btn" id="btnCod" onclick="setMetode('cod')">
            <i class="bi bi-person-badge"></i> COD
          </div>
        </div>
        <div class="rek-bar">
          <div>
            <span class="rek-bank"><?= htmlspecialchars($rekeningBank) ?></span>
            <span class="rek-no" id="rekeningNo"><?= htmlspecialchars($rekeningNo) ?></span>
          </div>
          <button type="button" class="btn-salin" onclick="salinRek()">Salin</button>
        </div>
      </div>
 
      <!-- KANAN -->
      <div class="tagihan-section">
        <p class="tagihan-title">Metode Pembayaran</p>
        <div class="produk-row">
          <img class="produk-img" src="<?= htmlspecialchars($product->getImageUrl()) ?>" alt="<?= htmlspecialchars($product->getName()) ?>">
          <div>
            <div class="produk-nama"><?= htmlspecialchars($product->getName()) ?></div>
            <div class="produk-harga"><?= $fmtHarga ?></div>
          </div>
        </div>
        <div class="total-row">
          <span class="tl">Total Tagihan</span>
          <span class="tv"><?= $fmtTotal ?></span>
        </div>
        <div id="uploadSection">
          <p class="upload-title">Upload Hasil Pembayaran</p>
          <label class="upload-box" for="fileBukti">
            <span class="utext"><i class="bi bi-upload"></i> Upload</span>
            <button type="button" class="btn-upload"
              onclick="event.preventDefault();document.getElementById('fileBukti').click()">Upload</button>
            <input type="file" id="fileBukti" name="bukti_pembayaran" accept="image/*" onchange="previewBukti(this)">
          </label>
          <img id="previewImg" class="preview-img" src="#" alt="Preview">
        </div>
        <button type="submit" class="btn-kirim" id="btnKirim">
          Kirim Bukti Pembayaran
        </button>
      </div>
    </div>
  </form>
</div>
 
<!-- Modal Transfer -->
<div class="modal fade" id="mTransfer" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5c38;color:#fff;">
        <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Pembayaran Berhasil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="sicon"><i class="bi bi-check-circle-fill"></i></div>
        <h5 class="fw-bold">Bukti Pembayaran Diterima!</h5>
        <p class="text-muted" style="font-size:13px;">Tim kami akan memverifikasi dalam 1×24 jam.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-success px-4" data-bs-dismiss="modal">Oke</button>
      </div>
    </div>
  </div>
</div>
 
<!-- Modal COD -->
<div class="modal fade" id="mCod" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#25D366;color:#fff;">
        <h5 class="modal-title"><i class="bi bi-whatsapp me-2"></i>Pesanan COD Diterima</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="sicon"><i class="bi bi-truck" style="color:#25D366"></i></div>
        <h5 class="fw-bold">Pesanan COD Berhasil!</h5>
        <p class="text-muted mb-3" style="font-size:13px;">Lanjutkan konfirmasi melalui WhatsApp.</p>
        <a id="waBtn" href="<?= htmlspecialchars($waLink) ?>" target="_blank"
          class="btn-kirim wa-btn text-decoration-none" style="display:inline-flex;width:auto;padding:10px 24px;">
          <i class="bi bi-whatsapp"></i> Chat WhatsApp
        </a>
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
 
<!-- Modal Error -->
<div class="modal fade" id="mError" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#c62828;color:#fff;">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Perhatian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
      </div>
      <div class="modal-body text-center py-3">
        <i class="bi bi-x-circle-fill text-danger d-block mb-2" style="font-size:2.4rem;"></i>
        <div id="errMsg" style="font-size:13px;color:#444;line-height:1.7;"></div>
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-danger px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
 
<!-- Modal Salin -->
<div class="modal fade" id="mSalin" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center py-3">
        <i class="bi bi-clipboard-check-fill text-success d-block mb-1" style="font-size:2rem;"></i>
        <p class="mb-0 fw-bold" style="font-size:13px;">Nomor rekening disalin!</p>
      </div>
    </div>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const PHP_TYPE = <?= json_encode($msgType) ?>;
const PHP_MSG  = <?= json_encode($errMsg)  ?>;
const PHP_WA   = <?= json_encode($waLink)  ?>;
 
window.addEventListener('DOMContentLoaded', () => {
  if (PHP_TYPE === 'transfer') new bootstrap.Modal(document.getElementById('mTransfer')).show();
  else if (PHP_TYPE === 'cod') { document.getElementById('waBtn').href = PHP_WA; new bootstrap.Modal(document.getElementById('mCod')).show(); }
  else if (PHP_TYPE === 'error') { document.getElementById('errMsg').innerHTML = PHP_MSG; new bootstrap.Modal(document.getElementById('mError')).show(); }
});
 
function setMetode(val) {
  document.getElementById('metodeHidden').value = val;
  const isTransfer = val === 'transfer';
  document.getElementById('btnTransfer').className = 'metode-btn' + (isTransfer ? ' active' : '');
  document.getElementById('btnCod').className       = 'metode-btn' + (!isTransfer ? ' active' : '');
  document.getElementById('uploadSection').style.display = isTransfer ? 'block' : 'none';
  const btn = document.getElementById('btnKirim');
  btn.innerHTML = isTransfer ? 'Kirim Bukti Pembayaran' : '<i class="bi bi-whatsapp me-1"></i> Kirim ke WhatsApp';
  btn.className = isTransfer ? 'btn-kirim' : 'btn-kirim wa-btn';
}
 
function previewBukti(input) {
  const img = document.getElementById('previewImg');
  if (input.files?.[0]) {
    const r = new FileReader();
    r.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
    r.readAsDataURL(input.files[0]);
  }
}
 
function salinRek() {
  navigator.clipboard.writeText(document.getElementById('rekeningNo').innerText.replace(/\s/g, '')).then(() => {
    const m = new bootstrap.Modal(document.getElementById('mSalin'));
    m.show(); setTimeout(() => m.hide(), 1600);
  });
}
 
document.getElementById('mainForm').addEventListener('submit', function(e) {
  const get = name => this.querySelector('[name="' + name + '"]').value.trim();
  const errs = [];
  if (!get('nama'))     errs.push('Nama wajib diisi.');
  if (!get('telepon'))  errs.push('No Telepon wajib diisi.');
  if (!get('alamat'))   errs.push('Alamat wajib diisi.');
  if (!get('kode_pos')) errs.push('Kode Pos wajib diisi.');
  if (document.getElementById('metodeHidden').value === 'transfer' && !document.getElementById('fileBukti').files.length)
    errs.push('Bukti pembayaran wajib diupload.');
  if (errs.length) {
    e.preventDefault();
    document.getElementById('errMsg').innerHTML = errs.join('<br>');
    new bootstrap.Modal(document.getElementById('mError')).show();
  }
});
</script>
</body>
</html>