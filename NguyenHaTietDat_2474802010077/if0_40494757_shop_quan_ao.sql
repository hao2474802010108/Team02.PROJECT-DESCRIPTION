-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql104.byetcluster.com
-- Generation Time: Nov 24, 2025 at 01:29 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40494757_shop_quan_ao`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `country`) VALUES
(1, 'Brand A', 'Vietnam'),
(2, 'Brand B', 'China');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` enum('S','M','L','XL','XXL') DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`) VALUES
(1, 'Áo', 'Các loại áo', 1),
(2, 'Quần', 'Các loại quần', 1),
(3, 'Váy', 'Các loại váy', 1),
(4, 'Phụ kiện', 'Các loại phụ kiện thời trang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_phone` varchar(15) NOT NULL,
  `status` enum('pending','confirmed','shipping','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `size` enum('S','M','L','XL','XXL') DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `size` enum('S','M','L','XL','XXL') DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `material` varchar(50) DEFAULT NULL,
  `gender` enum('nam','nu','unisex') DEFAULT 'unisex',
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('available','out_of_stock','discontinued') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `original_price`, `stock_quantity`, `category_id`, `brand_id`, `size`, `color`, `material`, `gender`, `image_url`, `status`, `created_at`) VALUES
(1, 'Quần Lót Lọt Khe Da Báo', 'Quần Lót Lọt Khe Họa Tiết Da Bao Siêu Sexy!!', '5000000.00', '500000.00', 1, 2, 1, 'XXL', 'Da Báo', 'Su Da Beo', 'nu', 'images/dabao.jpg', 'available', '2025-11-22 14:37:26'),
(2, 'Áo Sơ Mi Quagmire', 'Áo Sơ Mi Có Họa Tiết Quagmire!!', '500000.00', '600000.00', 10, 1, 1, NULL, 'Đỏ', 'Giggity', 'unisex', 'images/quagmire.png', 'available', '2025-11-22 14:48:29'),
(3, 'Áo Phao', 'Áo Phao Cứu Trợ.', '50000.00', '50000.00', 100, 1, 1, NULL, 'Cam', 'Phao', 'unisex', 'images/aophao.jpg', 'available', '2025-11-22 15:06:38'),
(4, 'Áo Thun', 'Áo Thun Họa Tiết Hình Đi Chơi', '100000.00', '100000.00', 50, 1, 1, NULL, NULL, 'cotton', 'unisex', 'images/aothundichoi.jpg', 'available', '2025-11-22 15:09:37'),
(5, 'Áo Đại Bàng', 'Áo Họa Tiết Hình Đại Bàng', '300000.00', '300000.00', 10, 1, 1, NULL, NULL, NULL, 'unisex', 'images/aodb.jpg', 'available', '2025-11-22 15:10:19'),
(6, 'Áo Sói', 'Áo Họa Tiết Hình Con Sói', '350000.00', '350000.00', 18, 1, 1, NULL, NULL, 'cotton', 'unisex', 'images/aosoi.jpg', 'available', '2025-11-22 15:11:04'),
(7, 'Áo Chống Nắng', 'Áo Chống Nắng, Chống Tia UV', '250000.00', '250000.00', 36, 1, 1, NULL, NULL, 'Nỉ', 'unisex', 'images/aochongnang.jpg', 'available', '2025-11-22 15:12:45'),
(8, 'Áo ONTOP', 'Áo Họa Tiết ONTOP, Local Brand Made In China', '150000.00', '150000.00', 150, 1, 1, NULL, 'Đen', 'cotton', 'unisex', 'images/aolocalbrand.jpg', 'available', '2025-11-22 15:14:11'),
(9, 'Áo Manchester United', 'Áo Của Những Công Dân Trong Hang', '700000.00', '700000.00', 0, 1, 1, NULL, 'Đỏ', 'cotton', 'unisex', 'images/aomu.jpg', 'out_of_stock', '2025-11-22 15:16:21'),
(10, 'Áo Mưa', 'Áo Mưa', '100000.00', '100000.00', 100, 1, 1, NULL, NULL, 'Dù', 'unisex', 'images/aomua.jpg', 'available', '2025-11-22 15:18:37'),
(11, 'Áo Sơ Mi Nam', 'Áo Sơ Mi Trắng Cho Nam Giới', '100.00', '250000.00', 0, 1, 1, NULL, 'Trắng', NULL, 'nam', 'images/aosominam.jpg', 'available', '2025-11-22 15:19:44'),
(12, 'Áo Sơ Mi Nữ', 'Áo Sơ Mi Cho Nữ', '250000.00', '250000.00', 100, 1, 1, NULL, NULL, NULL, 'nu', 'images/aosominu.jpg', 'available', '2025-11-22 15:20:21'),
(13, 'Áo Bomber Lót Lông', 'Áo Bomber Lót Lông Siêu Ấm', '500000.00', '500000.00', 67, 1, 1, NULL, 'Đen', NULL, 'unisex', 'images/bombernam.jpg', 'available', '2025-11-22 15:21:29'),
(14, 'Áo Bomber', 'Áo Bomber', '350000.00', '350000.00', 75, 1, 1, NULL, NULL, 'Dù', 'unisex', 'images/bombernu.jpg', 'available', '2025-11-22 15:22:15'),
(15, 'Quần Jean Nam', 'Quần Jean Dành Cho Nam Giới', '450000.00', '450000.00', 56, 2, 2, NULL, NULL, 'Jean', 'nam', 'images/quanjeannam.jpg', 'available', '2025-11-22 15:23:43'),
(16, 'Quần Jean Nữ', 'Quần Jean Dành Cho Nữ Giới', '400000.00', '400000.00', 65, 2, 2, NULL, NULL, 'Jean', 'nu', 'images/quanjeannu.jpg', 'available', '2025-11-22 15:24:24'),
(17, 'Quần Jean Rách', 'Quần Jean Rách', '400000.00', '400000.00', 34, 2, 2, NULL, NULL, 'Jean', 'unisex', 'images/quanjueanrach.jpg', 'available', '2025-11-22 15:25:16'),
(18, 'Quần Short Nam', 'Quần Short Dành Cho Nam Giới', '250000.00', '250000.00', 89, 2, 2, NULL, NULL, 'Nỉ', 'nam', 'images/quanshortnam.jpg', 'available', '2025-11-22 15:25:56'),
(19, 'Quần Short Nữ', 'Quần Short Dành Cho Nữ Giới', '250000.00', '250000.00', 87, 2, 2, NULL, NULL, 'Jean', 'nu', 'images/quanshortnu.jpg', 'available', '2025-11-22 15:26:37'),
(20, 'Quần Tây Nam', 'Quần Tây Dành Cho Nam Giới', '400000.00', '400000.00', 96, 2, 2, NULL, NULL, NULL, 'nam', 'images/quantaynam.png', 'available', '2025-11-22 15:27:10'),
(21, 'Quần Tây Nữ', 'Quần Tây Dành Cho Nữ Giới', '400000.00', '400000.00', 95, 2, 2, NULL, NULL, NULL, 'nu', 'images/quantaynu.jpg', 'available', '2025-11-22 15:27:39'),
(22, 'Váy Dài Nữ', 'Váy Dài Cho Nữ', '300000.00', '300000.00', 40, 3, 1, NULL, NULL, 'Cotton', 'nu', 'images/vaydai.jpg', 'available', '2025-11-22 15:28:00'),
(23, 'Váy Ngắn Nữ', 'Váy Ngắn Cho Nữ', '250000.00', '250000.00', 60, 3, 1, NULL, NULL, 'Cotton', 'nu', 'images/vayngan.jpg', 'available', '2025-11-22 15:28:30'),
(24, 'Thắt Lưng Nam', 'Thắt Lưng Da', '150000.00', '150000.00', 100, 4, 2, NULL, NULL, 'Da', 'nam', 'images/thatlungnam.jpg', 'available', '2025-11-22 15:29:00'),
(25, 'Thắt Lưng Nữ', 'Thắt Lưng Da', '150000.00', '150000.00', 80, 4, 2, NULL, NULL, 'Da', 'nu', 'images/thatlungnu.jpg', 'available', '2025-11-22 15:29:30');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'laam', '$2y$10$NsKkFaRqSzswL3vvfKeDJumBnxKZX/ZQ/.73bMl9F/aloUmrfs2o.', 'laamdz123@gmail.com', 'Nguyễn Bảo Lâm', '0963090442', '', 'admin', '2025-11-22 14:18:50'),
(2, 'user', '$2y$10$0RVhZ8WUkmScMA2PMONj3uZf4Y2EyR88VV5/6mPw3mjWkswcEGx9G', 'user123456@gmail.com', 'User', '19000009', '', 'customer', '2025-11-22 14:19:24'),
(3, 'admin', '$2y$10$pRp6s52vQQe0gmnyq426xuIPKfD8JLbUFdSubTTqrvrGDvzSPvDgu', 'admin@gmail.com', 'Admin', '19000009', '', 'admin', '2025-11-22 14:19:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
