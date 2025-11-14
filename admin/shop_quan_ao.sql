-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2025 at 06:00 AM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `MaAdmin` char(5) NOT NULL,
  `TenAdmin` varchar(50) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `TenDN` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`MaAdmin`, `TenAdmin`, `Email`, `TenDN`) VALUES
('AD001', 'Quản Trị Viên', 'admin@shopquanao.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `chitietdh`
--

CREATE TABLE `chitietdh` (
  `MaDH` char(5) NOT NULL,
  `MaSP` char(5) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(10,2) NOT NULL,
  `ThanhTien` decimal(12,2) NOT NULL,
  `KichThuoc` enum('S','M','L','XL','XXL') DEFAULT NULL,
  `MauSac` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chitietdh`
--

INSERT INTO `chitietdh` (`MaDH`, `MaSP`, `SoLuong`, `DonGia`, `ThanhTien`, `KichThuoc`, `MauSac`) VALUES
('DH001', 'SP001', 2, 350000.00, 700000.00, 'M', 'Đen'),
('DH001', 'SP007', 1, 280000.00, 280000.00, 'L', 'Xám'),
('DH002', 'SP009', 1, 680000.00, 680000.00, 'M', 'Đen'),
('DH002', 'SP010', 1, 620000.00, 620000.00, 'S', 'Hoa nhí');

-- --------------------------------------------------------

--
-- Table structure for table `danhgia`
--

CREATE TABLE `danhgia` (
  `MaDG` int(11) NOT NULL,
  `MaKH` char(5) NOT NULL,
  `MaSP` char(5) NOT NULL,
  `Diem` int(11) DEFAULT NULL,
  `NoiDung` text DEFAULT NULL,
  `NgayDG` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danhgia`
--

INSERT INTO `danhgia` (`MaDG`, `MaKH`, `MaSP`, `Diem`, `NoiDung`, `NgayDG`) VALUES
(1, 'KH001', 'SP001', 5, 'Áo đẹp, chất lượng tốt', '2025-11-13 04:51:50'),
(2, 'KH002', 'SP009', 4, 'Đầm vừa người, chất vải mát', '2025-11-13 04:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDM` char(5) NOT NULL,
  `TenDM` varchar(100) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`MaDM`, `TenDM`, `MoTa`, `TrangThai`) VALUES
('DM001', 'Áo thun', 'Áo thun các loại', 1),
('DM002', 'Áo sơ mi', 'Áo sơ mi nam nữ', 1),
('DM003', 'Quần jeans', 'Quần jeans nam nữ', 1),
('DM004', 'Quần short', 'Quần short mùa hè', 1),
('DM005', 'Đầm/Váy', 'Đầm váy nữ', 1),
('DM006', 'Áo khoác', 'Áo khoác các loại', 1);

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `MaDH` char(5) NOT NULL,
  `MaKH` char(5) NOT NULL,
  `NgayLap` timestamp NOT NULL DEFAULT current_timestamp(),
  `TongTien` decimal(12,2) NOT NULL,
  `DiaChiGiaoHang` text DEFAULT NULL,
  `SDTNhanHang` varchar(15) DEFAULT NULL,
  `MaTT` char(3) DEFAULT '001',
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`MaDH`, `MaKH`, `NgayLap`, `TongTien`, `DiaChiGiaoHang`, `SDTNhanHang`, `MaTT`, `GhiChu`) VALUES
('DH001', 'KH001', '2025-11-13 04:51:50', 800000.00, '123 Nguyễn Trãi, Hà Nội', '0912345678', '004', NULL),
('DH002', 'KH002', '2025-11-13 04:51:50', 1170000.00, '456 Lê Lợi, TP.HCM', '0923456789', '003', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `MaKH` char(5) NOT NULL,
  `MaSP` char(5) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `KichThuoc` enum('S','M','L','XL','XXL') NOT NULL,
  `MauSac` varchar(30) NOT NULL,
  `NgayThem` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`MaKH`, `MaSP`, `SoLuong`, `KichThuoc`, `MauSac`, `NgayThem`) VALUES
('KH001', 'SP005', 1, 'L', 'Đen', '2025-11-13 04:51:50'),
('KH001', 'SP011', 1, 'L', 'Xanh đậm', '2025-11-13 04:51:50'),
('KH002', 'SP004', 2, 'M', 'Xanh caro', '2025-11-13 04:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKH` char(5) NOT NULL,
  `TenKH` varchar(50) NOT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `DiaChi` text DEFAULT NULL,
  `TenDN` varchar(30) DEFAULT NULL,
  `NgayDangKy` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `TenKH`, `SDT`, `Email`, `DiaChi`, `TenDN`, `NgayDangKy`) VALUES
('KH001', 'Nguyễn Văn Nam', '0912345678', 'namnguyen@email.com', '123 Nguyễn Trãi, Hà Nội', 'khach01', '2025-11-13 04:51:50'),
('KH002', 'Trần Thị Hoa', '0923456789', 'hoatran@email.com', '456 Lê Lợi, TP.HCM', 'khach02', '2025-11-13 04:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSP` char(5) NOT NULL,
  `TenSP` varchar(100) NOT NULL,
  `GiaBan` decimal(10,2) NOT NULL,
  `GiaGoc` decimal(10,2) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `SoLuongTon` int(11) DEFAULT 0,
  `AnhSP` varchar(100) DEFAULT NULL,
  `MaDM` char(5) NOT NULL,
  `MaTH` char(5) NOT NULL,
  `GioiTinh` enum('nam','nu','unisex') DEFAULT 'unisex',
  `ChatLieu` varchar(50) DEFAULT NULL,
  `MauSac` varchar(30) DEFAULT NULL,
  `KichThuoc` enum('S','M','L','XL','XXL') DEFAULT NULL,
  `TrangThai` enum('con_hang','het_hang','ngung_ban') DEFAULT 'con_hang',
  `NgayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `GiaBan`, `GiaGoc`, `MoTa`, `SoLuongTon`, `AnhSP`, `MaDM`, `MaTH`, `GioiTinh`, `ChatLieu`, `MauSac`, `KichThuoc`, `TrangThai`, `NgayTao`) VALUES
('SP001', 'Áo thun Nike Sportswear', 350000.00, 299000.00, 'Áo thun thể thao Nike', 50, NULL, 'DM001', 'TH001', 'unisex', 'Cotton', 'Đen', 'M', 'con_hang', '2025-11-13 04:51:50'),
('SP002', 'Áo thun Adidas Essentials', 320000.00, 279000.00, 'Áo thun basic Adidas', 40, NULL, 'DM001', 'TH002', 'unisex', 'Cotton', 'Trắng', 'L', 'con_hang', '2025-11-13 04:51:50'),
('SP003', 'Áo sơ mi trắng công sở', 450000.00, 399000.00, 'Áo sơ mi trắng form regular', 30, NULL, 'DM002', 'TH006', 'nam', 'Cotton', 'Trắng', 'L', 'con_hang', '2025-11-13 04:51:50'),
('SP004', 'Áo sơ mi nữ caro', 420000.00, 369000.00, 'Áo sơ mi nữ form rộng', 25, NULL, 'DM002', 'TH004', 'nu', 'Cotton', 'Xanh caro', 'M', 'con_hang', '2025-11-13 04:51:50'),
('SP005', 'Quần jeans nam đen', 550000.00, 499000.00, 'Quần jeans nam slim fit', 35, NULL, 'DM003', 'TH005', 'nam', 'Denim', 'Đen', 'L', 'con_hang', '2025-11-13 04:51:50'),
('SP006', 'Quần jeans nữ rách gối', 520000.00, 479000.00, 'Quần jeans nữ boyfriend', 28, NULL, 'DM003', 'TH004', 'nu', 'Denim', 'Xanh nhạt', 'M', 'con_hang', '2025-11-13 04:51:50'),
('SP007', 'Quần short Nike Sport', 280000.00, 249000.00, 'Quần short thể thao', 45, NULL, 'DM004', 'TH001', 'nam', 'Polyester', 'Xám', 'L', 'con_hang', '2025-11-13 04:51:50'),
('SP008', 'Quần short nữ kẻ caro', 250000.00, 219000.00, 'Quần short nữ mùa hè', 32, NULL, 'DM004', 'TH005', 'nu', 'Cotton', 'Đỏ caro', 'S', 'con_hang', '2025-11-13 04:51:50'),
('SP009', 'Đầm body nữ đen', 680000.00, 599000.00, 'Đầm body dự tiệc', 20, NULL, 'DM005', 'TH004', 'nu', 'Viscose', 'Đen', 'M', 'con_hang', '2025-11-13 04:51:50'),
('SP010', 'Váy liền nữ họa tiết', 620000.00, 549000.00, 'Váy liền mùa hè', 22, NULL, 'DM005', 'TH005', 'nu', 'Cotton', 'Hoa nhí', 'S', 'con_hang', '2025-11-13 04:51:50'),
('SP011', 'Áo khoác jeans nam', 750000.00, 699000.00, 'Áo khoác jeans unisex', 18, NULL, 'DM006', 'TH003', 'unisex', 'Denim', 'Xanh đậm', 'L', 'con_hang', '2025-11-13 04:51:50'),
('SP012', 'Áo khoác hoodie Adidas', 690000.00, 629000.00, 'Áo hoodie thể thao', 26, NULL, 'DM006', 'TH002', 'unisex', 'Nỉ', 'Xanh navy', 'M', 'con_hang', '2025-11-13 04:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `TenDN` varchar(30) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `VaiTro` enum('admin','customer') NOT NULL,
  `NgayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`TenDN`, `MatKhau`, `VaiTro`, `NgayTao`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-11-13 04:51:50'),
('khach01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '2025-11-13 04:51:50'),
('khach02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '2025-11-13 04:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `thuonghieu`
--

CREATE TABLE `thuonghieu` (
  `MaTH` char(5) NOT NULL,
  `TenTH` varchar(100) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `QuocGia` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thuonghieu`
--

INSERT INTO `thuonghieu` (`MaTH`, `TenTH`, `MoTa`, `QuocGia`) VALUES
('TH001', 'Nike', NULL, 'Mỹ'),
('TH002', 'Adidas', NULL, 'Đức'),
('TH003', 'Uniqlo', NULL, 'Nhật Bản'),
('TH004', 'Zara', NULL, 'Tây Ban Nha'),
('TH005', 'H&M', NULL, 'Thụy Điển'),
('TH006', 'Việt Tiến', NULL, 'Việt Nam');

-- --------------------------------------------------------

--
-- Table structure for table `trangthaidon`
--

CREATE TABLE `trangthaidon` (
  `MaTT` char(3) NOT NULL,
  `TenTT` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trangthaidon`
--

INSERT INTO `trangthaidon` (`MaTT`, `TenTT`) VALUES
('001', 'Chờ xác nhận'),
('002', 'Đã xác nhận'),
('003', 'Đang giao hàng'),
('004', 'Giao thành công'),
('005', 'Đã hủy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `TenDN` (`TenDN`);

--
-- Indexes for table `chitietdh`
--
ALTER TABLE `chitietdh`
  ADD PRIMARY KEY (`MaDH`,`MaSP`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`MaDG`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDM`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDH`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaTT` (`MaTT`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaKH`,`MaSP`,`KichThuoc`,`MauSac`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKH`),
  ADD UNIQUE KEY `SDT` (`SDT`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `TenDN` (`TenDN`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaDM` (`MaDM`),
  ADD KEY `MaTH` (`MaTH`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`TenDN`);

--
-- Indexes for table `thuonghieu`
--
ALTER TABLE `thuonghieu`
  ADD PRIMARY KEY (`MaTH`);

--
-- Indexes for table `trangthaidon`
--
ALTER TABLE `trangthaidon`
  ADD PRIMARY KEY (`MaTT`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `MaDG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`TenDN`) REFERENCES `taikhoan` (`TenDN`);

--
-- Constraints for table `chitietdh`
--
ALTER TABLE `chitietdh`
  ADD CONSTRAINT `chitietdh_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`),
  ADD CONSTRAINT `chitietdh_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`MaTT`) REFERENCES `trangthaidon` (`MaTT`);

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`TenDN`) REFERENCES `taikhoan` (`TenDN`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`MaTH`) REFERENCES `thuonghieu` (`MaTH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
