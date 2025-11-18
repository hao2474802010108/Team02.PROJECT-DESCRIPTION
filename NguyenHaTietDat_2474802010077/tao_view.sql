USE shop_quan_ao;

CREATE VIEW SanPhamSapHetHang AS
SELECT MaSP, TenSP, SoLuongTon, GiaBan
FROM SANPHAM 
WHERE SoLuongTon <= 3 AND SoLuongTon > 0
ORDER BY SoLuongTon ASC;

CREATE VIEW SanPhamHetHang AS
SELECT MaSP, TenSP, SoLuongTon, GiaBan
FROM SANPHAM 
WHERE SoLuongTon = 0
ORDER BY TenSP;

CREATE VIEW ThongKeTonKho AS
SELECT 
    dm.TenDM as DanhMuc,
    COUNT(sp.MaSP) as TongSoSP,
    SUM(sp.SoLuongTon) as TongTonKho,
    SUM(sp.SoLuongTon * sp.GiaBan) as TongGiaTriTonKho
FROM DANHMUC dm
LEFT JOIN SANPHAM sp ON dm.MaDM = sp.MaDM
GROUP BY dm.MaDM, dm.TenDM;