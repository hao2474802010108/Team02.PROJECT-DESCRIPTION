USE shop_quan_ao;

CREATE TABLE SANPHAM (
    MaSP CHAR(5) PRIMARY KEY,
    TenSP VARCHAR(100) NOT NULL,
    GiaBan DECIMAL(10,2) NOT NULL,
    GiaGoc DECIMAL(10,2) NOT NULL,
    MoTa TEXT,
    SoLuongTon INT DEFAULT 0,
    AnhSP VARCHAR(100),
    MaDM CHAR(5) NOT NULL,
    MaTH CHAR(5) NOT NULL,
    GioiTinh ENUM('nam', 'nu', 'unisex') DEFAULT 'unisex',
    ChatLieu VARCHAR(50),
    MauSac VARCHAR(30),
    KichThuoc ENUM('S', 'M', 'L', 'XL', 'XXL'),
    TrangThai ENUM('con_hang', 'het_hang', 'ngung_ban') DEFAULT 'con_hang',
    NgayTao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaDM) REFERENCES DANHMUC(MaDM),
    FOREIGN KEY (MaTH) REFERENCES THUONGHIEU(MaTH)
);