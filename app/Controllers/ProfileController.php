<?php
class ProfileController {
    private $db;
    private $user;
    private $order;
    
    public function __construct($db) {
        $this->db = $db;
        require_once __DIR__ . '/../Models/User.php';
        $this->user = new User($db);
        
        require_once __DIR__ . '/../Models/Order.php';
        $this->order = new Order($db);
        
        // Kiểm tra và thêm cột avatar_url nếu chưa tồn tại
        try {
            $this->db->query("SELECT avatar_url FROM users LIMIT 1");
        } catch (PDOException $e) {
            // Nếu có lỗi, thì cột không tồn tại - thêm nó vào
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN avatar_url VARCHAR(255) DEFAULT NULL AFTER phone_number");
                error_log("Đã thêm cột avatar_url vào bảng users");
            } catch (PDOException $e2) {
                error_log("Lỗi khi thêm cột avatar_url: " . $e2->getMessage());
            }
        }
        
        // Kiểm tra và thêm cột address nếu chưa tồn tại
        try {
            $this->db->query("SELECT address FROM users LIMIT 1");
        } catch (PDOException $e) {
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN address TEXT DEFAULT NULL AFTER avatar_url");
                error_log("Đã thêm cột address vào bảng users");
            } catch (PDOException $e2) {
                error_log("Lỗi khi thêm cột address: " . $e2->getMessage());
            }
        }
        
        // Kiểm tra và thêm cột birthday nếu chưa tồn tại
        try {
            $this->db->query("SELECT birthday FROM users LIMIT 1");
        } catch (PDOException $e) {
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN birthday DATE DEFAULT NULL AFTER address");
                error_log("Đã thêm cột birthday vào bảng users");
            } catch (PDOException $e2) {
                error_log("Lỗi khi thêm cột birthday: " . $e2->getMessage());
            }
        }
        
        // Kiểm tra và thêm cột total_points nếu chưa tồn tại
        try {
            $this->db->query("SELECT total_points FROM users LIMIT 1");
        } catch (PDOException $e) {
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN total_points INT DEFAULT 0 AFTER birthday");
                error_log("Đã thêm cột total_points vào bảng users");
            } catch (PDOException $e2) {
                error_log("Lỗi khi thêm cột total_points: " . $e2->getMessage());
            }
        }
        
        // Kiểm tra và thêm cột total_orders nếu chưa tồn tại
        try {
            $this->db->query("SELECT total_orders FROM users LIMIT 1");
        } catch (PDOException $e) {
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN total_orders INT DEFAULT 0 AFTER total_points");
                error_log("Đã thêm cột total_orders vào bảng users");
            } catch (PDOException $e2) {
                error_log("Lỗi khi thêm cột total_orders: " . $e2->getMessage());
            }
        }
        
        // Kiểm tra và thêm cột total_spent nếu chưa tồn tại
        try {
            $this->db->query("SELECT total_spent FROM users LIMIT 1");
        } catch (PDOException $e) {
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN total_spent DECIMAL(10,2) DEFAULT 0 AFTER total_orders");
                error_log("Đã thêm cột total_spent vào bảng users");
            } catch (PDOException $e2) {
                error_log("Lỗi khi thêm cột total_spent: " . $e2->getMessage());
            }
        }
    }
    
    // Hiển thị trang profile với thông tin user
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $this->user->user_id = $_SESSION['user_id'];
        error_log("User ID: " . $_SESSION['user_id']);
        
        if($this->user->readOne()) {
            error_log("User data found");
            error_log("User data: " . print_r($this->user, true));
            
            // Lấy tổng số đơn hàng và tổng tiền đã chi
            $total_orders = $this->order->getTotalOrdersByUserId($_SESSION['user_id']);
            $total_spent = $this->order->getTotalSpentByUserId($_SESSION['user_id']);
            
            // Tính điểm tích lũy từ tổng số tiền đã chi
            // Quy đổi: 1 điểm cho mỗi 10,000 VNĐ
            $calculated_points = floor($total_spent / 10000);
            
            try {
                // Cập nhật điểm tích lũy trong database
                $stmt = $this->db->prepare("UPDATE users SET total_points = :points WHERE user_id = :user_id");
                $stmt->bindParam(':points', $calculated_points);
                $stmt->bindParam(':user_id', $_SESSION['user_id']);
                $stmt->execute();
                error_log("Đã cập nhật điểm tích lũy: {$calculated_points} điểm cho user_id {$_SESSION['user_id']}");
            } catch (PDOException $e) {
                error_log("Lỗi khi cập nhật điểm tích lũy: " . $e->getMessage());
            }
            
            // Lấy danh sách đơn hàng
            $orders = $this->order->getOrdersByUserId($_SESSION['user_id']);
            
            // Lấy thông tin người dùng - lấy trực tiếp từ cơ sở dữ liệu
            $query = "SELECT * FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $_SESSION['user_id']);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_data) {
                // Thêm thông tin bổ sung
                $user_data['total_orders'] = $total_orders;
                $user_data['total_spent'] = $total_spent;
                $user = $user_data;
                error_log("User data from database: " . print_r($user, true));
            } else {
                // Tạo từ đối tượng như cũ nếu không lấy được từ DB
                $user = [
                    'user_id' => $this->user->user_id,
                    'username' => $this->user->username,
                    'email' => $this->user->email,
                    'full_name' => $this->user->full_name,
                    'phone_number' => isset($this->user->phone_number) ? $this->user->phone_number : '',
                    'role' => $this->user->role,
                    'status' => $this->user->status,
                    'avatar_url' => isset($this->user->avatar_url) ? $this->user->avatar_url : '',
                    'address' => isset($this->user->address) ? $this->user->address : '',
                    'birthday' => isset($this->user->birthday) ? $this->user->birthday : '',
                    'total_points' => isset($this->user->total_points) ? $this->user->total_points : 0,
                    'total_orders' => $total_orders,
                    'total_spent' => $total_spent,
                    'created_at' => isset($this->user->created_at) ? $this->user->created_at : '',
                    'last_login' => isset($this->user->last_login) ? $this->user->last_login : ''
                ];
            }
            error_log("User data array: " . print_r($user, true));
            
            try {
                // Lấy cài đặt thông báo
                $query = "SELECT * FROM user_settings WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $_SESSION['user_id']);
                $stmt->execute();
                $settings = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$settings) {
                    // Nếu chưa có cài đặt, tạo mới với giá trị mặc định
                    $query = "INSERT INTO user_settings (user_id) VALUES (?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$_SESSION['user_id']]);
                    
                    // Lấy lại cài đặt vừa tạo
                    $query = "SELECT * FROM user_settings WHERE user_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(1, $_SESSION['user_id']);
                    $stmt->execute();
                    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                
                // Lấy danh sách địa chỉ giao hàng
                $query = "SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $_SESSION['user_id']);
                $stmt->execute();
                $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
            } catch (PDOException $e) {
                $settings = [];
                $addresses = [];
            }
            
            // Truyền dữ liệu để view hiển thị
            $data = [
                'user' => $user,
                'settings' => $settings,
                'addresses' => $addresses,
                'orders' => $orders
            ];
            extract($data);
            require_once __DIR__ . '/../Views/profile/profile.php';
        } else {
            $_SESSION['error'] = "Không thể tải thông tin người dùng.";
            require_once __DIR__ . '/../Views/profile/profile.php';
        }
    }
    
    // Cập nhật thông tin người dùng
    public function update() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->user_id = $_SESSION['user_id'];
            
            // Kiểm tra nếu là yêu cầu đổi mật khẩu
            if(isset($_POST['change_password']) && $_POST['change_password'] == 1) {
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];
                
                if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin mật khẩu.";
                    header("Location: /profile");
                    exit();
                }
                
                if($new_password !== $confirm_password) {
                    $_SESSION['error'] = "Mật khẩu mới không khớp.";
                    header("Location: /profile");
                    exit();
                }
                
                // Kiểm tra mật khẩu hiện tại
                $query = "SELECT password FROM Users WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $_SESSION['user_id']);
                $stmt->execute();
                
                if($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(password_verify($current_password, $row['password'])) {
                        if($this->user->changePassword($new_password)) {
                            $_SESSION['success'] = "Đổi mật khẩu thành công.";
                            header("Location: /profile");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = "Mật khẩu hiện tại không chính xác.";
                        header("Location: /profile");
                        exit();
                    }
                }
            } else {
                // Cập nhật thông tin cá nhân
                $errors = [];
                
                // Kiểm tra email
                if(empty($_POST['email'])) {
                    $errors[] = "Email không được để trống.";
                } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Email không hợp lệ.";
                } else {
                    // Kiểm tra email đã tồn tại chưa (trừ email của người dùng hiện tại)
                    $query = "SELECT user_id FROM Users WHERE email = ? AND user_id != ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(1, $_POST['email']);
                    $stmt->bindParam(2, $_SESSION['user_id']);
                    $stmt->execute();
                    
                    if($stmt->rowCount() > 0) {
                        $errors[] = "Email đã được sử dụng bởi tài khoản khác.";
                    }
                }
                
                // Kiểm tra họ tên
                if(empty($_POST['full_name'])) {
                    $errors[] = "Họ tên không được để trống.";
                }
                
                // Debug: In ra dữ liệu POST
                error_log("POST data: " . print_r($_POST, true));
                
                // Kiểm tra số điện thoại
                if(empty($_POST['phone_number'])) {
                    $errors[] = "Số điện thoại không được để trống.";
                } elseif(!preg_match("/^[0-9]{10,11}$/", $_POST['phone_number'])) {
                    $errors[] = "Số điện thoại không hợp lệ.";
                }
                
                // Nếu có lỗi, hiển thị thông báo và quay lại
                if(!empty($errors)) {
                    $_SESSION['error'] = implode("<br>", $errors);
                    header("Location: /profile");
                    exit();
                }
                
                // Cập nhật thông tin
                $this->user->email = $_POST['email'];
                $this->user->full_name = $_POST['full_name'];
                $this->user->phone_number = $_POST['phone_number'];
                $this->user->address = isset($_POST['address']) ? $_POST['address'] : null;
                $this->user->birthday = isset($_POST['birthday']) ? $_POST['birthday'] : null;
                
                // Xử lý upload ảnh đại diện
                if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $max_size = 2 * 1024 * 1024; // 2MB
                    
                    // Kiểm tra loại file và kích thước
                    if(!in_array($_FILES['avatar']['type'], $allowed_types)) {
                        $errors[] = "Chỉ chấp nhận file ảnh (jpg, png, gif, webp).";
                    } elseif($_FILES['avatar']['size'] > $max_size) {
                        $errors[] = "Kích thước ảnh không được vượt quá 2MB.";
                    } else {
                        // Tạo thư mục lưu trữ ảnh nếu chưa tồn tại
                        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/avatars/';
                        if(!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        // Tạo tên file duy nhất
                        $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                        $new_filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_extension;
                        $target_file = $upload_dir . $new_filename;
                        
                        // Di chuyển file từ thư mục tạm vào thư mục đích
                        if(move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                            // Cập nhật đường dẫn ảnh vào database
                            $this->user->avatar_url = '/public/images/avatars/' . $new_filename;
                            error_log("Avatar uploaded successfully: " . $this->user->avatar_url);
                        } else {
                            $errors[] = "Có lỗi xảy ra khi tải ảnh lên. Vui lòng thử lại.";
                            error_log("Error uploading avatar: " . $_FILES['avatar']['error']);
                        }
                    }
                    
                    // Nếu có lỗi, hiển thị thông báo và quay lại
                    if(!empty($errors)) {
                        $_SESSION['error'] = implode("<br>", $errors);
                        header("Location: /profile");
                        exit();
                    }
                }
                
                if($this->user->update()) {
                    $_SESSION['success'] = "Cập nhật thông tin thành công.";
                } else {
                    $_SESSION['error'] = "Cập nhật thông tin thất bại. Vui lòng thử lại sau.";
                }
                
                header("Location: /profile");
                exit();
            }
        }
        
        header("Location: /profile");
        exit();
    }
    
    // Xử lý cài đặt thông báo
    public function updateSettings() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'order_notifications' => isset($_POST['order_notifications']) ? 1 : 0,
                'promo_notifications' => isset($_POST['promo_notifications']) ? 1 : 0,
                'email_notifications' => isset($_POST['email_notifications']) ? 1 : 0,
                'save_order_history' => isset($_POST['save_order_history']) ? 1 : 0,
                'personalized_recommendations' => isset($_POST['personalized_recommendations']) ? 1 : 0
            ];
            
            $query = "UPDATE user_settings SET 
                     order_notifications = :order_notifications,
                     promo_notifications = :promo_notifications,
                     email_notifications = :email_notifications,
                     save_order_history = :save_order_history,
                     personalized_recommendations = :personalized_recommendations
                     WHERE user_id = :user_id";
                     
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            foreach($settings as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            if($stmt->execute()) {
                $_SESSION['success'] = "Cập nhật cài đặt thành công.";
            } else {
                $_SESSION['error'] = "Cập nhật cài đặt thất bại.";
            }
            
            header("Location: /profile");
            exit();
        }
        
        header("Location: /profile");
        exit();
    }
    
    // Xử lý thêm địa chỉ giao hàng
    public function addAddress() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $query = "INSERT INTO user_addresses (user_id, recipient_name, phone_number, address_line1, address_line2, is_default) 
                     VALUES (:user_id, :recipient_name, :phone_number, :address_line1, :address_line2, :is_default)";
                     
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':recipient_name', $_POST['recipient_name']);
            $stmt->bindParam(':phone_number', $_POST['phone_number']);
            $stmt->bindParam(':address_line1', $_POST['address_line1']);
            $stmt->bindParam(':address_line2', $_POST['address_line2']);
            $stmt->bindParam(':is_default', $_POST['is_default']);
            
            if($stmt->execute()) {
                $_SESSION['success'] = "Thêm địa chỉ giao hàng thành công.";
            } else {
                $_SESSION['error'] = "Thêm địa chỉ giao hàng thất bại.";
            }
            
            header("Location: /profile");
            exit();
        }
        
        header("Location: /profile");
        exit();
    }
    
    // Xử lý xóa địa chỉ giao hàng
    public function deleteAddress($address_id) {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $query = "DELETE FROM user_addresses WHERE address_id = :address_id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':address_id', $address_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        
        if($stmt->execute()) {
            $_SESSION['success'] = "Xóa địa chỉ giao hàng thành công.";
        } else {
            $_SESSION['error'] = "Xóa địa chỉ giao hàng thất bại.";
        }
        
        header("Location: /profile");
        exit();
    }
    
    // Xử lý đặt địa chỉ mặc định
    public function setDefaultAddress($address_id) {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        // Bỏ đặt mặc định cho tất cả địa chỉ
        $query = "UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        
        // Đặt địa chỉ mới làm mặc định
        $query = "UPDATE user_addresses SET is_default = 1 WHERE address_id = :address_id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':address_id', $address_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        
        if($stmt->execute()) {
            $_SESSION['success'] = "Đặt địa chỉ mặc định thành công.";
        } else {
            $_SESSION['error'] = "Đặt địa chỉ mặc định thất bại.";
        }
        
        header("Location: /profile");
        exit();
    }
} 