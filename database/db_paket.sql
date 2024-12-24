-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 08:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_paket`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notif` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_pesanan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id_notif`, `message`, `is_read`, `created_at`, `id_pesanan`) VALUES
(216, 'Pesanan baru telah diterima dengan ID Pesanan: 224 oleh Mr. Lawak', 1, '2024-12-24 10:09:43', 224),
(217, 'Mr. Lawak telah melakukan pembayaran untuk ID pesanan: 224', 1, '2024-12-24 10:10:10', 224),
(218, 'Pesanan baru telah diterima dengan ID Pesanan: 225 oleh Mr. Lawak', 1, '2024-12-24 10:28:17', 225),
(219, 'Mr. Lawak telah melakukan pembayaran untuk ID pesanan: 225', 1, '2024-12-24 10:28:29', 225),
(220, 'Pesanan baru telah diterima dengan ID Pesanan: 226 oleh Mr. Awikwok', 1, '2024-12-24 10:38:29', 226),
(222, 'Pesanan baru telah diterima dengan ID Pesanan: 228 oleh Mr. Lawak', 1, '2024-12-24 18:04:45', 228),
(223, 'Mr. Lawak telah melakukan pembayaran untuk ID pesanan: 228', 1, '2024-12-24 18:05:05', 228),
(224, 'Pesanan baru telah diterima dengan ID Pesanan: 229 oleh Mr. Lawak', 0, '2024-12-24 18:25:04', 229),
(225, 'Mr. Lawak telah melakukan pembayaran untuk ID pesanan: 229', 1, '2024-12-24 18:25:09', 229);

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id_paket` int(11) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `tujuan` varchar(150) NOT NULL,
  `durasi_hari` varchar(11) NOT NULL,
  `harga` varchar(45) NOT NULL,
  `status_paket` enum('Aktif','Nonaktif') NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id_paket`, `nama_paket`, `tujuan`, `durasi_hari`, `harga`, `status_paket`, `deskripsi`, `foto`) VALUES
(67, 'Paket Tour Bali', 'Bali', '2', '2000000', 'Aktif', 'v csyghjcefc egcbicnwsl', '1733837169_lukisan-merpati.jpg'),
(71, 'Paket Tour Bandung', 'Bandung', '5', '5000000', 'Aktif', 'why you got be so rude', '../uploads/1734411874_download.jpeg'),
(72, 'Nama Paket Baru', 'Tujuan Baru', '2', '2000000', 'Aktif', 'jynhtgf nu6ytbfh 6uy', '../uploads/1734415159_download.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_pemesan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `harga_total` decimal(20,2) NOT NULL,
  `tanggal_perjalanan` date DEFAULT NULL,
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_transfer` varchar(255) NOT NULL,
  `status_pesanan` enum('Pending','Dikonfirmasi','Dibatalkan','Selesai') DEFAULT 'Pending',
  `masa_pembayaran` datetime DEFAULT NULL,
  `status_pembayaran` enum('Belum Dibayar','Sudah Dibayar') DEFAULT 'Belum Dibayar',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_paket`, `user_id`, `nama_pemesan`, `email`, `jumlah_peserta`, `harga_total`, `tanggal_perjalanan`, `tanggal_pesan`, `foto_transfer`, `status_pesanan`, `masa_pembayaran`, `status_pembayaran`, `catatan`) VALUES
(224, 71, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 50, 250000000.00, '2024-12-24', '2024-12-24 10:09:43', '1735035010_download.jpg', 'Dikonfirmasi', '2024-12-25 17:09:43', 'Sudah Dibayar', ''),
(225, 71, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 25, 125000000.00, '2024-12-30', '2024-12-24 10:28:17', '1735036109_download.jpg', 'Dikonfirmasi', '2024-12-25 17:28:17', 'Sudah Dibayar', ''),
(226, 71, 71, 'Mr. Awikwok', 'awikwok@wok.com', 20, 100000000.00, '2024-12-25', '2024-12-24 10:38:29', '', 'Pending', '2024-12-25 17:38:29', 'Belum Dibayar', ''),
(228, 71, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 100000000.00, '2024-12-27', '2024-12-24 18:04:45', '1735063505_download.jpg', 'Dikonfirmasi', '2024-12-26 01:04:45', 'Sudah Dibayar', 'dcfvhkm '),
(229, 71, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 100000000.00, '2024-12-31', '2024-12-24 18:25:04', '1735064709_download.jpg', 'Dikonfirmasi', '2024-12-26 01:25:04', 'Sudah Dibayar', '');

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id_tiket` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `tanggal_perjalanan` date NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `kode_tiket` varchar(50) NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`id_tiket`, `id_pesanan`, `user_id`, `nama_paket`, `tanggal_perjalanan`, `jumlah_peserta`, `kode_tiket`, `tanggal_dibuat`) VALUES
(489, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57b91e', '2024-12-24 10:16:21'),
(490, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57c314', '2024-12-24 10:16:21'),
(491, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57c9fd', '2024-12-24 10:16:21'),
(492, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57d623', '2024-12-24 10:16:21'),
(493, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57dac2', '2024-12-24 10:16:21'),
(494, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57e0db', '2024-12-24 10:16:21'),
(495, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57e7dc', '2024-12-24 10:16:21'),
(496, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57f0d4', '2024-12-24 10:16:21'),
(497, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f57f8f1', '2024-12-24 10:16:21'),
(498, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f580005', '2024-12-24 10:16:21'),
(499, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58072f', '2024-12-24 10:16:21'),
(500, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f580bb6', '2024-12-24 10:16:21'),
(501, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58119b', '2024-12-24 10:16:21'),
(502, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58188c', '2024-12-24 10:16:21'),
(503, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58213e', '2024-12-24 10:16:21'),
(504, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f582b7a', '2024-12-24 10:16:21'),
(505, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f5832f8', '2024-12-24 10:16:21'),
(506, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f583c93', '2024-12-24 10:16:21'),
(507, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f584322', '2024-12-24 10:16:21'),
(508, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f584ae7', '2024-12-24 10:16:21'),
(509, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f585228', '2024-12-24 10:16:21'),
(510, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f5859d4', '2024-12-24 10:16:21'),
(511, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f586111', '2024-12-24 10:16:21'),
(512, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f586805', '2024-12-24 10:16:21'),
(513, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f586f30', '2024-12-24 10:16:21'),
(514, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58765d', '2024-12-24 10:16:21'),
(515, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f587d08', '2024-12-24 10:16:21'),
(516, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f5883de', '2024-12-24 10:16:21'),
(517, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f5888fc', '2024-12-24 10:16:21'),
(518, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f588dab', '2024-12-24 10:16:21'),
(519, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f589163', '2024-12-24 10:16:21'),
(520, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f589550', '2024-12-24 10:16:21'),
(521, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f589975', '2024-12-24 10:16:21'),
(522, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f589d88', '2024-12-24 10:16:21'),
(523, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58a0d4', '2024-12-24 10:16:21'),
(524, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58a40c', '2024-12-24 10:16:21'),
(525, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58a819', '2024-12-24 10:16:21'),
(526, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58abd9', '2024-12-24 10:16:21'),
(527, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58b010', '2024-12-24 10:16:21'),
(528, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58b3d4', '2024-12-24 10:16:21'),
(529, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58b811', '2024-12-24 10:16:21'),
(530, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58bc07', '2024-12-24 10:16:21'),
(531, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58c196', '2024-12-24 10:16:21'),
(532, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58c75b', '2024-12-24 10:16:21'),
(533, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58cc09', '2024-12-24 10:16:21'),
(534, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58d162', '2024-12-24 10:16:21'),
(535, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58d6ee', '2024-12-24 10:16:21'),
(536, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58da86', '2024-12-24 10:16:21'),
(537, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58dfa0', '2024-12-24 10:16:21'),
(538, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a89f58e7dd', '2024-12-24 10:16:21'),
(539, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2769be7', '2024-12-24 10:17:11'),
(540, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276a0d1', '2024-12-24 10:17:11'),
(541, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276a684', '2024-12-24 10:17:11'),
(542, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276ab48', '2024-12-24 10:17:11'),
(543, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276b4f9', '2024-12-24 10:17:11'),
(544, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276b93c', '2024-12-24 10:17:11'),
(545, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276bd45', '2024-12-24 10:17:11'),
(546, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276c14d', '2024-12-24 10:17:11'),
(547, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276cc78', '2024-12-24 10:17:11'),
(548, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276d275', '2024-12-24 10:17:11'),
(549, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276da46', '2024-12-24 10:17:11'),
(550, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276de4b', '2024-12-24 10:17:11'),
(551, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276e330', '2024-12-24 10:17:11'),
(552, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276e774', '2024-12-24 10:17:11'),
(553, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276eb14', '2024-12-24 10:17:11'),
(554, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276eeaa', '2024-12-24 10:17:11'),
(555, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276f205', '2024-12-24 10:17:11'),
(556, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276f5f4', '2024-12-24 10:17:11'),
(557, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276f951', '2024-12-24 10:17:11'),
(558, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a276fceb', '2024-12-24 10:17:11'),
(559, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2770048', '2024-12-24 10:17:11'),
(560, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27704a5', '2024-12-24 10:17:11'),
(561, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27708c7', '2024-12-24 10:17:11'),
(562, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2770c9f', '2024-12-24 10:17:11'),
(563, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a277102e', '2024-12-24 10:17:11'),
(564, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a277137f', '2024-12-24 10:17:11'),
(565, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27717ac', '2024-12-24 10:17:11'),
(566, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2771b7b', '2024-12-24 10:17:11'),
(567, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2771ec1', '2024-12-24 10:17:11'),
(568, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2772212', '2024-12-24 10:17:11'),
(569, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2772751', '2024-12-24 10:17:11'),
(570, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2772bad', '2024-12-24 10:17:11'),
(571, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2772f7e', '2024-12-24 10:17:11'),
(572, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a277331c', '2024-12-24 10:17:11'),
(573, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2773663', '2024-12-24 10:17:11'),
(574, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2773a2c', '2024-12-24 10:17:11'),
(575, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2773da1', '2024-12-24 10:17:11'),
(576, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a277411a', '2024-12-24 10:17:11'),
(577, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27744dc', '2024-12-24 10:17:11'),
(578, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2774882', '2024-12-24 10:17:11'),
(579, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27752e1', '2024-12-24 10:17:11'),
(580, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a277596e', '2024-12-24 10:17:11'),
(581, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2775f40', '2024-12-24 10:17:11'),
(582, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a277634f', '2024-12-24 10:17:11'),
(583, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2776665', '2024-12-24 10:17:11'),
(584, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27769c0', '2024-12-24 10:17:11'),
(585, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2776cc9', '2024-12-24 10:17:11'),
(586, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2777a86', '2024-12-24 10:17:11'),
(587, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a2778000', '2024-12-24 10:17:11'),
(588, 224, 70, 'Paket Tour Bandung', '2024-12-24', 50, 'TKT-676a8a27784ef', '2024-12-24 10:17:11'),
(589, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1583d', '2024-12-24 10:29:03'),
(590, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef16058', '2024-12-24 10:29:03'),
(591, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef166a1', '2024-12-24 10:29:03'),
(592, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef16dd7', '2024-12-24 10:29:03'),
(593, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef174df', '2024-12-24 10:29:03'),
(594, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef17c30', '2024-12-24 10:29:03'),
(595, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef18357', '2024-12-24 10:29:03'),
(596, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef18a49', '2024-12-24 10:29:03'),
(597, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef191b6', '2024-12-24 10:29:03'),
(598, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef19859', '2024-12-24 10:29:03'),
(599, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1a12e', '2024-12-24 10:29:03'),
(600, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1a76b', '2024-12-24 10:29:03'),
(601, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1ae3a', '2024-12-24 10:29:03'),
(602, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1b4b8', '2024-12-24 10:29:03'),
(603, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1bf36', '2024-12-24 10:29:03'),
(604, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1c5eb', '2024-12-24 10:29:03'),
(605, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1ccbe', '2024-12-24 10:29:03'),
(606, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1d39f', '2024-12-24 10:29:03'),
(607, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1dcef', '2024-12-24 10:29:03'),
(608, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1e27d', '2024-12-24 10:29:03'),
(609, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1e8a4', '2024-12-24 10:29:03'),
(610, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1eef0', '2024-12-24 10:29:03'),
(611, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef1f60d', '2024-12-24 10:29:03'),
(612, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef20016', '2024-12-24 10:29:03'),
(613, 225, 70, 'Paket Tour Bandung', '2024-12-30', 25, 'TKT-676a8cef207ed', '2024-12-24 10:29:03'),
(614, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd201777', '2024-12-24 18:22:10'),
(615, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd2055a1', '2024-12-24 18:22:10'),
(616, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd205c86', '2024-12-24 18:22:10'),
(617, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd2065ba', '2024-12-24 18:22:10'),
(618, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd206ab2', '2024-12-24 18:22:10'),
(619, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20727c', '2024-12-24 18:22:10'),
(620, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd207b58', '2024-12-24 18:22:10'),
(621, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20842e', '2024-12-24 18:22:10'),
(622, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd208fc7', '2024-12-24 18:22:10'),
(623, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd209933', '2024-12-24 18:22:10'),
(624, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20a2b6', '2024-12-24 18:22:10'),
(625, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20abcd', '2024-12-24 18:22:10'),
(626, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20b423', '2024-12-24 18:22:10'),
(627, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20b9fe', '2024-12-24 18:22:10'),
(628, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20bf7f', '2024-12-24 18:22:10'),
(629, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20d22c', '2024-12-24 18:22:10'),
(630, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20da95', '2024-12-24 18:22:10'),
(631, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20e330', '2024-12-24 18:22:10'),
(632, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20e880', '2024-12-24 18:22:10'),
(633, 228, 70, 'Paket Tour Bandung', '2024-12-27', 20, 'TKT-676afbd20ee2f', '2024-12-24 18:22:10'),
(634, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97e95b4', '2024-12-24 18:25:27'),
(635, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97e9c71', '2024-12-24 18:25:27'),
(636, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ea2ea', '2024-12-24 18:25:27'),
(637, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ea962', '2024-12-24 18:25:27'),
(638, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97eaf88', '2024-12-24 18:25:27'),
(639, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97eb575', '2024-12-24 18:25:27'),
(640, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ebd03', '2024-12-24 18:25:27'),
(641, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ec605', '2024-12-24 18:25:27'),
(642, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ecea3', '2024-12-24 18:25:27'),
(643, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ed5df', '2024-12-24 18:25:27'),
(644, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97edea0', '2024-12-24 18:25:27'),
(645, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ee87e', '2024-12-24 18:25:27'),
(646, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97ef378', '2024-12-24 18:25:27'),
(647, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97efa50', '2024-12-24 18:25:27'),
(648, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97f00e5', '2024-12-24 18:25:27'),
(649, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97f0767', '2024-12-24 18:25:27'),
(650, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97f0dfd', '2024-12-24 18:25:27'),
(651, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97f1443', '2024-12-24 18:25:27'),
(652, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97f1ae2', '2024-12-24 18:25:27'),
(653, 229, 70, 'Paket Tour Bandung', '2024-12-31', 20, 'TKT-676afc97f2138', '2024-12-24 18:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `no_hp`, `password`, `role`, `reset_token`) VALUES
(67, 'a', 'avixleonel@gmail.com', NULL, '$2y$10$/4CZyXeKJKIzcA7uetUfDuvWjPy/GXv3mgVSeBoHY7qmzpT8HIaYa', 'admin', NULL),
(70, 'Mr. Lawak', 'akhdanixe@gmail.com', NULL, '$2y$10$I52YJldVM5rq7BNwGHhQRuDMSknyYs.eiLE2tzFPjZ4myOEKBO7HS', 'user', 'a7b30683382c10e4a6d2bc4392dae28d1345e56a977a31f9f294cd064899d72bd456664aaad62cf47b19009eb8bfecb45723'),
(71, 'Mr. Awikwok', 'awikwok@wok.com', NULL, '$2y$10$mFy63G9CEMv0lFmOV48uDO7rjpzCtUjoPkD5kKwGMGEWlVodpjwPW', 'user', NULL),
(72, 'Mr. Bejir', 'bejir@bejir.com', '085645687528', '$2y$10$RdXGtRoGWUy86aoD3EflEO0b5.l9Y3CMjr./Fupi8H3ju/mLUu4JS', 'user', NULL),
(75, 'admin', 'admin@gmail.com', '083815819330', '$2y$10$KMTTQNGdT4lXWqzsSi7Py.AIT1QrLZILYLq9wrVoVrKIrMNrZ0pVy', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `fk_id_pesanan` (`id_pesanan`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `pesanan_ibfk_1` (`id_paket`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id_tiket`),
  ADD UNIQUE KEY `kode_tiket` (`kode_tiket`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id_tiket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=654;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_id_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
