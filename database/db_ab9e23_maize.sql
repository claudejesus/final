-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: MYSQL1002.site4now.net
-- Generation Time: Jun 21, 2025 at 02:05 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ab9e23_maize`
--

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

CREATE TABLE `commands` (
  `id` int NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','success','failed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commands`
--

INSERT INTO `commands` (`id`, `action`, `status`, `timestamp`) VALUES
(1, 'fan_on', 'success', '2025-05-22 22:06:38'),
(2, 'fan_on', 'success', '2025-05-22 22:06:38'),
(3, 'fan_off', 'success', '2025-05-22 22:06:39'),
(4, 'fan_off', 'success', '2025-05-22 22:06:42'),
(5, 'fan_off', 'success', '2025-05-22 22:06:43'),
(6, 'fan_on', 'success', '2025-05-22 22:06:45'),
(7, 'fan_on', 'success', '2025-05-22 22:06:46'),
(8, 'fan_on', 'success', '2025-05-22 22:06:47'),
(9, 'fan_on', 'failed', '2025-05-22 22:06:49'),
(10, 'fan_on', 'failed', '2025-05-22 22:06:50'),
(11, 'fan_off', 'success', '2025-05-22 22:06:54'),
(12, 'fan_on', 'success', '2025-05-22 22:10:41'),
(13, 'fan_on', 'success', '2025-05-22 22:14:28'),
(14, 'fan_on', 'failed', '2025-05-22 22:15:26'),
(15, 'fan_off', 'failed', '2025-05-22 22:15:35'),
(16, 'fan_on', 'success', '2025-05-22 22:18:14'),
(17, 'fan_off', 'success', '2025-05-22 22:18:17'),
(18, 'fan_on', 'failed', '2025-05-22 22:19:05'),
(19, 'fan_on', 'success', '2025-05-23 20:07:59'),
(20, 'fan_on', 'success', '2025-05-23 20:39:03'),
(21, 'fan_on', 'failed', '2025-05-24 14:32:21'),
(22, 'fan_off', 'failed', '2025-05-24 14:32:34'),
(23, 'fan_off', 'failed', '2025-05-24 14:35:14'),
(24, 'fan_on', 'success', '2025-05-24 14:35:16'),
(25, 'fan_on', 'success', '2025-05-24 22:15:04'),
(26, 'fan_on', 'failed', '2025-05-24 22:15:04'),
(27, 'fan_on', 'success', '2025-05-24 22:15:04'),
(28, 'fan_on', 'failed', '2025-05-24 22:15:17'),
(29, 'fan_on', 'success', '2025-05-24 22:15:18'),
(30, 'fan_on', 'failed', '2025-05-24 23:10:12'),
(31, 'fan_on', 'success', '2025-05-24 23:28:57'),
(32, 'fan_on', 'success', '2025-05-25 19:34:34'),
(33, 'fan_off', 'success', '2025-05-25 19:34:35'),
(34, 'fan_off', 'success', '2025-05-25 19:34:36'),
(35, 'fan_on', 'success', '2025-05-26 10:34:48'),
(36, 'fan_off', 'failed', '2025-05-26 10:35:01'),
(37, 'fan_on', 'success', '2025-05-26 22:20:00'),
(38, 'fan_off', 'success', '2025-05-26 22:20:07'),
(39, 'fan_on', 'success', '2025-05-26 22:20:23'),
(40, 'fan_on', 'success', '2025-05-26 22:20:44'),
(41, 'fan_off', 'failed', '2025-05-26 22:20:47'),
(42, 'fan_off', 'success', '2025-05-26 22:20:52'),
(43, 'fan_off', 'success', '2025-05-26 22:20:57'),
(44, 'fan_on', 'failed', '2025-05-26 22:21:00'),
(45, 'fan_off', 'success', '2025-05-26 22:21:05'),
(46, 'fan_off', 'success', '2025-05-29 17:33:49'),
(47, 'fan_on', 'failed', '2025-05-29 17:37:16'),
(48, 'fan_on', 'failed', '2025-05-29 17:37:21'),
(49, 'fan_on', 'success', '2025-05-29 17:37:30'),
(50, 'fan_on', 'success', '2025-05-29 17:37:37'),
(51, 'fan_off', 'success', '2025-05-29 17:37:40'),
(52, 'fan_on', 'success', '2025-06-03 11:16:41'),
(53, 'fan_off', 'success', '2025-06-03 11:18:04'),
(54, 'fan_off', 'success', '2025-06-03 11:18:15'),
(55, 'fan_on', 'failed', '2025-06-11 22:47:24'),
(56, 'fan_on', 'success', '2025-06-11 22:47:28'),
(57, 'fan_off', 'success', '2025-06-11 22:47:32'),
(58, 'fan_off', 'success', '2025-06-12 07:27:56'),
(59, 'fan_on', 'success', '2025-06-12 07:28:04'),
(60, 'fan_off', 'success', '2025-06-13 22:32:55'),
(61, 'fan_on', 'success', '2025-06-20 22:28:43'),
(62, 'fan_on', 'success', '2025-06-21 20:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(5,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `temperature`, `humidity`, `timestamp`) VALUES
(1, 65.90, 5.00, '2025-05-16 17:22:29'),
(2, 60.90, 23.00, '2025-05-22 22:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','farmer') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'farmer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2a$12$r2Xe7A8Gu0l9OWic/lfVMuewQoDCNCFMHjkP0j3EcDZj3PFKbPXZa', 'admin', '2025-05-22 22:04:47'),
(2, 'jesus', '$2a$12$6ObpjoTfERiz89L.Efn.bepjYIp15VmTPwUNRlw6j024uv.kPc5YS', 'farmer', '2025-05-23 20:06:38'),
(4, 'jesus2', '$2a$12$6ObpjoTfERiz89L.Efn.bepjYIp15VmTPwUNRlw6j024uv.kPc5YS', 'admin', '2025-05-24 22:57:30'),
(5, 'regis', '$2y$10$M08PMZpUkEsksHE1tFWPQ.cXyMdGewKvYx47WDOCDkQpKROiwXdsq', 'farmer', '2025-05-26 10:34:15'),
(6, 'eric', '$2y$10$Ekwh9b.mmMhrq05ryfRQfOXUvAMAisc6X7vDIdrQq7redwhK2OP2u', 'farmer', '2025-05-26 22:19:52'),
(7, 'dieudonne', '$2y$10$MahSIE6XMZzbF83VKKmQOePoN09kifaaoMCDzZ4Sd74ougT5F0z5K', 'farmer', '2025-05-29 17:34:29'),
(8, 'ert78u', '$2y$10$Ro2Z259CVUhcUHNI5dXhP.cJy3XL6S8Bsx5rcY4zZkbbTKDkitY.G', 'farmer', '2025-05-29 17:36:45'),
(9, 'claude', '$2y$10$QbtogHffWrvnQnqqWpOD7.vuENQX2tHjI/rqEZJKVrtBQH5CQPGWK', 'farmer', '2025-06-11 22:47:17'),
(10, 'farmer1', '$2y$10$6XMAsHCgtkNg57PRAgwj1uytJsQUcD7opHyy/..sy3HrhXGmgnPpK', 'farmer', '2025-06-12 15:07:02'),
(11, 'farmer2', '$2y$10$0qoBrFDz2ezGzhD9wB7/qek99pFV5R4fJ3ySdno.2R6gV1Z9ODKny', 'farmer', '2025-06-12 15:07:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commands`
--
ALTER TABLE `commands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
