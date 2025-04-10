<?php

class ContactController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Hiển thị trang liên hệ
    public function index() {
        require_once __DIR__ . '/../Views/contact.php';
    }

    // Hiển thị trang giới thiệu
    public function about() {
        require_once __DIR__ . '/../Views/about.php';
    }

    // Xử lý gửi form liên hệ
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contact');
            exit();
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        // Validate dữ liệu
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            header('Location: /contact');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: /contact');
            exit();
        }

        try {
            // Lưu thông tin liên hệ vào database
            $query = "INSERT INTO contacts (name, email, phone, subject, message, created_at) 
                     VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$name, $email, $phone, $subject, $message]);

            // Gửi email thông báo cho admin
            $to = "admin@thecoffeehouse.com";
            $emailSubject = "Liên hệ mới từ: " . $name;
            $emailMessage = "Thông tin liên hệ mới:\n\n";
            $emailMessage .= "Họ tên: " . $name . "\n";
            $emailMessage .= "Email: " . $email . "\n";
            $emailMessage .= "Số điện thoại: " . $phone . "\n";
            $emailMessage .= "Chủ đề: " . $subject . "\n\n";
            $emailMessage .= "Nội dung:\n" . $message;
            
            $headers = "From: " . $email . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";
            
            mail($to, $emailSubject, $emailMessage, $headers);

            $_SESSION['success'] = 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại sau';
        }

        header('Location: /contact');
        exit();
    }
} 