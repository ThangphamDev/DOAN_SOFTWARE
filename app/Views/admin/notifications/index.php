<?php
$pageTitle = "Quản lý thông báo";
$currentPage = 'notifications';
$content = ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản lý thông báo</h1>
    
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
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách thông báo</h6>
            <a href="/admin/notifications/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tạo thông báo mới
            </a>
        </div>
        <div class="card-body">
            <?php if(empty($notifications)): ?>
                <div class="text-center py-5">
                    <p class="text-muted mb-0">Chưa có thông báo nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="notificationsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Người nhận</th>
                                <th>Loại thông báo</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($notifications as $notification): ?>
                                <tr>
                                    <td><?php echo $notification['notification_id']; ?></td>
                                    <td>
                                        <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                                        <div class="notification-preview text-muted small">
                                            <?php echo mb_substr(htmlspecialchars($notification['message']), 0, 70) . (mb_strlen($notification['message']) > 70 ? '...' : ''); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-info">
                                            <?php echo htmlspecialchars($notification['full_name'] ?? $notification['email']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $badge_class = '';
                                        switch($notification['type']) {
                                            case 'promotion':
                                                $badge_class = 'badge-primary';
                                                $type_text = 'Khuyến mãi';
                                                break;
                                            case 'order':
                                                $badge_class = 'badge-success';
                                                $type_text = 'Đơn hàng';
                                                break;
                                            case 'product':
                                                $badge_class = 'badge-warning';
                                                $type_text = 'Sản phẩm';
                                                break;
                                            case 'system':
                                                $badge_class = 'badge-danger';
                                                $type_text = 'Hệ thống';
                                                break;
                                            default:
                                                $badge_class = 'badge-secondary';
                                                $type_text = 'Chung';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $type_text; ?></span>
                                    </td>
                                    <td>
                                        <?php if($notification['is_read']): ?>
                                            <span class="badge badge-light">Đã đọc</span>
                                        <?php else: ?>
                                            <span class="badge badge-info">Chưa đọc</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info view-notification-btn" 
                                                data-bs-toggle="modal" data-bs-target="#viewNotificationModal"
                                                data-id="<?php echo $notification['notification_id']; ?>"
                                                data-title="<?php echo htmlspecialchars($notification['title']); ?>"
                                                data-message="<?php echo htmlspecialchars($notification['message']); ?>"
                                                data-type="<?php echo $notification['type']; ?>"
                                                data-related="<?php echo $notification['related_id']; ?>"
                                                data-created="<?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?>"
                                                data-user="<?php echo htmlspecialchars($notification['full_name'] ?? $notification['email']); ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-notification-btn"
                                                data-bs-toggle="modal" data-bs-target="#deleteNotificationModal"
                                                data-id="<?php echo $notification['notification_id']; ?>"
                                                data-title="<?php echo htmlspecialchars($notification['title']); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal xem thông báo -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" role="dialog" aria-labelledby="viewNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNotificationModalLabel">Chi tiết thông báo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="notification-detail">
                    <h5 id="modal-notification-title"></h5>
                    <div class="notification-meta">
                        <div><strong>Người nhận:</strong> <span id="modal-notification-user"></span></div>
                        <div><strong>Loại thông báo:</strong> <span id="modal-notification-type"></span></div>
                        <div><strong>Ngày tạo:</strong> <span id="modal-notification-created"></span></div>
                        <div id="modal-notification-related-container" style="display: none;">
                            <strong>ID liên quan:</strong> <span id="modal-notification-related"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="notification-content">
                        <p id="modal-notification-message"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa thông báo -->
<div class="modal fade" id="deleteNotificationModal" tabindex="-1" role="dialog" aria-labelledby="deleteNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteNotificationModalLabel">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa thông báo "<span id="delete-notification-title"></span>"?</p>
                <p class="text-danger">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form id="delete-notification-form" action="/admin/notifications/delete" method="post">
                    <input type="hidden" name="notification_id" id="delete-notification-id">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo DataTable
    if (document.getElementById('notificationsTable')) {
        $('#notificationsTable').DataTable({
            "order": [[5, "desc"]], // Sắp xếp theo ngày tạo mới nhất
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
            }
        });
    }
    
    // Xử lý modal xem thông báo
    $('.view-notification-btn').on('click', function() {
        var title = $(this).data('title');
        var message = $(this).data('message');
        var type = $(this).data('type');
        var related = $(this).data('related');
        var created = $(this).data('created');
        var user = $(this).data('user');
        
        // Hiển thị thông tin trong modal
        $('#modal-notification-title').text(title);
        $('#modal-notification-message').text(message);
        $('#modal-notification-created').text(created);
        $('#modal-notification-user').text(user);
        
        // Xử lý loại thông báo
        var typeText = 'Chung';
        switch(type) {
            case 'promotion':
                typeText = 'Khuyến mãi';
                break;
            case 'order':
                typeText = 'Đơn hàng';
                break;
            case 'product':
                typeText = 'Sản phẩm';
                break;
            case 'system':
                typeText = 'Hệ thống';
                break;
        }
        $('#modal-notification-type').text(typeText);
        
        // Hiển thị ID liên quan nếu có
        if (related) {
            $('#modal-notification-related').text(related);
            $('#modal-notification-related-container').show();
        } else {
            $('#modal-notification-related-container').hide();
        }
    });
    
    // Xử lý modal xóa thông báo
    $('.delete-notification-btn').on('click', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        
        $('#delete-notification-id').val(id);
        $('#delete-notification-title').text(title);
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../layouts/admin_layout.php";
?> 