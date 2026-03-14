-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Mar 2026 pada 14.47
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hay farm`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kesehatan`
--

CREATE TABLE `data_kesehatan` (
  `id_kesehatan` int(11) NOT NULL,
  `id_hewan` int(11) NOT NULL,
  `tgl_pemeriksaan` date NOT NULL,
  `status_kesehatan` enum('sehat','observasi','perawatan','') NOT NULL,
  `diagnosis` varchar(255) NOT NULL,
  `tindakan` varchar(255) NOT NULL,
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_kesehatan`
--

INSERT INTO `data_kesehatan` (`id_kesehatan`, `id_hewan`, `tgl_pemeriksaan`, `status_kesehatan`, `diagnosis`, `tindakan`, `catatan`) VALUES
(1, 1, '2026-01-10', 'sehat', 'Tidak ada penyakit', 'Vitamin rutin', 'Kondisi baik'),
(2, 2, '2026-02-05', 'observasi', 'Demam ringan', 'Pemberian obat', 'Perlu observasi 3 hari');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_produk`
--

CREATE TABLE `data_produk` (
  `id_produk` int(11) NOT NULL,
  `id_hewan` int(11) DEFAULT NULL,
  `jenis_produk` enum('hewan','rumput','susu') NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` float NOT NULL,
  `stok` int(11) NOT NULL,
  `satuan` enum('liter','ton','ekor','') NOT NULL,
  `tgl_kadaluarsa` date NOT NULL,
  `deskripsi` text NOT NULL,
  `status_produk` enum('terjual','blm_terjual','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_produk`
--

INSERT INTO `data_produk` (`id_produk`, `id_hewan`, `jenis_produk`, `nama_produk`, `harga`, `stok`, `satuan`, `tgl_kadaluarsa`, `deskripsi`, `status_produk`) VALUES
(1, NULL, 'susu', 'Susu Sapi Segar', 15000, 50, 'liter', '2026-12-01', 'Susu sapi segar dari peternakan', 'blm_terjual'),
(2, 2, 'hewan', 'Kambing Etawa Dewasa', 2500000, 5, 'ekor', '0000-00-00', 'Kambing etawa sehat', 'blm_terjual'),
(3, NULL, 'rumput', 'Rumput Pakan Ternak', 5000, 100, '', '0000-00-00', 'Rumput segar untuk pakan', 'blm_terjual');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_reproduksi`
--

CREATE TABLE `data_reproduksi` (
  `id_reproduksi` int(11) NOT NULL,
  `id_hewan` int(11) NOT NULL,
  `tgl_ib` date NOT NULL,
  `ib_ke` int(11) NOT NULL,
  `tgl_perkiraan` date NOT NULL,
  `status_ib` enum('berhasil','tdk_berhasil','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_reproduksi`
--

INSERT INTO `data_reproduksi` (`id_reproduksi`, `id_hewan`, `tgl_ib`, `ib_ke`, `tgl_perkiraan`, `status_ib`) VALUES
(1, 3, '2026-01-15', 1, '2026-10-15', 'berhasil'),
(2, 1, '2026-02-10', 2, '2026-11-10', 'tdk_berhasil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_ternak`
--

CREATE TABLE `data_ternak` (
  `id_hewan` int(11) NOT NULL,
  `jenis_hewan` enum('sapi_perah','sapi_po','kambing','domba') NOT NULL,
  `nama_hewan` varchar(255) NOT NULL,
  `berat_badan` float NOT NULL,
  `jenis_kelamin` enum('jantan','betina') NOT NULL,
  `no_kandang` varchar(5) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `foto_hewan` varchar(255) NOT NULL,
  `status_hewan` enum('produktif','tdk_produktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_ternak`
--

INSERT INTO `data_ternak` (`id_hewan`, `jenis_hewan`, `nama_hewan`, `berat_badan`, `jenis_kelamin`, `no_kandang`, `tgl_lahir`, `foto_hewan`, `status_hewan`) VALUES
(1, 'sapi_perah', 'Sapi Lestari', 350, 'betina', 'K01', '2021-05-10', 'sapi1.jpg', 'produktif'),
(2, 'kambing', 'Kambing Etawa', 60, 'jantan', 'K02', '2022-02-15', 'kambing1.jpg', 'produktif'),
(3, 'domba', 'Domba Garut', 55, 'jantan', 'K03', '2022-08-12', 'domba1.jpg', 'produktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_keranjang`
--

CREATE TABLE `detail_keranjang` (
  `id_detail_keranjang` int(11) NOT NULL,
  `id_keranjang` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` float NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_keranjang`
--

INSERT INTO `detail_keranjang` (`id_detail_keranjang`, `id_keranjang`, `id_produk`, `jumlah`, `harga`, `sub_total`) VALUES
(1, 2, 1, 2, 0, 30000),
(2, 1, 3, 1, 0, 2500000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` float NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `id_transaksi`, `id_produk`, `jumlah`, `harga`, `sub_total`) VALUES
(1, 1, 3, 2, 15000, 30000),
(2, 2, 1, 1, 2500000, 2500000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `created_at`) VALUES
(1, 2, '2026-03-10 07:57:53'),
(2, 3, '2026-03-10 07:57:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_pembeli` varchar(255) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `kode_pos` varchar(5) NOT NULL,
  `metode_pembayaran` enum('cod','transfer','','') NOT NULL,
  `jumlah_pembelian` float NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `total_tagihan` float NOT NULL,
  `status_transaksi` enum('menunggu_verifikasi','telah_dikonfirmasi','dibatalkan','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `nama_pembeli`, `no_telp`, `alamat`, `kode_pos`, `metode_pembayaran`, `jumlah_pembelian`, `bukti_pembayaran`, `tgl_transaksi`, `total_tagihan`, `status_transaksi`) VALUES
(1, 2, 'Budi Santoso', '08123456789', 'Pasuruan', '67116', 'transfer', 2, 'bukti1.jpg', '2026-03-01', 30000, 'menunggu_verifikasi'),
(2, 3, 'Sari Wulandari', '08123456780', 'Malang', '65141', 'cod', 1, '', '2026-03-02', 2500000, 'telah_dikonfirmasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pembeli','admin','manager') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin1', 'admin@hayfarm.com', '123456', 'admin'),
(2, 'budi', 'budi@gmail.com', '123456', 'pembeli'),
(3, 'sari', 'sari@gmail.com', '123456', 'pembeli');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_kesehatan`
--
ALTER TABLE `data_kesehatan`
  ADD PRIMARY KEY (`id_kesehatan`),
  ADD KEY `id_hewan` (`id_hewan`);

--
-- Indeks untuk tabel `data_produk`
--
ALTER TABLE `data_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_hewan` (`id_hewan`);

--
-- Indeks untuk tabel `data_reproduksi`
--
ALTER TABLE `data_reproduksi`
  ADD PRIMARY KEY (`id_reproduksi`),
  ADD KEY `id_hewan` (`id_hewan`);

--
-- Indeks untuk tabel `data_ternak`
--
ALTER TABLE `data_ternak`
  ADD PRIMARY KEY (`id_hewan`);

--
-- Indeks untuk tabel `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD PRIMARY KEY (`id_detail_keranjang`),
  ADD KEY `id_keranjang` (`id_keranjang`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `id_transaksi` (`id_transaksi`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_kesehatan`
--
ALTER TABLE `data_kesehatan`
  MODIFY `id_kesehatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `data_produk`
--
ALTER TABLE `data_produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `data_reproduksi`
--
ALTER TABLE `data_reproduksi`
  MODIFY `id_reproduksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `data_ternak`
--
ALTER TABLE `data_ternak`
  MODIFY `id_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  MODIFY `id_detail_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_kesehatan`
--
ALTER TABLE `data_kesehatan`
  ADD CONSTRAINT `data_kesehatan_ibfk_1` FOREIGN KEY (`id_hewan`) REFERENCES `data_ternak` (`id_hewan`);

--
-- Ketidakleluasaan untuk tabel `data_produk`
--
ALTER TABLE `data_produk`
  ADD CONSTRAINT `data_produk_ibfk_1` FOREIGN KEY (`id_hewan`) REFERENCES `data_ternak` (`id_hewan`);

--
-- Ketidakleluasaan untuk tabel `data_reproduksi`
--
ALTER TABLE `data_reproduksi`
  ADD CONSTRAINT `data_reproduksi_ibfk_1` FOREIGN KEY (`id_hewan`) REFERENCES `data_ternak` (`id_hewan`);

--
-- Ketidakleluasaan untuk tabel `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD CONSTRAINT `detail_keranjang_ibfk_1` FOREIGN KEY (`id_keranjang`) REFERENCES `keranjang` (`id_keranjang`),
  ADD CONSTRAINT `detail_keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `data_produk` (`id_produk`);

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `data_produk` (`id_produk`);

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
