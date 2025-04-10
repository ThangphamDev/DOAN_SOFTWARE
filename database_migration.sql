-- Migration script to create user_settings table
-- Run this script to fix the "Table 'cafet2k.user_settings' doesn't exist" error

CREATE TABLE IF NOT EXISTS `user_settings` (
  `setting_id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `email_notifications` BOOLEAN DEFAULT TRUE,
  `push_notifications` BOOLEAN DEFAULT TRUE,
  `order_updates` BOOLEAN DEFAULT TRUE,
  `promotional_emails` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for better performance
CREATE INDEX idx_user_settings_user_id ON user_settings(user_id); 