-- Tạo database
CREATE DATABASE IF NOT EXISTS mywebdb CHARACTER SET utf8mb4;
USE mywebdb;

-- Bảng users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Tài khoản admin mặc định (mật khẩu: 123)
INSERT INTO users (fullname, email, password, role)
VALUES ('Quản trị viên', 'admin@gmail.com', '123', 'admin');

-- Bảng sản phẩm
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(12,2),
    image VARCHAR(255),
    category VARCHAR(100),
    description TEXT
);

-- Thêm vài sản phẩm mẫu
INSERT INTO products (name, price, image, category, description)
VALUES
('Áo thun nam', 199000, 'images/aothun1.jpg', 'ao', 'Áo thun cotton 100%'),
('Quần jean nữ', 299000, 'images/jean1.jpg', 'quan', 'Quần jean dáng ôm'),
('Váy công sở', 350000, 'images/vay1.jpg', 'vay', 'Váy xếp ly cao cấp');

-- Bảng pages (phục vụ lab 8-9-10)
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT
);

INSERT INTO pages (title, content)
VALUES ('Trang 1', 'Nội dung trang 1'),
       ('Trang 2', 'Nội dung trang 2'),
       ('Trang 3', 'Nội dung trang 3');

-- Bảng giỏ hàng
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1
);

-- Bảng đơn hàng
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(12,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Chi tiết đơn hàng
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(12,2)
);
