-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 18, 2025 lúc 11:09 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop_quan_ao`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CapNhatSoLuongTon` (IN `ma_san_pham` CHAR(5), IN `so_luong_mua` INT)   BEGIN
    UPDATE SANPHAM 
    SET SoLuongTon = SoLuongTon - so_luong_mua
    WHERE MaSP = ma_san_pham AND SoLuongTon >= so_luong_mua;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `KiemTraSoLuongTon` (IN `ma_san_pham` CHAR(5), IN `so_luong_mua` INT, OUT `kha_dung` BOOLEAN)   BEGIN
    DECLARE ton_kho INT;
    SELECT SoLuongTon INTO ton_kho FROM SANPHAM WHERE MaSP = ma_san_pham;
    
    IF ton_kho >= so_luong_mua THEN
        SET kha_dung = TRUE;
    ELSE
        SET kha_dung = FALSE;
    END IF;
END$$

--
-- Các hàm
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CapNhatTrangThaiSanPham` (`ma_san_pham` CHAR(5)) RETURNS VARCHAR(20) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci DETERMINISTIC READS SQL DATA BEGIN
    DECLARE so_luong INT;
    DECLARE trang_thai_moi VARCHAR(20);
    
    SELECT SoLuongTon INTO so_luong FROM SANPHAM WHERE MaSP = ma_san_pham;
    
    IF so_luong > 5 THEN
        SET trang_thai_moi = 'con_hang';
    ELSEIF so_luong > 0 THEN
        SET trang_thai_moi = 'con_hang';
    ELSE
        SET trang_thai_moi = 'het_hang';
    END IF;
    
    UPDATE SANPHAM SET TrangThai = trang_thai_moi WHERE MaSP = ma_san_pham;
    RETURN trang_thai_moi;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `MaAdmin` char(5) NOT NULL,
  `TenAdmin` varchar(50) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `TenDN` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`MaAdmin`, `TenAdmin`, `Email`, `TenDN`) VALUES
('AD001', 'Quản Trị Viên', 'admin@shopquanao.com', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdh`
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
-- Đang đổ dữ liệu cho bảng `chitietdh`
--

INSERT INTO `chitietdh` (`MaDH`, `MaSP`, `SoLuong`, `DonGia`, `ThanhTien`, `KichThuoc`, `MauSac`) VALUES
('DH001', 'SP001', 2, 350000.00, 700000.00, 'M', 'Đen'),
('DH001', 'SP007', 1, 280000.00, 280000.00, 'L', 'Xám'),
('DH002', 'SP009', 1, 680000.00, 680000.00, 'M', 'Đen'),
('DH002', 'SP010', 1, 620000.00, 620000.00, 'S', 'Hoa nhí');

--
-- Bẫy `chitietdh`
--
DELIMITER $$
CREATE TRIGGER `TruSoLuongTon` AFTER INSERT ON `chitietdh` FOR EACH ROW BEGIN
    UPDATE SANPHAM 
    SET SoLuongTon = SoLuongTon - NEW.SoLuong
    WHERE MaSP = NEW.MaSP;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
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
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`MaDG`, `MaKH`, `MaSP`, `Diem`, `NoiDung`, `NgayDG`) VALUES
(1, 'KH001', 'SP001', 5, 'Áo đẹp, chất lượng tốt', '2025-11-18 08:12:12'),
(2, 'KH002', 'SP007', 4, 'Quần short mặc rất thoải mái', '2025-11-18 08:12:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDM` char(5) NOT NULL,
  `TenDM` varchar(100) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
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
-- Cấu trúc bảng cho bảng `donhang`
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
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`MaDH`, `MaKH`, `NgayLap`, `TongTien`, `DiaChiGiaoHang`, `SDTNhanHang`, `MaTT`, `GhiChu`) VALUES
('DH001', 'KH001', '2025-11-18 08:12:12', 800000.00, '123 Nguyễn Trãi, Hà Nội', '0912345678', '004', NULL),
('DH002', 'KH002', '2025-11-18 08:12:12', 1170000.00, '456 Lê Lợi, TP.HCM', '0923456789', '003', NULL);

--
-- Bẫy `donhang`
--
DELIMITER $$
CREATE TRIGGER `CongLaiSoLuongTon` AFTER UPDATE ON `donhang` FOR EACH ROW BEGIN
    IF NEW.MaTT = '005' AND OLD.MaTT != '005' THEN
        UPDATE SANPHAM sp
        JOIN CHITIETDH ct ON sp.MaSP = ct.MaSP
        SET sp.SoLuongTon = sp.SoLuongTon + ct.SoLuong
        WHERE ct.MaDH = NEW.MaDH;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
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
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`MaKH`, `MaSP`, `SoLuong`, `KichThuoc`, `MauSac`, `NgayThem`) VALUES
('KH001', 'SP005', 1, 'L', 'Đen', '2025-11-18 08:12:12'),
('KH001', 'SP011', 1, 'L', 'Xanh đậm', '2025-11-18 08:12:12'),
('KH002', 'SP003', 2, 'M', 'Trắng', '2025-11-18 08:12:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
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
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `TenKH`, `SDT`, `Email`, `DiaChi`, `TenDN`, `NgayDangKy`) VALUES
('KH001', 'Nguyễn Văn Nam', '0912345678', 'namnguyen@email.com', '123 Nguyễn Trãi, Hà Nội', 'khach01', '2025-11-18 08:12:12'),
('KH002', 'Trần Thị Hoa', '0923456789', 'hoatran@email.com', '456 Lê Lợi, TP.HCM', 'khach02', '2025-11-18 08:12:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
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
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `GiaBan`, `GiaGoc`, `MoTa`, `SoLuongTon`, `AnhSP`, `MaDM`, `MaTH`, `GioiTinh`, `ChatLieu`, `MauSac`, `KichThuoc`, `TrangThai`, `NgayTao`) VALUES
('SP001', 'Áo thun Nike Sportswear', 350000.00, 299000.00, 'Áo thun thể thao Nike', 5, NULL, 'DM001', 'TH001', 'unisex', 'Cotton', 'Đen', 'M', 'con_hang', '2025-11-18 08:11:57'),
('SP002', 'Áo thun Adidas Essentials', 320000.00, 279000.00, 'Áo thun basic Adidas', 3, NULL, 'DM001', 'TH002', 'unisex', 'Cotton', 'Trắng', 'L', 'con_hang', '2025-11-18 08:11:57'),
('SP003', 'Áo sơ mi trắng công sở', 450000.00, 399000.00, 'Áo sơ mi trắng form regular', 8, NULL, 'DM002', 'TH006', 'nam', 'Cotton', 'Trắng', 'L', 'con_hang', '2025-11-18 08:11:57'),
('SP004', 'Áo sơ mi nữ caro', 420000.00, 369000.00, 'Áo sơ mi nữ form rộng', 0, NULL, 'DM002', 'TH004', 'nu', 'Cotton', 'Xanh caro', 'M', 'het_hang', '2025-11-18 08:11:57'),
('SP005', 'Quần jeans nam đen', 550000.00, 499000.00, 'Quần jeans nam slim fit', 2, NULL, 'DM003', 'TH005', 'nam', 'Denim', 'Đen', 'L', 'con_hang', '2025-11-18 08:11:57'),
('SP006', 'Quần jeans nữ rách gối', 520000.00, 479000.00, 'Quần jeans nữ boyfriend', 1, NULL, 'DM003', 'TH004', 'nu', 'Denim', 'Xanh nhạt', 'M', 'con_hang', '2025-11-18 08:11:57'),
('SP007', 'Quần short Nike Sport', 280000.00, 249000.00, 'Quần short thể thao', 10, NULL, 'DM004', 'TH001', 'nam', 'Polyester', 'Xám', 'L', 'con_hang', '2025-11-18 08:11:57'),
('SP008', 'Quần short nữ kẻ caro', 250000.00, 219000.00, 'Quần short nữ mùa hè', 15, NULL, 'DM004', 'TH005', 'nu', 'Cotton', 'Đỏ caro', 'S', 'con_hang', '2025-11-18 08:11:57'),
('SP009', 'Đầm body nữ đen', 680000.00, 599000.00, 'Đầm body dự tiệc', 0, NULL, 'DM005', 'TH004', 'nu', 'Viscose', 'Đen', 'M', 'het_hang', '2025-11-18 08:11:57'),
('SP010', 'Váy liền nữ họa tiết', 620000.00, 549000.00, 'Váy liền mùa hè', 4, NULL, 'DM005', 'TH005', 'nu', 'Cotton', 'Hoa nhí', 'S', 'con_hang', '2025-11-18 08:11:57'),
('SP011', 'Áo khoác jeans nam', 750000.00, 699000.00, 'Áo khoác jeans unisex', 6, NULL, 'DM006', 'TH003', 'unisex', 'Denim', 'Xanh đậm', 'L', 'con_hang', '2025-11-18 08:11:57'),
('SP012', 'Áo khoác hoodie Adidas', 690000.00, 629000.00, 'Áo hoodie thể thao', 0, NULL, 'DM006', 'TH002', 'unisex', 'Nỉ', 'Xanh navy', 'M', 'het_hang', '2025-11-18 08:11:57');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `sanphamhethang`
-- (See below for the actual view)
--
CREATE TABLE `sanphamhethang` (
`MaSP` char(5)
,`TenSP` varchar(100)
,`SoLuongTon` int(11)
,`GiaBan` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `sanphamsaphethang`
-- (See below for the actual view)
--
CREATE TABLE `sanphamsaphethang` (
`MaSP` char(5)
,`TenSP` varchar(100)
,`SoLuongTon` int(11)
,`GiaBan` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `TenDN` varchar(30) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `VaiTro` enum('admin','customer') NOT NULL,
  `NgayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`TenDN`, `MatKhau`, `VaiTro`, `NgayTao`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-11-18 08:11:40'),
('khach01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '2025-11-18 08:11:40'),
('khach02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '2025-11-18 08:11:40');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `thongketonkho`
-- (See below for the actual view)
--
CREATE TABLE `thongketonkho` (
`DanhMuc` varchar(100)
,`TongSoSP` bigint(21)
,`TongTonKho` decimal(32,0)
,`TongGiaTriTonKho` decimal(42,2)
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuonghieu`
--

CREATE TABLE `thuonghieu` (
  `MaTH` char(5) NOT NULL,
  `TenTH` varchar(100) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `QuocGia` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuonghieu`
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
-- Cấu trúc bảng cho bảng `trangthaidon`
--

CREATE TABLE `trangthaidon` (
  `MaTT` char(3) NOT NULL,
  `TenTT` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trangthaidon`
--

INSERT INTO `trangthaidon` (`MaTT`, `TenTT`) VALUES
('001', 'Chờ xác nhận'),
('002', 'Đã xác nhận'),
('003', 'Đang giao hàng'),
('004', 'Giao thành công'),
('005', 'Đã hủy');

-- --------------------------------------------------------

--
-- Cấu trúc cho view `sanphamhethang`
--
DROP TABLE IF EXISTS `sanphamhethang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sanphamhethang`  AS SELECT `sanpham`.`MaSP` AS `MaSP`, `sanpham`.`TenSP` AS `TenSP`, `sanpham`.`SoLuongTon` AS `SoLuongTon`, `sanpham`.`GiaBan` AS `GiaBan` FROM `sanpham` WHERE `sanpham`.`SoLuongTon` = 0 ORDER BY `sanpham`.`TenSP` ASC ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `sanphamsaphethang`
--
DROP TABLE IF EXISTS `sanphamsaphethang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sanphamsaphethang`  AS SELECT `sanpham`.`MaSP` AS `MaSP`, `sanpham`.`TenSP` AS `TenSP`, `sanpham`.`SoLuongTon` AS `SoLuongTon`, `sanpham`.`GiaBan` AS `GiaBan` FROM `sanpham` WHERE `sanpham`.`SoLuongTon` <= 3 AND `sanpham`.`SoLuongTon` > 0 ORDER BY `sanpham`.`SoLuongTon` ASC ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `thongketonkho`
--
DROP TABLE IF EXISTS `thongketonkho`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `thongketonkho`  AS SELECT `dm`.`TenDM` AS `DanhMuc`, count(`sp`.`MaSP`) AS `TongSoSP`, sum(`sp`.`SoLuongTon`) AS `TongTonKho`, sum(`sp`.`SoLuongTon` * `sp`.`GiaBan`) AS `TongGiaTriTonKho` FROM (`danhmuc` `dm` left join `sanpham` `sp` on(`dm`.`MaDM` = `sp`.`MaDM`)) GROUP BY `dm`.`MaDM`, `dm`.`TenDM` ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `TenDN` (`TenDN`);

--
-- Chỉ mục cho bảng `chitietdh`
--
ALTER TABLE `chitietdh`
  ADD PRIMARY KEY (`MaDH`,`MaSP`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`MaDG`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDM`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDH`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `idx_donhang_ngaylap` (`NgayLap`),
  ADD KEY `idx_donhang_trangthai` (`MaTT`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaKH`,`MaSP`,`KichThuoc`,`MauSac`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKH`),
  ADD UNIQUE KEY `SDT` (`SDT`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `TenDN` (`TenDN`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaDM` (`MaDM`),
  ADD KEY `MaTH` (`MaTH`),
  ADD KEY `idx_sanpham_tensp` (`TenSP`),
  ADD KEY `idx_sanpham_gia` (`GiaBan`),
  ADD KEY `idx_sanpham_soluong` (`SoLuongTon`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`TenDN`);

--
-- Chỉ mục cho bảng `thuonghieu`
--
ALTER TABLE `thuonghieu`
  ADD PRIMARY KEY (`MaTH`);

--
-- Chỉ mục cho bảng `trangthaidon`
--
ALTER TABLE `trangthaidon`
  ADD PRIMARY KEY (`MaTT`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `MaDG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`TenDN`) REFERENCES `taikhoan` (`TenDN`);

--
-- Các ràng buộc cho bảng `chitietdh`
--
ALTER TABLE `chitietdh`
  ADD CONSTRAINT `chitietdh_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`),
  ADD CONSTRAINT `chitietdh_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`MaTT`) REFERENCES `trangthaidon` (`MaTT`);

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Các ràng buộc cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`TenDN`) REFERENCES `taikhoan` (`TenDN`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`MaTH`) REFERENCES `thuonghieu` (`MaTH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
