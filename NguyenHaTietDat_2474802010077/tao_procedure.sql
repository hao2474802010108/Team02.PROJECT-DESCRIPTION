USE shop_quan_ao;

DELIMITER $$
CREATE PROCEDURE CapNhatSoLuongTon(IN ma_san_pham CHAR(5), IN so_luong_mua INT)
BEGIN
    UPDATE SANPHAM 
    SET SoLuongTon = SoLuongTon - so_luong_mua
    WHERE MaSP = ma_san_pham AND SoLuongTon >= so_luong_mua;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE KiemTraSoLuongTon(IN ma_san_pham CHAR(5), IN so_luong_mua INT, OUT kha_dung BOOLEAN)
BEGIN
    DECLARE ton_kho INT;
    SELECT SoLuongTon INTO ton_kho FROM SANPHAM WHERE MaSP = ma_san_pham;
    
    IF ton_kho >= so_luong_mua THEN
        SET kha_dung = TRUE;
    ELSE
        SET kha_dung = FALSE;
    END IF;
END$$
DELIMITER ;