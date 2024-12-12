-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2024 at 05:33 PM
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
(67, 'Paket Tour Bali', 'Bali', '2', '2000000', 'Aktif', 'v csyghjcefc egcbicnwsl', '1733837169_lukisan-merpati.jpg'),
(68, 'Paket Tour Bandung', 'Bandung', '5', '500000', 'Aktif', 'xctfvgbh vygbhunjkml', '../uploads/1733848494_download.jpeg');

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
  `harga_total` decimal(20,2) NOT NULL,
  `tanggal_perjalanan` date DEFAULT NULL,
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_transfer` varchar(255) NOT NULL,
  `status_pesanan` enum('Pending','Dikonfirmasi','Dibatalkan') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_paket`, `user_id`, `nama_pemesan`, `email`, `jumlah_peserta`, `harga_total`, `tanggal_perjalanan`, `tanggal_pesan`, `foto_transfer`, `status_pesanan`) VALUES
(34, 67, 70, 'Akhdan Subagja', 'akhdanixe@gmail.com', 65, 130000000.00, '2024-12-26', '2024-12-10 15:46:03', '', 'Pending'),
(37, 68, 71, 'Mr. Awikwok', 'awikwok@wok.com', 1, 500000.00, '2024-12-27', '2024-12-10 16:40:15', '', 'Pending');

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
(70, 'Akhdan Subagja', 'akhdanixe@gmail.com', '$2y$10$I52YJldVM5rq7BNwGHhQRuDMSknyYs.eiLE2tzFPjZ4myOEKBO7HS', 'user', NULL),
(71, 'Mr. Awikwok', 'awikwok@wok.com', '$2y$10$mFy63G9CEMv0lFmOV48uDO7rjpzCtUjoPkD5kKwGMGEWlVodpjwPW', 'user', NULL);

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
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
