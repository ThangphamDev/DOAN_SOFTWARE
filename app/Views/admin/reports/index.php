<?php
$page_title = "Báo cáo doanh thu";
$currentPage = 'reports';

// Bắt đầu output buffering
ob_start();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Báo cáo doanh thu</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
        <li class="breadcrumb-item active">Báo cáo doanh thu</li>
    </ol>
    
    <!-- Lọc báo cáo theo tháng/năm -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Bộ lọc báo cáo
        </div>
        <div class="card-body">
            <form method="GET" action="/admin/reports" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label for="month" class="form-label">Tháng</label>
                    <select class="form-select" id="month" name="month">
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $month == $i ? 'selected' : ''; ?>>
                                Tháng <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">Năm</label>
                    <select class="form-select" id="year" name="year">
                        <?php 
                        $currentYear = date('Y');
                        for($i = $currentYear; $i >= $currentYear - 2; $i--): 
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo $year == $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Lọc báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <!-- Doanh thu theo ngày trong tháng -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Doanh thu theo ngày (Tháng <?php echo $month; ?>/<?php echo $year; ?>)
                </div>
                <div class="card-body">
                    <canvas id="revenueByDayChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Doanh thu theo danh mục trong tháng -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Doanh thu theo danh mục (Tháng <?php echo $month; ?>/<?php echo $year; ?>)
                </div>
                <div class="card-body">
                    <canvas id="revenueByCategoryChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Doanh thu theo sản phẩm trong tháng -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Top sản phẩm bán chạy (Tháng <?php echo $month; ?>/<?php echo $year; ?>)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="productRevenueTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Số lượng bán</th>
                            <th>Doanh thu</th>
                            <th>Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalRevenue = 0;
                        if (!empty($productRevenue)) {
                            foreach($productRevenue as $product) {
                                $totalRevenue += $product['revenue'];
                            }
                        }
                        
                        if (!empty($productRevenue)):
                            foreach($productRevenue as $product): 
                                $percentage = $totalRevenue > 0 ? round(($product['revenue'] / $totalRevenue) * 100, 2) : 0;
                        ?>
                            <tr>
                                <td>
                                    <?php if(!empty($product['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>" width="50" class="me-2">
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($product['product_name'] ?? 'Sản phẩm không xác định'); ?>
                                </td>
                                <td><?php echo isset($product['category_name']) ? htmlspecialchars($product['category_name']) : 'Không có danh mục'; ?></td>
                                <td><?php echo $product['total_quantity']; ?></td>
                                <td><?php echo number_format($product['revenue'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%;" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percentage; ?>%</div>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu doanh thu sản phẩm cho thời gian này</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Doanh thu theo ngày
        const revenueByDayCtx = document.getElementById('revenueByDayChart');
        const revenueByDayData = {
            labels: [
                <?php 
                $dayLabels = [];
                $revenueData = [];
                $orderCountData = [];
                
                if (!empty($monthlyRevenue)) {
                    foreach($monthlyRevenue as $day) {
                        $dayLabels[] = "'{$day['day']}'";
                        $revenueData[] = $day['revenue'];
                        $orderCountData[] = $day['order_count'];
                    }
                }
                
                if (empty($dayLabels)) {
                    $dayLabels[] = "'Không có dữ liệu'";
                    $revenueData[] = 0;
                    $orderCountData[] = 0;
                }
                
                echo implode(', ', $dayLabels);
                ?>
            ],
            datasets: [
                {
                    label: 'Doanh thu (VNĐ)',
                    data: [<?php echo implode(', ', $revenueData); ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Số đơn hàng',
                    data: [<?php echo implode(', ', $orderCountData); ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1'
                }
            ]
        };
        
        new Chart(revenueByDayCtx, {
            type: 'bar',
            data: revenueByDayData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        }
                    },
                    y1: {
                        beginAtZero: true,
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
        
        // Doanh thu theo danh mục
        const revenueByCategoryCtx = document.getElementById('revenueByCategoryChart');
        const revenueByCategoryData = {
            labels: [
                <?php 
                $categoryLabels = [];
                $categoryRevenueData = [];
                $backgroundColors = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(199, 199, 199, 0.2)'
                ];
                $borderColors = [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(199, 199, 199, 1)'
                ];
                
                $colorIndex = 0;
                $backgroundColorValues = [];
                $borderColorValues = [];
                
                if (!empty($categoryRevenue)) {
                    foreach($categoryRevenue as $category) {
                        $categoryLabels[] = "'" . (isset($category['category_name']) ? $category['category_name'] : 'Không có danh mục') . "'";
                        $categoryRevenueData[] = $category['revenue'];
                        
                        $backgroundColorValues[] = $backgroundColors[$colorIndex % count($backgroundColors)];
                        $borderColorValues[] = $borderColors[$colorIndex % count($borderColors)];
                        $colorIndex++;
                    }
                }
                
                if (empty($categoryLabels)) {
                    $categoryLabels[] = "'Không có dữ liệu'";
                    $categoryRevenueData[] = 0;
                    $backgroundColorValues[] = $backgroundColors[0];
                    $borderColorValues[] = $borderColors[0];
                }
                
                echo implode(', ', $categoryLabels);
                ?>
            ],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: [<?php echo implode(', ', $categoryRevenueData); ?>],
                backgroundColor: [<?php echo implode(', ', $backgroundColorValues); ?>],
                borderColor: [<?php echo implode(', ', $borderColorValues); ?>],
                borderWidth: 1
            }]
        };
        
        new Chart(revenueByCategoryCtx, {
            type: 'pie',
            data: revenueByCategoryData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                return label + ': ' + new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });
        
        // Bảng doanh thu sản phẩm
        $('#productRevenueTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            order: [[3, 'desc']] // Sắp xếp theo doanh thu, cao nhất trước
        });
    });
</script>

<?php
// Lấy nội dung đã buffer và gán vào biến $content
$content = ob_get_clean();

require_once __DIR__ . '/../layouts/admin_layout.php';
?> 