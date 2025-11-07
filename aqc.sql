-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 08:19 AM
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
-- Database: `aqc`
--

-- --------------------------------------------------------

--
-- Table structure for table `apple_data`
--

CREATE TABLE `apple_data` (
  `apple_id` int(11) NOT NULL,
  `size` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `sweetness` float DEFAULT NULL,
  `crunchiness` float DEFAULT NULL,
  `juiciness` float DEFAULT NULL,
  `ripeness` float DEFAULT NULL,
  `acidity` float DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quality` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confidence` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apple_data`
--

INSERT INTO `apple_data` (`apple_id`, `size`, `weight`, `sweetness`, `crunchiness`, `juiciness`, `ripeness`, `acidity`, `user_id`, `quality`, `created_at`, `confidence`) VALUES
(9, 5, 5, 5, 7, 7, 7, 7, 14, 'good', '2025-05-10 03:58:41', 71.00),
(10, -2.63, -2.14, -2.44, 0.66, 2.2, 4.76, -1.33, 14, 'bad', '2025-05-10 04:00:40', 99.00),
(11, -2, -3, -5, -7, -1, -5, -5, 14, 'good', '2025-05-10 04:03:22', 57.00),
(12, -7.15, 3, 6, 6, 4, 2, 5, 14, 'bad', '2025-05-10 04:40:01', 67.00),
(13, 2, 3, 4, 5, 6, 6, 6, 14, 'good', '2025-05-10 05:01:06', 69.00),
(14, 1, 2, 3, 4, 5, 6, 7, 14, 'good', '2025-05-10 05:03:31', 64.00),
(16, -1, -5, -6, -2, -4, -4, -7, 16, 'bad', '2025-05-10 05:07:34', 55.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `birthdate` date NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `lastname`, `firstname`, `middlename`, `gender`, `birthdate`, `contact_no`, `username`, `password`, `role`) VALUES
(13, 'mabatid', 'spc', 'premacio', 'm', '2025-04-04', '099291353809', 'sayl@gmail.com', '$2y$10$DLDM1dHgWFG3DXNyPwcHbusGtbHJIvBlf1LYHDI.i6tXrX0ymzKz.', 'admin'),
(14, 'Garcia', 'Joseph Ric', 'Alipoyo', 'm', '2025-05-09', '09933885730', 'sep@gmail.com', '$2y$10$Mk6zTQirJMGrMHbBewry9OQPLTJCJhKZUqt061vPO2hA9navqtyDG', 'admin'),
(16, 'Ramos', 'Marian', 'Boyonas', 'f', '2025-05-10', '09123456789', 'marian@email.com', '$2y$10$CICq.MFCW9Ajg059d4mZaeSAiKfpSclHrjkv/FYkQMuuPKrONgjPG', 'user'),
(17, 'Garcia', 'Joseph Ric', 'Boyonas', 'm', '2025-05-10', '09123123454', 'josephricgarcia@gmail.com', '$2y$10$QzLYnIkRJ0ygLqesyUvIxOO0IuO3IcT8fCIeGX4Yq0FUSBiOGENua', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apple_data`
--
ALTER TABLE `apple_data`
  ADD PRIMARY KEY (`apple_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apple_data`
--
ALTER TABLE `apple_data`
  MODIFY `apple_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
