USE shop_quan_ao;

UPDATE SANPHAM 
SET TrangThai = CASE 
    WHEN SoLuongTon > 0 THEN 'con_hang' 
    ELSE 'het_hang' 
END;

CREATE INDEX idx_sanpham_tensp ON SANPHAM(TenSP);
CREATE INDEX idx_sanpham_gia ON SANPHAM(GiaBan);
CREATE INDEX idx_sanpham_soluong ON SANPHAM(SoLuongTon);
CREATE INDEX idx_donhang_ngaylap ON DONHANG(NgayLap);
CREATE INDEX idx_donhang_trangthai ON DONHANG(MaTT);