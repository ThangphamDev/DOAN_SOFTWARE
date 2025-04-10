-- Kiểm tra xem bảng user_settings đã tồn tại chưa và tạo nếu chưa có
CREATE TABLE IF NOT EXISTS `user_settings` (
  `setting_id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `order_notifications` TINYINT(1) DEFAULT 1,
  `promo_notifications` TINYINT(1) DEFAULT 1,
  `email_notifications` TINYINT(1) DEFAULT 1,
  `save_order_history` TINYINT(1) DEFAULT 1,
  `personalized_recommendations` TINYINT(1) DEFAULT 1,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng order_logs nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS `order_logs` (
  `log_id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng point_history nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS `point_history` (
  `point_id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `order_id` INT DEFAULT NULL,
  `points` INT NOT NULL,
  `action_type` ENUM('earned', 'spent', 'expired', 'adjusted') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 