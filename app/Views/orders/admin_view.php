<?php
// File: app/Views/orders/admin_view.php
// View hiển thị chi tiết đơn hàng cho admin

// Kiểm tra xem biến $order đã được truyền từ controller
if (!isset($order)) {
    header("Location: /admin/orders");
    exit();
}

// Header và CSS
include_once __DIR__ . '/../../Views/components/header.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #<?php echo $order['order_id']; ?></h1>
        <a href="/admin/orders" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Mã đơn hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">#<?php echo $order['order_id']; ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Ngày đặt hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Tổng tiền</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Phương thức thanh toán</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $order['payment_method']; ?></div>
                            </div>
                            <?php if (!empty($order['notes'])): ?>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Ghi chú</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($order['notes']); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <?php if (isset($customer)): ?>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Họ tên</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($customer['full_name']); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Email</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($customer['email']); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Số điện thoại</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($customer['phone_number'] ?? 'Chưa có'); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Địa chỉ</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($customer['address'] ?? 'Chưa có'); ?></div>
                            </div>
                            <?php else: ?>
                            <div class="text-center">
                                <p class="text-gray-500">Khách hàng không đăng nhập</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trạng thái đơn hàng -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="mb-4">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Trạng thái hiện tại</div>
                                <div class="h5 mb-0 font-weight-bold">
                                    <?php 
                                    $status_class = '';
                                    $status_text = '';
                                    
                                    switch($order['status']) {
                                        case 'pending':
                                        case 'đang chờ':
                                            $status_class = 'text-warning';
                                            $status_text = 'Đang chờ';
                                            break;
                                        case 'processing':
                                        case 'đang xử lý':
                                            $status_class = 'text-info';
                                            $status_text = 'Đang xử lý';
                                            break;
                                        case 'completed':
                                        case 'hoàn thành':
                                            $status_class = 'text-success';
                                            $status_text = 'Hoàn thành';
                                            break;
                                        case 'cancelled':
                                        case 'đã hủy':
                                            $status_class = 'text-danger';
                                            $status_text = 'Đã hủy';
                                            break;
                                        default:
                                            $status_class = 'text-gray-800';
                                            $status_text = $order['status'];
                                    }
                                    ?>
                                    <span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Trạng thái thanh toán</div>
                                <div class="h5 mb-0 font-weight-bold">
                                    <?php 
                                    $payment_status_class = '';
                                    $payment_status_text = '';
                                    
                                    switch($order['payment_status']) {
                                        case 'pending':
                                        case 'đang chờ':
                                            $payment_status_class = 'text-warning';
                                            $payment_status_text = 'Chờ thanh toán';
                                            break;
                                        case 'paid':
                                            $payment_status_class = 'text-success';
                                            $payment_status_text = 'Đã thanh toán';
                                            break;
                                        case 'failed':
                                            $payment_status_class = 'text-danger';
                                            $payment_status_text = 'Thanh toán thất bại';
                                            break;
                                        default:
                                            $payment_status_class = 'text-gray-800';
                                            $payment_status_text = $order['payment_status'];
                                    }
                                    ?>
                                    <span class="<?php echo $payment_status_class; ?>"><?php echo $payment_status_text; ?></span>
                                </div>
                            </div>

                            <!-- Form cập nhật trạng thái -->
                            <form action="/admin/orders/<?php echo $order['order_id']; ?>/status" method="post" class="mt-4">
                                <div class="form-group">
                                    <label for="status" class="text-xs font-weight-bold text-uppercase mb-1">Cập nhật trạng thái</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="pending" <?php echo ($order['status'] == 'pending' || $order['status'] == 'đang chờ') ? 'selected' : ''; ?>>Đang chờ</option>
                                        <option value="processing" <?php echo ($order['status'] == 'processing' || $order['status'] == 'đang xử lý') ? 'selected' : ''; ?>>Đang xử lý</option>
                                        <option value="completed" <?php echo ($order['status'] == 'completed' || $order['status'] == 'hoàn thành') ? 'selected' : ''; ?>>Hoàn thành</option>
                                        <option value="cancelled" <?php echo ($order['status'] == 'cancelled' || $order['status'] == 'đã hủy') ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết sản phẩm -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Chi tiết sản phẩm</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th width="120">Đơn giá</th>
                            <th width="100">Số lượng</th>
                            <th width="150">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($order['items']) && !empty($order['items'])): ?>
                            <?php foreach($order['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="product-info">
                                                <strong><?php echo htmlspecialchars($item['name'] ?? 'Không có tên'); ?></strong>
                                                <?php if(!empty($item['size_name'])): ?>
                                                <div class="small text-muted"><?php echo htmlspecialchars($item['size_name']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right"><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>đ</td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-right font-weight-bold"><?php echo number_format($item['unit_price'] * $item['quantity'], 0, ',', '.'); ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có thông tin chi tiết sản phẩm</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Tổng cộng:</td>
                            <td class="text-right font-weight-bold text-primary"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- In đơn hàng và các nút thao tác -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="javascript:window.print();" class="btn btn-secondary">
                <i class="fas fa-print"></i> In đơn hàng
            </a>
            <a href="/admin/orders" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>

<style>
/* CSS cho trang admin view */
@media print {
    .sidebar, .topbar, .card-header, .d-sm-flex, .btn, footer, .scroll-to-top {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .container-fluid {
        padding: 0 !important;
    }
}

/* Layout */
.container-fluid {
    padding: 1.5rem;
}

/* Cards */
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    margin-bottom: 1.5rem;
}

.shadow {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
}

.card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
}

.border-left-primary {
    border-left: .25rem solid #4e73df !important;
}

.border-left-success {
    border-left: .25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: .25rem solid #f6c23e !important;
}

.h-100 {
    height: 100% !important;
}

.py-2 {
    padding-top: 0.5rem !important;
    padding-bottom: 0.5rem !important;
}

.py-3 {
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
}

/* Flex */
.d-flex {
    display: flex !important;
}

.d-sm-flex {
    display: flex !important;
}

.align-items-center {
    align-items: center !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

.flex-column {
    flex-direction: column !important;
}

/* Spacing */
.mb-0 {
    margin-bottom: 0 !important;
}

.mb-1 {
    margin-bottom: 0.25rem !important;
}

.mb-3 {
    margin-bottom: 1rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mr-2 {
    margin-right: 0.5rem !important;
}

.mt-4 {
    margin-top: 1.5rem !important;
}

/* Grid */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -0.75rem;
    margin-left: -0.75rem;
}

.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding-right: 0.75rem;
    padding-left: 0.75rem;
}

.col-xl-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding-right: 0.75rem;
    padding-left: 0.75rem;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding-right: 0.75rem;
    padding-left: 0.75rem;
}

.col-md-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding-right: 0.75rem;
    padding-left: 0.75rem;
}

/* Typography */
.h1, .h2, .h3, .h4, .h5, .h6 {
    margin-bottom: 0.5rem;
    font-weight: 500;
    line-height: 1.2;
}

.h3 {
    font-size: 1.75rem;
}

.h5 {
    font-size: 1.25rem;
}

.h6 {
    font-size: 1rem;
}

.text-xs {
    font-size: 0.7rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-uppercase {
    text-transform: uppercase !important;
}

.text-primary {
    color: #4e73df !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-danger {
    color: #e74a3b !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-500 {
    color: #b7b9cc !important;
}

.text-center {
    text-align: center !important;
}

.text-right {
    text-align: right !important;
}

/* Table */
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #e3e6f0;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #e3e6f0;
}

.table-bordered {
    border: 1px solid #e3e6f0;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #e3e6f0;
}

.small {
    font-size: 80%;
    font-weight: 400;
}

.text-muted {
    color: #6c757d !important;
}

/* Forms */
.form-group {
    margin-bottom: 1rem;
}

.form-control {
    display: block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6e707e;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

/* Buttons */
.btn {
    display: inline-block;
    font-weight: 400;
    color: #858796;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    text-decoration: none;
    cursor: pointer;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-primary {
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-secondary {
    color: #fff;
    background-color: #858796;
    border-color: #858796;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}

/* Alerts */
.alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-success {
    color: #0f5132;
    background-color: #d1e7dd;
    border-color: #badbcc;
}

.alert-danger {
    color: #842029;
    background-color: #f8d7da;
    border-color: #f5c2c7;
}

.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.no-gutters {
    margin-right: 0;
    margin-left: 0;
}

.no-gutters > .col,
.no-gutters > [class*="col-"] {
    padding-right: 0;
    padding-left: 0;
}

/* Responsive */
@media (max-width: 767.98px) {
    .col-md-6, .col-xl-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .d-none {
        display: none !important;
    }
}

@media (min-width: 768px) {
    .d-md-block {
        display: block !important;
    }
}

@media (min-width: 1200px) {
    .d-xl-block {
        display: block !important;
    }
}
</style>

<?php
// Footer
include_once __DIR__ . '/../../Views/components/footer.php';
?> 