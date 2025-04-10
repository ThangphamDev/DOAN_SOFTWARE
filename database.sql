-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for cafet2k
CREATE DATABASE IF NOT EXISTS `cafet2k` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cafet2k`;

-- Dumping structure for table cafet2k.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.categories: ~5 rows (approximately)
INSERT INTO `categories` (`category_id`, `name`, `description`, `image_url`, `display_order`, `is_active`) VALUES
	(1, 'Cà Phê', 'Các loại cà phê đặc trưng', NULL, 0, 1),
	(2, 'Trà', 'Các loại trà thơm ngon', NULL, 0, 1),
	(3, 'Sinh Tố', 'Sinh tố từ trái cây tươi', NULL, 0, 1),
	(4, 'Bánh Ngọt', 'Các loại bánh ngọt', NULL, 0, 1),
	(5, 'Đồ Ăn Vặt', 'Các món ăn vặt', NULL, 0, 1);

-- Dumping structure for table cafet2k.feedback
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.feedback: ~0 rows (approximately)

-- Dumping structure for table cafet2k.feedback_replies
CREATE TABLE IF NOT EXISTS `feedback_replies` (
  `reply_id` int NOT NULL AUTO_INCREMENT,
  `feedback_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `reply_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reply_id`),
  KEY `feedback_id` (`feedback_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `feedback_replies_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`feedback_id`) ON DELETE CASCADE,
  CONSTRAINT `feedback_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.feedback_replies: ~0 rows (approximately)

-- Dumping structure for table cafet2k.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'order',
  `related_id` int DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.notifications: ~0 rows (approximately)

-- Dumping structure for table cafet2k.orderitems
CREATE TABLE IF NOT EXISTS `orderitems` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `variant_id` (`variant_id`),
  CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT,
  CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE RESTRICT,
  CONSTRAINT `orderitems_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`variant_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.orderitems: ~12 rows (approximately)
INSERT INTO `orderitems` (`order_item_id`, `order_id`, `product_id`, `variant_id`, `quantity`, `unit_price`, `notes`, `created_at`) VALUES
	(5, 7, 10, NULL, 1, 55000.00, NULL, '2025-04-08 23:25:01'),
	(6, 7, 11, NULL, 2, 50000.00, NULL, '2025-04-08 23:25:01'),
	(7, 7, 12, NULL, 1, 45000.00, NULL, '2025-04-08 23:25:01'),
	(8, 7, 1, NULL, 1, 25000.00, NULL, '2025-04-08 23:25:01'),
	(9, 8, 2, NULL, 1, 30000.00, NULL, '2025-04-09 00:01:39'),
	(10, 9, 1, NULL, 4, 25000.00, NULL, '2025-04-09 05:30:46'),
	(11, 9, 12, NULL, 5, 55000.00, NULL, '2025-04-09 05:30:46'),
	(12, 10, 10, NULL, 1, 55000.00, NULL, '2025-04-09 05:32:04'),
	(13, 11, 1, NULL, 1, 35000.00, NULL, '2025-04-09 12:31:05'),
	(14, 12, 10, NULL, 1, 55000.00, NULL, '2025-04-09 14:31:19'),
	(15, 13, 1, NULL, 1, 35000.00, NULL, '2025-04-09 22:47:54'),
	(16, 14, 16, NULL, 1, 37000.00, NULL, '2025-04-09 23:51:13');

-- Dumping structure for table cafet2k.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'đang chờ',
  `payment_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'tiền mặt',
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'đang chờ',
  `notes` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.orders: ~8 rows (approximately)
INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `payment_method`, `payment_status`, `notes`) VALUES
	(7, NULL, '2025-04-08 23:25:01', 225000.00, 'pending', 'tiền mặt', 'pending', ''),
	(8, 1, '2025-04-09 00:01:39', 30000.00, 'pending', 'tiền mặt', 'pending', ''),
	(9, 1, '2025-04-09 05:30:46', 375000.00, 'pending', 'tiền mặt', 'pending', ''),
	(10, 1, '2025-04-09 05:32:04', 55000.00, 'pending', 'tiền mặt', 'pending', ''),
	(11, 1, '2025-04-09 12:31:05', 35000.00, 'processing', 'tiền mặt', 'pending', ''),
	(12, 1, '2025-04-09 14:31:19', 55000.00, 'completed', 'chuyển khoản', 'pending', ''),
	(13, 1, '2025-04-09 22:47:54', 35000.00, 'pending', 'tiền mặt', 'pending', ''),
	(14, 1, '2025-04-09 23:51:13', 37000.00, 'pending', 'tiền mặt', 'pending', '');

-- Dumping structure for table cafet2k.products
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.products: ~15 rows (approximately)
INSERT INTO `products` (`product_id`, `category_id`, `name`, `description`, `base_price`, `image_url`, `is_available`, `is_featured`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Cà Phê Đen', 'Cà phê đen đậm đà hương vị Việt Nam', 25000.00, '/public/images/products/product_1_1744181578.jpg', 1, 0, '2025-03-20 03:07:05', '2025-04-09 13:52:58'),
	(2, 1, 'Cà Phê Sữa', 'Cà phê sữa béo ngậy', 30000.00, '/public/images/products/product_2_1744181777.jpg', 1, 0, '2025-03-20 03:07:05', '2025-04-09 13:56:17'),
	(3, 1, 'Bạc Xỉu', 'Bạc xỉu ngọt ngào', 35000.00, '/public/images/products/product_3_1744181550.jpg', 1, 0, '2025-03-20 03:07:05', '2025-04-09 13:52:30'),
	(4, 2, 'Trà Đào', 'Trà đào thơm mát', 40000.00, '/public/images/home/peach-tea.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(5, 2, 'Trà Vải', 'Trà vải thanh mát', 40000.00, '/public/images/home/lychee-tea.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(7, 3, 'Sinh Tố Bơ', 'Sinh tố bơ béo ngậy', 45000.00, '/public/images/home/avocado-smoothie.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(8, 3, 'Sinh Tố Xoài', 'Sinh tố xoài ngọt mát', 40000.00, '/public/images/home/mango-smoothie.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(9, 3, 'Sinh Tố Dâu', 'Sinh tố dâu tươi', 45000.00, '/public/images/home/strawberry-smoothie.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(10, 4, 'Bánh Tiramisu', 'Bánh tiramisu Ý', 55000.00, '/public/images/home/tiramisu.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(11, 4, 'Bánh Phô Mai', 'Bánh phô mai Nhật', 50000.00, '/public/images/home/cheesecake.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(12, 4, 'Bánh Chocolate', 'Bánh chocolate đắng', 45000.00, '/public/images/home/chocolate-cake.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(13, 5, 'Khoai Tây Chiên', 'Khoai tây chiên giòn', 35000.00, '/public/images/home/french-fries.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(14, 5, 'Xúc Xích Chiên', 'Xúc xích chiên nóng', 40000.00, '/public/images/home/sausage.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(15, 5, 'Mì Cay', 'Mì cay Hàn Quốc', 45000.00, '/public/images/home/spicy-noodles.jpg', 1, 0, '2025-03-20 03:07:05', NULL),
	(16, 1, 'Cappuccino Đá', 'ngon vãi nồi', 32000.00, '/public/images/products/cappuccino Đá.jpg', 1, 0, '2025-04-09 13:33:45', '2025-04-09 13:50:46');

-- Dumping structure for table cafet2k.productvariants
CREATE TABLE IF NOT EXISTS `productvariants` (
  `variant_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `variant_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_value` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_price` decimal(10,2) DEFAULT '0.00',
  `is_available` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`variant_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `productvariants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.productvariants: ~3 rows (approximately)
INSERT INTO `productvariants` (`variant_id`, `product_id`, `variant_type`, `variant_value`, `additional_price`, `is_available`, `created_at`) VALUES
	(69, 16, 'Size', 'Lớn', 5000.00, 1, '2025-04-09 13:50:46'),
	(70, 2, 'Đường', 'Nhiều', 0.00, 1, '2025-04-09 13:56:17'),
	(71, 2, 'Đường', 'Ít', 0.00, 1, '2025-04-09 13:56:17');

-- Dumping structure for table cafet2k.promotions
CREATE TABLE IF NOT EXISTS `promotions` (
  `promotion_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`promotion_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.promotions: ~4 rows (approximately)
INSERT INTO `promotions` (`promotion_id`, `code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount`, `start_date`, `end_date`, `usage_limit`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'WELCOME10', 'Giảm 10% cho khách hàng mới', 'Giảm 10% tổng hóa đơn cho khách hàng lần đầu đặt hàng', 'percentage', 10.00, 50000.00, 30000.00, '2025-04-08 23:41:25', '2025-07-08 23:41:25', 100, 0, 1, '2025-04-08 23:41:25', NULL),
	(2, 'FREESHIP', 'Miễn phí vận chuyển', 'Miễn phí vận chuyển cho đơn hàng từ 100,000đ', 'fixed', 15000.00, 100000.00, 15000.00, '2025-04-08 23:41:25', '2025-05-08 23:41:25', NULL, 0, 1, '2025-04-08 23:41:25', NULL),
	(3, 'SUMMER23', 'Khuyến mãi hè 2023', 'Giảm 15% cho các đơn hàng trong mùa hè', 'percentage', 15.00, 0.00, 50000.00, '2025-04-08 23:41:25', '2025-06-08 23:41:25', 200, 0, 1, '2025-04-08 23:41:25', NULL),
	(4, 'COMBO50', 'Giảm 50K cho Combo 2 người', 'Áp dụng cho đơn hàng từ 200,000đ', 'fixed', 50000.00, 200000.00, 50000.00, '2025-04-08 23:41:25', '2025-05-08 23:41:25', 50, 0, 1, '2025-04-08 23:41:25', NULL);

-- Dumping structure for table cafet2k.rewards
CREATE TABLE IF NOT EXISTS `rewards` (
  `reward_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `points_required` int NOT NULL,
  `reward_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'discount',
  `reward_value` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reward_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.rewards: ~4 rows (approximately)
INSERT INTO `rewards` (`reward_id`, `name`, `description`, `points_required`, `reward_type`, `reward_value`, `is_active`, `created_at`) VALUES
	(1, 'Giảm 10% đơn hàng', 'Giảm 10% tổng giá trị đơn hàng', 100, 'percentage', 10.00, 1, '2025-04-08 23:41:25'),
	(2, 'Miễn phí topping', 'Miễn phí 1 topping bất kỳ cho đồ uống', 50, 'freebie', NULL, 1, '2025-04-08 23:41:25'),
	(3, 'Đồ uống miễn phí', 'Đổi 1 đồ uống bất kỳ trị giá tối đa 50.000đ', 200, 'freebie', 50000.00, 1, '2025-04-08 23:41:25'),
	(4, 'Giảm 20.000đ', 'Giảm 20.000đ cho đơn hàng từ 100.000đ', 80, 'fixed', 20000.00, 1, '2025-04-08 23:41:25');

-- Dumping structure for table cafet2k.reward_usage
CREATE TABLE IF NOT EXISTS `reward_usage` (
  `usage_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `reward_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `points_used` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usage_id`),
  KEY `user_id` (`user_id`),
  KEY `reward_id` (`reward_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `reward_usage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `reward_usage_ibfk_2` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`reward_id`) ON DELETE CASCADE,
  CONSTRAINT `reward_usage_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.reward_usage: ~0 rows (approximately)

-- Dumping structure for table cafet2k.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'khách hàng',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'hoạt động',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.users: ~0 rows (approximately)
INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `full_name`, `phone_number`, `role`, `created_at`, `last_login`, `status`) VALUES
	(1, 'Thang', '$2y$10$X6hj0Smo7j4pLINb2i4XGON2gyqspH7NoBDMNcQq58MIg7nQg.OBG', 'Thangphamxuan097@gmail.com', 'Phạm Xuân Thắng', '0326314436', 'admin', '2025-03-25 01:59:58', NULL, 'hoạt động');

-- Dumping structure for table cafet2k.user_points
CREATE TABLE IF NOT EXISTS `user_points` (
  `point_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `earned_from` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`point_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cafet2k.user_points: ~0 rows (approximately)

-- Dumping structure for trigger cafet2k.trg_Feedback_Update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_Feedback_Update` BEFORE UPDATE ON `feedback` FOR EACH ROW BEGIN
    SET NEW.updated_at = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger cafet2k.trg_Products_Update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_Products_Update` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    SET NEW.updated_at = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger cafet2k.trg_Promotions_Update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_Promotions_Update` BEFORE UPDATE ON `promotions` FOR EACH ROW BEGIN
    SET NEW.updated_at = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for table cafet2k.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_item_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `variant_id` (`variant_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`variant_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;



-- Bảng địa chỉ người dùng
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `ward` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng lịch sử điểm thưởng
CREATE TABLE IF NOT EXISTS `point_history` (
  `history_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `points` int NOT NULL,
  `type` enum('earn','redeem') NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `point_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `users`
ADD COLUMN `avatar_url` varchar(255) DEFAULT NULL AFTER `phone_number`,
ADD COLUMN `address` text AFTER `avatar_url`,
ADD COLUMN `birthday` date DEFAULT NULL AFTER `address`,
ADD COLUMN `total_points` int DEFAULT 0 AFTER `birthday`,
ADD COLUMN `total_orders` int DEFAULT 0 AFTER `total_points`,
ADD COLUMN `total_spent` decimal(10,2) DEFAULT 0.00 AFTER `total_orders`;

-- Tạo bảng user_settings nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS `user_settings` (
  `user_id` int NOT NULL,
  `order_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `promo_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `save_order_history` tinyint(1) NOT NULL DEFAULT 1,
  `personalized_recommendations` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_settings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;