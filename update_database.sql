-- Thêm cột role nếu chưa tồn tại
ALTER TABLE Users
ADD COLUMN IF NOT EXISTS role VARCHAR(10) DEFAULT 'user';

-- Sửa kiểu dữ liệu của cột role để thêm giá trị admin
ALTER TABLE Users
MODIFY COLUMN role ENUM('user', 'admin') DEFAULT 'user';

-- Cập nhật tài khoản admin mặc định nếu chưa có
INSERT IGNORE INTO Users (username, password, email, role, full_name) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@cafet2k.com', 'admin', 'Administrator');

-- Tạo bảng Promotions (Mã giảm giá)
CREATE TABLE IF NOT EXISTS Promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name NVARCHAR(100) NOT NULL,
    description NVARCHAR(MAX),
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0,
    max_discount DECIMAL(10,2),
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    usage_limit INT,
    used_count INT DEFAULT 0,
    is_active BIT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng UserPromotions (Lưu trữ mã giảm giá đã sử dụng)
CREATE TABLE IF NOT EXISTS UserPromotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    promotion_id INT NOT NULL,
    order_id INT NOT NULL,
    used_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (promotion_id) REFERENCES Promotions(id),
    FOREIGN KEY (order_id) REFERENCES Orders(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng Feedback (Đánh giá sản phẩm)
CREATE TABLE IF NOT EXISTS Feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    order_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment NVARCHAR(MAX),
    images TEXT,
    is_approved BIT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (product_id) REFERENCES Products(id),
    FOREIGN KEY (order_id) REFERENCES Orders(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng FeedbackReplies (Phản hồi cho đánh giá)
CREATE TABLE IF NOT EXISTS FeedbackReplies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    feedback_id INT NOT NULL,
    user_id INT NOT NULL,
    reply_text NVARCHAR(MAX) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (feedback_id) REFERENCES Feedback(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm các indexes để tối ưu hiệu suất
CREATE INDEX idx_promotion_code ON Promotions(code);
CREATE INDEX idx_feedback_product ON Feedback(product_id);
CREATE INDEX idx_feedback_user ON Feedback(user_id);
CREATE INDEX idx_feedback_order ON Feedback(order_id);
CREATE INDEX idx_user_promotion_user ON UserPromotions(user_id);
CREATE INDEX idx_user_promotion_promotion ON UserPromotions(promotion_id);

-- Thêm các indexes mới nếu chưa tồn tại
CREATE INDEX IF NOT EXISTS idx_user_email ON Users(email);
CREATE INDEX IF NOT EXISTS idx_user_username ON Users(username);
CREATE INDEX IF NOT EXISTS idx_user_role ON Users(role);
CREATE INDEX IF NOT EXISTS idx_product_category ON Products(category_id);
CREATE INDEX IF NOT EXISTS idx_order_user ON Orders(user_id);
CREATE INDEX IF NOT EXISTS idx_order_status ON Orders(status); 