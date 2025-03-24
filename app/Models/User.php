<?php
class User {
    private $conn;
    private $table_name = "Users";

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $full_name;
    public $phone_number;
    public $role;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đăng ký user mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (username, password, email, full_name, phone_number, role)
                VALUES
                (:username, :password, :email, :full_name, :phone_number, :role)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Mã hóa mật khẩu
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind các giá trị
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":role", $this->role);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Kiểm tra đăng nhập
    public function login($username, $password) {
        $query = "SELECT user_id, username, password, role, status
                FROM " . $this->table_name . "
                WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password']) && $row['status'] === 'hoạt động') {
                $this->user_id = $row['user_id'];
                $this->username = $row['username'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    // Cập nhật thông tin user
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    email = :email,
                    full_name = :full_name,
                    phone_number = :phone_number
                WHERE
                    user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));

        // Bind các giá trị
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":user_id", $this->user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Đổi mật khẩu
    public function changePassword($new_password) {
        $query = "UPDATE " . $this->table_name . "
                SET password = :password
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":password", $new_password);
        $stmt->bindParam(":user_id", $this->user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Kiểm tra username tồn tại
    public function usernameExists() {
        $query = "SELECT user_id, username, password, role, status
                FROM " . $this->table_name . "
                WHERE username = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // Kiểm tra email tồn tại
    public function emailExists() {
        $query = "SELECT user_id
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
} 