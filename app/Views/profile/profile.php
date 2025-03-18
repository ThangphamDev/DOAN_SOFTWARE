<?php include '../app/Views/Shares/header.php'; ?>

<div class="profile-container">
    <h2>Thông Tin Cá Nhân</h2>
    
    <div class="profile-content">
        <div class="profile-info">
            <form class="profile-form" action="/profile/update" method="POST">
                <div class="form-group">
                    <label for="name">Họ tên:</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['name'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email'] ?? ''; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Địa chỉ:</label>
                    <textarea id="address" name="address" required><?php echo $user['address'] ?? ''; ?></textarea>
                </div>
                
                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </form>
        </div>
        
        <div class="change-password">
            <h3>Đổi mật khẩu</h3>
            <form class="password-form" action="/profile/change-password" method="POST">
                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="change-password-btn">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>

<style>
.profile-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
}

.profile-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.profile-info,
.change-password {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.form-group input[readonly] {
    background: #f8f9fa;
}

.save-btn,
.change-password-btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1em;
}

.save-btn {
    background: #4CAF50;
    color: white;
}

.change-password-btn {
    background: #007bff;
    color: white;
}

.save-btn:hover {
    background: #45a049;
}

.change-password-btn:hover {
    background: #0056b3;
}

@media (max-width: 768px) {
    .profile-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../app/Views/Shares/footer.php'; ?> 