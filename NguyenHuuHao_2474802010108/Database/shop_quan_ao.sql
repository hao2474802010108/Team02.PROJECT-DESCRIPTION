-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 09:12 AM
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
-- Database: `shop_quan_ao`
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

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_orders_after_update_status_cancel` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF OLD.status <> 'cancelled' AND NEW.status = 'cancelled' THEN
        UPDATE products p
        JOIN order_items oi ON oi.product_id = p.id
        SET p.stock_quantity = p.stock_quantity + oi.quantity
        WHERE oi.order_id = NEW.id;
    END IF;
END
$$
DELIMITER ;

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

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `trg_order_items_after_delete` AFTER DELETE ON `order_items` FOR EACH ROW BEGIN
    UPDATE products
    SET stock_quantity = stock_quantity + OLD.quantity
    WHERE id = OLD.product_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_order_items_after_insert` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    UPDATE products
    SET stock_quantity = stock_quantity - NEW.quantity
    WHERE id = NEW.product_id;
END
$$
DELIMITER ;

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
(5, 'Quần Lót Lọt Khe Da Báo', 'Quần Lót Lọt Khe Họa Tiết Da Bao Siêu Sexy!!', 5000000.00, 500000.00, 1, 2, NULL, 'XXL', 'Da Báo', 'Su Da Beo', 'nu', 'images\\dabao.jpg', 'available', '2025-11-22 14:37:26'),
(6, 'Áo Sơ Mi Quagmire', 'Áo Sơ Mi Có Họa Tiết Quagmire!!', 500000.00, 600000.00, 10, 1, NULL, '', 'Đỏ', 'Giggity', 'unisex', 'images\\quagmire.png', 'available', '2025-11-22 14:48:29'),
(7, 'Áo Phao', 'Áo Phao Cứu Trợ.', 50000.00, 50000.00, 100, 1, NULL, '', 'Cam', 'Phao', 'unisex', 'images\\aophao.jpg', 'available', '2025-11-22 15:06:38'),
(8, 'Áo Thun', 'Áo Thun Họa Tiết Hình Đi Chơi', 100000.00, 100000.00, 50, 1, NULL, '', '', 'cotton', 'unisex', 'images\\aothundichoi.jpg', 'available', '2025-11-22 15:09:37'),
(9, 'Áo Đại Bàng', 'Áo Họa Tiết Hình Đại Bàng', 300000.00, 300000.00, 10, 1, NULL, '', '', '', 'unisex', 'images\\aodb.jpg', 'available', '2025-11-22 15:10:19'),
(10, 'Áo Sói', 'Áo Họa Tiết Hình Con Sói', 350000.00, 350000.00, 18, 1, NULL, '', '', 'cotton', 'unisex', 'images\\aosoi.jpg', 'available', '2025-11-22 15:11:04'),
(11, 'Áo Chống Nắng', 'Áo Chống Nắng, Chống Tia UV', 250000.00, 250000.00, 36, 1, NULL, '', '', 'Nỉ', 'unisex', 'images\\aochongnang.jpg', 'available', '2025-11-22 15:12:45'),
(12, 'Áo ONTOP', 'Áo Họa Tiết ONTOP, Local Brand Made In China', 150000.00, 150000.00, 150, 1, NULL, '', 'Đen', 'cotton', 'unisex', 'images\\aolocalbrand.jpg', 'available', '2025-11-22 15:14:11'),
(13, 'Áo Manchester United', 'Áo Của Những Công Dân Trong Hang', 700000.00, 700000.00, 0, 1, NULL, '', 'Đỏ', 'cotton', 'unisex', 'images\\aomu.jpg', 'out_of_stock', '2025-11-22 15:16:21'),
(14, 'Áo Mưa', 'Áo Mưa', 100000.00, 100000.00, 100, 1, NULL, '', '', 'Dù', 'unisex', 'images\\aomua.jpg', 'available', '2025-11-22 15:18:37'),
(15, 'Áo Sơ Mi', 'Áo Sơ Mi Trắng Cho Nam Giới', 100.00, 250000.00, 0, 1, NULL, '', 'Trắng', '', 'nam', 'images\\aosominam.jpg', 'available', '2025-11-22 15:19:44'),
(16, 'Áo Sơ Mi', 'Áo Sơ Mi Cho Nữ', 250000.00, 250000.00, 100, 1, NULL, '', '', '', 'nu', 'images\\aosominu.jpg', 'available', '2025-11-22 15:20:21'),
(17, 'Áo Bomber Lót Lông', 'Áo Bomber Lót Lông Siêu Ấm', 500000.00, 500000.00, 67, 1, NULL, '', 'Đen', '', 'unisex', 'images\\bombernam.jpg', 'available', '2025-11-22 15:21:29'),
(18, 'Áo Bomber', 'Áo Bomber', 350000.00, 350000.00, 75, 1, NULL, '', '', 'Dù', 'unisex', 'images\\bombernu.jpg', 'available', '2025-11-22 15:22:15'),
(19, 'Quần Jean Nam', 'Quần Jean Dành Cho Nam Giới', 450000.00, 450000.00, 56, 2, NULL, '', '', 'Jean', 'nam', 'images\\quanjeannam.jpg', 'available', '2025-11-22 15:23:43'),
(20, 'Quần Jean Nữ', 'Quần Jean Dành Cho Nữ Giới', 400000.00, 400000.00, 65, 2, NULL, '', '', 'Jean', 'nu', 'images\\quanjeannu.jpg', 'available', '2025-11-22 15:24:24'),
(21, 'Quần Jean Rách', 'Quần Jean Rách', 400000.00, 400000.00, 34, 2, NULL, '', '', 'Jean', 'unisex', 'images\\quanjueanrach.jpg', 'available', '2025-11-22 15:25:16'),
(22, 'Quần Short Nam', 'Quần Short Dành Cho Nam Giới', 250000.00, 250000.00, 89, 2, NULL, '', '', 'Nỉ', 'nam', 'images\\quanshortnam.jpg', 'available', '2025-11-22 15:25:56'),
(23, 'Quần Short Nữ', 'Quần Short Dành Cho Nữ Giới', 250000.00, 250000.00, 87, 2, NULL, '', '', 'Jean', 'nu', 'images\\quanshortnu.jpg', 'available', '2025-11-22 15:26:37'),
(24, 'Quần Tây Nam', 'Quân Tây Dành Cho Nam Giới', 400000.00, 400000.00, 96, 2, NULL, '', '', '', 'nam', 'images\\quantaynam.png', 'available', '2025-11-22 15:27:10'),
(25, 'Quần Tây Nữ', 'Quần Tây Dành Cho Nữ Giới', 400000.00, 400000.00, 95, 2, NULL, '', '', '', 'nu', 'images\\quantaynu.jpg', 'available', '2025-11-22 15:27:39');

-- --------------------------------------------------------

--
-- Table structure for table `revenues`
--

CREATE TABLE `revenues` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 'user', '$2y$10$0RVhZ8WUkmScMA2PMONj3uZf4Y2EyR88VV5/6mPw3mjWkswcEGx9G', 'user123456@gmail.com', 'User', '19000009', '', 'customer', '2025-11-22 14:19:24'),
(3, 'admin', '$2y$10$pRp6s52vQQe0gmnyq426xuIPKfD8JLbUFdSubTTqrvrGDvzSPvDgu', 'admin@gmail.com', 'Admin', '19000009', '', 'admin', '2025-11-22 14:19:58');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_doanh_thu`
-- (See below for the actual view)
--
CREATE TABLE `view_doanh_thu` (
`ngay` date
,`doanh_thu` decimal(34,2)
);

-- --------------------------------------------------------

--
-- Structure for view `view_doanh_thu`
--
DROP TABLE IF EXISTS `view_doanh_thu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_doanh_thu`  AS SELECT cast(`orders`.`created_at` as date) AS `ngay`, sum(`orders`.`total_amount`) AS `doanh_thu` FROM `orders` WHERE `orders`.`status` = 'delivered' GROUP BY cast(`orders`.`created_at` as date) ;

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
-- Indexes for table `revenues`
--
ALTER TABLE `revenues`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `revenues`
--
ALTER TABLE `revenues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
