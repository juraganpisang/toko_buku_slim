-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2021 at 10:33 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `kode_item` varchar(20) NOT NULL,
  `nama_item` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `disc` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `transaksi_id`, `kode_item`, `nama_item`, `qty`, `harga`, `disc`, `subtotal`) VALUES
(1, 1, '0002', 'Buku Gambar', 1, 10000, 0, 10000),
(2, 1, '0003', 'Novel Hitam Putih', 1, 10000, 0, 10000),
(3, 2, '0002', 'Novel Baswedan', 2, 20000, 0, 40000),
(4, 0, '0002', 'Jurnal', 3, 25000, 0, 75000),
(5, 7, '0003', 'Jrunal', 4, 25000, 0, 100000),
(6, 8, '0004', 'Jurnal Risa', 4, 20000, 0, 80000),
(7, 8, '0002', 'Jurnal Yudi', 4, 25000, 10, 90000),
(8, 9, '0003', 'Jurnal 1', 3, 20000, 0, 60000),
(9, 10, '003', 'Jurnal', 3, 25000, 0, 75000),
(12, 12, '0004', 'Pepak', 3, 25000, 0, 75000),
(13, 12, '0001', 'Bahasa', 4, 25000, 40, 60000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `nota` varchar(20) NOT NULL,
  `nama_customer` varchar(50) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `nota`, `nama_customer`, `total_bayar`, `kembalian`, `created_at`) VALUES
(1, 'NT001', 'Ronald Arrival Fajar', 20000, 0, '2021-10-12 03:20:18'),
(2, 'NT002', 'Aji Pratama', 40000, 0, '2021-10-12 11:20:23'),
(4, 'NT1634028121', '', 200000, 0, '2021-10-12 15:42:01'),
(5, 'NT1634028483', '', 400000, 0, '2021-10-12 15:48:03'),
(6, 'NT1634028635', '', 20000, 0, '2021-10-12 15:50:35'),
(7, 'NT1634028694', 'Rina', 400000, 0, '2021-10-12 15:51:34'),
(8, 'NT1634029705', 'Yudi', 4000000, 0, '2021-10-12 16:08:25'),
(9, 'NT1634030255', 'Rina', 70000, 0, '2021-10-12 16:17:35'),
(10, 'NT1634090305', 'Joko', 900, 0, '2021-10-13 08:58:25'),
(12, 'NT1634113536', 'Rita', 200000, 65000, '2021-10-13 15:25:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
