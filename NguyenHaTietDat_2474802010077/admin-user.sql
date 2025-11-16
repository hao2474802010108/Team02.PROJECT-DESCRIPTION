-- Thêm tài khoản admin2
INSERT INTO `taikhoan` (`TenDN`, `MatKhau`, `VaiTro`, `NgayTao`) 
VALUES ('admin2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());

-- Thêm tài khoản user
INSERT INTO `taikhoan` (`TenDN`, `MatKhau`, `VaiTro`, `NgayTao`) 
VALUES ('user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW());

-- Thêm admin mới
INSERT INTO `admin` (`MaAdmin`, `TenAdmin`, `Email`, `TenDN`) 
VALUES ('AD002', 'Admin 2', 'admin2@shopquanao.com', 'admin2');

-- Thêm khách hàng mới
INSERT INTO `khachhang` (`MaKH`, `TenKH`, `SDT`, `Email`, `DiaChi`, `TenDN`, `NgayDangKy`) 
VALUES ('KH003', 'Người Dùng Mới', '0934567890', 'user@email.com', '789 Lý Thường Kiệt, Đà Nẵng', 'user', NOW());