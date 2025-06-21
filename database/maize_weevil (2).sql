-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 11:06 PM
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
-- Database: `maize_weevil`
--

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

CREATE TABLE `commands` (
  `id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
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
(19, 'fan_off', 'success', '2025-06-12 11:09:55'),
(20, 'fan_off', 'success', '2025-06-12 11:10:51'),
(21, 'fan_off', 'success', '2025-06-13 22:28:36'),
(22, 'fan_off', 'success', '2025-06-13 23:02:22'),
(23, 'fan_on', 'failed', '2025-06-13 23:02:25'),
(24, 'fan_on', 'success', '2025-06-13 23:02:28'),
(25, 'fan_off', 'success', '2025-06-14 13:10:15'),
(26, 'fan_off', 'success', '2025-06-17 20:39:28'),
(27, 'fan_on', 'success', '2025-06-17 20:39:38'),
(28, 'fan_off', 'failed', '2025-06-17 20:58:56'),
(29, 'fan_off', 'failed', '2025-06-17 20:59:00'),
(30, 'fan_on', 'success', '2025-06-17 20:59:03'),
(31, 'fan_on', 'success', '2025-06-17 20:59:12'),
(32, 'fan_off', 'success', '2025-06-17 20:59:15'),
(33, 'fan_off', 'success', '2025-06-17 20:59:19'),
(34, 'fan_off', 'success', '2025-06-17 20:59:27'),
(35, 'fan_on', 'success', '2025-06-17 20:59:31'),
(36, 'fan_on', 'success', '2025-06-21 16:57:41'),
(37, 'fan_on', 'failed', '2025-06-21 17:40:38'),
(38, 'fan_on', 'success', '2025-06-21 17:40:41'),
(39, 'fan_on', 'success', '2025-06-21 19:33:59'),
(40, 'fan_on', 'success', '2025-06-21 19:35:21'),
(41, 'fan_on', 'failed', '2025-06-21 19:45:48'),
(42, 'fan_on', 'success', '2025-06-21 19:45:54'),
(43, 'fan_on', 'success', '2025-06-21 19:47:50'),
(44, 'fan_on', 'success', '2025-06-21 19:47:55'),
(45, 'fan_off', 'failed', '2025-06-21 19:48:00'),
(46, 'fan_on', 'success', '2025-06-21 19:49:17'),
(47, 'fan_on', 'failed', '2025-06-21 19:49:20'),
(48, 'fan_off', 'success', '2025-06-21 19:49:23'),
(49, 'fan_on', 'success', '2025-06-21 19:49:27'),
(50, 'fan_on', 'success', '2025-06-21 19:51:15'),
(51, 'fan_off', 'success', '2025-06-21 19:51:19'),
(52, 'fan_on', 'failed', '2025-06-21 19:51:22'),
(53, 'fan_on', 'success', '2025-06-21 19:51:25'),
(54, 'fan_on', 'failed', '2025-06-21 19:55:14'),
(55, 'fan_on', 'success', '2025-06-21 19:55:21'),
(56, 'fan_on', 'success', '2025-06-21 19:55:59'),
(57, 'fan_on', 'failed', '2025-06-21 19:56:03'),
(58, 'fan_on', 'failed', '2025-06-21 19:56:05'),
(59, 'fan_on', 'success', '2025-06-21 19:56:08'),
(60, 'fan_off', 'success', '2025-06-21 19:56:11'),
(61, 'fan_on', 'success', '2025-06-21 20:14:09'),
(62, 'fan_on', 'success', '2025-06-21 20:52:33'),
(63, 'fan_on', 'success', '2025-06-21 20:52:38'),
(64, 'fan_on', 'failed', '2025-06-21 20:52:40'),
(65, 'fan_on', 'success', '2025-06-21 20:52:44'),
(66, 'fan_on', 'success', '2025-06-21 21:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(11) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(5,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
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
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','farmer') NOT NULL DEFAULT 'farmer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2a$12$HJ.8X29ydV4Lsjxrj2nvZu/6RVrdiI4NiBFLOntUd0JgbOWcNzQcK', 'admin', '2025-05-22 22:04:47'),
(2, 'classic', '$2y$10$nF.xTdWzVDGPx0Zzuvckyeuw7cCEKekXJJb7Hvw4/MrmkxZVbQK/u', 'farmer', '2025-06-17 20:58:30'),
(3, 'regis', '$2y$10$vr6z3x8r4.0ZhT.o7Yjgs.w/gMFOPTYih4mpNOXoXc6SNs2zUFr9q', 'farmer', '2025-06-21 19:36:20'),
(4, 'jesus2', '$2y$10$pSTTZ5NgnCgnL//U0QaTXe0XEFB4lKUXJ5O6RiLJm1rboYygjWeuu', 'farmer', '2025-06-21 19:36:26'),
(5, 'nzizalinker@gmail.com', '$2y$10$cG6SghcaXAYpkzqlH1giv.mQIHB.q5PaRbdMIKo3K22rDiT49B.d2', 'farmer', '2025-06-21 19:36:41');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
