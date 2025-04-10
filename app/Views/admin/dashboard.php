<?php
$pageTitle = "Dashboard";
$currentPage = "dashboard";

// Chuẩn bị dữ liệu cho biểu đồ doanh thu theo tháng
$chartMonths = [];
$chartRevenue = [];
$chartOrders = [];

foreach ($revenueByMonth as $data) {
    $monthYear = explode('-', $data['month']);
    $month = intval($monthYear[1]);
    $year = $monthYear[0];
    
    // Định dạng tháng/năm
    $formattedMonth = date('m/Y', strtotime($data['month'] . '-01'));
    
    $chartMonths[] = $formattedMonth;
    $chartRevenue[] = $data['revenue'];
    $chartOrders[] = $data['order_count'];
}

// Dữ liệu JavaScript cho biểu đồ
$chartDataJS = json_encode($chartMonths);
$chartRevenueJS = json_encode($chartRevenue);
$chartOrdersJS = json_encode($chartOrders);

// Custom script cho biểu đồ
$customScript = "
    // Biểu đồ doanh thu theo tháng
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyRevenueChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: " . $chartDataJS . ",
            datasets: [
                {
                    label: 'Doanh thu (VNĐ)',
                    data: " . $chartRevenueJS . ",
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Số đơn hàng',
                    data: " . $chartOrdersJS . ",
                    type: 'line',
                    fill: false,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Doanh thu (VNĐ)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Số đơn hàng'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
    
    // Biểu đồ sản phẩm bán chạy
    const productsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsData = {
        labels: [" . implode(',', array_map(function($product) { 
            return "'" . addslashes($product['name']) . "'"; 
        }, iterator_to_array($topProducts))) . "],
        datasets: [{
            label: 'Số lượng bán ra',
            data: [" . implode(',', array_map(function($product) { 
                return isset($product['sold_count']) ? $product['sold_count'] : 0; 
            }, iterator_to_array($topProducts))) . "],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };
    
    const topProductsChart = new Chart(productsCtx, {
        type: 'pie',
        data: topProductsData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Top 5 sản phẩm bán chạy'
                }
            }
        }
    });
";

// Bắt đầu đệm đầu ra
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-group">
        <a href="/admin/reports" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-chart-line"></i> Xem báo cáo chi tiết
        </a>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
    <div class="col">
        <div class="card h-100 border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted">Tổng doanh thu</h5>
                        <h2 class="mt-3 mb-0"><?php echo number_format($totalRevenue, 0, ',', '.'); ?> đ</h2>
                    </div>
                    <div class="icon-shape bg-primary text-white rounded-circle shadow">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card h-100 border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted">Tổng đơn hàng</h5>
                        <h2 class="mt-3 mb-0"><?php echo $totalOrders; ?></h2>
                    </div>
                    <div class="icon-shape bg-success text-white rounded-circle shadow">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card h-100 border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted">Tổng sản phẩm</h5>
                        <h2 class="mt-3 mb-0"><?php echo $totalProducts; ?></h2>
                    </div>
                    <div class="icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-coffee"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card h-100 border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted">Tổng khách hàng</h5>
                        <h2 class="mt-3 mb-0"><?php echo $totalUsers; ?></h2>
                    </div>
                    <div class="icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Biểu đồ doanh thu theo tháng -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Doanh thu theo tháng</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ top sản phẩm bán chạy -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Sản phẩm bán chạy</h5>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Các đơn hàng gần đây -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Đơn hàng gần đây</h5>
        <a href="/admin/orders" class="btn btn-sm btn-primary">Xem tất cả</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Ngày đặt</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Thanh toán</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $statusBadges = [
                        'pending' => '<span class="badge bg-warning">Đang chờ</span>',
                        'processing' => '<span class="badge bg-info">Đang xử lý</span>',
                        'completed' => '<span class="badge bg-success">Hoàn thành</span>',
                        'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
                    ];
                    
                    $paymentBadges = [
                        'pending' => '<span class="badge bg-warning">Chưa thanh toán</span>',
                        'paid' => '<span class="badge bg-success">Đã thanh toán</span>',
                        'refunded' => '<span class="badge bg-danger">Đã hoàn tiền</span>'
                    ];
                    
                    while($order = $recentOrders->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <th scope="row">#<?php echo $order['order_id']; ?></th>
                        <td><?php echo $order['username'] ? htmlspecialchars($order['username']) : 'Khách vãng lai'; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                        <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
                        <td><?php echo isset($statusBadges[$order['status']]) ? $statusBadges[$order['status']] : $order['status']; ?></td>
                        <td><?php echo isset($paymentBadges[$order['payment_status']]) ? $paymentBadges[$order['payment_status']] : $order['payment_status']; ?></td>
                        <td>
                            <a href="/admin/orders/<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if($recentOrders->rowCount() == 0): ?>
                    <tr>
                        <td colspan="7" class="text-center py-3">Không có đơn hàng nào</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Lấy nội dung đã đệm
$content = ob_get_clean();

// Hiển thị layout
require_once __DIR__ . '/../admin/layouts/admin_layout.php';
?> 