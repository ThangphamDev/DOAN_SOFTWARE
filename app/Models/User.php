<?php
class User {
    private $conn;
    private $table_name = "users";

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $full_name;
    public $phone_number;
    public $role;
    public $status;
    public $is_admin;
    public $avatar_url;
    public $address;
    public $birthday;
    public $total_points;
    public $total_orders;
    public $total_spent;
    public $created_at;
    public $last_login;

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
        try {
            $query = "UPDATE " . $this->table_name . "
                    SET
                        email = :email,
                        full_name = :full_name,
                        phone_number = :phone_number,
                        address = :address,
                        birthday = :birthday,
                        avatar_url = :avatar_url
                    WHERE
                        user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            // Làm sạch dữ liệu
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->full_name = htmlspecialchars(strip_tags($this->full_name));
            $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
            $this->address = isset($this->address) ? htmlspecialchars(strip_tags($this->address)) : null;
            $this->birthday = isset($this->birthday) ? htmlspecialchars(strip_tags($this->birthday)) : null;
            $this->avatar_url = isset($this->avatar_url) ? htmlspecialchars($this->avatar_url) : null;

            // Bind các giá trị
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":full_name", $this->full_name);
            $stmt->bindParam(":phone_number", $this->phone_number);
            $stmt->bindParam(":address", $this->address);
            $stmt->bindParam(":birthday", $this->birthday);
            $stmt->bindParam(":avatar_url", $this->avatar_url);
            $stmt->bindParam(":user_id", $this->user_id);

            // In log trước khi thực hiện truy vấn
            error_log("Updating user with avatar_url: " . $this->avatar_url);

            // Thực hiện truy vấn
            if($stmt->execute()) {
                return true;
            }
            
            error_log("Error updating user: " . print_r($stmt->errorInfo(), true));
            return false;
        } catch(PDOException $e) {
            error_log("Exception in User->update(): " . $e->getMessage());
            return false;
        }
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
    
    // Đọc tất cả người dùng (cho admin)
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY user_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Đọc thông tin một user
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        error_log("Query: " . $query);
        error_log("User ID: " . $this->user_id);
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Query result: " . print_r($row, true));
        
        if($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->full_name = $row['full_name'];
            $this->phone_number = $row['phone_number'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            $this->avatar_url = $row['avatar_url'];
            $this->address = $row['address'];
            $this->birthday = $row['birthday'];
            $this->total_points = $row['total_points'];
            $this->total_orders = $row['total_orders'];
            $this->total_spent = $row['total_spent'];
            $this->created_at = $row['created_at'];
            $this->last_login = $row['last_login'];
            return true;
        }
        return false;
    }
    
    // Đếm tổng số người dùng
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Cập nhật quyền người dùng
    public function updateRole() {
        $query = "UPDATE " . $this->table_name . "
                SET role = :role
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":user_id", $this->user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Cập nhật trạng thái người dùng
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":user_id", $this->user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
} 