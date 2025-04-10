<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $user;

    public function __construct($db) {
        $this->user = new User($db);
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị form đăng nhập
    public function loginForm() {
        if(isset($_SESSION['user_id'])) {
            header("Location: /");
            exit();
        }
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // Xử lý đăng nhập
    public function login() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if($this->user->login($username, $password)) {
                $_SESSION['user_id'] = $this->user->user_id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['role'] = $this->user->role;
                $_SESSION['success'] = "Đăng nhập thành công.";
                header("Location: /");
                exit();
            } else {
                $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không chính xác.";
                header("Location: /login");
                exit();
            }
        }
    }

    // Hiển thị form đăng ký
    public function registerForm() {
        if(isset($_SESSION['user_id'])) {
            header("Location: /");
            exit();
        }
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    // Xử lý đăng ký
    public function register() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $full_name = $_POST['full_name'];
            $phone_number = $_POST['phone_number'];

            // Kiểm tra username đã tồn tại
            if($this->user->usernameExists($username)) {
                $_SESSION['error'] = "Tên đăng nhập đã tồn tại.";
                header("Location: /register");
                exit();
            }

            // Kiểm tra email đã tồn tại
            if($this->user->emailExists($email)) {
                $_SESSION['error'] = "Email đã tồn tại.";
                header("Location: /register");
                exit();
            }

            // Tạo tài khoản mới
            $this->user->username = $username;
            $this->user->password = $password;
            $this->user->email = $email;
            $this->user->full_name = $full_name;
            $this->user->phone_number = $phone_number;
            $this->user->role = 'user';
            $this->user->status = 1;

            if($this->user->create()) {
                $_SESSION['success'] = "Đăng ký thành công. Vui lòng đăng nhập.";
                header("Location: /login");
                exit();
            } else {
                $_SESSION['error'] = "Không thể tạo tài khoản.";
                header("Location: /register");
                exit();
            }
        }
    }

    // Xử lý đăng xuất
    public function logout() {
        session_destroy();
        header("Location: /login");
        exit();
    }

    // Hiển thị form đổi mật khẩu
    public function changePasswordForm() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        require_once __DIR__ . '/../Views/auth/change_password.php';
    }

    // Xử lý đổi mật khẩu
    public function changePassword() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if($new_password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu mới không khớp.";
                header("Location: /change-password");
                exit();
            }

            $this->user->user_id = $_SESSION['user_id'];
            if($this->user->changePassword($current_password, $new_password)) {
                $_SESSION['success'] = "Đổi mật khẩu thành công.";
                header("Location: /profile");
                exit();
            } else {
                $_SESSION['error'] = "Mật khẩu hiện tại không chính xác.";
                header("Location: /change-password");
                exit();
            }
        }
    }
} 