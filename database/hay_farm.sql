-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 15, 2026 at 12:03 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hayfarm`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_kesehatan`
--

CREATE TABLE `data_kesehatan` (
  `id_kesehatan` int NOT NULL,
  `id_hewan` int NOT NULL,
  `tgl_pemeriksaan` date NOT NULL,
  `status_kesehatan` enum('sehat','observasi','perawatan','') COLLATE utf8mb4_general_ci NOT NULL,
  `diagnosis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tindakan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_kesehatan`
--

INSERT INTO `data_kesehatan` (`id_kesehatan`, `id_hewan`, `tgl_pemeriksaan`, `status_kesehatan`, `diagnosis`, `tindakan`, `catatan`) VALUES
(34, 22, '2026-05-15', 'sehat', 'infeksi ringang', 'pemberian antibioitik', '');

-- --------------------------------------------------------

--
-- Table structure for table `data_produk`
--

CREATE TABLE `data_produk` (
  `id_produk` int NOT NULL,
  `id_hewan` int DEFAULT NULL,
  `jenis_produk` enum('hewan','rumput','susu') COLLATE utf8mb4_general_ci NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` float NOT NULL,
  `stok` int NOT NULL,
  `satuan` enum('liter','ton','ekor','') COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_produksi` date DEFAULT NULL,
  `tgl_kadaluarsa` date NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `status_produk` enum('terjual','blm_terjual') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_produk`
--

INSERT INTO `data_produk` (`id_produk`, `id_hewan`, `jenis_produk`, `nama_produk`, `harga`, `stok`, `satuan`, `tgl_produksi`, `tgl_kadaluarsa`, `deskripsi`, `status_produk`) VALUES
(20, NULL, 'susu', 'Susu Segar Premium', 12000, 40, 'liter', NULL, '2026-05-14', '', 'blm_terjual'),
(22, NULL, 'rumput', 'Rumput Odot', 20000, 85, 'ton', NULL, '2099-12-31', '', 'blm_terjual'),
(23, 23, 'hewan', 'Sapi Po', 20000000, 1, 'ekor', NULL, '2099-12-31', '', 'terjual');


CREATE TABLE `data_reproduksi` (
  `id_reproduksi` int NOT NULL,
  `id_kesehatan` int DEFAULT NULL,
  `id_hewan` int NOT NULL,
  `tgl_ib` date NOT NULL,
  `ib_ke` int NOT NULL,
  `tgl_perkiraan` date NOT NULL,
  `status_ib` enum('berhasil','tdk_berhasil','proses') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_ternak`
--

CREATE TABLE `data_ternak` (
  `id_hewan` int NOT NULL,
  `kode_hewan` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_hewan` enum('sapi_perah','sapi_po') COLLATE utf8mb4_general_ci NOT NULL,
  `berat_badan` float NOT NULL,
  `jenis_kelamin` enum('jantan','betina') COLLATE utf8mb4_general_ci NOT NULL,
  `no_kandang` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `foto_hewan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status_hewan` enum('produktif','tdk_produktif') COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_ternak`
--

INSERT INTO `data_ternak` (`id_hewan`, `kode_hewan`, `jenis_hewan`, `berat_badan`, `jenis_kelamin`, `no_kandang`, `tgl_lahir`, `foto_hewan`, `status_hewan`, `is_deleted`, `deleted_at`) VALUES
(22, 'HR-32', 'sapi_perah', 450, 'jantan', 'K01', '2026-05-13', 'uploads/hewan/hewan_1778776506_6a05f9ba0c35a.jpeg', 'produktif', 0, NULL),
(23, 'FRW11', 'sapi_po', 444, 'betina', 'K01', '2026-05-10', 'uploads/hewan/hewan_1778776569_6a05f9f9d53a8.jpg', 'produktif', 1, '2026-05-14 23:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `detail_keranjang`
--

CREATE TABLE `detail_keranjang` (
  `id_detail_keranjang` int NOT NULL,
  `id_keranjang` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga` float NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga` float NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `id_transaksi`, `id_produk`, `jumlah`, `harga`, `sub_total`) VALUES
(5, 56, 22, 5, 20000, 100000),
(6, 57, 22, 5, 20000, 100000),
(7, 58, 23, 1, 20000000, 20000000);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_user` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `created_at`) VALUES
(13, 7, '2026-05-14 15:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_user` int NOT NULL,
  `nama_pembeli` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `kode_pos` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `metode_pembayaran` enum('cod','transfer') COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_pembelian` float NOT NULL,
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `tgl_verifikasi` datetime DEFAULT NULL,
  `total_tagihan` float NOT NULL,
  `status_transaksi` enum('menunggu_verifikasi','telah_dikonfirmasi','dibatalkan') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `nama_pembeli`, `no_telp`, `alamat`, `kode_pos`, `metode_pembayaran`, `jumlah_pembelian`, `bukti_pembayaran`, `tgl_transaksi`, `total_tagihan`, `status_transaksi`) VALUES
(56, 7, 'BUDI SANTOSO', '085850030268', 'jalan sini', '68262', 'transfer', 5, 'uploads/bukti/bukti_20260514124117_d1b94026.png', '2026-05-14', 100000, 'telah_dikonfirmasi'),
(57, 7, 'Budi', '085850030268', 'PUJER BARU, KECAMATAN MAESAN, KABUPATEN BONDOWOSO', '68262', 'transfer', 5, 'uploads/bukti/bukti_20260514153744_8c33f294.png', '2026-05-14', 100000, 'telah_dikonfirmasi'),
(58, 7, 'Amariana', '085850030268', 'PUJER BARU, KECAMATAN MAESAN, KABUPATEN BONDOWOSO', '68262', 'transfer', 1, 'uploads/bukti/bukti_20260514164501_5e4a9604.png', '2026-05-14', 20000000, 'telah_dikonfirmasi');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('pembeli','admin','manager') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `role`) VALUES
(5, 'admin', 'admin@hayfarm.com', '$2y$10$xl.uyb5Al2ekyuZ1s6nIlOh8.4nhAFrGqSRzjm.hSeDCHuq.ABBXm', 'admin'),
(6, 'manager', 'manager@hayfarm.com', '$2y$10$feh5/JUjzZ1sG7sDIo3J4.ehykL5o8ZyufPYFuPlmtbvN8I/yzhSq', 'manager'),
(7, 'muhammad rizki', 'muhmmadrizki081207@gmail.com', '$2y$10$0ppGd7MERe85zhcZsprNuO1YmhOSCWPpQM5Wb6Z54pnzxH6UW7WrO', 'pembeli'),
(10, 'Marshanda14', 'marshanda1408@gmail.com', '$2y$10$LoFmOcvlpLzBAZt2aL2SKORr9Tn/KfNU7pdg4a/Kd5ThvHn4gnj8W', 'pembeli');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_kesehatan`
--
ALTER TABLE `data_kesehatan`
  ADD PRIMARY KEY (`id_kesehatan`),
  ADD KEY `id_hewan` (`id_hewan`);

--
-- Indexes for table `data_produk`
--
ALTER TABLE `data_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_hewan` (`id_hewan`);

--
-- Indexes for table `data_reproduksi`
--
ALTER TABLE `data_reproduksi`
  ADD PRIMARY KEY (`id_reproduksi`),
  ADD KEY `id_hewan` (`id_hewan`),
  ADD KEY `id_kesehatan` (`id_kesehatan`);

--
-- Indexes for table `data_ternak`
--
ALTER TABLE `data_ternak`
  ADD PRIMARY KEY (`id_hewan`),
  ADD UNIQUE KEY `kode_hewan` (`kode_hewan`);

--
-- Indexes for table `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD PRIMARY KEY (`id_detail_keranjang`),
  ADD KEY `id_keranjang` (`id_keranjang`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `id_transaksi` (`id_transaksi`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_kesehatan`
--
ALTER TABLE `data_kesehatan`
  MODIFY `id_kesehatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `data_produk`
--
ALTER TABLE `data_produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `data_reproduksi`
--
ALTER TABLE `data_reproduksi`
  MODIFY `id_reproduksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `data_ternak`
--
ALTER TABLE `data_ternak`
  MODIFY `id_hewan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  MODIFY `id_detail_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_kesehatan`
--
ALTER TABLE `data_kesehatan`
  ADD CONSTRAINT `data_kesehatan_ibfk_1` FOREIGN KEY (`id_hewan`) REFERENCES `data_ternak` (`id_hewan`);

--
-- Constraints for table `data_produk`
--
ALTER TABLE `data_produk`
  ADD CONSTRAINT `data_produk_ibfk_1` FOREIGN KEY (`id_hewan`) REFERENCES `data_ternak` (`id_hewan`);

--
-- Constraints for table `data_reproduksi`
--
ALTER TABLE `data_reproduksi`
  ADD CONSTRAINT `data_reproduksi_ibfk_1` FOREIGN KEY (`id_hewan`) REFERENCES `data_ternak` (`id_hewan`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_reproduksi_ibfk_2` FOREIGN KEY (`id_kesehatan`) REFERENCES `data_kesehatan` (`id_kesehatan`) ON DELETE CASCADE;

--
-- Constraints for table `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD CONSTRAINT `detail_keranjang_ibfk_1` FOREIGN KEY (`id_keranjang`) REFERENCES `keranjang` (`id_keranjang`),
  ADD CONSTRAINT `detail_keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `data_produk` (`id_produk`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `data_produk` (`id_produk`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;
