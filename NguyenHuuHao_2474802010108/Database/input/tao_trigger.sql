USE shop_quan_ao;

DELIMITER $$
CREATE FUNCTION CapNhatTrangThaiSanPham(ma_san_pham CHAR(5)) 
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
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

DELIMITER $$
CREATE TRIGGER TruSoLuongTon AFTER INSERT ON CHITIETDH
FOR EACH ROW
BEGIN
    UPDATE SANPHAM 
    SET SoLuongTon = SoLuongTon - NEW.SoLuong
    WHERE MaSP = NEW.MaSP;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER CongLaiSoLuongTon AFTER UPDATE ON DONHANG
FOR EACH ROW
BEGIN
    IF NEW.MaTT = '005' AND OLD.MaTT != '005' THEN
        UPDATE SANPHAM sp
        JOIN CHITIETDH ct ON sp.MaSP = ct.MaSP
        SET sp.SoLuongTon = sp.SoLuongTon + ct.SoLuong
        WHERE ct.MaDH = NEW.MaDH;
    END IF;
END$$
DELIMITER ;