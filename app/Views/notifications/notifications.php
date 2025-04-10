<?php 
// Thiết lập trang hiện tại 
$page = 'notifications';
include_once('app/Views/components/header.php');
?>

<div class="container notifications-container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-title">Thông báo của bạn</h2>
            
            <?php if(empty($notifications)): ?>
                <div class="empty-notifications">
                    <i class="far fa-bell-slash"></i>
                    <p>Bạn chưa có thông báo nào</p>
                </div>
            <?php else: ?>
                <div class="notification-header">
                    <div class="notification-count">
                        <span><?= count(array_filter($notifications, function($n) { return !$n['is_read']; })) ?></span> thông báo chưa đọc
                    </div>
                    <div class="notification-actions">
                        <button class="btn btn-sm btn-outline-primary mark-all-read-btn">
                            <i class="fas fa-check-double"></i> Đánh dấu tất cả đã đọc
                        </button>
                    </div>
                </div>
                
                <div class="notification-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?= $notification['is_read'] ? 'read' : 'unread' ?>" data-id="<?= $notification['notification_id'] ?>">
                            <div class="notification-icon">
                                <?php if ($notification['type'] === 'promotion'): ?>
                                    <i class="fas fa-gift text-primary"></i>
                                <?php elseif ($notification['type'] === 'order'): ?>
                                    <i class="fas fa-shopping-bag text-success"></i>
                                <?php elseif ($notification['type'] === 'product'): ?>
                                    <i class="fas fa-coffee text-warning"></i>
                                <?php elseif ($notification['type'] === 'system'): ?>
                                    <i class="fas fa-cog text-danger"></i>
                                <?php else: ?>
                                    <i class="fas fa-bell text-secondary"></i>
                                <?php endif; ?>
                            </div>
                            <div class="notification-content">
                                <h3 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h3>
                                <p class="notification-text"><?= htmlspecialchars($notification['message']) ?></p>
                                <div class="notification-meta">
                                    <span class="notification-time">
                                        <i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="notification-actions">
                                <?php if (!$notification['is_read']): ?>
                                    <button class="mark-read-btn" data-id="<?= $notification['notification_id'] ?>">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="delete-notification-btn" data-id="<?= $notification['notification_id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .notifications-container {
        padding-top: 120px;
        margin-bottom: 50px;
    }
    
    .page-title {
        font-size: 1.8rem;
        color: var(--primary);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary);
    }
    
    .empty-notifications {
        text-align: center;
        padding: 50px 0;
    }
    
    .empty-notifications i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }
    
    .empty-notifications p {
        font-size: 1.2rem;
        color: #888;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .notification-count {
        font-size: 1rem;
    }
    
    .notification-count span {
        font-weight: bold;
        color: var(--primary);
    }
    
    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .notification-item {
        display: flex;
        background-color: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
    }
    
    .notification-item.unread {
        background-color: #f8f9fa;
        border-left: 3px solid var(--primary);
    }
    
    .notification-item:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .notification-icon i {
        font-size: 16px;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--dark);
    }
    
    .notification-text {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }
    
    .notification-meta {
        font-size: 0.8rem;
        color: #888;
    }
    
    .notification-time i {
        margin-right: 5px;
    }
    
    .notification-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .mark-read-btn, .delete-notification-btn {
        background: none;
        border: none;
        cursor: pointer;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .mark-read-btn {
        color: var(--primary);
        background-color: rgba(111, 78, 55, 0.1);
    }
    
    .delete-notification-btn {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .mark-read-btn:hover {
        background-color: rgba(111, 78, 55, 0.2);
    }
    
    .delete-notification-btn:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }
    
    .mark-all-read-btn {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .notification-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .notification-item {
            flex-direction: column;
        }
        
        .notification-icon {
            margin-bottom: 10px;
        }
        
        .notification-actions {
            position: absolute;
            top: 15px;
            right: 15px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Đánh dấu một thông báo là đã đọc
    const markReadButtons = document.querySelectorAll('.mark-read-btn');
    markReadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            const notificationItem = this.closest('.notification-item');
            
            // Gửi request đến server
            fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `notification_id=${notificationId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật UI
                    notificationItem.classList.remove('unread');
                    notificationItem.classList.add('read');
                    this.remove();
                    
                    // Cập nhật số lượng thông báo chưa đọc
                    updateUnreadCount();
                    
                    // Cập nhật badge thông báo trên menu
                    updateNotificationBadge();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    
    // Đánh dấu tất cả thông báo là đã đọc
    const markAllReadButton = document.querySelector('.mark-all-read-btn');
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function() {
            // Gửi request đến server
            fetch('/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật UI
                    const unreadNotifications = document.querySelectorAll('.notification-item.unread');
                    unreadNotifications.forEach(item => {
                        item.classList.remove('unread');
                        item.classList.add('read');
                        const markReadBtn = item.querySelector('.mark-read-btn');
                        if (markReadBtn) markReadBtn.remove();
                    });
                    
                    // Cập nhật số lượng thông báo chưa đọc
                    document.querySelector('.notification-count span').textContent = '0';
                    
                    // Cập nhật badge thông báo trên menu
                    updateNotificationBadge();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
    
    // Xóa một thông báo
    const deleteButtons = document.querySelectorAll('.delete-notification-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            const notificationItem = this.closest('.notification-item');
            
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                // Gửi request đến server
                fetch('/notifications/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `notification_id=${notificationId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Xóa thông báo khỏi UI
                        notificationItem.remove();
                        
                        // Kiểm tra nếu không còn thông báo nào
                        const notificationList = document.querySelector('.notification-list');
                        if (notificationList && notificationList.children.length === 0) {
                            // Tạo phần tử "không có thông báo"
                            const emptyNotifications = document.createElement('div');
                            emptyNotifications.className = 'empty-notifications';
                            emptyNotifications.innerHTML = `
                                <i class="far fa-bell-slash"></i>
                                <p>Bạn chưa có thông báo nào</p>
                            `;
                            notificationList.replaceWith(emptyNotifications);
                        }
                        
                        // Cập nhật số lượng thông báo chưa đọc
                        updateUnreadCount();
                        
                        // Cập nhật badge thông báo trên menu
                        updateNotificationBadge();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });
    
    // Cập nhật số lượng thông báo chưa đọc
    function updateUnreadCount() {
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        const countElement = document.querySelector('.notification-count span');
        if (countElement) {
            countElement.textContent = unreadNotifications.length;
        }
    }
    
    // Cập nhật badge thông báo trên menu
    function updateNotificationBadge() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-icon .badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});
</script>

<?php include_once('app/Views/components/footer.php'); ?> 