<?php
$page = 'profile';
include "app/Views/components/header.php";
?>

<style>
    .profile-container {
        max-width: 1000px;
        margin: 120px auto 60px;
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: var(--shadow);
        overflow: hidden;
        min-height: 600px;
    }
    
    .profile-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: var(--white);
        padding: 40px;
        text-align: center;
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid var(--white);
        object-fit: cover;
        margin: 0 auto 20px;
        background-color: var(--white);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-avatar i {
        font-size: 60px;
        color: var(--gray);
    }
    
    .profile-name {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .profile-email {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 20px;
    }
    
    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 20px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: 700;
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.8;
    }
    
    .profile-tabs {
        display: flex;
        border-bottom: 1px solid var(--gray);
        background-color: var(--white);
    }
    
    .profile-tab {
        padding: 15px 30px;
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        cursor: pointer;
        transition: var(--transition);
        border-bottom: 3px solid transparent;
    }
    
    .profile-tab:hover {
        background-color: rgba(0,0,0,0.02);
        color: var(--primary);
    }
    
    .profile-tab.active {
        color: var(--primary);
        border-bottom: 3px solid var(--primary);
    }
    
    .tab-content {
        padding: 30px;
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--gray);
        border-radius: 5px;
        font-size: 16px;
        transition: var(--transition);
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(111, 78, 55, 0.1);
    }
    
    .btn-primary {
        background-color: var(--primary);
        color: var(--white);
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-block;
    }
    
    .btn-primary:hover {
        background-color: var(--dark);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-outline {
        background-color: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-block;
    }
    
    .btn-outline:hover {
        background-color: var(--primary);
        color: var(--white);
    }
    
    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background-color: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }
    
    .alert-danger {
        background-color: #F8D7DA;
        color: #721C24;
        border: 1px solid #F5C6CB;
    }
    
    .order-card {
        border: 1px solid var(--gray);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: var(--transition);
    }
    
    .order-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-3px);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--gray);
    }
    
    .order-id {
        font-weight: 700;
        color: var(--dark);
    }
    
    .order-date {
        font-size: 14px;
        color: #666;
    }
    
    .order-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #FFF3CD;
        color: #856404;
    }
    
    .status-processing {
        background-color: #D1ECF1;
        color: #0C5460;
    }
    
    .status-completed {
        background-color: #D4EDDA;
        color: #155724;
    }
    
    .status-cancelled {
        background-color: #F8D7DA;
        color: #721C24;
    }
    
    .order-items {
        margin-bottom: 15px;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray);
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .item-img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        margin-right: 15px;
    }
    
    .item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-info {
        flex: 1;
    }
    
    .item-name {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }
    
    .item-variant {
        font-size: 14px;
        color: #666;
    }
    
    .item-price {
        font-weight: 600;
        color: var(--primary);
    }
    
    .order-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        padding-top: 15px;
        border-top: 1px solid var(--gray);
    }
    
    .order-actions {
        margin-top: 20px;
    }
    
    .password-section {
        border-top: 1px solid var(--gray);
        margin-top: 30px;
        padding-top: 30px;
    }
    
    .notification-setting {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid var(--gray);
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: var(--primary);
    }
    
    input:focus + .toggle-slider {
        box-shadow: 0 0 1px var(--primary);
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    
    .setting-description {
        flex: 1;
        padding-right: 20px;
    }
    
    .setting-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .setting-subtitle {
        font-size: 14px;
        color: #666;
    }
    
    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 768px) {
        .profile-stats {
            gap: 20px;
        }
        
        .profile-tab {
            padding: 12px 20px;
            font-size: 14px;
        }
        
        .two-column {
            grid-template-columns: 1fr;
        }
        
        .tab-content {
            padding: 20px;
        }
    }
</style>

<div class="container">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if (isset($user) && !empty($user['avatar_url'])): ?>
                    <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="User Avatar">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <h1 class="profile-name"><?php echo isset($user) ? htmlspecialchars($user['full_name']) : 'Khách hàng'; ?></h1>
            <p class="profile-email"><?php echo isset($user) ? htmlspecialchars($user['email']) : 'example@email.com'; ?></p>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($user) && isset($user['total_orders']) ? $user['total_orders'] : '0'; ?></div>
                    <div class="stat-label">Đơn hàng</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($user) && isset($user['total_spent']) ? number_format($user['total_spent'], 0, ',', '.') : '0'; ?></div>
                    <div class="stat-label">Đã chi tiêu (VNĐ)</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($user) && isset($user['total_points']) ? $user['total_points'] : '0'; ?></div>
                    <div class="stat-label">Điểm tích lũy</div>
                </div>
            </div>
        </div>
        
        <div class="profile-tabs">
            <div class="profile-tab active" data-tab="info">Thông tin cá nhân</div>
            <div class="profile-tab" data-tab="orders">Lịch sử đơn hàng</div>
            <div class="profile-tab" data-tab="settings">Cài đặt tài khoản</div>
        </div>
        
        <div id="info" class="tab-content active">
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form action="/profile/update" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-4 text-center">
                    <label for="avatar" class="form-label d-block">Ảnh đại diện</label>
                    <div class="avatar-preview mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 2px solid #ddd; position: relative; cursor: pointer;">
                        <?php if (isset($user) && !empty($user['avatar_url'])): ?>
                            <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="Avatar Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                <i class="fas fa-user" style="font-size: 40px; color: #adb5bd;"></i>
                            </div>
                        <?php endif; ?>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); color: white; text-align: center; padding: 3px 0; font-size: 12px;">
                            <i class="fas fa-camera"></i> Thay đổi
                        </div>
                    </div>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="form-control d-none">
                    <small class="text-muted">Nhấp vào ảnh để thay đổi (tối đa 2MB)</small>
                </div>

                <div class="two-column">
                    <div class="form-group">
                        <label for="full_name" class="form-label">Họ và tên</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" 
                               value="<?php echo isset($user) ? htmlspecialchars($user['full_name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo isset($user) ? htmlspecialchars($user['email']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="two-column">
                    <div class="form-group">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone_number" class="form-control" 
                               value="<?php echo isset($user) ? htmlspecialchars($user['phone_number']) : ''; ?>">
                    </div>
                
                    <div class="form-group">
                        <label for="birthday" class="form-label">Ngày sinh</label>
                        <input type="date" id="birthday" name="birthday" class="form-control" 
                               value="<?php echo isset($user) && isset($user['birthday']) ? $user['birthday'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" id="address" name="address" class="form-control" 
                           value="<?php echo isset($user) && isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-primary">Cập nhật thông tin</button>
                </div>
                
                <!-- Mục hiển thị điểm tích lũy -->
                <div class="points-section mt-4">
                    <h3>Điểm tích lũy của bạn</h3>
                    <div class="points-info p-3 border rounded mb-3">
                        <div class="points-value mb-2">
                            <i class="fas fa-star text-warning"></i> 
                            <span style="font-size: 24px; font-weight: bold; color: var(--primary);">
                                <?php echo isset($user) && isset($user['total_points']) ? number_format($user['total_points']) : '0'; ?> điểm
                            </span>
                        </div>
                        <div class="points-info-text">
                            <p>Điểm tích lũy được tính dựa trên giá trị đơn hàng của bạn:</p>
                            <ul>
                                <li>Mỗi 10,000 VNĐ = 1 điểm tích lũy</li>
                                <li>Tổng chi tiêu của bạn: <?php echo isset($user) && isset($user['total_spent']) ? number_format($user['total_spent'], 0, ',', '.') : '0'; ?> VNĐ</li>
                                <li>Tương đương với: <?php echo isset($user) && isset($user['total_spent']) ? floor($user['total_spent']/10000) : '0'; ?> điểm</li>
                            </ul>
                            <p>Bạn có thể dùng điểm tích lũy để đổi các ưu đãi đặc biệt trong mục "Phần thưởng".</p>
                        </div>
                    </div>
                </div>
                
                <div class="password-section">
                    <h3>Đổi mật khẩu</h3>
                    <div class="form-group">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                    </div>
                    
                    <div class="two-column">
                        <div class="form-group">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" id="new_password" name="new_password" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="change_password" value="1" class="btn-primary">Đổi mật khẩu</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div id="orders" class="tab-content">
            <h2>Lịch sử đơn hàng</h2>
            
            <?php if(isset($orders) && !empty($orders)): ?>
                <?php foreach($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-id">Đơn hàng #<?php echo $order['order_id']; ?></div>
                                <div class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></div>
                            </div>
                            <?php 
                                $statusClass = '';
                                $statusText = '';
                                
                                switch($order['status']) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        $statusText = 'Chờ xử lý';
                                        break;
                                    case 'processing':
                                        $statusClass = 'status-processing';
                                        $statusText = 'Đang xử lý';
                                        break;
                                    case 'completed':
                                        $statusClass = 'status-completed';
                                        $statusText = 'Hoàn thành';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'status-cancelled';
                                        $statusText = 'Đã hủy';
                                        break;
                                    case 'đang chờ':
                                        $statusClass = 'status-pending';
                                        $statusText = 'Đang chờ';
                                        break;
                                    default:
                                        $statusClass = 'status-pending';
                                        $statusText = $order['status'];
                                }
                            ?>
                            <div class="order-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></div>
                        </div>
                        
                        <div class="order-items">
                            <?php if(isset($order['items']) && !empty($order['items'])): ?>
                                <?php foreach($order['items'] as $item): ?>
                                    <div class="order-item">
                                        <div class="item-img">
                                            <?php
                                            $imageUrl = !empty($item['image_url']) ? $item['image_url'] : '/public/images/default-product.jpg';
                                            // Đảm bảo đường dẫn ảnh bắt đầu với /public
                                            if (!empty($imageUrl) && !str_starts_with($imageUrl, '/public')) {
                                                $imageUrl = '/public' . $imageUrl;
                                            }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        </div>
                                        <div class="item-info">
                                            <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <?php if(isset($item['variant_value']) && !empty($item['variant_value'])): ?>
                                                <div class="item-variant">
                                                    <?php echo htmlspecialchars($item['variant_type'] ?? 'Loại'); ?>: 
                                                    <?php echo htmlspecialchars($item['variant_value']); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="item-variant">Số lượng: <?php echo $item['quantity']; ?></div>
                                        </div>
                                        <div class="item-price"><?php echo number_format($item['unit_price'], 0, ',', '.'); ?> đ</div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Không có thông tin chi tiết đơn hàng</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="order-total">
                            <div>Tổng tiền:</div>
                            <div><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</div>
                        </div>
                        
                        <div class="order-actions">
                            <a href="/orders/view/<?php echo $order['order_id']; ?>" class="btn-outline">Xem chi tiết</a>
                            
                            <?php if($order['status'] === 'completed'): ?>
                                <a href="/feedback/create/<?php echo $order['order_id']; ?>" class="btn-outline">Đánh giá</a>
                            <?php endif; ?>
                            
                            <?php if($order['status'] === 'pending' || $order['status'] === 'đang chờ'): ?>
                                <a href="/orders/cancel/<?php echo $order['order_id']; ?>" class="btn-outline" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">Hủy đơn</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag mb-3" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mb-3">Bạn chưa có đơn hàng nào.</p>
                    <a href="/menu" class="btn-primary">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
        
        <div id="settings" class="tab-content">
            <h2>Cài đặt tài khoản</h2>
            
            <form action="/profile/settings" method="POST">
                <h3>Thông báo</h3>
                
                <div class="notification-setting">
                    <div class="setting-description">
                        <div class="setting-title">Thông báo đơn hàng</div>
                        <div class="setting-subtitle">Nhận thông báo về trạng thái đơn hàng của bạn</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="order_notifications" <?php echo isset($settings) && isset($settings['order_notifications']) && $settings['order_notifications'] ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-setting">
                    <div class="setting-description">
                        <div class="setting-title">Thông báo khuyến mãi</div>
                        <div class="setting-subtitle">Nhận thông báo về các chương trình khuyến mãi và ưu đãi</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="promo_notifications" <?php echo isset($settings) && isset($settings['promo_notifications']) && $settings['promo_notifications'] ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-setting">
                    <div class="setting-description">
                        <div class="setting-title">Thông báo qua email</div>
                        <div class="setting-subtitle">Nhận thông báo qua địa chỉ email của bạn</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="email_notifications" <?php echo isset($settings) && isset($settings['email_notifications']) && $settings['email_notifications'] ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <h3 class="mt-4">Quyền riêng tư</h3>
                
                <div class="notification-setting">
                    <div class="setting-description">
                        <div class="setting-title">Lưu lịch sử đơn hàng</div>
                        <div class="setting-subtitle">Cho phép hệ thống lưu trữ lịch sử đơn hàng của bạn</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="save_order_history" <?php echo isset($settings) && isset($settings['save_order_history']) && $settings['save_order_history'] ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-setting">
                    <div class="setting-description">
                        <div class="setting-title">Sử dụng dữ liệu để cá nhân hóa</div>
                        <div class="setting-subtitle">Cho phép sử dụng dữ liệu của bạn để đề xuất sản phẩm phù hợp</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="personalized_recommendations" <?php echo isset($settings) && isset($settings['personalized_recommendations']) && $settings['personalized_recommendations'] ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn-primary">Lưu cài đặt</button>
                </div>
            </form>
            
            <div class="mt-5">
                <h3>Địa chỉ giao hàng</h3>
                
                <button type="button" class="btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fas fa-plus"></i> Thêm địa chỉ mới
                </button>
                
                <?php if(isset($addresses) && !empty($addresses)): ?>
                    <div class="address-list">
                        <?php foreach($addresses as $address): ?>
                            <div class="address-card p-3 border rounded mb-3 position-relative">
                                <h5 class="mb-2">
                                    <?php echo htmlspecialchars($address['recipient_name']); ?>
                                    <?php if($address['is_default']): ?>
                                        <span class="badge bg-primary ms-2">Mặc định</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($address['phone_number']); ?></p>
                                <p class="mb-1">
                                    <?php echo htmlspecialchars($address['address_line1']); ?>
                                    <?php if(!empty($address['address_line2'])): ?>, <?php echo htmlspecialchars($address['address_line2']); ?><?php endif; ?>
                                </p>
                                <div class="address-actions mt-2">
                                    <?php if(!$address['is_default']): ?>
                                        <a href="/profile/address/default/<?php echo $address['address_id']; ?>" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-star"></i> Đặt làm mặc định
                                        </a>
                                    <?php endif; ?>
                                    <a href="/profile/address/delete/<?php echo $address['address_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Bạn chưa có địa chỉ giao hàng nào.</p>
                <?php endif; ?>
            </div>
            
            <!-- Modal thêm địa chỉ -->
            <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ giao hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/profile/address/add" method="POST">
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="recipient_name" class="form-label">Họ và tên người nhận</label>
                                    <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="phone_number" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="address_line1" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="address_line2" class="form-label">Địa chỉ phụ (tùy chọn)</label>
                                    <input type="text" class="form-control" id="address_line2" name="address_line2">
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                                    <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Thêm địa chỉ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <h3>Xóa tài khoản</h3>
                <p>Khi xóa tài khoản, tất cả dữ liệu của bạn sẽ bị xóa vĩnh viễn và không thể khôi phục.</p>
                <button type="button" class="btn-outline" onclick="confirmDelete()">Xóa tài khoản</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Switching
        const tabs = document.querySelectorAll('.profile-tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to current tab and content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
                
                // Lưu tab đang active vào localStorage
                localStorage.setItem('activeProfileTab', tabId);
            });
        });
        
        // Khôi phục tab đã chọn từ localStorage
        const savedTab = localStorage.getItem('activeProfileTab');
        if (savedTab) {
            const tabToActivate = document.querySelector(`.profile-tab[data-tab="${savedTab}"]`);
            if (tabToActivate) {
                tabToActivate.click();
            }
        }
        
        // Password validation
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        if (newPasswordInput && confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== newPasswordInput.value) {
                    this.setCustomValidity('Mật khẩu không khớp');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            newPasswordInput.addEventListener('input', function() {
                if (confirmPasswordInput.value && confirmPasswordInput.value !== this.value) {
                    confirmPasswordInput.setCustomValidity('Mật khẩu không khớp');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
        }
        
        // Avatar preview functionality
        const avatarPreview = document.querySelector('.avatar-preview');
        const avatarInput = document.getElementById('avatar');
        
        if (avatarPreview && avatarInput) {
            avatarPreview.addEventListener('click', function() {
                avatarInput.click();
            });
            
            avatarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Kiểm tra kích thước file (giới hạn 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Kích thước ảnh quá lớn. Vui lòng chọn ảnh dưới 2MB.');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = avatarPreview.querySelector('img');
                        if (img) {
                            img.src = e.target.result;
                        } else {
                            avatarPreview.innerHTML = `
                                <img src="${e.target.result}" alt="Avatar Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); color: white; text-align: center; padding: 3px 0; font-size: 12px;">
                                    <i class="fas fa-camera"></i> Thay đổi
                                </div>
                            `;
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    
    // Delete account confirmation
    function confirmDelete() {
        if (confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác.')) {
            window.location.href = '/profile/delete';
        }
    }
</script>

<?php include "app/Views/components/footer.php"; ?>