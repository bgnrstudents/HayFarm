<?php

class Keranjang
{
    private mysqli $conn;
    private string $rootDir;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
        $this->rootDir = dirname(__DIR__, 2);
    }

    public function formatRupiah(float $angka): string
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public function normalJumlah($jumlah): int
    {
        $jumlah = (int) $jumlah;
        return $jumlah > 0 ? $jumlah : 1;
    }

    public function getProdukById(int $id_produk): ?array
    {
        $query = "SELECT p.*, t.foto_hewan, t.kode_hewan, t.tgl_lahir, t.no_kandang, t.jenis_hewan, t.jenis_kelamin
                  FROM data_produk p
                  LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan
                  WHERE p.id_produk = ?
                    AND (p.jenis_produk <> 'hewan' OR t.jenis_hewan IN ('sapi_perah', 'sapi_po'))
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id_produk);
        $stmt->execute();
        $produk = $stmt->get_result()->fetch_assoc();

        if (!$produk) {
            return null;
        }

        $produk['gambar'] = $this->getGambarProduk($produk);
        return $produk;
    }

    public function getAtauBuatKeranjang(int $id_user): int
    {
        $stmt = $this->conn->prepare("SELECT id_keranjang FROM keranjang WHERE id_user = ? ORDER BY id_keranjang DESC LIMIT 1");
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $keranjang = $stmt->get_result()->fetch_assoc();

        if ($keranjang) {
            return (int) $keranjang['id_keranjang'];
        }

        $stmt = $this->conn->prepare("INSERT INTO keranjang (id_user) VALUES (?)");
        $stmt->bind_param('i', $id_user);
        $stmt->execute();

        return (int) $this->conn->insert_id;
    }

    public function tambahItem(int $id_user, int $id_produk, int $jumlah): array
    {
        $produk = $this->getProdukById($id_produk);
        if (!$produk || $produk['status_produk'] !== 'blm_terjual') {
            return ['status' => false, 'message' => 'Produk tidak tersedia'];
        }

        // Di method tambahItem(), setelah dapat $produk:
        if ($produk['status_produk'] !== 'blm_terjual') {
            return ['status' => false, 'message' => 'Produk sudah tidak tersedia'];
        }

        $stok = max(1, (int) $produk['stok']);
        if ($jumlah > $stok) {
            return ['status' => false, 'message' => "Stok hanya tersisa {$stok}"];
        }

        $stok = max(1, (int) $produk['stok']);
        $jumlah = min($this->normalJumlah($jumlah), $stok);
        $id_keranjang = $this->getAtauBuatKeranjang($id_user);
        $harga = (float) $produk['harga'];

        $stmt = $this->conn->prepare("SELECT id_detail_keranjang, jumlah FROM detail_keranjang WHERE id_keranjang = ? AND id_produk = ? LIMIT 1");
        $stmt->bind_param('ii', $id_keranjang, $id_produk);
        $stmt->execute();
        $itemLama = $stmt->get_result()->fetch_assoc();

        if ($itemLama) {
            $jumlahBaru = min($stok, (int) $itemLama['jumlah'] + $jumlah);
            $subtotal = $harga * $jumlahBaru;
            $id_detail = (int) $itemLama['id_detail_keranjang'];

            $stmt = $this->conn->prepare("UPDATE detail_keranjang SET jumlah = ?, harga = ?, sub_total = ? WHERE id_detail_keranjang = ?");
            $stmt->bind_param('iddi', $jumlahBaru, $harga, $subtotal, $id_detail);
            $stmt->execute();
        } else {
            $subtotal = $harga * $jumlah;
            $stmt = $this->conn->prepare("INSERT INTO detail_keranjang (id_keranjang, id_produk, jumlah, harga, sub_total) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('iiidd', $id_keranjang, $id_produk, $jumlah, $harga, $subtotal);
            $stmt->execute();
        }

        return [
            'status' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => $this->hitungJumlahItem($id_user),
        ];
    }

    public function getItems(int $id_user): array
    {
        $query = "SELECT
                    d.id_detail_keranjang,
                    d.id_keranjang,
                    d.id_produk,
                    d.jumlah,
                    d.harga,
                    d.sub_total,
                    p.nama_produk,
                    p.jenis_produk,
                    p.satuan,
                    p.stok,
                    p.status_produk,
                    t.foto_hewan,
                    t.kode_hewan,
                    t.tgl_lahir,
                    t.no_kandang,
                    t.jenis_hewan,
                    t.jenis_kelamin
                  FROM keranjang k
                  JOIN detail_keranjang d ON k.id_keranjang = d.id_keranjang
                  JOIN data_produk p ON d.id_produk = p.id_produk
                  LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan
                  WHERE k.id_user = ?
                    AND (p.jenis_produk <> 'hewan' OR t.jenis_hewan IN ('sapi_perah', 'sapi_po'))
                  ORDER BY d.id_detail_keranjang DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($items as &$item) {
            $item['jumlah'] = $this->normalJumlah($item['jumlah']);
            $item['harga'] = (float) $item['harga'];
            $item['sub_total'] = (float) $item['sub_total'];
            $item['gambar'] = $this->getGambarProduk($item);
        }

        return $items;
    }

    public function hitungTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += (float) $item['sub_total'];
        }

        return $total;
    }

    public function hitungJumlahItem(int $id_user): int
    {
        $query = "SELECT COALESCE(SUM(d.jumlah), 0) AS total
                  FROM keranjang k
                  JOIN detail_keranjang d ON k.id_keranjang = d.id_keranjang
                  JOIN data_produk p ON d.id_produk = p.id_produk
                  LEFT JOIN data_ternak t ON p.id_hewan = t.id_hewan
                  WHERE k.id_user = ?
                    AND (p.jenis_produk <> 'hewan' OR t.jenis_hewan IN ('sapi_perah', 'sapi_po'))";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        return (int) ($row['total'] ?? 0);
    }

    public function updateJumlah(int $id_user, int $id_detail, int $jumlah): array
    {
        $query = "SELECT d.id_detail_keranjang, p.harga, p.stok
                  FROM detail_keranjang d
                  JOIN keranjang k ON d.id_keranjang = k.id_keranjang
                  JOIN data_produk p ON d.id_produk = p.id_produk
                  WHERE d.id_detail_keranjang = ? AND k.id_user = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $id_detail, $id_user);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        if (!$item) {
            return ['status' => false, 'message' => 'Item keranjang tidak ditemukan'];
        }

        $stok = max(1, (int) $item['stok']);
        $jumlah = min($this->normalJumlah($jumlah), $stok);
        $harga = (float) $item['harga'];
        $subtotal = $harga * $jumlah;

        $stmt = $this->conn->prepare("UPDATE detail_keranjang SET jumlah = ?, harga = ?, sub_total = ? WHERE id_detail_keranjang = ?");
        $stmt->bind_param('iddi', $jumlah, $harga, $subtotal, $id_detail);
        $stmt->execute();

        return ['status' => true, 'message' => 'Jumlah produk berhasil diperbarui'];
    }

    public function hapusItem(int $id_user, int $id_detail): array
    {
        $query = "DELETE d FROM detail_keranjang d
                  JOIN keranjang k ON d.id_keranjang = k.id_keranjang
                  WHERE d.id_detail_keranjang = ? AND k.id_user = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $id_detail, $id_user);
        $stmt->execute();

        return ['status' => true, 'message' => 'Produk berhasil dihapus dari keranjang'];
    }

    private function getGambarProduk(array $produk): string
    {
        $jenis = $produk['jenis_produk'] ?? '';
        $foto  = trim((string) ($produk['foto_hewan'] ?? ''));

        // 1. Produk Non-Hewan (path fixed)
        if ($jenis === 'susu') return 'public/images/farel_perah.jpg';
        if ($jenis === 'rumput') return 'public/images/bgheader_produk.png';

        // 2. Produk Hewan
        if ($jenis === 'hewan' && $foto !== '') {
            if (str_starts_with($foto, 'public/'))  return $foto;
            if (str_starts_with($foto, 'uploads/')) return $foto;
            return 'uploads/hewan/' . $foto;
        }

        // 3. Default fallback
        return 'public/images/bgheader_produk.png';
    }
}
