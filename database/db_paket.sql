-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 06:56 AM
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
(4, 'Paket Surga Raja Ampat', 'Raja Ampat', '7', '8500000', 'Aktif', '0', NULL),
(7, 'Surakarta', 'Surabaya', '7', '2400000', 'Aktif', '0', NULL),
(12, 'nrsnrsdnsrg', 'test', '2', '2222222', 'Aktif', '0', '1733308626_zlogo.png'),
(13, 'Paket Tour Bandung', 'Bandung', '3', '7500000', 'Aktif', '0', '1733308626_zlogo.png'),
(14, 'Paket paketan', 'Gatau', '4', '5500000', 'Aktif', 'xdrctfvygbuh', '1733336800_56f35306-75a5-4943-ba31-d183a4af0b7c.jpg'),
(20, 'res', 'cd', '3', '2020202', 'Aktif', '1733339463', 'nhbrgvefs'),
(21, 'ressvsvs', 'vvevev', '4', '1515151', 'Aktif', 'tnhbgrvfdcsx', '1733339712');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_pemesan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `harga_total` decimal(10,2) NOT NULL,
  `tanggal_perjalanan` date DEFAULT NULL,
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_transfer` varchar(255) NOT NULL,
  `status_pesanan` enum('Pending','Dikonfirmasi','Dibatalkan') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_paket`, `user_id`, `nama_pemesan`, `email`, `jumlah_peserta`, `harga_total`, `tanggal_perjalanan`, `tanggal_pesan`, `foto_transfer`, `status_pesanan`) VALUES
(13, 7, 66, 'a', 'akhdanixe@gmail.com', 200, 99999999.99, '2025-01-01', '2024-11-29 17:29:47', '13_1732901398.jpg', 'Dikonfirmasi'),
(22, 4, 66, 'a', 'akhdanixe@gmail.com', 8, 68000000.00, '2024-12-27', '2024-12-04 18:00:07', '22_1733335215.png', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_custom`
--

CREATE TABLE `pesanan_custom` (
  `id_custom_order` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `nama_pemesan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `preferensi_hotel` text DEFAULT NULL,
  `tanggal_perjalanan` date DEFAULT NULL,
  `aktivitas_tambahan` text DEFAULT NULL,
  `harga_custom` decimal(10,2) NOT NULL,
  `status_pesanan` enum('Pending','Diproses','Selesai') DEFAULT 'Pending',
  `tanggal_pesan` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(2, 'afafat', 'fau@gmail.com', 'fcc95dc4857501665b5eddf95a3344f0', 'admin'),
(3, 'fufufafa', 'fufu@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$S3E0Z3E4ZzFkZVhmWU1FTw$wFd4zdy5k7+QUGCeceZsuvvaCKk34+zyj3UXQS4ZOvQ', 'user'),
(5, 'fufufafa', 'gdygdy@gmail.com', 'b49af1c38353da751daa03311e05abea', 'user'),
(6, 'fufufafa', 'gdy@gmail.com', 'e66b7a96ed55bce8d7a4d11854cc6bca', 'user'),
(7, 'fufufafa', 'gyqo@gmail.com', '3da4f5acbf20cc5bfb67de13c9b70376', 'user'),
(8, 'fufufafa', 'gyq@gmail.com', '7d6d227d6a3b3723ec571d8166586738', 'user'),
(9, 'fufufafa', 'gyqr@gmail.com', 'edc879f290da45e2a62acaf7b165fceb', 'user'),
(10, 'fufufafa', 'gytt@gmail.com', '9227fea122ab8bd9cadaf2ca73752c8d', 'user'),
(11, 'fufufafa', 'ufuhfuu@gmail.com', '48de35d5855b4330352464c2b7994b35', 'user'),
(55, 'Oji', 'fufu@gmail.om', '0dad6b705ed6bbcee824ab17ea19dd90', 'admin'),
(66, 'a', 'akhdanixe@gmail.com', '$2y$10$O.yE3jVr/0FZxWrGxkJmoO6/Cv4t3zM9B511AtTMNFtyOI9xgxAWq', 'user'),
(67, 'a', 'avixleonel@gmail.com', '$2y$10$mJ0Fmp4Nbt8N.ZXntmwy3ufU9PPERSJOE4RU.JRJoDDAxDJWL76Ry', 'admin'),
(68, 'Akhdan Subagja', 'dan@dan.com', '$2y$10$LqCGvR2lptJ/sn1Kb.7Za.E2V68e9OTq5B2IZlVNeFGISiP6pzEHS', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_ibfk_1` (`id_paket`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pesanan_custom`
--
ALTER TABLE `pesanan_custom`
  ADD PRIMARY KEY (`id_custom_order`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id_paket` (`id_paket`);

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
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pesanan_custom`
--
ALTER TABLE `pesanan_custom`
  MODIFY `id_custom_order` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan_custom`
--
ALTER TABLE `pesanan_custom`
  ADD CONSTRAINT `pesanan_custom_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pesanan_custom_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
