# HAY FARM - DOKUMENTASI TEKNIS LENGKAP

**Tanggal Dokumentasi**: 12 Mei 2026  
**Status Project**: Pengembangan (Semester 2)  
**Dokumentasi Untuk**: AI Debugging, Maintenance, Development

---

## 1. PROJECT OVERVIEW

### Penjelasan Singkat
**Hay Farm** adalah sistem informasi dan penjualan terintegrasi untuk peternakan berbasis web. Sistem ini menggabungkan:
- Manajemen data ternak (hewan) dengan tracking kesehatan dan reproduksi
- Platform e-commerce untuk penjualan produk (hewan, susu, rumput pakan)
- Sistem verifikasi transaksi dan manajemen inventory
- Dashboard monitoring untuk admin dan manager

### Tujuan Sistem
1. **Transparansi Data**: Menyediakan data kesehatan dan populasi hewan yang terverifikasi
2. **E-commerce**: Memudahkan pelanggan (pembeli) membeli produk langsung dari peternakan
3. **Management**: Memberikan dashboard untuk admin dan manager untuk monitoring
4. **Reporting**: Menghasilkan laporan populasi, kesehatan, dan transaksi untuk manager

### Teknologi Stack
- **Backend**: PHP Native (OOP + Procedural mix)
- **Database**: MySQL 8.0.30 (with utf8mb4)
- **Frontend**: Bootstrap 5, JavaScript, CSS
- **Library**: DOMPDF (untuk PDF export), Composer (autoload)
- **Environment**: Laragon (Local development)

### Users/Roles
1. **Pembeli (User)**: Dapat membeli produk, checkout, lihat riwayat pesanan
2. **Admin**: CRUD produk, hewan, kesehatan, verifikasi transaksi, lihat dashboard
3. **Manager**: View analytics, laporan populasi, kesehatan, transaksi

---

## 2. FOLDER STRUCTURE & EXPLANATION

```
project-root/
├── config/                          # Konfigurasi & database setup
│   └── database.php                # OOP Database class (mysqli wrapper)
│
├── process/                        # Backend business logic
│   ├── auth/
│   │   └── Auth.php               # OOP Auth class (login/register)
│   ├── handlers/                  # Action processors (CRUD handlers)
│   │   ├── auth_handler.php       # [EMPTY - tidak digunakan]
│   │   ├── produk_handler.php     # Produk CRUD (create/edit/delete)
│   │   ├── hewan_handler.php      # Hewan CRUD + foto upload
│   │   ├── kesehatan_handler.php  # Kesehatan + Reproduksi dengan transactions
│   │   ├── cart_handler.php       # Cart AJAX handler (add/update/delete)
│   │   ├── transaction.php        # Checkout & pembuat transaksi
│   │   └── verifikasi_handler.php # Admin verifikasi transaksi
│   ├── models/                    # OOP Models (database access)
│   │   ├── produk.php             # Produk CRUD operations
│   │   ├── hewan.php              # Hewan CRUD (sapi_perah, sapi_po only)
│   │   ├── kesehatan.php          # Kesehatan records (JOIN data_ternak)
│   │   ├── reproduksi.php         # Reproduksi/IB tracking
│   │   ├── keranjang.php          # Cart logic & formatting
│   │   ├── transaksi.php          # Transaction mgmt + history
│   │   ├── user.php               # [EMPTY - tidak digunakan]
│   │   ├── dashboard_admin.php    # Dashboard stats & charts
│   │   └── manager_reports.php    # Manager reporting system (DOMPDF)
│   └── utils/
│       └── session.php            # [EMPTY - tidak digunakan]
│
├── pages/                          # View/Page files
│   ├── user/                      # User-facing pages (pembeli)
│   │   ├── home.php               # Homepage + hero + featured products
│   │   ├── produk.php             # Product catalog + filters
│   │   ├── keranjang.php          # Shopping cart display
│   │   ├── chekout.php            # Checkout form (shipping + payment)
│   │   ├── riwayat_pesanan.php    # Order history + status
│   │   └── tentang_kami.php       # About page
│   ├── admin/                     # Admin pages (protected)
│   │   ├── dashboard.php          # Main dashboard + stats + charts
│   │   ├── data_hewan.php         # Hewan CRUD interface
│   │   ├── data_kesehatan.php     # Kesehatan records
│   │   ├── manajemen_produk.php   # Produk CRUD interface
│   │   ├── verifikasi_penjualan.php # Transaction verification
│   │   ├── data_hewan/            # [detail pages folder]
│   │   ├── kesehatan_hewan/       # [health records folder]
│   │   ├── produk/                # [product details folder]
│   │   └── verifikasi_penjualan/  # [verification details folder]
│   └── manager/                   # Manager pages (reports)
│       ├── index.php              # Manager dashboard
│       ├── manager_bootstrap.php  # Helper functions & factories
│       ├── detail_hewan_manager.php # Animal details for manager
│       ├── lap_populasi.php       # Population report
│       ├── lap_kesehatan.php      # Health report
│       ├── lap_transaksi.php      # Transaction report
│       └── export_report.php      # PDF export handler
│
├── components/                    # Reusable UI components
│   ├── header.php                # HTML <head> (external)
│   ├── footer.php                # Footer (external)
│   ├── navbar.php                # User navbar
│   ├── navbar_admin.php          # Admin topbar
│   ├── sidebar_admin.php         # Admin sidebar navigation
│   ├── manager/                  # Manager components
│   │   ├── header_manager.php
│   │   ├── footer_manager.php
│   │   ├── sidebar_manager.php
│   │   └── topbar_manager.php
│
├── public/                        # Static assets
│   ├── css/                       # Stylesheets (per-page)
│   │   ├── style.css              # Global styles
│   │   ├── admin_dashboard.css
│   │   ├── admin_dataHewan.css
│   │   ├── admin_dataKesehatan.css
│   │   ├── admin_manajemenProduk.css
│   │   ├── admin_verifikasiPenjualan.css
│   │   ├── home.css, produk.css, keranjang.css, etc.
│   │   └── manager/
│   ├── js/                        # JavaScript (per-page)
│   │   ├── script.js              # Global scripts
│   │   ├── dashboard_admin.js
│   │   ├── dashboard_manager.js
│   │   ├── dataHewan_admin.js
│   │   └── [other page-specific JS]
│   ├── images/
│   │   ├── logo/
│   │   └── [various background images]
│   ├── svg/
│   ├── uploads/                   # [Storage for uploads - NOT in public]
│   │   ├── hewan/                 # Animal photos
│
├── uploads/                       # Actual upload storage (outside public)
│   ├── bukti/                     # Payment proof images
│   └── hewan/                     # Animal photos
│
├── database/
│   └── hay_farm.sql              # Database schema dump
│
├── vendor/                        # Composer dependencies
│   ├── dompdf/                   # PDF generation library
│   └── autoload.php
│
├── index.php                     # Main router/entry point
├── login.php                     # Login page
├── register.php                  # Registration page
├── logout.php                    # Logout handler
├── logout_admin.php              # [Extra logout]
└── composer.json                 # Project dependencies
```

### File Penting & Fungsi

| File | Fungsi | Kriticalness |
|------|--------|--------------|
| `config/database.php` | Database connection (OOP) | CRITICAL |
| `process/auth/Auth.php` | Login/Register logic | CRITICAL |
| `index.php` | Main router + page routing | CRITICAL |
| `process/models/*.php` | CRUD operations | CRITICAL |
| `process/handlers/*.php` | Action processors | CRITICAL |
| `process/models/transaksi.php` | Transaction + stock management | CRITICAL |
| `pages/user/chekout.php` | Checkout interface | CRITICAL |
| `process/handlers/transaction.php` | Checkout processing + upload | CRITICAL |
| `pages/admin/dashboard.php` | Admin dashboard + stats | HIGH |
| `process/models/dashboard_admin.php` | Dashboard calculations | HIGH |
| `components/sidebar_admin.php` | Admin navigation | MEDIUM |
| `public/js/*.js` | Front-end interactions | MEDIUM |

---

## 3. SYSTEM ARCHITECTURE

### High-Level Architecture

```
┌─────────────────────────────────────────────────────┐
│         User (Browser)                              │
└──────────────────┬──────────────────────────────────┘
                   │
        ┌──────────▼───────────┐
        │   index.php (Router) │  ← Entry point, routing logic
        │   - Page routing     │
        │   - Layout selection │
        └──────────┬───────────┘
                   │
        ┌──────────▼─────────────────┐
        │    Pages (View Layer)      │
        │ - pages/user/*.php         │
        │ - pages/admin/*.php        │
        │ - pages/manager/*.php      │
        └──────────┬─────────────────┘
                   │
        ┌──────────▼─────────────────┐
        │  Process Handlers (Logic)  │
        │ - process/handlers/*.php   │ ← Handle actions
        │ - Validation + Processing  │
        └──────────┬─────────────────┘
                   │
        ┌──────────▼─────────────────┐
        │  Models (OOP Business)     │
        │ - process/models/*.php     │ ← DB operations
        │ - CRUD operations          │
        └──────────┬─────────────────┘
                   │
        ┌──────────▼─────────────────┐
        │  Database (MySQL)          │
        │ - Prepared statements      │
        │ - mysqli wrapper           │
        └────────────────────────────┘

Components:
  - config/database.php      → DB connection
  - process/auth/Auth.php    → Auth operations
  - components/*.php         → Layout components
  - public/js/*.js          → Frontend logic
```

### Routing System

**Entry Point**: `index.php`

```php
// index.php routing logic:
1. session_start()
2. Get ?page parameter from URL
3. Validate against WHITELIST (security)
4. Select appropriate CSS file
5. Include header.php
6. Include navbar/sidebar if needed
7. Include pages/{page}.php
8. Include footer.php if needed
```

**URL Pattern**: `index.php?page=user/produk`

**Admin/Manager Routes**: Direct file access (separate from index.php router)
- Admin: `/pages/admin/dashboard.php`
- Manager: `/pages/manager/index.php`
- Both check session + role in page header

### Authentication Flow

```
1. LOGIN (login.php)
   │
   ├─→ Form POST to login.php
   ├─→ Auth::login($email, $password)
   │   ├─→ Prepare + execute: "SELECT ... WHERE email=?"
   │   ├─→ password_verify() check
   │   └─→ If OK: session_regenerate_id(true) + set $_SESSION
   │
   └─→ Redirect based on role:
       - admin  → /pages/admin/dashboard.php
       - manager → /pages/manager/index.php
       - pembeli → /index.php (homepage)

2. REGISTER (register.php)
   │
   ├─→ Form POST to register.php
   ├─→ Validate: username, email, password
   ├─→ Check duplicate: SELECT ... WHERE username OR email
   ├─→ password_hash(PASSWORD_DEFAULT) 
   ├─→ INSERT user with role='pembeli'
   │
   └─→ Redirect to login.php

3. SESSION CHECK (on protected pages)
   │
   ├─→ if (!isset($_SESSION['login'], $_SESSION['role']))
   │   └─→ Redirect to /login.php
   │
   ├─→ if ($_SESSION['role'] !== 'admin')
   │   └─→ Unauthorized (for admin-only pages)
   │
   └─→ Continue to page

4. LOGOUT
   │
   └─→ logout.php:
       - $_SESSION = []
       - setcookie delete
       - session_destroy()
       - Redirect to index.php
```

### Session Management

**Session Variables Set During Login**:
```php
$_SESSION['id_user']    // int - primary key from user table
$_SESSION['username']   // string
$_SESSION['email']      // string
$_SESSION['role']       // enum: 'pembeli', 'admin', 'manager'
$_SESSION['login']      // bool: true
```

**Session Variables Used During Flow**:
```php
$_SESSION['cart_count']      // int - for cart badge
$_SESSION['flash_message']   // string - for alerts
$_SESSION['flash_type']      // 'success' / 'error' / 'warning'
```

**Security Notes**:
- `session_regenerate_id(true)` called on login (prevent session fixation)
- Password hashed with `PASSWORD_DEFAULT` (bcrypt)
- Prepared statements used everywhere
- Input trimmed before use

---

## 4. DATABASE STRUCTURE

### Schema Overview

```sql
Database: hayfarm
Charset: utf8mb4
Tables: 9 (8 active + 1 junction)
```

### Entity-Relationship Diagram (Text Format)

```
user (id_user PK)
  │
  ├─→ keranjang (id_user FK)
  │     │
  │     └─→ detail_keranjang (id_keranjang FK, id_produk FK)
  │
  └─→ transaksi (id_user FK)
        │
        └─→ detail_transaksi (id_transaksi FK, id_produk FK)

data_produk (id_produk PK)
  │
  └─→ data_ternak (id_hewan FK)
        │
        ├─→ data_kesehatan (id_hewan FK)
        │     │
        │     └─→ data_reproduksi (id_kesehatan FK)
        │
        └─→ data_reproduksi (id_hewan FK)
```

### Table Details

#### 1. **user** - User accounts
```sql
CREATE TABLE user (
  id_user         INT PRIMARY KEY AUTO_INCREMENT,
  username        VARCHAR(255) NOT NULL UNIQUE,
  email           VARCHAR(255) NOT NULL UNIQUE,
  password        VARCHAR(255) NOT NULL (bcrypt hash),
  role            ENUM('pembeli', 'admin', 'manager') NOT NULL
)
```
- **Purpose**: Menyimpan user accounts dengan role-based access
- **Key Field**: `role` untuk menentukan akses ke halaman
- **Constraint**: Unique pada username & email
- **Current Data**: 8 users (2 admin, 1 manager, 5 pembeli)

#### 2. **data_ternak** - Animal data (inventory)
```sql
CREATE TABLE data_ternak (
  id_hewan        INT PRIMARY KEY AUTO_INCREMENT,
  kode_hewan      VARCHAR(10) UNIQUE,
  jenis_hewan     ENUM('sapi_perah', 'sapi_po', 'kambing', 'domba'),
  berat_badan     FLOAT NOT NULL,
  jenis_kelamin   ENUM('jantan', 'betina'),
  no_kandang      VARCHAR(5),
  tgl_lahir       DATE NOT NULL,
  foto_hewan      VARCHAR(255),
  status_hewan    ENUM('produktif', 'tdk_produktif')
)
```
- **Purpose**: Menyimpan data hewan (ternak) peternakan
- **Scope**: System HANYA mendukung sapi_perah & sapi_po untuk produk penjualan
  - kambing & domba ada di enum tapi NOT USED (untuk expansion)
- **Key Fields**:
  - `kode_hewan`: Unique identifier untuk tracking
  - `foto_hewan`: Path to image file (uploads/hewan/...)
  - `status_hewan`: 'produktif' = dapat dijual, 'tdk_produktif' = tidak
- **Current Data**: 5 animals (2 sapi_perah, 2 sapi_po, 1 kambing)

#### 3. **data_kesehatan** - Health records
```sql
CREATE TABLE data_kesehatan (
  id_kesehatan    INT PRIMARY KEY AUTO_INCREMENT,
  id_hewan        INT NOT NULL FK → data_ternak,
  tgl_pemeriksaan DATE NOT NULL,
  status_kesehatan ENUM('sehat', 'observasi', 'perawatan', ''),
  diagnosis       VARCHAR(255),
  tindakan        VARCHAR(255),
  catatan         TEXT
)
```
- **Purpose**: Track kesehatan hewan per pemeriksaan
- **Scope**: Only untuk sapi_perah & sapi_po (dipilter di query)
- **Status Mapping**:
  - 'sehat' = Hewan sehat, boleh dijual
  - 'observasi' = Perlu pengamatan
  - 'perawatan' = Dalam perawatan
- **One-to-Many**: Satu hewan bisa punya multiple kesehatan records
- **Current Data**: 4 kesehatan records

#### 4. **data_reproduksi** - Reproduction/IB tracking
```sql
CREATE TABLE data_reproduksi (
  id_reproduksi   INT PRIMARY KEY AUTO_INCREMENT,
  id_kesehatan    INT FK → data_kesehatan,
  id_hewan        INT NOT NULL FK → data_ternak,
  tgl_ib          DATE NOT NULL (tanggal IB/inseminasi),
  ib_ke           INT (urutan IB ke-?),
  tgl_perkiraan   DATE (tanggal perkiraan melahirkan),
  status_ib       ENUM('berhasil', 'tdk_berhasil', 'proses')
)
```
- **Purpose**: Track status reproduksi (inseminasi buatan) hewan
- **Linked to**: Kesehatan record ketika IB dilakukan saat pemeriksaan
- **Status Values**:
  - 'berhasil' = IB berhasil (hewan bunting)
  - 'tdk_berhasil' = IB tidak berhasil
  - 'proses' = Proses IB (belum tahu hasil)
- **One-to-One or Many**: Satu hewan bisa multiple IB records
- **Current Data**: 2 reproduksi records

#### 5. **data_produk** - Product listing
```sql
CREATE TABLE data_produk (
  id_produk       INT PRIMARY KEY AUTO_INCREMENT,
  id_hewan        INT FK → data_ternak (nullable),
  jenis_produk    ENUM('hewan', 'rumput', 'susu'),
  nama_produk     VARCHAR(255) NOT NULL,
  harga           FLOAT NOT NULL,
  stok            INT NOT NULL,
  satuan          ENUM('liter', 'ton', 'ekor', ''),
  tgl_kadaluarsa  DATE NOT NULL,
  deskripsi       TEXT,
  status_produk   ENUM('terjual', 'blm_terjual')
)
```
- **Purpose**: Menyimpan produk yang dapat dijual
- **Product Types**:
  - 'hewan' = Hewan sapi (must link to data_ternak id_hewan)
  - 'susu' = Susu segar (no id_hewan needed)
  - 'rumput' = Rumput pakan (no id_hewan needed)
- **Scope**: Only 'sapi_perah' & 'sapi_po' hewan bisa dijual sebagai produk
- **Stock Management**: `stok` decremented pada checkout, `status_produk` = 'terjual' jika stok habis
- **Current Data**: 3 products (1 hewan, 1 susu, 1 rumput) - but none fully stocked

#### 6. **keranjang** - Shopping cart header
```sql
CREATE TABLE keranjang (
  id_keranjang    INT PRIMARY KEY AUTO_INCREMENT,
  id_user         INT NOT NULL FK → user,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```
- **Purpose**: Create cart for user (one cart per user, latest)
- **Lifecycle**: 
  - Created on first "add to cart" action
  - Items stored in detail_keranjang
  - Cleared after successful checkout
- **Current Data**: 2 keranjang (user 2, 3)

#### 7. **detail_keranjang** - Cart items
```sql
CREATE TABLE detail_keranjang (
  id_detail_keranjang INT PRIMARY KEY AUTO_INCREMENT,
  id_keranjang        INT NOT NULL FK → keranjang,
  id_produk           INT NOT NULL FK → data_produk,
  jumlah              INT NOT NULL,
  harga               FLOAT NOT NULL (harga saat item ditambah),
  sub_total           FLOAT NOT NULL (jumlah * harga)
)
```
- **Purpose**: Items dalam shopping cart
- **Harga Storage**: Harga disimpan untuk stability (jika admin ubah harga, cart tetap terlihat)
- **Many-to-One**: Banyak items per keranjang

#### 8. **transaksi** - Transaction/Order header
```sql
CREATE TABLE transaksi (
  id_transaksi        INT PRIMARY KEY AUTO_INCREMENT,
  id_user             INT NOT NULL FK → user,
  nama_pembeli        VARCHAR(255) NOT NULL,
  no_telp             VARCHAR(20) NOT NULL,
  alamat              TEXT NOT NULL,
  kode_pos            VARCHAR(5) NOT NULL,
  metode_pembayaran   ENUM('cod', 'transfer'),
  jumlah_pembelian    FLOAT NOT NULL,
  bukti_pembayaran    VARCHAR(255) (path to uploaded image),
  tgl_transaksi       DATE NOT NULL,
  total_tagihan       FLOAT NOT NULL,
  status_transaksi    ENUM('menunggu_verifikasi', 'telah_dikonfirmasi', 'dibatalkan')
)
```
- **Purpose**: Menyimpan order/transaksi dari user
- **Status Flow**:
  ```
  menunggu_verifikasi → [admin verifikasi] → telah_dikonfirmasi
                     ↘ [admin tolak] ↗ dibatalkan
  ```
- **Key Fields**:
  - `bukti_pembayaran`: Path to payment proof image (for transfer method)
  - `metode_pembayaran`: 'transfer' = bank transfer, 'cod' = Cash On Delivery
  - `total_tagihan`: Total harga (sama dengan sum dari detail_transaksi)
- **One-to-Many**: Satu user bisa punya multiple transaksi
- **Current Data**: 2 transaksi

#### 9. **detail_transaksi** - Transaction items
```sql
CREATE TABLE detail_transaksi (
  id_detail_transaksi INT PRIMARY KEY AUTO_INCREMENT,
  id_transaksi        INT NOT NULL FK → transaksi,
  id_produk           INT NOT NULL FK → data_produk,
  jumlah              INT NOT NULL,
  harga               FLOAT NOT NULL,
  sub_total           FLOAT NOT NULL
)
```
- **Purpose**: Items dalam transaksi (snapshot dari cart)
- **Immutable**: Data tidak berubah setelah transaksi dibuat (for history)

### Key Relationships & Constraints

| Constraint | From | To | Action |
|-----------|------|-----|--------|
| FK id_hewan | data_ternak | data_kesehatan | ON DELETE CASCADE |
| FK id_hewan | data_ternak | data_reproduksi | ON DELETE CASCADE |
| FK id_kesehatan | data_kesehatan | data_reproduksi | No action |
| FK id_produk | data_produk | detail_keranjang | No action |
| FK id_keranjang | keranjang | detail_keranjang | No action |
| FK id_user | user | keranjang | No action |
| FK id_user | user | transaksi | No action |
| FK id_produk | data_produk | detail_transaksi | No action |

---

## 5. USER FLOW (PEMBELI)

### User Journey Map

```
1. DISCOVERY
   │
   ├─→ Visit Homepage (index.php?page=user/home)
   │   ├─ Hero section + CTA "Lihat Produk"
   │   ├─ Benefit bar
   │   ├─ About section
   │   └─ Featured products carousel
   │
   └─→ Navigate to Products (index.php?page=user/produk)
       ├─ Product catalog with filters
       ├─ Search functionality
       └─ Product cards with images, price, stock

2. LOGIN CHECK
   │
   ├─ If NOT logged in:
   │  └─→ User can VIEW products but cannot add to cart
   │      (cart handler redirects to login)
   │
   └─ If logged in:
      └─→ User can add to cart

3. SHOPPING (keranjang)
   │
   ├─→ Add product to cart
   │   ├─ POST to process/handlers/cart_handler.php (AJAX)
   │   ├─ Action: 'add' → Keranjang::tambahItem()
   │   ├─ Check: produk tersedia, stok ada
   │   ├─ Logic: Jika produk sudah ada, update jumlah
   │   ├─ Response: JSON dengan cart_count
   │   └─ Update badge di navbar
   │
   ├─→ View Keranjang (index.php?page=user/keranjang)
   │   ├─ Display: Semua items di cart
   │   ├─ Actions: Update jumlah, delete item
   │   ├─ Calculate: Total price
   │   └─ CTA: "Checkout"
   │
   ├─→ Update item quantity (AJAX)
   │   ├─ POST to process/handlers/cart_handler.php
   │   ├─ Action: 'update' → Keranjang::updateJumlah()
   │   ├─ Response: JSON dengan subtotal baru & total baru
   │   └─ Update DOM real-time
   │
   └─→ Delete item dari cart (AJAX)
       ├─ POST to process/handlers/cart_handler.php
       ├─ Action: 'delete' → Keranjang::hapusItem()
       ├─ Response: JSON dengan total baru, cart_count, is_empty
       └─ Remove item dari DOM atau show empty state

4. CHECKOUT
   │
   ├─→ Proceed to Checkout (index.php?page=user/chekout)
   │   ├─ Source: ?source=cart (dari keranjang) atau ?produk_id=X&jumlah=Y (direct)
   │   ├─ Display: Order summary + items
   │   └─ Form: Shipping + Payment method
   │
   ├─→ Fill Shipping Information
   │   ├─ Nama Lengkap (required)
   │   ├─ No Telepon (required, validated)
   │   ├─ Alamat Pengiriman (required)
   │   ├─ Kode Pos (required)
   │   └─ Kota/Kabupaten (optional)
   │
   ├─→ Select Payment Method
   │   ├─ Transfer Bank (default)
   │   │  ├─ Show bank account: "BCA - a.n. Hay Farms Indonesia"
   │   │  ├─ REQUIRE: Upload bukti transfer (JPG/PNG, max 5MB)
   │   │  └─ Button "Salin" to copy account
   │   │
   │   └─ COD (Cash On Delivery)
   │      ├─ No file upload needed
   │      ├─ Info: "Hubungi admin via WhatsApp setelah order dibuat"
   │      └─ Status: menunggu_verifikasi (admin akan konfirmasi)
   │
   └─→ Submit Checkout Form
       ├─ POST to process/handlers/transaction.php
       ├─ Validation (Handler level):
       │  ├─ All shipping data required
       │  ├─ No. HP regex: ^08\d{8,12}$
       │  └─ Transfer: bukti file required + image format check
       │
       ├─ Business Logic:
       │  ├─ Get items: from cart atau direct produk
       │  ├─ Upload bukti to uploads/bukti/ (if transfer)
       │  ├─ BEGIN TRANSACTION
       │  ├─ Transaksi::buatTransaksi() - create order header
       │  ├─ Create detail_transaksi for each item
       │  ├─ Decrease stok in data_produk (stok - jumlah)
       │  ├─ IF stok <= 0: status_produk = 'terjual'
       │  ├─ IF source = 'cart': Clear keranjang items
       │  └─ COMMIT TRANSACTION
       │
       ├─ Response:
       │  ├─ Success: Redirect to riwayat_pesanan
       │  │          Show: "Pesanan berhasil dibuat"
       │  │
       │  └─ Error: Redirect back to chekout
       │            Show error message

5. RIWAYAT PESANAN (History)
   │
   ├─→ View Order History (index.php?page=user/riwayat_pesanan)
   │   ├─ Display: All transaksi for logged-in user
   │   ├─ Order by: tgl_transaksi DESC (newest first)
   │   ├─ Show: Date, status badge, items, total
   │   ├─ Filter: By status (dropdown)
   │   └─ Search: By product name
   │
   ├─→ Order Status Display
   │   ├─ menunggu_verifikasi
   │   │  └─ Badge: Yellow/Warning (clock icon)
   │   │
   │   ├─ telah_dikonfirmasi
   │   │  └─ Badge: Green/Success (checkmark icon)
   │   │
   │   └─ dibatalkan
   │      └─ Badge: Red/Danger (X icon)
   │
   └─→ View Order Details
       ├─ Click order to expand
       ├─ Show: All items in transaksi
       ├─ Show: Product details (foto, nama, harga, jumlah)
       ├─ Show: Shipping address
       └─ Show: Total amount
```

### User Cart Flow (Detailed)

```
ADD TO CART Flow:
  1. User click "Tambah ke Keranjang" button on produk page
  2. Button triggers JavaScript:
     - GET produk ID & jumlah
     - POST AJAX to process/handlers/cart_handler.php
     - Headers: X-Requested-With: XMLHttpRequest
  3. Handler validates & processes:
     - Check login status
     - Check produk exists & available
     - Check stok
     - Keranjang::tambahItem($userId, $produkId, $qty)
       ├─ Get/create keranjang record
       ├─ Check if item already in cart
       ├─ IF yes: UPDATE jumlah (capped at stok)
       ├─ IF no: INSERT new detail_keranjang
       └─ Return cart_count
  4. Response: JSON {status, message, cart_count}
  5. Update DOM:
     - Update cart badge di navbar
     - Show success toast

CART VIEW Flow:
  1. User click cart icon or go to keranjang page
  2. Page loads & fetch items via Keranjang::getItems($userId)
  3. Display logic:
     - JOIN detail_keranjang → data_produk → data_ternak
     - Get gambar for each item
     - Format harga & subtotal
     - Calculate total
  4. For each item display:
     - Produk image
     - Produk nama & jenis
     - Current jumlah (in input)
     - Harga per unit
     - Subtotal
     - Delete button

UPDATE QUANTITY Flow:
  1. User change quantity input
  2. JavaScript onchange event triggered
  3. POST AJAX to cart_handler.php
     - Action: 'update'
     - id_detail_keranjang
     - new_jumlah
  4. Handler:
     - Keranjang::updateJumlah($userId, $id_detail, $jumlah)
     - Validate stok
     - UPDATE detail_keranjang (jumlah, harga, sub_total)
     - Response: JSON {new_subtotal, new_total}
  5. Update DOM real-time (no page refresh)

DELETE FROM CART Flow:
  1. User click delete icon on item
  2. POST AJAX to cart_handler.php
     - Action: 'delete'
     - id_detail_keranjang
  3. Handler:
     - Keranjang::hapusItem($userId, $id_detail)
     - DELETE FROM detail_keranjang WHERE ...
     - Response: JSON {new_total, is_empty}
  4. Update DOM:
     - Remove item row
     - Update total
     - IF is_empty: Show "Keranjang kosong" message
```

---

## 6. ADMIN FLOW

### Admin Dashboard Overview

```
ADMIN ENTRY: /pages/admin/dashboard.php

Protection:
  - Check: isset($_SESSION['login'], $_SESSION['role'])
  - Check: in_array($_SESSION['role'], ['admin', 'manager'])
  - Else: Redirect to login.php

Components:
  - sidebar_admin.php   → Navigation menu (vertical)
  - navbar_admin.php    → Top bar with user info
  - Main content area   → Dynamic per page
```

### Admin Dashboard Main Page

```
Display Elements:
  1. STATS CARDS (4 cards)
     ├─ Jumlah Produk (blm_terjual)
     ├─ Jumlah Diverifikasi (transaksi telah_dikonfirmasi)
     ├─ Jumlah Hewan (produktif)
     └─ Hewan Sakit per Hari (kesehatan != 'sehat' tgl_pemeriksaan=TODAY)

  2. GRAFIK TRANSAKSI
     ├─ Monthly verified transactions
     ├─ Select tahun (dropdown)
     ├─ Line chart: bulan vs count

  3. NOTIFICATION CARDS (4-5 cards)
     ├─ Vaksinasi diperlukan
     │  └─ Count: hewan dengan status 'perawatan' atau 'observasi'
     │
     ├─ Produk Kedaluwarsa
     │  └─ Count: produk dengan tgl_kadaluarsa < TODAY
     │
     ├─ Hewan Hamil Bulan Ini
     │  └─ Count: data_reproduksi dengan status_ib='proses' & tgl_perkiraan this month
     │
     ├─ Transaksi Menunggu Verifikasi
     │  └─ Count: transaksi dengan status='menunggu_verifikasi'
     │
     └─ [Other notifications]
```

### CRUD Operations

#### A. MANAJEMEN PRODUK

```
INTERFACE: /pages/admin/manajemen_produk.php

DISPLAY:
  - Table: Semua produk (status = blm_terjual atau terjual)
  - Columns: ID, Nama, Jenis, Harga, Stok, Satuan, Status, Actions
  - Buttons: Edit, Delete, (View detail?)

ADD PRODUK:
  1. Click "Tambah Produk" button
  2. Show modal form:
     - Jenis Produk (select: hewan, susu, rumput)
       ├─ IF hewan: Show animal selector (data_ternak - sapi_perah, sapi_po only)
       ├─ ELSE: Satuan auto-select (susu=liter, rumput=ton)
     - Nama Produk (text)
     - Harga (number)
     - Stok (number)
     - Satuan (select: liter, ton, ekor) - auto if type set
     - Tgl Kadaluarsa (date picker)
     - Deskripsi (textarea)
     - Status (select: tersedia, tidak_tersedia)

  3. Form POST to process/handlers/produk_handler.php
     - Action: 'tambah'
     - Data: [jenis_produk, nama_produk, id_hewan, harga, stok, satuan, tgl_kadaluarsa, deskripsi, status_produk]

  4. Handler Produk::create($data):
     - Validate required fields
     - Validate harga > 0
     - Validate jenis_produk in enum
     - IF hewan: Validate id_hewan adalah sapi_perah/sapi_po
     - INSERT into data_produk
     - Return [status, message]

  5. Response:
     - Flash message (success/error)
     - Redirect to manajemen_produk.php
     - Table refresh with new data

EDIT PRODUK:
  1. Click "Edit" on product row
  2. Show modal form (pre-filled with current data)
  3. Form POST to produk_handler.php
     - Action: 'edit'
     - id_produk
     - Updated data
  4. Handler Produk::update($id, $data):
     - Validate id exists
     - Update field yang submitted
     - Validate same as create
     - Execute UPDATE
  5. Response: Flash message, page refresh

DELETE PRODUK:
  1. Click "Delete" button (with confirmation)
  2. Form POST to produk_handler.php
     - Action: 'hapus'
     - id_produk
  3. Handler Produk::delete($id):
     - Check if produk already in detail_transaksi
     - IF yes: Show error "Cannot delete, use status instead"
     - IF no: DELETE from data_produk
  4. Response: Flash message, page refresh

Status Management:
  - 'blm_terjual' = Available for sale, show in public catalog
  - 'terjual'     = Out of stock, hide from public, keep record
```

#### B. DATA HEWAN (CRUD)

```
INTERFACE: /pages/admin/data_hewan.php

DISPLAY:
  - Table: Semua hewan (status produktif/tidak, jenis sapi_perah/sapi_po)
  - Columns: Kode, Jenis, Kelamin, Kandang, Berat, Umur, Status, Actions
  - Buttons: Edit, Delete, View Health, View Reproduction

ADD HEWAN:
  1. Click "Tambah Hewan"
  2. Show form:
     - Kode Hewan (text, auto-uppercase) - required, unique
     - Jenis Hewan (select: sapi_perah, sapi_po)
     - Berat Badan (number) - required, > 0
     - Jenis Kelamin (select: jantan, betina)
     - No Kandang (text)
     - Tgl Lahir (date picker)
     - Foto Hewan (file upload, optional)
     - Status Hewan (select: produktif, tdk_produktif)
  3. POST to process/handlers/hewan_handler.php
     - Action: 'create'
  4. Handler uploads foto to uploads/hewan/:
     - Allowed ext: jpg, jpeg, png, webp
     - Max size: 2MB
     - Filename: hewan_{timestamp}_{uniqid}.ext
     - Store path: uploads/hewan/{filename}
  5. Hewan::create($data):
     - Validate all required
     - Check duplicate kode_hewan
     - Validate enum values
     - INSERT to data_ternak
  6. Response: Flash message, table refresh

EDIT HEWAN:
  1. Click "Edit" on hewan row
  2. Show form pre-filled
  3. Same as add hewan
  4. Handler Hewan::update($id, $data):
     - COALESCE foto upload: jika user tidak upload foto baru, keep old
     - UPDATE data_ternak
  5. Response: Flash message, refresh

DELETE HEWAN:
  1. Click "Delete"
  2. Handler Hewan::delete($id):
     - Check FK: data_kesehatan, data_reproduksi, data_produk
     - IF any related records exist: Cannot delete error
     - ELSE: DELETE from data_ternak
  3. Response: Flash message or error

Note: Only sapi_perah & sapi_po can be managed as products
```

#### C. DATA KESEHATAN

```
INTERFACE: /pages/admin/data_kesehatan.php

DISPLAY:
  - Table: Semua kesehatan records
  - Columns: Kode Hewan, Tanggal, Status, Diagnosis, Tindakan, Actions
  - JOIN: data_kesehatan ← data_ternak ← data_reproduksi

ADD KESEHATAN:
  1. Click "Tambah Kesehatan"
  2. Show form:
     - Hewan (select from data_ternak - sapi only)
     - Tgl Pemeriksaan (date picker)
     - Status Kesehatan (select: sehat, observasi, perawatan)
     - Diagnosis (text, required IF status != 'sehat')
     - Tindakan (text, required IF status != 'sehat')
     - Catatan (textarea)
     - [OPTIONAL IB Data]:
       ├─ Tgl IB (date picker)
       ├─ IB ke (number)
       ├─ Tgl Perkiraan (date picker)
       └─ Status IB (select: berhasil, tdk_berhasil, proses)
  3. POST to kesehatan_handler.php
     - Action: 'create'
  4. BEGIN TRANSACTION (if IB data included):
     - Kesehatan::create() → INSERT data_kesehatan
     - Get new id_kesehatan via $conn->insert_id
     - Reproduksi::create() → INSERT data_reproduksi (if IB data filled)
     - COMMIT or ROLLBACK
  5. Response: Flash message

UPDATE/DELETE: Similar pattern
```

#### D. VERIFIKASI PENJUALAN

```
INTERFACE: /pages/admin/verifikasi_penjualan.php

DISPLAY:
  - Table: Transaksi (status = menunggu_verifikasi)
  - Columns: ID, User, Tanggal, Items, Total, Metode, Actions
  - Buttons: Lihat Detail, Verifikasi, Tolak
  - Stats: Pending count, Verified count, Rejected count

VERIFY TRANSACTION:
  1. Click "Verifikasi" on transaksi
  2. Show detail view:
     - Transaction header: ID, user, date, total
     - Shipping info: nama, alamat, telp
     - Items table: produk, jumlah, harga, subtotal
     - Payment method: Transfer atau COD
     - IF Transfer: Show bukti image
  3. Form POST to verifikasi_handler.php
     - Action: 'verifikasi' (or 'tolak')
     - id_transaksi
  4. Handler Transaksi::updateStatusTransaksi($id, $status):
     - Update status_transaksi = 'telah_dikonfirmasi'
     - Response: [status, message]
  5. Response: Flash message, redirect to verifikasi page

REJECT TRANSACTION:
  1. Click "Tolak" button
  2. Form POST same as verify but action='tolak'
  3. Handler:
     - Update status = 'dibatalkan'
     - Restore stok: for each item in detail_transaksi
       ├─ stok += jumlah
       └─ status_produk = 'blm_terjual'
     - Transaksi tidak dihapus (for history)
  4. Response: Flash message

Note: updateStatusTransaksi() incomplete in code, needs implementation
```

---

## 7. MANAGER FLOW

### Manager Dashboard

```
INTERFACE: /pages/manager/index.php → manager_bootstrap.php (helpers)

Protection:
  - Check: isset($_SESSION['role']) && $_SESSION['role'] == 'manager'
  - Else: Redirect to login

Components:
  - header_manager.php    → HTML head
  - sidebar_manager.php   → Navigation
  - topbar_manager.php    → Top bar
  - footer_manager.php    → Footer

Data Source:
  - manager_make_report() factory function
  - ManagerReportFactory creates report objects
  - Report objects calculate aggregations
```

### Dashboard Charts

```
1. POPULASI HEWAN (Pie Chart)
   - Data: Count by jenis_hewan
   - Query: SELECT jenis_hewan, COUNT(*) FROM data_ternak GROUP BY jenis_hewan
   - Chart JS: Pie chart

2. TREND PEMERIKSAAN KESEHATAN (Line Chart)
   - Data: Count kesehatan per status
   - Query: SELECT status_kesehatan, COUNT(*) FROM data_kesehatan GROUP BY status_kesehatan
   - Chart JS: Line chart

3. STATUS REPRODUKSI HEWAN (Bar Chart)
   - Data: Count by status_ib
   - Query: SELECT status_ib, COUNT(*) FROM data_reproduksi GROUP BY status_ib
   - Chart JS: Bar chart

4. TREND PENJUALAN (Line Chart)
   - Data: Monthly verified transaksi
   - Query: SELECT MONTH(tgl_transaksi), COUNT(*) FROM transaksi WHERE status='telah_dikonfirmasi' GROUP BY MONTH(...)
   - Chart JS: Line chart
```

### Manager Reports

```
Available Reports:
  1. LAP_POPULASI.PHP (Population Report)
     - Count animals by type
     - Status (produktif/tidak)
     - Can export to PDF
  2. LAP_KESEHATAN.PHP (Health Report)
     - Health records by animal
     - Status breakdown
     - Timeline
  3. LAP_TRANSAKSI.PHP (Transaction Report)
     - Revenue by period
     - Items sold
     - Verification stats
  4. DETAIL_HEWAN_MANAGER.PHP (Animal Detail)
     - Individual animal profile
     - Health history
     - Reproduction history

Report System:
  - Uses DOMPDF for PDF export
  - Abstract classes for consistent report interface
  - Filters by month/year possible
  - Pagination for large datasets
```

---

## 8. CODING STRUCTURE ANALYSIS

### Architecture Pattern

```
Hybrid Architecture:
  ├─ OOP (Object-Oriented)
  │   └─ Models: Produk, Hewan, Kesehatan, Keranjang, Transaksi, etc.
  │      └─ CRUD methods, validation, data aggregation
  │   └─ Auth class for login/register
  │   └─ Database wrapper for mysqli
  │
  ├─ Procedural (Functional)
  │   └─ Page files (PHP + HTML mixed)
  │   └─ Handler files (some procedural, some call OOP)
  │   └─ Inline business logic in pages
  │
  └─ Separation of Concerns (Partial)
      ├─ GOOD: Models separated from views
      ├─ GOOD: Handlers separate action logic
      ├─ BAD: Some validation logic duplicated
      ├─ BAD: Some business logic in pages
      └─ BAD: Some business logic in handlers
```

### OOP Structure - Models

```
Each model class follows pattern:

class ModelName {
    private $conn;
    private $table = 'table_name';

    public __construct($db) {
        $this->conn = $db;
    }

    // CRUD Methods:
    public function getAll()         { /* SELECT * FROM $table */ }
    public function getById($id)     { /* SELECT ... WHERE id=? */ }
    public function create($data)    { /* INSERT INTO $table */ }
    public function update($id, $data) { /* UPDATE $table */ }
    public function delete($id)      { /* DELETE FROM $table */ }

    // Custom methods
    public function getBy...($param) { /* Specialized queries */ }
    private function validate...()   { /* Internal validation */ }
}

Prepared Statements:
  - All queries use prepared statements with bind_param()
  - Type strings: s (string), i (integer), d (double)
  - Example: $stmt->bind_param("issdissss", $var1, $var2, ...)
```

### Handler Structure - Action Processors

```
Handler Pattern:

1. SECURITY CHECK
   - Check request method: if ($_SERVER['REQUEST_METHOD'] !== 'POST')
   - Check action param: if (!isset($_POST['action']))
   - Check login (for user actions): if (!isset($_SESSION['login']))

2. INPUT COLLECTION
   - Collect $_POST data
   - Optional file upload ($_FILES)
   - Trim/sanitize inputs

3. FILE HANDLING (if needed)
   - Validate file: ext, size, MIME type
   - Create upload directory if not exists
   - Generate unique filename
   - move_uploaded_file()

4. BUSINESS LOGIC
   - Call model methods (CRUD)
   - Handle transactions (begin/commit/rollback)
   - Aggregate results

5. RESPONSE HANDLING
   - AJAX check: isset($_SERVER['HTTP_X_REQUESTED_WITH'])
   - AJAX response: header('Content-Type: application/json'), echo json_encode()
   - HTTP response: $_SESSION flash message + header redirect

6. ERROR HANDLING
   - try-catch for exceptions
   - Set $_SESSION['flash_message'] & ['flash_type']
   - Rollback transaction on error
```

### Validation Strategy

```
Two-Level Validation:

LEVEL 1: Handler Validation (INPUT)
  - Early form validation
  - Required field check
  - Basic format check (email, phone)
  - File upload validation (ext, size)

LEVEL 2: Model Validation (BUSINESS)
  - Enum validation
  - Duplicate check
  - Foreign key existence check
  - Complex business rules
  - Return [status, message] for handler

Problem: Some validation logic is mixed
  - Some in handlers, some in models
  - Inconsistent validation approach
  - Should standardize on model-based validation
```

### Database Connection Pattern

```
Database Wrapper (OOP):

class Database {
    private $host, $user, $pass, $db;
    public mysqli $conn;

    public __construct() {
        $this->conn = new mysqli(...);
        $this->conn->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli {
        return $this->conn;
    }
}

Usage in handlers/pages:
  $database = new Database();
  $db = $database->getConnection();
  // Now $db is mysqli object for passing to models

Pattern Issue:
  - Sometimes: $db_conn = new Database(); $connection = $db_conn->getConnection();
  - Sometimes: $database = new Database(); $db = $database->getConnection();
  - Inconsistent variable naming (db_conn vs database vs $db)
```

### Code Organization Issues

| Issue | Example | Impact |
|-------|---------|--------|
| Mixed concerns | Business logic in page files | Hard to test & maintain |
| Duplicated validation | Same checks in handler + model | Code duplication, inconsistency |
| Inconsistent naming | $db, $connection, $db_conn, $database | Confusing, error-prone |
| Procedural in handlers | Direct SQL vs model calls | Some handlers use models, some don't |
| Magic numbers/strings | 'uploads/hewan/', hardcoded paths | Brittle to changes |
| Inline HTML in PHP | Form rendering | Separate view files would be cleaner |
| No controller layer | Models called directly in handlers | No central orchestration |
| No dependency injection | New Database() in each handler | Tight coupling |

### Reusable Components

```
GOOD Components:

1. Models:
   - Well-structured CRUD
   - Reusable across handlers
   - Can be instantiated with any mysqli connection

2. Auth class:
   - Single responsibility (login/register)
   - Used in multiple places (login.php, register.php)

3. Format functions:
   - formatRupiah() in multiple files (keranjang, transaksi, riwayat)
   - Should be centralized in utils

POOR Components:

1. Duplicate form rendering:
   - Similar forms in different handlers
   - No form builder or template system

2. Duplicate HTML:
   - Header.php, footer.php included in each page
   - Could use layout inheritance

3. Flash message display:
   - Duplicated in riwayat_pesanan.php, chekout.php, etc.
   - Should be component
```

---

## 9. BUG & TECHNICAL DEBT ANALYSIS

### Critical Bugs Found

#### Bug #1: updateStatusTransaksi() Method Incomplete
**File**: `process/models/transaksi.php` (line ~200)  
**Severity**: CRITICAL  
**Issue**:
```php
public function updateStatusTransaksi($id_transaksi, $status, $admin_id): array
{
    // Validasi status
    $allowed_status = ['telah_dikonfirmasi', 'dibatalkan'];
    if (!in_array($status, $allowed_status)) {
        return ['status' => false, 'message' => 'Status tidak valid'];
    }
    // ... REST OF METHOD IS MISSING
}
```
**Impact**: Admin cannot verify or reject transaksi - verifikasi_penjualan.php page will throw error  
**Fix Needed**: Complete the method implementation:
```php
// Should have:
- UPDATE transaksi SET status_transaksi = ? WHERE id_transaksi = ?
- IF dibatalkan: Call restoreStokProduk() to reverse stock changes
- Return ['status' => true/false, 'message' => '...']
```

#### Bug #2: Session.php File Empty
**File**: `process/utils/session.php`  
**Severity**: LOW  
**Issue**: File exists but completely empty, not used  
**Impact**: Minor - no impact since session handling is done inline in pages  
**Fix**: Delete file or implement session utilities

#### Bug #3: Auth_Handler.php File Empty
**File**: `process/handlers/auth_handler.php`  
**Severity**: LOW  
**Issue**: File exists but empty, auth handled directly in login.php/register.php  
**Impact**: Confusing code organization  
**Fix**: Delete or move auth logic here for cleaner structure

#### Bug #4: User.php Model Not Implemented
**File**: `process/models/user.php`  
**Severity**: MEDIUM  
**Issue**: File exists but empty, user CRUD not available for admin  
**Impact**: Admin cannot manage users (create/edit/delete), only hardcoded users work  
**Fix**: Implement user management CRUD (if needed)

#### Bug #5: Incomplete Kesehatan Handler Normalization
**File**: `process/handlers/kesehatan_handler.php` (lines 40-90)  
**Severity**: HIGH  
**Issue**: Status normalization code appears twice with different logic
```php
// Appears twice:
1. First block (line 40-45): match() statement
2. Second block (line 60+): conditional logic
```
**Impact**: Confusing, potential status value mismatch  
**Fix**: Use single normalization at top, remove duplicates

#### Bug #6: Register Page Procedural (No CSRF Protection)
**File**: `register.php`  
**Severity**: MEDIUM  
**Issue**: No CSRF token validation, form directly executes SQL  
**Impact**: Vulnerable to CSRF attacks  
**Fix**: Add CSRF token validation

---

### High Priority Technical Debt

#### Debt #1: No Input Sanitization/Escaping
**Severity**: HIGH  
**Issue**: User input used in HTML output without proper escaping
```php
// Example in produk.php
<?= $produk['nama_produk'] ?>  // Unescaped!
```
**Impact**: Potential XSS vulnerabilities  
**Fix**: Use htmlspecialchars() everywhere user data is echoed:
```php
<?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8') ?>
```

#### Debt #2: Upload Validation Not Consistent
**Severity**: MEDIUM  
**Issue**: 
- `hewan_handler.php`: Validates ext + size in handler
- `transaction.php`: Also does validation
- No centralized upload utility
**Impact**: Code duplication, inconsistent validation rules  
**Fix**: Create `process/utils/upload.php` for centralized upload handling

#### Debt #3: Path/URL Hardcoded
**Severity**: MEDIUM  
**Issue**: Paths hardcoded in multiple places:
```php
// In various files:
'/TryHayFarm/pages/admin/...'
'../../'
'../../index.php?page=user/...'
```
**Impact**: Breaks on different server setups, hard to move project  
**Fix**: Define BASE_URL constant in config, use throughout

#### Debt #4: No Error Logging
**Severity**: MEDIUM  
**Issue**: Errors caught in try-catch but not logged to file  
**Impact**: Debugging production issues difficult  
**Fix**: Implement error_log() or Monolog integration

#### Debt #5: No Database Connection Error Handling
**Severity**: MEDIUM  
**Issue**: Database connection errors shown to user or silently fail
**Impact**: User sees raw error messages, security risk  
**Fix**: Catch connection errors, display friendly message

#### Debt #6: Mixed OOP and Procedural in Same Handler
**Severity**: LOW  
**Issue**: Some handlers use models (OOP), others write SQL inline  
**Fix**: Standardize on model-based approach everywhere

---

### Code Quality Issues

| Issue | Location | Priority | Suggested Fix |
|-------|----------|----------|---------------|
| Duplicate getAll() JOINs | kesehatan.php, transaksi.php | MEDIUM | Abstract JOIN logic |
| String status values | Multiple files | MEDIUM | Create StatusEnum constants |
| Magic numbers | File upload size: 2MB, 5MB | MEDIUM | Define SIZE_LIMITS constant |
| Duplicate format functions | formatRupiah() | LOW | Create utils/format.php |
| Inline SQL in some pages | produk.php | MEDIUM | Always use models |
| No pagination | Large data queries | MEDIUM | Add LIMIT OFFSET |
| No rate limiting | Cart/checkout handlers | MEDIUM | Add request throttling |
| No input length validation | User input | MEDIUM | Validate string length |

---

### Potential Runtime Issues

#### Issue #1: Stock Race Condition
**Scenario**: Two users add same product to cart simultaneously
**Current Logic**: Only checks stok, doesn't lock row  
**Problem**: Both could add more than available stok  
**Fix**: Use transaction with LOCK IN SHARE MODE

#### Issue #2: Stok Not Updated on Cart Clear
**Scenario**: User adds to cart → cart cleared without checkout  
**Current Logic**: Stock only decreases on checkout  
**Impact**: Stock correct (no bug), but user sees "stok habis" when adding to cart if others added first  
**Note**: This is actually correct behavior

#### Issue #3: Photo Upload Path Issues
**Current**: Photo path stored as `uploads/hewan/...`  
**Problem**: getGambarProduk() tries multiple path combinations, fragile  
**Impact**: Photos may not load on different servers  
**Fix**: Store absolute path or URL in database

#### Issue #4: Kesehatan Handler Transaction Duplication
**File**: kesehatan_handler.php line 41-150  
**Issue**: Same create logic repeated twice with slight variations  
**Impact**: Bug fix needs to be applied in two places  
**Fix**: Refactor to single implementation

---

## 10. SECURITY & VALIDATION ANALYSIS

### SQL Injection Risk Assessment

**Overall Status**: ✅ SAFE - All queries use prepared statements

```php
// SAFE - Using prepared statements
$stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// GOOD - All handlers follow this pattern
```

**Risk Areas**: NONE - No direct SQL concatenation found

### Session Security

**Session Handling**:
```php
✅ session_regenerate_id(true)  - Called on login
✅ $_SESSION['login'] = true     - Explicit flag set
✅ session_destroy()             - Called on logout
✅ Cookie params set             - Secure, httponly
❌ No CSRF tokens               - Missing in forms
❌ No session timeout           - Sessions never expire
```

**Improvement Needed**:
1. Add CSRF token validation to all POST forms
2. Add session timeout (30 min inactivity)
3. Add IP address verification

### Input Validation

**Current State**:
```
✅ Email validation             - FILTER_VALIDATE_EMAIL
✅ Required field checks        - isset() checks
✅ Type casting                 - (int), (float) casts
✅ Enum validation              - in_array() for enum fields
✅ File upload validation       - ext, size, MIME type

❌ String length limits        - Not validated
❌ HTML escaping               - Not consistent
❌ XSS protection              - Incomplete
❌ Phone number regex           - Basic (08xx check)
❌ Rate limiting               - Not implemented
```

### Validation Gaps

#### Gap #1: No HTML Escaping in Output
```php
// UNSAFE - User input echoed directly
<?php echo $_POST['nama_pembeli']; ?>

// SAFE - Should be:
<?php echo htmlspecialchars($_POST['nama_pembeli'], ENT_QUOTES, 'UTF-8'); ?>
```

#### Gap #2: File Upload Security
```php
// Validated ext but not MIME type
// Solution: Use mime_content_type() or getimagesize()

// No scan for malicious files
// Solution: Add file integrity check
```

#### Gap #3: No Rate Limiting
```php
// Same user can spam add-to-cart requests
// Solution: Implement rate limiting (max 10 requests/min)
```

### Protected Pages - Role-Based Access

**Admin Pages**:
```php
✅ session check
✅ role == 'admin' check
✅ Redirect to login if failed
```

**Manager Pages**:
```php
✅ session check
✅ role == 'manager' check
✅ Redirect to login if failed
```

**User Pages**:
```php
✅ session check for cart/checkout
✅ Redirect to login if not logged in
⚠️ But homepage/produk/tentang accessible without login
```

---

## 11. IMPROVEMENT SUGGESTIONS

### Priority 1: Critical Fixes (Do Immediately)

1. **Complete updateStatusTransaksi() Implementation**
   - Current: Method incomplete, verifikasi not working
   - Fix: Implement UPDATE query + stock restore logic
   - Time: 30 minutes
   - Priority: CRITICAL

2. **Add HTML Output Escaping**
   - Current: XSS vulnerability possible
   - Fix: Wrap all `<?= $variable ?>` with htmlspecialchars()
   - Time: 1-2 hours
   - Priority: HIGH

3. **Add CSRF Token to All Forms**
   - Current: No CSRF protection
   - Fix: Generate token in session, validate in handlers
   - Time: 1 hour
   - Priority: HIGH

### Priority 2: Security Improvements

1. **Implement Input Length Validation**
   - Add max length checks for text fields
   - Time: 1 hour

2. **Add File MIME Type Validation**
   - Not just ext check, validate actual file type
   - Time: 30 minutes

3. **Implement Rate Limiting**
   - Limit requests per user per minute
   - Time: 1-2 hours

4. **Add Session Timeout**
   - Expire session after 30 min inactivity
   - Time: 30 minutes

### Priority 3: Code Quality Improvements

1. **Centralize Format Functions**
   - Create `process/utils/format.php`
   - Move formatRupiah(), formatDate(), etc.
   - Time: 1 hour

2. **Implement Proper Error Logging**
   - Add error_log() calls to handlers
   - Create logs/ directory
   - Time: 1 hour

3. **Refactor Duplicate Kesehatan Handler Code**
   - Remove duplicate status normalization
   - Create helper function
   - Time: 30 minutes

4. **Add Database Query Logging (Development)**
   - Log slow queries to identify N+1 problems
   - Time: 1 hour

### Priority 4: Feature & Refactor Improvements

1. **Implement User Management CRUD (Admin)**
   - Create users, edit, delete
   - Assign roles
   - Time: 2-3 hours

2. **Add Transaction Pagination**
   - Currently loads all transactions in memory
   - Add LIMIT OFFSET pagination
   - Time: 1 hour

3. **Add Image Optimization**
   - Compress uploaded images
   - Generate thumbnails
   - Time: 2 hours

4. **Create Consistent Error Handling**
   - Standardize exception handling
   - Create custom exceptions
   - Time: 2 hours

---

## 12. IMPORTANT FILE MAP & DEPENDENCIES

### Dependency Graph

```
index.php (ENTRY)
├─→ config/database.php
│   └─ Database OOP class
├─→ components/header.php
├─→ components/navbar.php
├─→ pages/user/home.php
│   └─ No models
├─→ pages/user/produk.php
│   ├─ config/database.php
│   ├─ process/models/produk.php
│   │  └─ Database connection
│   └─ public/js/script.js
├─→ pages/user/keranjang.php
│   ├─ config/database.php
│   ├─ process/models/keranjang.php
│   └─ process/models/produk.php
├─→ pages/user/chekout.php
│   ├─ config/database.php
│   ├─ process/models/keranjang.php
│   └─ process/models/produk.php
└─→ components/footer.php

pages/admin/dashboard.php
├─→ config/database.php
├─→ process/models/dashboard_admin.php
├─→ components/sidebar_admin.php
├─→ components/navbar_admin.php
├─→ public/js/dashboard_admin.js
└─→ public/css/admin_dashboard.css

pages/admin/manajemen_produk.php
├─→ config/database.php
├─→ process/models/produk.php
└─→ process/handlers/produk_handler.php (form target)
    ├─ config/database.php
    └─ process/models/produk.php

process/handlers/cart_handler.php
├─ config/database.php
├─ process/models/keranjang.php
├─ process/models/produk.php
└─ Return JSON (AJAX)

process/handlers/transaction.php
├─ config/database.php
├─ process/models/keranjang.php
├─ process/models/transaksi.php
└─ uploads/bukti/ (file storage)

login.php
├─ config/database.php
├─ process/auth/Auth.php
└─ Redirect to pages/admin/dashboard.php or index.php

register.php
├─ config/database.php
└─ process/auth/Auth.php (implicit, via function call)

pages/manager/index.php
├─ pages/manager/manager_bootstrap.php
│  ├─ config/database.php
│  ├─ process/models/manager_reports.php
│  └─ vendor/autoload.php (DOMPDF)
├─ components/manager/header_manager.php
├─ components/manager/sidebar_manager.php
├─ components/manager/topbar_manager.php
└─ public/js/dashboard_manager.js
```

### File Modification Impact

| File | If Modified | Impact |
|------|----------|--------|
| `config/database.php` | Database connection broken everywhere | CRITICAL - ALL |
| `process/auth/Auth.php` | Login/register broken | CRITICAL - login.php, register.php |
| `process/models/produk.php` | Produk listing broken | HIGH - produk.php, handlers |
| `process/models/transaksi.php` | Transaksi handling broken | CRITICAL - checkout, admin |
| `process/models/keranjang.php` | Cart broken | HIGH - keranjang.php, checkout |
| `index.php` | Entire user site broken | CRITICAL - all pages |
| `pages/admin/dashboard.php` | Admin page broken | MEDIUM - admin only |
| `components/sidebar_admin.php` | Admin navigation broken | MEDIUM - admin pages |
| `process/handlers/transaction.php` | Checkout broken | CRITICAL |
| `process/handlers/verifikasi_handler.php` | Verification broken | CRITICAL |

### Critical Code Sections

```
1. Stock Management
   - process/models/keranjang.php: tambahItem()
   - process/models/transaksi.php: kurangiStokProduk()
   - process/handlers/transaction.php: stock decrease logic
   └─ MUST be synchronized

2. Transaction Creation
   - process/handlers/transaction.php
   - process/models/transaksi.php: buatTransaksi()
   - BEGIN TRANSACTION..COMMIT..ROLLBACK pattern
   └─ MUST maintain atomicity

3. Session Management
   - index.php: session_start()
   - pages/admin/*.php: session checks
   - process/auth/Auth.php: session_regenerate_id()
   └─ MUST be consistent

4. File Uploads
   - process/handlers/hewan_handler.php
   - process/handlers/transaction.php
   - uploads/ directory structure
   └─ MUST have consistent path handling
```

---

## 13. SYSTEM FLOW SUMMARY

### End-to-End User Journey

```
┌─────────────────────────────────────────────────────────┐
│ USER REGISTERS                                          │
├─────────────────────────────────────────────────────────┤
1. Visit register.php
2. Fill: username, email, password
3. Form POST to register.php
4. Validation: required, email format, duplicate check
5. password_hash() + INSERT user (role='pembeli')
6. Redirect to login.php with success message
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ USER LOGIN                                              │
├─────────────────────────────────────────────────────────┤
1. Visit login.php
2. Fill: email, password
3. Form POST to login.php
4. Auth::login() - check email + password_verify()
5. session_regenerate_id(true) + set $_SESSION
6. Redirect based on role (pembeli → index.php)
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ BROWSE & SHOP                                           │
├─────────────────────────────────────────────────────────┤
1. index.php?page=user/home (homepage)
   - Hero, benefits, featured products
2. index.php?page=user/produk (catalog)
   - Browse produk, filters, search
   - Click "Tambah ke Keranjang" (AJAX)
   - POST cart_handler.php → Keranjang::tambahItem()
   - Stock check, quantity normalized
   - Cart badge updated
3. Repeat for multiple products
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ REVIEW CART                                             │
├─────────────────────────────────────────────────────────┤
1. Click cart icon → index.php?page=user/keranjang
2. Display: All items, quantity controls, total
3. Actions:
   - Update quantity (AJAX) → real-time update
   - Delete item (AJAX) → remove from cart
4. Click "Checkout" → index.php?page=user/chekout?source=cart
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ CHECKOUT & PAYMENT                                      │
├─────────────────────────────────────────────────────────┤
1. Display: Order summary, items, total
2. Fill: Shipping address (nama, telp, alamat, kode_pos)
3. Select payment:
   - Transfer: Show account, require bukti upload
   - COD: Show info message
4. Submit form → POST transaction.php
5. Handler validation:
   - Data required, phone regex check
   - File upload (if transfer): ext, size, MIME check
   - Upload to uploads/bukti/
6. BEGIN TRANSACTION DB:
   - Transaksi::buatTransaksi() → create header
   - Create detail_transaksi for each item
   - kurangiStokProduk() for each
   - Clear keranjang items
   - COMMIT
7. Success: Redirect to riwayat_pesanan
   - Message: "Pesanan berhasil dibuat"
   - Status: menunggu_verifikasi (for transfer)
           or menunggu_verifikasi (for COD, admin confirm)
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ VIEW ORDER HISTORY                                      │
├─────────────────────────────────────────────────────────┤
1. index.php?page=user/riwayat_pesanan
2. Display: All transaksi for user
3. Filter: By status (menunggu, dikonfirmasi, dibatalkan)
4. Search: By product name
5. Each order shows:
   - Date, status badge, items, total
   - Click to expand: details, shipping, items, payment
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ADMIN WORKFLOW                                          │
├─────────────────────────────────────────────────────────┤
1. Login as admin → pages/admin/dashboard.php
2. Dashboard: Stats cards, charts, notifications
3. Manage Data:
   - Produk CRUD: pages/admin/manajemen_produk.php
   - Hewan CRUD: pages/admin/data_hewan.php
   - Kesehatan: pages/admin/data_kesehatan.php
4. Verify Sales: pages/admin/verifikasi_penjualan.php
   - View pending transaksi
   - Check payment proof (if transfer)
   - Verify (status → telah_dikonfirmasi)
   - Reject (status → dibatalkan, restore stok)
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ MANAGER WORKFLOW                                        │
├─────────────────────────────────────────────────────────┤
1. Login as manager → pages/manager/index.php
2. Dashboard: Charts, stats, trends
3. Reports:
   - Population report
   - Health report
   - Transaction report
   - Animal detail view
4. Export: PDF export (DOMPDF)
└─────────────────────────────────────────────────────────┘
```

### Data Flow Architecture

```
┌─────────────┐
│ User Action │ (click, submit)
└──────┬──────┘
       │
       ▼
┌──────────────────┐
│ Frontend Handler │ (JavaScript, form submit)
│ - Validate input │
│ - Show loading   │
└──────┬───────────┘
       │
       ▼
┌──────────────────────────┐
│ HTTP Request             │
│ GET/POST to handler.php  │
└──────┬───────────────────┘
       │
       ▼
┌────────────────────────────┐
│ Handler/Page File          │
│ - Check security           │
│ - Collect input            │
│ - File handling            │
└──────┬─────────────────────┘
       │
       ▼
┌────────────────────────────┐
│ Model Class                │
│ - Validation              │
│ - CRUD operations         │
│ - Prepared statements     │
└──────┬─────────────────────┘
       │
       ▼
┌────────────────────────────┐
│ MySQL Database            │
│ - Execute query            │
│ - Return result            │
└──────┬─────────────────────┘
       │
       ▼
┌────────────────────────────┐
│ Response to Client         │
│ - JSON (AJAX)              │
│ - Redirect (HTTP)          │
│ - Flash message (session)  │
└──────┬─────────────────────┘
       │
       ▼
┌────────────────────────────┐
│ Frontend Update            │
│ - Update DOM               │
│ - Show notification        │
│ - Redirect page            │
└────────────────────────────┘
```

---

## 14. ASSUMPTIONS & CLARIFICATIONS

### Assumptions Made During Analysis

1. **Animal Types Scope**
   - Only `sapi_perah` & `sapi_po` can be sold as products
   - `kambing` & `domba` exist in enum but NOT used (for future expansion)
   - Assumption: Based on query filters and product scope

2. **Payment Methods**
   - Transfer: Proof image required, manual verification
   - COD: No proof, admin confirms via WhatsApp
   - Assumption: Based on chekout.php implementation

3. **Stock Management**
   - Stock decreases only on checkout (verified transaksi)
   - Stock not reserved during cart phase
   - Assumption: Based on transaction.php kurangiStokProduk() call

4. **Session Persistence**
   - Sessions never automatically timeout
   - Login required for checkout/cart
   - Assumption: Based on session_start() and login checks

5. **File Upload Locations**
   - Animal photos: `/uploads/hewan/`
   - Payment proofs: `/uploads/bukti/`
   - Both outside `/public/` for security
   - Assumption: Based on handler implementations

### Ambiguities & Questions

1. **User Model Not Implemented**
   - Q: Should admin be able to create/manage users?
   - Current: Only hardcoded users work
   - Q: Should user role management be available?

2. **Reproduksi/IB Status Mapping**
   - Code shows: `status_ib IN ('berhasil', 'tdk_berhasil', 'proses')`
   - Q: Should 'tidak_berhasil' be stored as 'tdk_berhasil'?
   - Currently: Inconsistent spelling in comments vs code

3. **Kesehatan Status Mapping**
   - Enum shows: `('sehat', 'observasi', 'perawatan', '')`
   - Handler normalizes to: `('sehat', 'dalam_observasi', 'dalam_perawatan')`
   - Q: Which is correct? Inconsistent between DB schema & code

4. **Produk Status Values**
   - Handler uses: 'tersedia', 'tidak_tersedia' (from form)
   - Model converts to: 'blm_terjual', 'terjual' (for DB)
   - Q: Are these intentional conversions or bugs?

---

## CONCLUSION

This Hay Farm system is a well-structured e-commerce + farm management hybrid with:

**Strengths**:
- Clean OOP models for CRUD operations
- Prepared statements throughout (SQL injection safe)
- Role-based access control
- Transaction support for data integrity
- Functional dashboard & reporting

**Areas for Improvement**:
- Complete missing updateStatusTransaksi() method
- Add comprehensive input/output sanitization
- Implement CSRF token protection
- Consolidate duplicate code
- Add proper error logging
- Implement session timeouts

**For Next Phase**:
1. Fix critical bugs (updateStatusTransaksi, XSS)
2. Add security hardening (CSRF, rate limiting)
3. Implement user management features
4. Add comprehensive error handling
5. Create unit tests for models

---

**Documentation Created**: 12 May 2026  
**Ready for**: AI debugging, developer onboarding, feature development, maintenance
