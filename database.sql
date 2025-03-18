-- Tạo database
CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

-- Tạo bảng menu_items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category ENUM('coffee', 'tea', 'smoothie', 'dessert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Thêm dữ liệu mẫu cho cà phê
INSERT INTO menu_items (name, description, price, image, category) VALUES
('Cà Phê Sữa Đá', 'Cà phê đậm đà với sữa đặc, phong cách Việt Nam', 25000, '/public/images/menu/ca-phe-sua-da.jpg', 'coffee'),
('Cà Phê Đen Đá', 'Cà phê đen nguyên chất, đậm đà hương vị', 20000, '/public/images/menu/ca-phe-den-da.jpg', 'coffee'),
('Cappuccino', 'Cà phê Ý với lớp bọt sữa mịn màng', 45000, '/public/images/menu/cappuccino.jpg', 'coffee'),
('Latte', 'Cà phê sữa với lớp bọt sữa dày, có thể thêm siro', 50000, '/public/images/menu/latte.jpg', 'coffee');

-- Thêm dữ liệu mẫu cho trà
INSERT INTO menu_items (name, description, price, image, category) VALUES
('Trà Sữa Trân Châu', 'Trà sữa thơm ngon với trân châu đen dẻo dai', 35000, '/public/images/menu/tra-sua-tran-chau.jpg', 'tea'),
('Trà Đào Cam Sả', 'Trà đào thơm lừng với cam sả tươi', 40000, '/public/images/menu/tra-dao-cam-sa.jpg', 'tea'),
('Trà Xanh Thái Nguyên', 'Trà xanh thơm ngon, thanh mát', 25000, '/public/images/menu/tra-xanh-thai-nguyen.jpg', 'tea'),
('Trà Sữa Matcha', 'Trà sữa matcha Nhật Bản với bột trà xanh cao cấp', 45000, '/public/images/menu/tra-sua-matcha.jpg', 'tea');

-- Thêm dữ liệu mẫu cho smoothie
INSERT INTO menu_items (name, description, price, image, category) VALUES
('Smoothie Dâu Tây', 'Smoothie dâu tây tươi với sữa chua', 55000, '/public/images/menu/smoothie-dau-tay.jpg', 'smoothie'),
('Smoothie Xoài', 'Smoothie xoài chín ngọt với sữa tươi', 50000, '/public/images/menu/smoothie-xoai.jpg', 'smoothie'),
('Smoothie Việt Quất', 'Smoothie việt quất với sữa chua và mật ong', 60000, '/public/images/menu/smoothie-viet-quat.jpg', 'smoothie'),
('Smoothie Dưa Hấu', 'Smoothie dưa hấu mát lạnh, giải nhiệt', 45000, '/public/images/menu/smoothie-dua-hau.jpg', 'smoothie');

-- Thêm dữ liệu mẫu cho món tráng miệng
INSERT INTO menu_items (name, description, price, image, category) VALUES
('Tiramisu', 'Bánh tiramisu Ý với cà phê và cacao', 65000, '/public/images/menu/tiramisu.jpg', 'dessert'),
('Cheesecake', 'Bánh cheesecake mềm mịn với mứt dâu', 55000, '/public/images/menu/cheesecake.jpg', 'dessert'),
('Bánh Tiêu', 'Bánh tiêu nóng giòn với nhân đậu xanh', 25000, '/public/images/menu/banh-tieu.jpg', 'dessert'),
('Bánh Croissant', 'Bánh croissant bơ ngậy, giòn rụm', 35000, '/public/images/menu/croissant.jpg', 'dessert'); 