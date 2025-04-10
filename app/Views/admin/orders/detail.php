<?php
$page_title = "Chi tiết đơn hàng #" . $this->order->order_id;

// Bắt đầu output buffering
ob_start();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết đơn hàng #<?php echo $this->order->order_id; ?></h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/orders">Quản lý đơn hàng</a></li>
        <li class="breadcrumb-item active">Chi tiết đơn hàng #<?php echo $this->order->order_id; ?></li>
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
                    <i class="fas fa-info-circle me-1"></i>
                    Thông tin đơn hàng
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Mã đơn hàng:</th>
                            <td><?php echo $this->order->order_id; ?></td>
                        </tr>
                        <tr>
                            <th>Ngày đặt hàng:</th>
                            <td><?php echo date('d/m/Y H:i', strtotime($this->order->order_date)); ?></td>
                        </tr>
                        <tr>
                            <th>Tổng tiền:</th>
                            <td><?php echo number_format($this->order->total_amount, 0, ',', '.'); ?> đ</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge 
                                    <?php 
                                    switch($this->order->status) {
                                        case 'pending': echo 'bg-warning'; break;
                                        case 'processing': echo 'bg-info'; break;
                                        case 'completed': echo 'bg-success'; break;
                                        case 'cancelled': echo 'bg-danger'; break;
                                        default: echo 'bg-secondary';
                                    }
                                    ?>">
                                    <?php 
                                    switch($this->order->status) {
                                        case 'pending': echo 'Chờ xử lý'; break;
                                        case 'processing': echo 'Đang xử lý'; break;
                                        case 'completed': echo 'Hoàn thành'; break;
                                        case 'cancelled': echo 'Đã hủy'; break;
                                        default: echo $this->order->status;
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Phương thức thanh toán:</th>
                            <td><?php echo $this->order->payment_method; ?></td>
                        </tr>
                        <tr>
                            <th>Trạng thái thanh toán:</th>
                            <td>
                                <span class="badge <?php echo $this->order->payment_status == 'completed' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo $this->order->payment_status == 'completed' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php if(!empty($this->order->notes)): ?>
                        <tr>
                            <th>Ghi chú:</th>
                            <td><?php echo $this->order->notes; ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Thông tin khách hàng
                </div>
                <div class="card-body">
                    <?php if($this->order->user_id): ?>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Mã khách hàng:</th>
                                <td><?php echo $this->order->user_id; ?></td>
                            </tr>
                            <tr>
                                <th>Tên người dùng:</th>
                                <td><?php echo $this->order->user_name ?? 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Họ tên:</th>
                                <td><?php echo $this->order->user_full_name ?? 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $this->order->user_email ?? 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td><?php echo $this->order->user_phone ?? 'N/A'; ?></td>
                            </tr>
                        </table>
                        <div class="mt-2">
                            <a href="/admin/users/<?php echo $this->order->user_id; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-user"></i> Xem thông tin khách hàng
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Đơn hàng này được đặt bởi khách không đăng nhập
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Cập nhật trạng thái đơn hàng
                </div>
                <div class="card-body">
                    <form action="/admin/orders/<?php echo $this->order->order_id; ?>/status" method="POST">
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" <?php echo $this->order->status == 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                <option value="processing" <?php echo $this->order->status == 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                                <option value="completed" <?php echo $this->order->status == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                <option value="cancelled" <?php echo $this->order->status == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
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
            Chi tiết sản phẩm
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($orderItems) && count($orderItems) > 0): ?>
                            <?php foreach($orderItems as $item): ?>
                                <tr>
                                    <td style="width: 70px">
                                        <?php if(!empty($item['image_url'])): ?>
                                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" 
                                                 class="img-thumbnail" style="max-width: 60px; max-height: 60px;">
                                        <?php else: ?>
                                            <div class="text-center">
                                                <i class="fas fa-image text-muted" style="font-size: 30px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/admin/products/<?php echo $item['product_id']; ?>/edit">
                                            <?php echo $item['name']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo !empty($item['size_name']) ? $item['size_name'] : 'N/A'; ?>
                                    </td>
                                    <td><?php echo number_format($item['unit_price'], 0, ',', '.'); ?> đ</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($item['unit_price'] * $item['quantity'], 0, ',', '.'); ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu chi tiết đơn hàng</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Tổng tiền:</th>
                            <th><?php echo number_format($this->order->total_amount, 0, ',', '.'); ?> đ</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <a href="/admin/orders" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>
        <a href="/admin/orders" class="btn btn-primary">
            <i class="fas fa-list"></i> Xem tất cả đơn hàng
        </a>
    </div>
</div>

<?php
// Lấy nội dung đã buffer và gán vào biến $content
$content = ob_get_clean();

// Include layout
require_once __DIR__ . "/../layouts/admin_layout.php";
?> 