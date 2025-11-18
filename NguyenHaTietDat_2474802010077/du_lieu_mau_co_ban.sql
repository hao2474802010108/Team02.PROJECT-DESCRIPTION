USE shop_quan_ao;

INSERT INTO TAIKHOAN (TenDN, MatKhau, VaiTro) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('khach01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer'),
('khach02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');

INSERT INTO DANHMUC (MaDM, TenDM, MoTa) VALUES 
('DM001', 'Áo thun', 'Áo thun các loại'),
('DM002', 'Áo sơ mi', 'Áo sơ mi nam nữ'),
('DM003', 'Quần jeans', 'Quần jeans nam nữ'),
('DM004', 'Quần short', 'Quần short mùa hè'),
('DM005', 'Đầm/Váy', 'Đầm váy nữ'),
('DM006', 'Áo khoác', 'Áo khoác các loại');

INSERT INTO THUONGHIEU (MaTH, TenTH, QuocGia) VALUES 
('TH001', 'Nike', 'Mỹ'),
('TH002', 'Adidas', 'Đức'),
('TH003', 'Uniqlo', 'Nhật Bản'),
('TH004', 'Zara', 'Tây Ban Nha'),
('TH005', 'H&M', 'Thụy Điển'),
('TH006', 'Việt Tiến', 'Việt Nam');