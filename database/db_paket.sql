-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 07:55 AM
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
(69, 'Pesanan baru telah diterima untuk paket ID: 72 oleh Mr. Lawak', 1, '2024-12-17 06:50:36', 118),
(70, 'Pesanan baru telah diterima untuk paket ID: 72 oleh Mr. Lawak', 0, '2024-12-17 06:50:39', 119),
(71, 'Pesanan baru telah diterima untuk paket ID: 71 oleh Mr. Lawak', 0, '2024-12-17 06:50:46', 120),
(72, 'Pesanan baru telah diterima untuk paket ID: 71 oleh Mr. Lawak', 0, '2024-12-17 06:50:49', 121),
(73, 'Pesanan baru telah diterima untuk paket ID: 67 oleh Mr. Lawak', 0, '2024-12-17 06:50:57', 122),
(74, 'Pesanan baru telah diterima untuk paket ID: 67 oleh Mr. Lawak', 0, '2024-12-17 06:51:00', 123);

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
  `status_pesanan` enum('Pending','Dikonfirmasi','Dibatalkan','Selesai') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_paket`, `user_id`, `nama_pemesan`, `email`, `jumlah_peserta`, `harga_total`, `tanggal_perjalanan`, `tanggal_pesan`, `foto_transfer`, `status_pesanan`) VALUES
(118, 72, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 40000000.00, '2024-12-31', '2024-12-17 06:50:36', '', 'Dikonfirmasi'),
(119, 72, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 40000000.00, '2024-12-30', '2024-12-17 06:50:39', '', 'Pending'),
(120, 71, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 100000000.00, '2024-12-29', '2024-12-17 06:50:46', '', 'Pending'),
(121, 71, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 100000000.00, '2024-12-28', '2024-12-17 06:50:49', '', 'Pending'),
(122, 67, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 40000000.00, '2024-12-27', '2024-12-17 06:50:57', '', 'Pending'),
(123, 67, 70, 'Mr. Lawak', 'akhdanixe@gmail.com', 20, 40000000.00, '2024-12-26', '2024-12-17 06:51:00', '', 'Pending');

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
  `role` enum('user','admin') DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `reset_token`) VALUES
(55, 'Oji', 'fufu@gmail.om', '0dad6b705ed6bbcee824ab17ea19dd90', 'admin', NULL),
(67, 'a', 'avixleonel@gmail.com', '$2y$10$/4CZyXeKJKIzcA7uetUfDuvWjPy/GXv3mgVSeBoHY7qmzpT8HIaYa', 'admin', NULL),
(68, 'Akhdan Subagja', 'dan@dan.com', '$2y$10$LqCGvR2lptJ/sn1Kb.7Za.E2V68e9OTq5B2IZlVNeFGISiP6pzEHS', 'user', NULL),
(69, 'a', 'tidakberkah6@gmail.com', '$2y$10$7gFxiVZZa0w8kvdQ8O053e1yvoWdBgq4/e1Ufw4PCluyXRODmxW6G', 'user', NULL),
(70, 'Mr. Lawak', 'akhdanixe@gmail.com', '$2y$10$I52YJldVM5rq7BNwGHhQRuDMSknyYs.eiLE2tzFPjZ4myOEKBO7HS', 'user', 'a7b30683382c10e4a6d2bc4392dae28d1345e56a977a31f9f294cd064899d72bd456664aaad62cf47b19009eb8bfecb45723'),
(71, 'Mr. Awikwok', 'awikwok@wok.com', '$2y$10$mFy63G9CEMv0lFmOV48uDO7rjpzCtUjoPkD5kKwGMGEWlVodpjwPW', 'user', NULL);

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
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `pesanan_custom`
--
ALTER TABLE `pesanan_custom`
  MODIFY `id_custom_order` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

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
-- Constraints for table `pesanan_custom`
--
ALTER TABLE `pesanan_custom`
  ADD CONSTRAINT `pesanan_custom_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pesanan_custom_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
