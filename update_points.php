<?php
// Kết nối đến cơ sở dữ liệu
try {
    $db = new PDO('mysql:host=localhost;dbname=cafet2k', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cập nhật điểm tích lũy
    $stmt = $db->prepare('UPDATE users SET total_points = 100 WHERE user_id = 1');
    $stmt->execute();
    
    echo 'Đã cập nhật điểm tích lũy thành công!';
} catch(PDOException $e) {
    echo 'Lỗi: ' . $e->getMessage();
}
?> 