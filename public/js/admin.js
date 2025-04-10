// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Auto close alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Image preview for file inputs
    const imageFileInputs = document.querySelectorAll('.image-file-input');
    imageFileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);

            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Image URL preview for input fields
    const imageUrlInputs = document.querySelectorAll('.image-url-input');
    imageUrlInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);

            if (preview && this.value) {
                preview.src = this.value;
                preview.style.display = 'block';
            }
        });
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
                e.preventDefault();
            }
        });
    });

    // Product variants management
    setupVariantsManagement();

    // Product image gallery
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    thumbnails.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            const mainImage = document.getElementById('mainProductImage');
            if (mainImage) {
                mainImage.src = this.dataset.image;
                // Update active thumbnail
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Order status update
    const orderStatusSelect = document.getElementById('orderStatus');
    if (orderStatusSelect) {
        orderStatusSelect.addEventListener('change', function() {
            const statusForm = document.getElementById('orderStatusForm');
            if (statusForm && confirm('Cập nhật trạng thái đơn hàng?')) {
                statusForm.submit();
            }
        });
    }

    // Date range picker for reports
    const dateRangePicker = document.getElementById('reportDateRange');
    if (dateRangePicker) {
        // This would typically integrate with a date range picker library
        dateRangePicker.addEventListener('change', function() {
            const reportForm = document.getElementById('reportFilterForm');
            if (reportForm) {
                reportForm.submit();
            }
        });
    }
});

// Setup product variants management
function setupVariantsManagement() {
    const variantContainer = document.getElementById('variantsContainer');
    const addVariantBtn = document.getElementById('addVariantBtn');
    
    if (variantContainer && addVariantBtn) {
        addVariantBtn.addEventListener('click', function() {
            const variantCount = variantContainer.querySelectorAll('.variant-row').length;
            const newVariantHtml = `
                <div class="row variant-row mb-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Loại biến thể</label>
                        <input type="text" class="form-control" name="variants[${variantCount}][type]" placeholder="VD: Size" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Giá trị</label>
                        <input type="text" class="form-control" name="variants[${variantCount}][value]" placeholder="VD: Nhỏ" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Giá thêm</label>
                        <input type="number" class="form-control" name="variants[${variantCount}][price]" value="0" min="0" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-variant-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Add the new variant row
            variantContainer.insertAdjacentHTML('beforeend', newVariantHtml);
            
            // Add event listener to the remove button
            const removeButtons = variantContainer.querySelectorAll('.remove-variant-btn');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.variant-row').remove();
                });
            });
        });
        
        // Add event listeners to existing remove buttons
        const removeButtons = variantContainer.querySelectorAll('.remove-variant-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.variant-row').remove();
            });
        });
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', { year: 'numeric', month: '2-digit', day: '2-digit' });
}

// Debounce function for search inputs
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
} 