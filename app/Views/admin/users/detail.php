<?php
$page_title = "Chi tiết người dùng";
$currentPage = 'users';

// Bắt đầu output buffering
ob_start();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết người dùng</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/users">Quản lý người dùng</a></li>
        <li class="breadcrumb-item active">Chi tiết người dùng</li>
    </ol>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Thông tin người dùng
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">ID:</th>
                            <td><?php echo $this->user->user_id; ?></td>
                        </tr>
                        <tr>
                            <th>Tên đăng nhập:</th>
                            <td><?php echo $this->user->username; ?></td>
                        </tr>
                        <tr>
                            <th>Họ tên:</th>
                            <td><?php echo $this->user->full_name; ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo $this->user->email; ?></td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td><?php echo $this->user->phone_number; ?></td>
                        </tr>
                        <tr>
                            <th>Vai trò:</th>
                            <td>
                                <span class="badge <?php echo $this->user->role === 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                    <?php echo $this->user->role === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge <?php echo $this->user->status === 'hoạt động' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $this->user->status; ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-shield me-1"></i>
                    Quản lý quyền người dùng
                </div>
                <div class="card-body">
                    <form action="/admin/users/<?php echo $this->user->user_id; ?>/role" method="POST">
                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò người dùng</label>
                            <select class="form-select" id="role" name="role">
                                <option value="user" <?php echo $this->user->role === 'user' ? 'selected' : ''; ?>>Người dùng</option>
                                <option value="admin" <?php echo $this->user->role === 'admin' ? 'selected' : ''; ?>>Quản trị viên</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật quyền</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Thống kê
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="h2"><?php echo isset($orderCount) ? $orderCount : count($userOrders); ?></div>
                            <div class="text-muted">Đơn hàng</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="h2"><?php echo isset($totalSpent) ? number_format($totalSpent, 0, ',', '.') : '0'; ?></div>
                            <div class="text-muted">Tổng chi tiêu (VNĐ)</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="h2"><?php echo isset($lastOrderDate) ? date('d/m/Y', strtotime($lastOrderDate)) : 'N/A'; ?></div>
                            <div class="text-muted">Đơn hàng gần nhất</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-cog me-1"></i>
                    Quản lý trạng thái
                </div>
                <div class="card-body">
                    <form action="/admin/users/<?php echo $this->user->user_id; ?>/status" method="POST">
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái người dùng</label>
                            <select class="form-select" id="status" name="status">
                                <option value="hoạt động" <?php echo $this->user->status === 'hoạt động' ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="vô hiệu hóa" <?php echo $this->user->status === 'vô hiệu hóa' ? 'selected' : ''; ?>>Vô hiệu hóa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Lịch sử đơn hàng
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($userOrders) && !empty($userOrders)): ?>
                            <?php foreach($userOrders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
                                    <td><?php echo $order['payment_method']; ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php 
                                            switch($order['status']) {
                                                case 'pending': echo 'bg-warning'; break;
                                                case 'processing': echo 'bg-info'; break;
                                                case 'completed': echo 'bg-success'; break;
                                                case 'cancelled': echo 'bg-danger'; break;
                                                default: echo 'bg-secondary';
                                            }
                                            ?>">
                                            <?php 
                                            switch($order['status']) {
                                                case 'pending': echo 'Chờ xử lý'; break;
                                                case 'processing': echo 'Đang xử lý'; break;
                                                case 'completed': echo 'Hoàn thành'; break;
                                                case 'cancelled': echo 'Đã hủy'; break;
                                                default: echo $order['status'];
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/orders/<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Người dùng chưa có đơn hàng nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <a href="/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách người dùng
        </a>
    </div>
</div>

<?php
// Lấy nội dung đã buffer và gán vào biến $content
$content = ob_get_clean();

// Include layout
require_once __DIR__ . "/../layouts/admin_layout.php";
?> 