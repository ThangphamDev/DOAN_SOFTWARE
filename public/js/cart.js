// Hàm thêm sản phẩm vào giỏ hàng
async function addToCart(productId, variantId = null, quantity = 1) {
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                variant_id: variantId,
                quantity: quantity
            })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Có lỗi xảy ra');
        }

        // Cập nhật UI
        updateCartUI(data);
        showToast('success', data.message);
    } catch (error) {
        showToast('error', error.message);
    }
}

// Hàm cập nhật số lượng sản phẩm
async function updateCartItem(cartItemId, quantity) {
    try {
        const response = await fetch('/api/cart/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cart_item_id: cartItemId,
                quantity: quantity
            })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Có lỗi xảy ra');
        }

        // Cập nhật UI
        updateCartUI(data);
        showToast('success', data.message);
    } catch (error) {
        showToast('error', error.message);
    }
}

// Hàm xóa sản phẩm khỏi giỏ hàng
async function removeFromCart(cartItemId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        return;
    }

    try {
        const response = await fetch('/api/cart/remove', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cart_item_id: cartItemId
            })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Có lỗi xảy ra');
        }

        // Cập nhật UI
        updateCartUI(data);
        showToast('success', data.message);
    } catch (error) {
        showToast('error', error.message);
    }
}

// Hàm lấy thông tin giỏ hàng
async function getCart() {
    try {
        const response = await fetch('/api/cart');
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Có lỗi xảy ra');
        }

        // Cập nhật UI
        updateCartUI(data);
    } catch (error) {
        showToast('error', error.message);
    }
}

// Hàm xóa toàn bộ giỏ hàng
async function clearCart() {
    if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        return;
    }

    try {
        const response = await fetch('/api/cart/clear', {
            method: 'DELETE'
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Có lỗi xảy ra');
        }

        // Cập nhật UI
        updateCartUI(data);
        showToast('success', data.message);
    } catch (error) {
        showToast('error', error.message);
    }
}

// Hàm cập nhật UI giỏ hàng
function updateCartUI(data) {
    // Cập nhật số lượng sản phẩm trên icon giỏ hàng
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.textContent = data.cart_items.length;
    }

    // Cập nhật danh sách sản phẩm trong giỏ hàng
    const cartItemsContainer = document.querySelector('.cart-items');
    if (cartItemsContainer) {
        if (data.cart_items.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="empty-cart">
                    <p>Giỏ hàng của bạn đang trống</p>
                    <a href="/menu" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
                </div>
            `;
        } else {
            cartItemsContainer.innerHTML = data.cart_items.map(item => `
                <div class="cart-item" data-id="${item.cart_item_id}">
                    <div class="item-image">
                        <img src="${item.image_url}" alt="${item.name}" 
                             onerror="this.src='/public/images/default-product.jpg'">
                    </div>
                    <div class="item-info">
                        <h4>${item.name}</h4>
                        ${item.variant_value ? `<p>Size: ${item.variant_value}</p>` : ''}
                        <p class="price">${formatCurrency(item.base_price + (item.additional_price || 0))}</p>
                        <div class="quantity-control">
                            <button onclick="updateCartItem(${item.cart_item_id}, ${item.quantity - 1})" 
                                    ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                            <input type="number" value="${item.quantity}" min="1" 
                                   onchange="updateCartItem(${item.cart_item_id}, this.value)">
                            <button onclick="updateCartItem(${item.cart_item_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <button onclick="removeFromCart(${item.cart_item_id})" class="btn btn-danger">Xóa</button>
                    </div>
                </div>
            `).join('');
        }
    }

    // Cập nhật tổng tiền
    const cartTotal = document.querySelector('.cart-total');
    if (cartTotal) {
        cartTotal.textContent = formatCurrency(data.cart_total);
    }
}

// Hàm format tiền tệ
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Hàm hiển thị thông báo
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Load giỏ hàng khi trang web được tải
document.addEventListener('DOMContentLoaded', getCart); 