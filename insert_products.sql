-- Thêm sản phẩm cho danh mục Cà phê (category_id = 1)
INSERT INTO Products (category_id, name, description, price, image_url, is_active) VALUES
(1, 'Cà Phê Sữa Đá', 'Cà phê phin truyền thống kết hợp với sữa đặc tạo nên hương vị đậm đà, hài hòa', 29000, '/public/images/products/ca-phe-sua-da.jpg', 1),
(1, 'Cà Phê Đen', 'Cà phê đen đậm đà, đắng thanh, hương thơm nồng nàn', 25000, '/public/images/products/ca-phe-den.jpg', 1),
(1, 'Bạc Xỉu', 'Cà phê espresso với sữa đặc và sữa tươi béo ngậy', 32000, '/public/images/products/bac-xiu.jpg', 1),
(1, 'Cà Phê Mocha', 'Cà phê espresso kết hợp với sốt socola và sữa tươi', 45000, '/public/images/products/ca-phe-mocha.jpg', 1);

-- Thêm sản phẩm cho danh mục Trà (category_id = 2)
INSERT INTO Products (category_id, name, description, price, image_url, is_active) VALUES
(2, 'Trà Sen Vàng', 'Trà ướp hương sen thanh mát, tinh tế và thơm nhẹ', 35000, '/public/images/products/tra-sen-vang.jpg', 1),
(2, 'Trà Đào', 'Trà đen kết hợp với đào tươi và syrup đào', 38000, '/public/images/products/tra-dao.jpg', 1),
(2, 'Trà Matcha Latte', 'Bột trà xanh Nhật Bản kết hợp với sữa tươi', 42000, '/public/images/products/tra-matcha-latte.jpg', 1),
(2, 'Trà Gừng', 'Trà đen pha với gừng tươi, mật ong và chanh', 32000, '/public/images/products/tra-gung.jpg', 1);

-- Thêm sản phẩm cho danh mục Sinh tố (category_id = 3)
INSERT INTO Products (category_id, name, description, price, image_url, is_active) VALUES
(3, 'Sinh Tố Xoài', 'Xoài chín ngọt mát kết hợp với sữa tươi', 40000, '/public/images/products/sinh-to-xoai.jpg', 1),
(3, 'Sinh Tố Bơ', 'Bơ sáp béo ngậy với sữa đặc và sữa tươi', 45000, '/public/images/products/sinh-to-bo.jpg', 1),
(3, 'Sinh Tố Dâu', 'Dâu tây tươi ngon với sữa chua và sữa tươi', 42000, '/public/images/products/sinh-to-dau.jpg', 1),
(3, 'Sinh Tố Việt Quất', 'Việt quất tươi giàu vitamin với sữa chua', 43000, '/public/images/products/sinh-to-viet-quat.jpg', 1);

-- Thêm sản phẩm cho danh mục Bánh ngọt (category_id = 4)
INSERT INTO Products (category_id, name, description, price, image_url, is_active) VALUES
(4, 'Bánh Tiramisu', 'Bánh tiramisu truyền thống với lớp kem mascarpone mềm mịn', 35000, '/public/images/products/banh-tiramisu.jpg', 1),
(4, 'Bánh Phô Mai Trà Xanh', 'Bánh phô mai mềm mịn với hương vị trà xanh độc đáo', 32000, '/public/images/products/banh-pho-mai-tra-xanh.jpg', 1),
(4, 'Bánh Chocolate', 'Bánh chocolate đậm đà với lớp kem socola béo ngậy', 34000, '/public/images/products/banh-chocolate.jpg', 1),
(4, 'Bánh Chuối', 'Bánh chuối mềm thơm với lớp kem tươi và caramel', 30000, '/public/images/products/banh-chuoi.jpg', 1); 