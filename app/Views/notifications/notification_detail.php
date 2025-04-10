<?php 
// Thiết lập trang hiện tại 
$page = 'notifications';
include_once('app/Views/components/header.php');

// Kiểm tra nếu không có thông báo hoặc ID không hợp lệ
if (!isset($notification) || empty($notification)) {
    header('Location: /notifications');
    exit;
}
?>

<div class="container notification-detail-container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="back-link">
                <a href="/notifications" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách thông báo
                </a>
            </div>
            
            <div class="notification-detail-card">
                <div class="notification-header">
                    <div class="notification-icon-large">
                        <?php if ($notification['type'] === 'promotion'): ?>
                            <i class="fas fa-gift"></i>
                        <?php elseif ($notification['type'] === 'order'): ?>
                            <i class="fas fa-shopping-bag"></i>
                        <?php elseif ($notification['type'] === 'product'): ?>
                            <i class="fas fa-coffee"></i>
                        <?php elseif ($notification['type'] === 'system'): ?>
                            <i class="fas fa-cog"></i>
                        <?php else: ?>
                            <i class="fas fa-bell"></i>
                        <?php endif; ?>
                    </div>
                    <div class="notification-title-container">
                        <h2 class="notification-detail-title"><?= htmlspecialchars($notification['title']) ?></h2>
                        <div class="notification-detail-meta">
                            <span class="notification-time">
                                <i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                            </span>
                            <?php if (!$notification['is_read']): ?>
                                <span class="unread-badge">Chưa đọc</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="notification-detail-body">
                    <?php if (!empty($notification['image_url'])): ?>
                    <div class="notification-image">
                        <img src="<?= htmlspecialchars($notification['image_url']) ?>" alt="Notification image">
                    </div>
                    <?php endif; ?>
                    
                    <div class="notification-detail-message">
                        <p><?= nl2br(htmlspecialchars($notification['message'])) ?></p>
                        
                        <?php if (!empty($notification['additional_content'])): ?>
                        <div class="additional-content">
                            <?= $notification['additional_content'] ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($notification['action_url'])): ?>
                    <div class="notification-actions-container">
                        <a href="<?= htmlspecialchars($notification['action_url']) ?>" class="btn-action">
                            <?= !empty($notification['action_text']) ? htmlspecialchars($notification['action_text']) : 'Xem chi tiết' ?>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="notification-detail-footer">
                    <?php if (!$notification['is_read']): ?>
                    <form action="/notifications/mark-as-read" method="post" class="d-inline">
                        <input type="hidden" name="notification_id" value="<?= $notification['notification_id'] ?>">
                        <input type="hidden" name="redirect" value="detail">
                        <button type="submit" class="btn-mark-read">
                            <i class="fas fa-check"></i> Đánh dấu đã đọc
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <button type="button" class="btn-delete" id="deleteNotificationBtn">
                        <i class="fas fa-trash"></i> Xóa thông báo
                    </button>
                </div>
            </div>
            
            <?php if (isset($related_notifications) && !empty($related_notifications)): ?>
            <div class="related-notifications">
                <h4>Thông báo liên quan</h4>
                <div class="related-list">
                    <?php foreach ($related_notifications as $related): ?>
                    <a href="/notifications/detail/<?= $related['notification_id'] ?>" class="related-item <?= $related['is_read'] ? '' : 'unread' ?>">
                        <div class="related-icon">
                            <?php if ($related['type'] === 'promotion'): ?>
                                <i class="fas fa-gift text-primary"></i>
                            <?php elseif ($related['type'] === 'order'): ?>
                                <i class="fas fa-shopping-bag text-success"></i>
                            <?php elseif ($related['type'] === 'product'): ?>
                                <i class="fas fa-coffee text-warning"></i>
                            <?php elseif ($related['type'] === 'system'): ?>
                                <i class="fas fa-cog text-danger"></i>
                            <?php else: ?>
                                <i class="fas fa-bell text-secondary"></i>
                            <?php endif; ?>
                        </div>
                        <div class="related-content">
                            <h5 class="related-title"><?= htmlspecialchars($related['title']) ?></h5>
                            <span class="related-time"><?= date('d/m/Y', strtotime($related['created_at'])) ?></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="custom-modal" id="deleteConfirmModal">
    <div class="custom-modal-overlay"></div>
    <div class="custom-modal-container">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Xác nhận xóa</h5>
            <button type="button" class="custom-modal-close" id="closeModalBtn">&times;</button>
        </div>
        <div class="custom-modal-body">
            <p>Bạn có chắc chắn muốn xóa thông báo này?</p>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-cancel" id="cancelDeleteBtn">Hủy</button>
            <form action="/notifications/delete" method="post">
                <input type="hidden" name="notification_id" value="<?= $notification['notification_id'] ?>">
                <input type="hidden" name="redirect" value="1">
                <button type="submit" class="btn-confirm">Xóa</button>
            </form>
        </div>
    </div>
</div>

<style>
    .notification-detail-container {
        padding-top: 120px;
        margin-bottom: 50px;
    }
    
    .back-link {
        margin-bottom: 20px;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        color: #6F4E37;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-back i {
        margin-right: 5px;
    }
    
    .btn-back:hover {
        color: #8B4513;
        text-decoration: none;
    }
    
    .notification-detail-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .notification-header {
        display: flex;
        align-items: flex-start;
        padding: 25px;
        border-bottom: 1px solid #eee;
    }
    
    .notification-icon-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(111, 78, 55, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        flex-shrink: 0;
    }
    
    .notification-icon-large i {
        font-size: 24px;
        color: #6F4E37;
    }
    
    .notification-title-container {
        flex: 1;
    }
    
    .notification-detail-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .notification-detail-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        color: #666;
        font-size: 0.9rem;
    }
    
    .unread-badge {
        background-color: #6F4E37;
        color: white;
        padding: 3px 10px;
        border-radius: 30px;
        font-size: 0.8rem;
    }
    
    .notification-detail-body {
        padding: 25px;
    }
    
    .notification-image {
        margin-bottom: 20px;
        text-align: center;
    }
    
    .notification-image img {
        max-width: 100%;
        height: auto;
        max-height: 400px;
        border-radius: 5px;
    }
    
    .notification-detail-message {
        font-size: 1rem;
        line-height: 1.6;
        color: #333;
        margin-bottom: 20px;
    }
    
    .additional-content {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .notification-actions-container {
        margin-top: 25px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        background-color: #6F4E37;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-action i {
        margin-left: 8px;
    }
    
    .btn-action:hover {
        background-color: #8B4513;
        color: white;
        text-decoration: none;
    }
    
    .notification-detail-footer {
        display: flex;
        justify-content: space-between;
        padding: 15px 25px;
        background-color: #f9f9f9;
        border-top: 1px solid #eee;
    }
    
    .btn-mark-read, .btn-delete {
        display: inline-flex;
        align-items: center;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    
    .btn-mark-read {
        background-color: rgba(111, 78, 55, 0.1);
        color: #6F4E37;
    }
    
    .btn-mark-read:hover {
        background-color: rgba(111, 78, 55, 0.2);
    }
    
    .btn-delete {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .btn-delete:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }
    
    .btn-mark-read i, .btn-delete i {
        margin-right: 5px;
    }
    
    .related-notifications {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .related-notifications h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .related-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .related-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s ease;
    }
    
    .related-item:hover {
        background-color: #f5f5f5;
        text-decoration: none;
        color: #333;
    }
    
    .related-item.unread {
        background-color: #f8f9fa;
        border-left: 3px solid #6F4E37;
    }
    
    .related-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    .related-content {
        flex: 1;
    }
    
    .related-title {
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 5px;
    }
    
    .related-time {
        font-size: 0.8rem;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .notification-header {
            flex-direction: column;
        }
        
        .notification-icon-large {
            margin-bottom: 15px;
        }
        
        .notification-detail-footer {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-mark-read, .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Custom modal styles */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
    }
    
    .custom-modal.show {
        display: block;
    }
    
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1051;
    }
    
    .custom-modal-container {
        position: relative;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 90%;
        margin: 10vh auto;
        z-index: 1052;
        animation: modalFadeIn 0.3s ease-out;
        overflow: hidden;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }
    
    .custom-modal-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .custom-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #888;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 0;
        line-height: 1;
    }
    
    .custom-modal-close:hover {
        color: #333;
    }
    
    .custom-modal-body {
        padding: 20px;
        font-size: 1rem;
        color: #555;
    }
    
    .custom-modal-body p {
        margin: 0;
    }
    
    .custom-modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 15px 20px;
        border-top: 1px solid #eee;
        gap: 10px;
    }
    
    .btn-cancel, .btn-confirm {
        padding: 8px 16px;
        border-radius: 5px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-cancel {
        background-color: #f1f1f1;
        color: #333;
    }
    
    .btn-cancel:hover {
        background-color: #e0e0e0;
    }
    
    .btn-confirm {
        background-color: #e53935;
        color: white;
    }
    
    .btn-confirm:hover {
        background-color: #d32f2f;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Nếu thông báo chưa đọc, gửi request đánh dấu đã đọc khi mở trang
        <?php if (!$notification['is_read']): ?>
        fetch('/notifications/mark-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=<?= $notification['notification_id'] ?>&ajax=true'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật badge thông báo trên menu
                updateNotificationBadge();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        <?php endif; ?>
        
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
        
        // Modal xác nhận xóa
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteBtn = document.getElementById('deleteNotificationBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        
        // Mở modal khi nhấn nút xóa
        deleteBtn.addEventListener('click', function() {
            deleteModal.classList.add('show');
        });
        
        // Đóng modal
        function closeModal() {
            deleteModal.classList.remove('show');
        }
        
        // Đóng modal khi nhấn nút đóng hoặc hủy
        closeModalBtn.addEventListener('click', closeModal);
        cancelDeleteBtn.addEventListener('click', closeModal);
        
        // Đóng modal khi nhấn ra ngoài
        window.addEventListener('click', function(event) {
            if (event.target === deleteModal.querySelector('.custom-modal-overlay')) {
                closeModal();
            }
        });
    });
</script>

<?php include_once('app/Views/components/footer.php'); ?> 