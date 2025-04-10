<?php
$page_title = "Quản lý đơn hàng";
$currentPage = 'orders';

// Bắt đầu output buffering
ob_start();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý đơn hàng</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
        <li class="breadcrumb-item active">Quản lý đơn hàng</li>
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
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-bag me-1"></i>
            Danh sách đơn hàng
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ordersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($orders->rowCount() > 0): ?>
                            <?php while($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td>
                                        <?php if($order['user_id']): ?>
                                            <a href="/admin/users/<?php echo $order['user_id']; ?>">
                                                <?php echo (isset($order['full_name']) && !empty($order['full_name'])) ? $order['full_name'] : ($order['username'] ?? 'Khách hàng'); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo $order['customer_name'] ?? 'Khách vãng lai'; ?> (Khách)
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <?php
                                        $paymentLabel = 'Không xác định';
                                        $paymentClass = 'bg-secondary';
                                        
                                        switch($order['payment_method']) {
                                            case 'cod':
                                                $paymentLabel = 'Tiền mặt';
                                                $paymentClass = 'bg-warning text-dark';
                                                break;
                                            case 'banking':
                                                $paymentLabel = 'Chuyển khoản';
                                                $paymentClass = 'bg-info';
                                                break;
                                            case 'momo':
                                                $paymentLabel = 'Ví MoMo';
                                                $paymentClass = 'bg-danger';
                                                break;
                                            case 'zalopay':
                                                $paymentLabel = 'ZaloPay';
                                                $paymentClass = 'bg-primary';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $paymentClass; ?>">
                                            <?php echo $paymentLabel; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'bg-secondary';
                                        
                                        switch($order['status']) {
                                            case 'pending':
                                                $statusClass = 'bg-warning text-dark';
                                                break;
                                            case 'processing':
                                                $statusClass = 'bg-info';
                                                break;
                                            case 'shipped':
                                                $statusClass = 'bg-primary';
                                                break;
                                            case 'delivered':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <?php 
                                            $statusLabels = [
                                                'pending' => 'Chờ xác nhận',
                                                'processing' => 'Đang xử lý',
                                                'shipped' => 'Đang giao hàng',
                                                'delivered' => 'Đã giao hàng',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            echo $statusLabels[$order['status']] ?? $order['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/orders/<?php echo $order['order_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu đơn hàng</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#ordersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            order: [[0, 'desc']]
        });
    });
</script>

<?php
// Lấy nội dung đã buffer và gán vào biến $content
$content = ob_get_clean();

// Include layout
require_once __DIR__ . "/../layouts/admin_layout.php";
?> 