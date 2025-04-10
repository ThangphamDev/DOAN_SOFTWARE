<?php
require_once __DIR__ . '/../Models/Notification.php';

class NotificationController {
    private $db;
    private $notification;

    public function __construct($db) {
        $this->db = $db;
        $this->notification = new Notification($db);
    }

    // Hiển thị trang thông báo cho người dùng
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        // Lấy danh sách thông báo của người dùng hiện tại
        $notifications = $this->notification->getByUserId($_SESSION['user_id']);
        require_once __DIR__ . '/../Views/notifications/notifications.php';
    }

    // Đánh dấu 1 thông báo là đã đọc
    public function markAsRead() {
        if(!isset($_SESSION['user_id']) || !isset($_POST['notification_id'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit();
        }

        $this->notification->notification_id = $_POST['notification_id'];
        $this->notification->user_id = $_SESSION['user_id'];
        
        if($this->notification->markAsRead()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông báo']);
        }
    }

    // Đánh dấu tất cả thông báo là đã đọc
    public function markAllAsRead() {
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit();
        }

        if($this->notification->markAllAsRead($_SESSION['user_id'])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông báo']);
        }
    }

    // Trang quản lý thông báo (dành cho admin)
    public function adminIndex() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        // Lấy danh sách tất cả thông báo
        $notifications = $this->notification->getAll();
        require_once __DIR__ . '/../Views/admin/notifications/index.php';
    }

    // Trang tạo thông báo mới (dành cho admin)
    public function create() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        require_once __DIR__ . '/../Views/admin/notifications/create.php';
    }

    // Xử lý tạo thông báo mới (dành cho admin)
    public function store() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/notifications');
            exit();
        }

        $title = $_POST['title'] ?? '';
        $message = $_POST['message'] ?? '';
        $type = $_POST['type'] ?? 'general';
        $related_id = !empty($_POST['related_id']) ? $_POST['related_id'] : null;
        
        // Kiểm tra các trường bắt buộc
        if(empty($title) || empty($message)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
            header('Location: /admin/notifications/create');
            exit();
        }

        // Xác định người nhận thông báo
        $recipients = [];
        
        if($_POST['recipient_type'] === 'all') {
            // Lấy tất cả user_id từ bảng users
            $query = "SELECT user_id FROM users WHERE status = 'hoạt động'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipients[] = $row['user_id'];
            }
        } 
        elseif($_POST['recipient_type'] === 'specific' && !empty($_POST['user_ids'])) {
            $recipients = $_POST['user_ids'];
        }
        elseif($_POST['recipient_type'] === 'role' && !empty($_POST['role'])) {
            // Lấy user_id từ bảng users dựa trên role
            $query = "SELECT user_id FROM users WHERE role = ? AND status = 'hoạt động'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $_POST['role']);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipients[] = $row['user_id'];
            }
        }

        // Tạo thông báo cho từng người dùng
        $success = true;
        foreach($recipients as $user_id) {
            $this->notification->user_id = $user_id;
            $this->notification->title = $title;
            $this->notification->message = $message;
            $this->notification->type = $type;
            $this->notification->related_id = $related_id;
            $this->notification->is_read = 0;
            
            if(!$this->notification->create()) {
                $success = false;
            }
        }

        if($success) {
            $_SESSION['success'] = "Tạo thông báo thành công.";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi tạo thông báo.";
        }

        header('Location: /admin/notifications');
        exit();
    }

    // Xóa thông báo (cho người dùng)
    public function delete() {
        if(!isset($_SESSION['user_id']) || !isset($_POST['notification_id'])) {
            if (isset($_POST['redirect'])) {
                header('Location: /notifications');
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
                exit();
            }
        }

        $this->notification->notification_id = $_POST['notification_id'];
        $this->notification->user_id = $_SESSION['user_id'];
        
        $result = $this->notification->delete();
        
        // Nếu có tham số redirect, thực hiện chuyển hướng sau khi xóa
        if (isset($_POST['redirect'])) {
            if ($result) {
                $_SESSION['success'] = 'Đã xóa thông báo thành công.';
            } else {
                $_SESSION['error'] = 'Không thể xóa thông báo.';
            }
            header('Location: /notifications');
            exit();
        } else {
            // Trả về JSON response cho AJAX requests
            if($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa thông báo']);
            }
        }
    }

    // Lấy số thông báo chưa đọc của người dùng
    public function getUnreadCount() {
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(['count' => 0]);
            exit();
        }

        $count = $this->notification->getUnreadCount($_SESSION['user_id']);
        echo json_encode(['count' => $count]);
    }

    // Hiển thị chi tiết thông báo
    public function detail($id = null) {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        if($id === null) {
            header('Location: /notifications');
            exit();
        }

        // Lấy thông tin chi tiết thông báo
        $notification = $this->notification->getById($id, $_SESSION['user_id']);
        
        if(!$notification) {
            header('Location: /notifications');
            exit();
        }

        // Lấy thông báo liên quan
        $related_notifications = $this->notification->getRelated($id, $_SESSION['user_id']);
        
        // Nếu là request AJAX đánh dấu đã đọc, không cần load view
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
            // Đánh dấu đã đọc
            $this->notification->notification_id = $id;
            $this->notification->user_id = $_SESSION['user_id'];
            $this->notification->markAsRead();
            
            echo json_encode(['success' => true]);
            exit();
        }
        
        require_once __DIR__ . '/../Views/notifications/notification_detail.php';
    }

    // Xóa thông báo (cho admin)
    public function adminDeleteNotification() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['notification_id'])) {
            $_SESSION['error'] = "Thiếu thông tin cần thiết.";
            header('Location: /admin/notifications');
            exit();
        }

        $notification_id = $_POST['notification_id'];
        
        // Xóa thông báo
        $query = "DELETE FROM notifications WHERE notification_id = ?";
        $stmt = $this->db->prepare($query);
        
        if($stmt->execute([$notification_id])) {
            $_SESSION['success'] = "Đã xóa thông báo thành công.";
        } else {
            $_SESSION['error'] = "Không thể xóa thông báo.";
        }
        
        header('Location: /admin/notifications');
        exit();
    }
} 