USE shop_quan_ao;

INSERT INTO KHACHHANG (MaKH, TenKH, SDT, Email, DiaChi, TenDN) VALUES 
('KH001', 'Nguyễn Văn Nam', '0912345678', 'namnguyen@email.com', '123 Nguyễn Trãi, Hà Nội', 'khach01'),
('KH002', 'Trần Thị Hoa', '0923456789', 'hoatran@email.com', '456 Lê Lợi, TP.HCM', 'khach02');

INSERT INTO ADMIN (MaAdmin, TenAdmin, Email, TenDN) VALUES 
('AD001', 'Quản Trị Viên', 'admin@shopquanao.com', 'admin');

INSERT INTO TRANGTHAIDON (MaTT, TenTT) VALUES 
('001', 'Chờ xác nhận'),
('002', 'Đã xác nhận'),
('003', 'Đang giao hàng'),
('004', 'Giao thành công'),
('005', 'Đã hủy');

INSERT INTO DONHANG (MaDH, MaKH, TongTien, DiaChiGiaoHang, SDTNhanHang, MaTT) VALUES 
('DH001', 'KH001', 800000, '123 Nguyễn Trãi, Hà Nội', '0912345678', '004'),
('DH002', 'KH002', 1170000, '456 Lê Lợi, TP.HCM', '0923456789', '003');

INSERT INTO CHITIETDH (MaDH, MaSP, SoLuong, DonGia, ThanhTien, KichThuoc, MauSac) VALUES 
('DH001', 'SP001', 2, 350000, 700000, 'M', 'Đen'),
('DH001', 'SP007', 1, 280000, 280000, 'L', 'Xám'),
('DH002', 'SP009', 1, 680000, 680000, 'M', 'Đen'),
('DH002', 'SP010', 1, 620000, 620000, 'S', 'Hoa nhí');

INSERT INTO GIOHANG (MaKH, MaSP, SoLuong, KichThuoc, MauSac) VALUES 
('KH001', 'SP005', 1, 'L', 'Đen'),
('KH001', 'SP011', 1, 'L', 'Xanh đậm'),
('KH002', 'SP003', 2, 'M', 'Trắng');

INSERT INTO DANHGIA (MaKH, MaSP, Diem, NoiDung) VALUES 
('KH001', 'SP001', 5, 'Áo đẹp, chất lượng tốt'),
('KH002', 'SP007', 4, 'Quần short mặc rất thoải mái');