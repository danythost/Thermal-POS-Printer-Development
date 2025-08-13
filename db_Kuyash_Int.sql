-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 05, 2025 at 09:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `your_DB_Here`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='admin_user_Login';

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `email`, `passwd`, `created`) VALUES
(1, 'user@kuyash.com', '$2y$10$4ty7FBigsUtINyW1qvzC/.UK6g7/2tSHskr8TObfd1REDFmbfZTQu', '2025-02-04 19:42:36'),
(2, 'admin@kuyash.com', '$2y$10$vGIL8b3V/NoRsQQiK6EQqO5MCOzsenjzoN1VLZ/i5hk6f2TqEr4oC', '2025-02-04 19:49:58');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `created_at`) VALUES
(27, 'Henry Gandu', '08068622951', 'ABU, NAPRI QTrs, Shika', '2025-02-04 20:34:45'),
(28, 'coby Bryant', '07949377`', 'USA West CaliFornia', '2025-02-04 21:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `total_amount`, `discount`, `grand_total`, `created_at`) VALUES
(19, 27, 17000.00, 18000.00, 8000.00, '2025-02-04 20:34:45'),
(24, 27, 0.00, 0.00, 0.00, '2025-02-04 21:28:59'),
(25, 28, 0.00, 0.00, 0.00, '2025-02-04 21:40:52'),
(26, 28, 0.00, 0.00, 0.00, '2025-02-04 21:49:55'),
(27, 27, 0.00, 0.00, 0.00, '2025-02-04 22:40:40'),
(28, 27, 0.00, 0.00, 0.00, '2025-02-05 01:45:10'),
(29, 27, 0.00, 0.00, 0.00, '2025-02-05 06:06:14'),
(30, 27, 0.00, 0.00, 0.00, '2025-02-05 06:12:56'),
(31, 27, 0.00, 0.00, 0.00, '2025-02-05 06:20:12'),
(32, 27, 0.00, 0.00, 0.00, '2025-02-05 06:22:18'),
(33, 27, 0.00, 0.00, 0.00, '2025-02-05 06:23:25'),
(34, 27, 0.00, 0.00, 0.00, '2025-02-05 06:27:02'),
(35, 27, 0.00, 0.00, 0.00, '2025-02-05 07:17:04'),
(36, 28, 0.00, 0.00, 0.00, '2025-02-05 08:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  `net_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `unit_price`, `discount`, `total_price`, `net_total`, `discount_amount`) VALUES
(38, 19, 1, 3, 3000.00, 18000.00, 9000.00, 0.00, 0.00),
(39, 19, 2, 2, 4000.00, 0.00, 8000.00, 8000.00, 0.00),
(40, 24, 1, 3, 3000.00, 0.00, 9000.00, 0.00, 0.00),
(41, 24, 2, 3, 4000.00, 0.00, 12000.00, 0.00, 0.00),
(42, 25, 1, 3, 3000.00, 0.00, 9000.00, 0.00, 0.00),
(43, 25, 2, 2, 4000.00, 0.00, 8000.00, 0.00, 0.00),
(44, 26, 2, 3, 4000.00, 0.00, 12000.00, 0.00, 0.00),
(45, 26, 3, 7, 7000.00, 0.00, 49000.00, 0.00, 0.00),
(46, 26, 6, 3, 9300.00, 0.00, 27900.00, 0.00, 0.00),
(47, 27, 2, 2, 4000.00, 0.00, 8000.00, 0.00, 0.00),
(48, 27, 3, 4, 7000.00, 0.00, 28000.00, 0.00, 0.00),
(49, 27, 7, 6, 6300.00, 0.00, 37800.00, 0.00, 0.00),
(50, 28, 1, 4, 3000.00, 0.00, 12000.00, 0.00, 0.00),
(51, 28, 2, 3, 4000.00, 0.00, 12000.00, 0.00, 0.00),
(52, 29, 1, 4, 3000.00, 0.00, 12000.00, 0.00, 0.00),
(53, 29, 4, 3, 6500.00, 0.00, 19500.00, 0.00, 0.00),
(54, 30, 1, 5, 3000.00, 0.00, 15000.00, 0.00, 0.00),
(55, 30, 2, 3, 4000.00, 0.00, 12000.00, 0.00, 0.00),
(56, 31, 3, 4, 7000.00, 0.00, 28000.00, 0.00, 0.00),
(57, 31, 2, 2, 4000.00, 0.00, 8000.00, 0.00, 0.00),
(58, 32, 1, 4, 3000.00, 0.00, 12000.00, 0.00, 0.00),
(59, 32, 2, 2, 4000.00, 0.00, 8000.00, 0.00, 0.00),
(60, 33, 2, 4, 4000.00, 0.00, 16000.00, 0.00, 0.00),
(61, 33, 1, 3, 3000.00, 0.00, 9000.00, 0.00, 0.00),
(62, 34, 1, 4, 3000.00, 0.00, 12000.00, 0.00, 0.00),
(63, 34, 2, 3, 4000.00, 0.00, 12000.00, 0.00, 0.00),
(64, 35, 2, 4, 4000.00, 0.00, 16000.00, 0.00, 0.00),
(65, 35, 1, 3, 3000.00, 0.00, 9000.00, 0.00, 0.00),
(66, 36, 1, 3, 3000.00, 0.00, 9000.00, 0.00, 0.00),
(67, 36, 2, 6, 4000.00, 0.00, 24000.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `item_name`, `unit_price`, `discount`, `discount_percentage`) VALUES
(1, 'paper-crate', 3000.00, 200.00, 0.00),
(2, 'egg', 4000.00, 0.00, 0.00),
(3, 'fish', 7000.00, 700.00, 0.00),
(4, 'iceblock', 6500.00, 400.00, 0.00),
(5, 'Dairy-milk', 5000.00, 0.00, 0.00),
(6, 'broilers', 9300.00, 400.00, 0.00),
(7, 'Spent-Layers', 6300.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='users_to_access_system';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `passwd`, `role`, `created`) VALUES
(1, 'Jon Gandu', 'jon@email.com', '$2y$10$2XueSucJ3c9Wv6OJV6I2ouxxPWE6BEPd6wJgSxDvu7iclaIyqNAVe', 'Manager', '2025-02-03 00:11:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
