<?php
// ============================================================
// CLASS: Validation
// ============================================================
class Validation {
    private array $errors = [];

    public function validate(array $data): array {
        $this->errors = [];
        $this->validateNama($data['nama'] ?? '');
        $this->validateTelepon($data['telepon'] ?? '');
        $this->validateAlamat($data['alamat'] ?? '');
        $this->validateKodePos($data['kode_pos'] ?? '');
        $this->validateMetode($data['metode'] ?? '');
        return $this->errors;
    }

    private function validateNama(string $v): void {
        if (empty($v)) $this->errors[] = 'Nama wajib diisi.';
        elseif (strlen($v) < 3) $this->errors[] = 'Nama minimal 3 karakter.';
    }
    private function validateTelepon(string $v): void {
        $v = preg_replace('/[\s\-\+]/', '', $v);
        if (empty($v)) $this->errors[] = 'No Telepon wajib diisi.';
        elseif (!preg_match('/^(08|628)\d{8,12}$/', $v)) $this->errors[] = 'Format No Telepon tidak valid (08xx / 628xx).';
    }
    private function validateAlamat(string $v): void {
        if (empty($v)) $this->errors[] = 'Alamat wajib diisi.';
    }
    private function validateKodePos(string $v): void {
        if (empty($v)) $this->errors[] = 'Kode Pos wajib diisi.';
        elseif (!preg_match('/^\d{5,6}$/', $v)) $this->errors[] = 'Kode Pos harus 5–6 angka.';
    }
    private function validateMetode(string $v): void {
        if (!in_array($v, ['transfer','cod'])) $this->errors[] = 'Metode pembayaran wajib dipilih.';
    }
    public static function sanitize(string $i): string {
        return htmlspecialchars(strip_tags(trim($i)), ENT_QUOTES, 'UTF-8');
    }
}

// ============================================================
// CLASS: Payment
// ============================================================
class Payment {
    private string $uploadDir  = 'uploads/';
    private array  $allowed    = ['image/jpeg','image/png','image/jpg','image/webp'];
    private int    $maxSize    = 5242880;

    public function __construct() {
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);
    }

    public function uploadBukti(array $file): array {
        if ($file['error'] !== UPLOAD_ERR_OK)
            return ['success'=>false,'message'=>'Gagal upload file.'];
        if (!in_array($file['type'], $this->allowed))
            return ['success'=>false,'message'=>'Format tidak valid. Hanya JPG/PNG/WEBP.'];
        if ($file['size'] > $this->maxSize)
            return ['success'=>false,'message'=>'File terlalu besar (maks 5MB).'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = 'bukti_'.uniqid().'.'.$ext;
        if (move_uploaded_file($file['tmp_name'], $this->uploadDir.$name))
            return ['success'=>true,'filename'=>$name];
        return ['success'=>false,'message'=>'Gagal menyimpan file.'];
    }

    public function generateWALink(array $d, string $no): string {
        $msg = "Halo, saya {$d['nama']} ingin pesan COD.\nNo HP: {$d['telepon']}\nAlamat: {$d['alamat']}, {$d['kode_pos']}\nTotal: Rp ".number_format($d['total'],0,',','.')." \nMetode: COD";
        return "https://wa.me/{$no}?text=".urlencode($msg);
    }
}

// ============================================================
// CLASS: Order
// ============================================================
class Order {
    public function create(array $data): array {
        try {
            if (session_status()===PHP_SESSION_NONE) session_start();
            $data['id']         = uniqid('ORD-',true);
            $data['created_at'] = date('Y-m-d H:i:s');
            $_SESSION['last_order']    = $data;
            $_SESSION['all_orders'][]  = $data;
            return ['success'=>true,'order_id'=>$data['id']];
        } catch(Exception $e) {
            return ['success'=>false,'message'=>$e->getMessage()];
        }
    }
}

// ============================================================
// PROSES FORM
// ============================================================
if (session_status()===PHP_SESSION_NONE) session_start();

$val     = new Validation();
$pay     = new Payment();
$ord     = new Order();

$msgType = '';
$errMsg  = '';
$waLink  = '#';
$old     = [];

// Produk dummy — ganti sesuai data nyata
$produkNama  = 'Sapi Perah';
$produkHarga = 10000;
$totalTagihan= 10000;
$waNumber    = '6281234567890';
$rekeningNo  = '1234 5678 90';
$rekeningBank= 'BCA – a.n. Hay Farms Indonesia';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nama   = Validation::sanitize($_POST['nama']    ?? '');
    $telp   = Validation::sanitize($_POST['telepon'] ?? '');
    $alamat = Validation::sanitize($_POST['alamat']  ?? '');
    $pos    = Validation::sanitize($_POST['kode_pos']?? '');
    $metode = $_POST['metode_pembayaran'] ?? '';
    $bukti  = null;

    $errors = $val->validate(['nama'=>$nama,'telepon'=>$telp,'alamat'=>$alamat,'kode_pos'=>$pos,'metode'=>$metode]);

    if ($metode==='transfer') {
        if (!empty($_FILES['bukti_pembayaran']['tmp_name'])) {
            $up = $pay->uploadBukti($_FILES['bukti_pembayaran']);
            if ($up['success']) $bukti = $up['filename'];
            else $errors[] = $up['message'];
        } else {
            $errors[] = 'Bukti pembayaran wajib diupload untuk Transfer.';
        }
    }

    if (empty($errors)) {
        $data = ['nama'=>$nama,'telepon'=>$telp,'alamat'=>$alamat,'kode_pos'=>$pos,
                 'metode_pembayaran'=>$metode,'bukti'=>$bukti,'total'=>$totalTagihan,'status'=>'pending'];
        $res  = $ord->create($data);
        if ($res['success']) {
            $msgType = $metode==='cod' ? 'cod' : 'transfer';
            if ($metode==='cod') $waLink = $pay->generateWALink($data, $waNumber);
            $old = [];
        } else {
            $msgType = 'error'; $errMsg = $res['message']; $old = $_POST;
        }
    } else {
        $msgType = 'error'; $errMsg = implode('<br>',$errors); $old = $_POST;
    }
}

$fmtTotal = 'Rp. '.number_format($totalTagihan,2,',','.');
$fmtHarga = 'Rp. '.number_format($produkHarga,2,',','.');
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
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Nunito',sans-serif;background:#f4f6f4;color:#1a1a1a;min-height:100vh;}

/* ── Page header ── */
.page-top{
  background:#fff;
  border-bottom:2px solid #e0e0e0;
  padding:14px 24px;
  display:flex;
  align-items:center;
  gap:10px;
}
.page-top .back-arrow{
  font-size:18px; color:#1a5c38; cursor:pointer; font-weight:800;
}
.page-top h1{
  font-size:20px; font-weight:800; color:#1a5c38;
  font-style:italic; text-decoration:underline; text-underline-offset:3px;
  margin:0;
}
/* search bar kanan */
.page-top .search-box{
  margin-left:auto;
  border:1.5px solid #d0d0d0;
  border-radius:8px;
  padding:6px 14px;
  font-size:13px;
  font-family:'Nunito',sans-serif;
  width:260px;
  outline:none;
  color:#555;
}
.page-top .search-box:focus{ border-color:#1a5c38; }

/* ── Main wrapper ── */
.checkout-wrap{
  max-width:820px;
  margin:28px auto;
  padding:0 16px 40px;
}

/* ── Card putih utama ── */
.checkout-card{
  background:#fff;
  border-radius:16px;
  box-shadow:0 2px 16px rgba(0,0,0,.07);
  padding:28px 28px 28px 28px;
  display:grid;
  grid-template-columns:1fr 260px;
  gap:24px;
  align-items:start;
}

/* ── Kiri: form ── */
.form-section{}

/* Label field */
.field-label{
  font-size:13px; font-weight:700; color:#1a1a1a; margin-bottom:6px; display:block;
}

/* Input field */
.field-input{
  width:100%;
  border:1.5px solid #d8d8d8;
  border-radius:10px;
  padding:10px 14px 10px 36px;
  font-size:13px;
  font-family:'Nunito',sans-serif;
  color:#1a1a1a;
  background:#fff;
  transition:border-color .2s,box-shadow .2s;
  outline:none;
}
.field-input:focus{
  border-color:#1a5c38;
  box-shadow:0 0 0 3px rgba(26,92,56,.1);
}
.field-input.no-icon{ padding-left:14px; }

.input-wrap{ position:relative; }
.input-wrap .fi{
  position:absolute; left:11px; top:50%; transform:translateY(-50%);
  color:#aaa; font-size:14px;
}

/* Row 2 col */
.row2{ display:grid; grid-template-columns:1fr 140px; gap:12px; }

/* Metode pembayaran */
.metode-title{
  font-size:15px; font-weight:800; color:#1a5c38;
  font-style:italic; margin:20px 0 12px;
}

.metode-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }

.metode-btn{
  border:1.5px solid #d0d0d0;
  border-radius:12px;
  padding:14px 10px;
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  gap:6px; cursor:pointer; background:#fff;
  font-size:14px; font-weight:700; color:#555;
  transition:all .2s;
  user-select:none;
}
.metode-btn i{ font-size:22px; }
.metode-btn:hover{ border-color:#1a5c38; }

.metode-btn.active{
  background:#1a5c38; color:#fff; border-color:#1a5c38;
}

/* Rekening bar */
.rek-bar{
  margin-top:14px;
  background:#fff;
  border:1.5px solid #d0d0d0;
  border-radius:10px;
  padding:10px 16px;
  display:flex; align-items:center; justify-content:space-between;
}
.rek-bar .rek-bank{
  font-size:10px; color:#888; font-weight:600; display:block; margin-bottom:2px;
}
.rek-bar .rek-no{
  font-size:20px; font-weight:800; color:#1a1a1a; letter-spacing:1px;
}
.btn-salin{
  background:#1a5c38; color:#fff; border:none; border-radius:8px;
  padding:8px 20px; font-size:13px; font-weight:700;
  font-family:'Nunito',sans-serif; cursor:pointer; transition:background .2s;
  flex-shrink:0;
}
.btn-salin:hover{ background:#144a2d; }

/* ── Kanan: panel tagihan ── */
.tagihan-section{}

.tagihan-title{
  font-size:15px; font-weight:800; color:#1a5c38;
  font-style:italic; margin-bottom:14px;
}

/* Produk row */
.produk-row{
  display:flex; align-items:center; gap:12px; margin-bottom:14px;
}
.produk-img{
  width:80px; height:64px; border-radius:10px; object-fit:cover;
  background:#eee; flex-shrink:0;
}
.produk-nama{ font-size:14px; font-weight:800; color:#1a1a1a; }
.produk-harga{ font-size:13px; font-weight:600; color:#555; margin-top:2px; }

/* Total */
.total-row{
  display:flex; justify-content:space-between; align-items:center;
  padding:10px 0; border-top:1.5px solid #eee; margin-bottom:16px;
}
.total-row .tl{ font-size:14px; font-weight:800; color:#1a1a1a; }
.total-row .tv{ font-size:14px; font-weight:800; color:#1a1a1a; }

/* Upload area */
.upload-title{
  font-size:13px; font-weight:700; color:#1a1a1a; margin-bottom:8px;
}
.upload-box{
  border:1.5px solid #d0d0d0;
  border-radius:10px;
  padding:10px 14px;
  display:flex; align-items:center; justify-content:space-between;
  background:#fff;
  cursor:pointer;
}
.upload-box .utext{ font-size:13px; color:#aaa; display:flex; align-items:center; gap:8px; }
.upload-box .utext i{ font-size:15px; }
.btn-upload{
  background:#1a5c38; color:#fff; border:none; border-radius:7px;
  padding:7px 18px; font-size:13px; font-weight:700;
  font-family:'Nunito',sans-serif; cursor:pointer; flex-shrink:0;
}
.upload-box input[type="file"]{ display:none; }
.preview-img{ width:100%; max-height:110px; object-fit:cover; border-radius:8px; margin-top:8px; display:none; }

/* Kirim button */
.btn-kirim{
  width:100%; background:#1a5c38; color:#fff; border:none; border-radius:10px;
  padding:13px; font-size:15px; font-weight:800;
  font-family:'Nunito',sans-serif; cursor:pointer; margin-top:14px;
  transition:background .2s; display:flex; align-items:center; justify-content:center; gap:8px;
}
.btn-kirim:hover{ background:#144a2d; }
.btn-kirim.wa-btn{ background:#25D366; }
.btn-kirim.wa-btn:hover{ background:#1da851; }

/* Divider antar field */
.mb12{ margin-bottom:12px; }
.mb0{ margin-bottom:0; }

/* Modal tweaks */
.modal-green .modal-header{ background:#1a5c38; color:#fff; }
.modal-green .modal-header .btn-close{ filter:invert(1); }
.sicon{ width:56px;height:56px;border-radius:50%;background:#e8f5e9;display:flex;align-items:center;justify-content:center;margin:0 auto 10px; }
.sicon i{ font-size:26px;color:#1a5c38; }

@media(max-width:700px){
  .checkout-card{ grid-template-columns:1fr; }
  .row2{ grid-template-columns:1fr 1fr; }
}
</style>
</head>
<body>

<!-- ── Page header ── -->
<div class="page-top">
  <span class="back-arrow"><i class="bi bi-arrow-left"></i></span>
  <h1>Informasi pengiriman</h1>
  <input type="text" class="search-box" placeholder="">
</div>

<!-- ── Checkout wrap ── -->
<div class="checkout-wrap">
  <form method="POST" enctype="multipart/form-data" id="mainForm">
    <input type="hidden" name="metode_pembayaran" id="metodeHidden" value="transfer">

    <div class="checkout-card">

      <!-- ════ KIRI ════ -->
      <div class="form-section">

        <!-- Nama -->
        <div class="mb12">
          <label class="field-label">Nama</label>
          <div class="input-wrap">
            <i class="bi bi-person fi"></i>
            <input type="text" name="nama" class="field-input"
              placeholder="Farel"
              value="<?= htmlspecialchars($old['nama'] ?? '') ?>">
          </div>
        </div>

        <!-- No Telepon -->
        <div class="mb12">
          <label class="field-label">No Telepon</label>
          <div class="input-wrap">
            <i class="bi bi-telephone fi"></i>
            <input type="text" name="telepon" class="field-input"
              placeholder="+62-124-930834"
              value="<?= htmlspecialchars($old['telepon'] ?? '') ?>">
          </div>
        </div>

        <!-- Alamat + Kode Pos -->
        <div class="row2 mb12">
          <div>
            <label class="field-label">Alamat Pengiriman</label>
            <div class="input-wrap">
              <i class="bi bi-geo-alt fi"></i>
              <input type="text" name="alamat" class="field-input"
                placeholder="JL. Sirotul Mustaqim"
                value="<?= htmlspecialchars($old['alamat'] ?? '') ?>">
            </div>
          </div>
          <div>
            <label class="field-label">Kode Pos</label>
            <div class="input-wrap">
              <i class="bi bi-mailbox fi"></i>
              <input type="text" name="kode_pos" class="field-input"
                placeholder="369948"
                value="<?= htmlspecialchars($old['kode_pos'] ?? '') ?>">
            </div>
          </div>
        </div>

        <!-- Metode Pembayaran -->
        <p class="metode-title">Metode Pembayaran</p>
        <div class="metode-grid">
          <div class="metode-btn active" id="btnTransfer" onclick="setMetode('transfer')">
            <i class="bi bi-credit-card-2-front"></i>
            Transfer
          </div>
          <div class="metode-btn" id="btnCod" onclick="setMetode('cod')">
            <i class="bi bi-person-badge"></i>
            COD
          </div>
        </div>

        <!-- Rekening -->
        <div class="rek-bar">
          <div>
            <span class="rek-bank"><?= $rekeningBank ?></span>
            <span class="rek-no" id="rekeningNo"><?= $rekeningNo ?></span>
          </div>
          <button type="button" class="btn-salin" onclick="salinRek()">Salin</button>
        </div>

      </div><!-- /kiri -->

      <!-- ════ KANAN ════ -->
      <div class="tagihan-section">

        <p class="tagihan-title">Metode Pembayaran</p>

        <!-- Produk -->
        <div class="produk-row">
          <img class="produk-img"
            src="images/farel_perah.jpg"
            onerror="this.style.background='#d4edda';this.style.fontSize='32px';this.style.display='flex';this.style.alignItems='center';this.style.justifyContent='center';this.src='';this.alt='🐄'"
            alt="🐄">
          <div>
            <div class="produk-nama"><?= htmlspecialchars($produkNama) ?></div>
            <div class="produk-harga"><?= $fmtHarga ?></div>
          </div>
        </div>

        <!-- Total -->
        <div class="total-row">
          <span class="tl">Total Tagihan</span>
          <span class="tv"><?= $fmtTotal ?></span>
        </div>

        <!-- Upload Bukti (hanya tampil saat Transfer) -->
        <div id="uploadSection">
          <p class="upload-title">Upload Hasil Pembayaran</p>
          <label class="upload-box" for="fileBukti">
            <span class="utext"><i class="bi bi-upload"></i> Upload</span>
            <button type="button" class="btn-upload"
              onclick="event.preventDefault();document.getElementById('fileBukti').click()">Upload</button>
            <input type="file" id="fileBukti" name="bukti_pembayaran"
              accept="image/*" onchange="previewBukti(this)">
          </label>
          <img id="previewImg" class="preview-img" src="#" alt="Preview">
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn-kirim" id="btnKirim">
          Kirim Bukti Pembayaran
        </button>

      </div><!-- /kanan -->

    </div><!-- /checkout-card -->
  </form>
</div><!-- /checkout-wrap -->


<!-- ══ MODAL SUKSES TRANSFER ══ -->
<div class="modal fade" id="mTransfer" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header modal-green" style="background:#1a5c38;color:#fff;">
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

<!-- ══ MODAL SUKSES COD ══ -->
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
        <a id="waBtn" href="<?= $waLink ?>" target="_blank" class="btn-kirim wa-btn text-decoration-none" style="display:inline-flex;width:auto;padding:10px 24px;">
          <i class="bi bi-whatsapp"></i> Chat WhatsApp
        </a>
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL ERROR ══ -->
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

<!-- ══ MODAL SALIN ══ -->
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
const PHP_TYPE   = <?= json_encode($msgType) ?>;
const PHP_MSG    = <?= json_encode($errMsg)  ?>;
const PHP_WA     = <?= json_encode($waLink)  ?>;

window.addEventListener('DOMContentLoaded', () => {
  if (PHP_TYPE === 'transfer') {
    new bootstrap.Modal(document.getElementById('mTransfer')).show();
  } else if (PHP_TYPE === 'cod') {
    document.getElementById('waBtn').href = PHP_WA;
    new bootstrap.Modal(document.getElementById('mCod')).show();
  } else if (PHP_TYPE === 'error') {
    document.getElementById('errMsg').innerHTML = PHP_MSG;
    new bootstrap.Modal(document.getElementById('mError')).show();
  }
});

// Toggle metode
function setMetode(val) {
  document.getElementById('metodeHidden').value = val;
  const isTransfer = val === 'transfer';

  document.getElementById('btnTransfer').className = 'metode-btn' + (isTransfer ? ' active' : '');
  document.getElementById('btnCod').className       = 'metode-btn' + (!isTransfer ? ' active' : '');

  document.getElementById('uploadSection').style.display = isTransfer ? 'block' : 'none';

  const btn = document.getElementById('btnKirim');
  if (isTransfer) {
    btn.innerHTML = 'Kirim Bukti Pembayaran';
    btn.className = 'btn-kirim';
  } else {
    btn.innerHTML = '<i class="bi bi-whatsapp me-1"></i> Kirim ke WhatsApp';
    btn.className = 'btn-kirim wa-btn';
  }
}

// Preview gambar
function previewBukti(input) {
  const img = document.getElementById('previewImg');
  if (input.files && input.files[0]) {
    const r = new FileReader();
    r.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
    r.readAsDataURL(input.files[0]);
  }
}

// Salin rekening
function salinRek() {
  const no = document.getElementById('rekeningNo').innerText.replace(/\s/g,'');
  navigator.clipboard.writeText(no).then(() => {
    const m = new bootstrap.Modal(document.getElementById('mSalin'));
    m.show();
    setTimeout(() => m.hide(), 1600);
  });
}

// Validasi client-side
document.getElementById('mainForm').addEventListener('submit', function(e) {
  const nama   = this.querySelector('[name="nama"]').value.trim();
  const telp   = this.querySelector('[name="telepon"]').value.trim();
  const alamat = this.querySelector('[name="alamat"]').value.trim();
  const pos    = this.querySelector('[name="kode_pos"]').value.trim();
  const metode = document.getElementById('metodeHidden').value;
  const bukti  = document.getElementById('fileBukti');
  const errs   = [];

  if (!nama)   errs.push('Nama wajib diisi.');
  if (!telp)   errs.push('No Telepon wajib diisi.');
  if (!alamat) errs.push('Alamat wajib diisi.');
  if (!pos)    errs.push('Kode Pos wajib diisi.');
  if (metode === 'transfer' && !bukti.files.length)
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
