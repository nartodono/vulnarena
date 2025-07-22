-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: vulnarena-mysql:3306
-- Generation Time: Jun 08, 2025 at 07:18 PM
-- Server version: 8.4.5
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vulnarenalab`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(128) NOT NULL,
  `product_stock` int NOT NULL DEFAULT '0',
  `product_price` bigint NOT NULL,
  `product_category` varchar(64) NOT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_stock`, `product_price`, `product_category`, `is_hidden`) VALUES
(1, 'Smasnug Galak Sih U25', 13, 19000000, 'Handphone', 0),
(2, 'Iphoney 16 Min Max', 9, 15999000, 'Handphone', 0),
(3, 'Kaos Uniqnih', 22, 800000, 'Fashion', 0),
(4, 'Sepatu Adadus Ultra Boost', 17, 1250000, 'Fashion', 0),
(5, 'Tas Luis Fitnes', 41, 2500000, 'Fashion', 0),
(6, 'Nasi Padang Asli Sunda', 57, 45000, 'Food', 0),
(7, 'Kopi Sejuta Hutang', 120, 20000, 'Food', 0),
(8, 'Do-Mana Pizza Makaroni', 32, 89000, 'Food', 0),
(9, 'Rokok Filterless 2077', 77, 24000, 'Food', 0),
(10, 'Teh Botol Kotak', 250, 7000, 'Food', 0),
(11, 'Mata Bionic', 2, 800000000, 'Black Market', 1),
(12, 'Ginjal Manusia', 4, 2000000000, 'Black Market', 1),
(13, 'Jantung Siber', 1, 3500000000, 'Black Market', 1),
(14, 'Data Penduduk Nasional', 8, 1200000000, 'Black Market', 1),
(15, 'Identitas Palsu Internasional', 11, 500000000, 'Black Market', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isadmin` int NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `isadmin`, `nickname`) VALUES
(1, 'admin', 'admin123', 1, 'administrator'),
(2, 'john', 'smith', 0, 'john smith'),
(3, 'superuser', 'supersecret', 1, 'super admin'),
(4, 'alice', 'beautiful', 0, 'alice'),
(5, 'jack29', '20291998', 0, 'Jack Albert'),
(6, 'mawar', '558_Mawar', 0, 'Mawar Sintia'),
(7, 'stark', 'ILOVEYOU3000', 0, 'Tony Stark'),
(8, 'tester', 'test123', 0, 'terster#1'),
(9, 'risky', 'jagoan123', 0, 'noobmaster666');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `ktp` varchar(20) DEFAULT NULL,
  `rekening` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `email`, `phone`, `address`, `birthdate`, `ktp`, `rekening`) VALUES
(1, 1, 'admin@vulnarena.space', '081234567890', 'Jl. Admin 1, Vulnarena', '1999-01-01', '1234567890123456', '1234567890'),
(2, 2, 'jsmith@vulnarena.space', '082199301818', 'Jl. Doang Ga Jadian 3x', '2001-02-02', '2345678901234567', '2233445566'),
(3, 3, 'superuser@vulnarena.space', '083456789012', 'Jl. Admin 3, Vulnarena', '1998-03-03', '3456789012345678', '3344556677'),
(4, 4, 'alicekxy221@vulnarena.space', '084993222051', 'Jl. -In Aja Dulu', '2003-04-04', '4567890123456789', '4455667788'),
(5, 5, 'jack29@vulnarena.space', '085577799911', 'Jl. Sama Kamu Tapi Hati Sama Dia', '2002-05-05', '5678901234567890', '5566778899'),
(6, 6, 'mawar_sintia@vulnarena.space', '086688800022', 'Jl. Penantian Tak Berujung', '1998-06-06', '6789012345678901', '6677889900'),
(7, 7, 'stark3000@vulnarena.space', '08730003000', 'Jl. Avengers, New York', '2000-07-07', '7890123456789012', '7788990011'),
(8, 8, 'tester1@vulnarena.space', '088888888880', 'Jl. Tanpa Status', '2004-08-08', '8901234567890123', '8899001122'),
(9, 9, 'risky_nm666@vulnarena.space', '089911133255', 'Jl. Bersama Tapi Beda Tujuan', '2005-09-09', '9012345678901234', '9900112233');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
