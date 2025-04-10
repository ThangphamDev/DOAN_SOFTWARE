<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-top">
            <div class="footer-widget footer-about">
                <h4>Về T&2K Coffee</h4>
                <p>T&2K Coffee là quán cà phê chất lượng cao với không gian thoáng mát, phục vụ các loại đồ uống và bánh ngọt ngon nhất, mang đến trải nghiệm thư giãn tuyệt vời cho khách hàng.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-widget footer-links">
                <h4>Liên kết nhanh</h4>
                <ul>
                    <li><a href="/">Trang chủ</a></li>
                    <li><a href="/menu">Menu</a></li>
                    <li><a href="/products">Sản phẩm</a></li>
                    <li><a href="/about">Giới thiệu</a></li>
                    <li><a href="/contact">Liên hệ</a></li>
                </ul>
            </div>
            <div class="footer-widget footer-contact">
                <h4>Thông tin liên hệ</h4>
                <ul>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <p>123 Đường ABC, Quận 1, TP.HCM</p>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <p>0123.456.789</p>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <p>info@t2kcoffee.com</p>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <p>7:00 - 22:00 hàng ngày</p>
                    </li>
                </ul>
            </div>
            <div class="footer-widget footer-newsletter">
                <h4>Đăng ký nhận tin</h4>
                <p>Đăng ký để nhận thông tin khuyến mãi mới nhất từ T&2K Coffee</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Email của bạn" required>
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 T&2K Coffee. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<a href="#" class="back-to-top" id="backToTop">
    <i class="fas fa-chevron-up"></i>
</a>

<script>
    // Newsletter form submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Simple validation
            if (!email || !email.includes('@')) {
                alert('Vui lòng nhập email hợp lệ');
                return;
            }
            
            // Submit newsletter subscription
            fetch('/newsletter/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cảm ơn bạn đã đăng ký nhận tin!');
                    this.reset();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi đăng ký');
            });
        });
    }

    // Back to top functionality
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('active');
            } else {
                backToTopBtn.classList.remove('active');
            }
        });
        
        backToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
</script>

<style>
    /* Footer Styles */
    footer {
        background-color: var(--dark);
        color: var(--white);
        padding: 80px 0 20px;
        position: relative;
        overflow: hidden;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        margin-top: 50px;
    }
    
    footer::before {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(rgba(212, 175, 55, 0.3), transparent);
        top: 50px;
        right: -50px;
        z-index: 0;
    }
    
    footer::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: radial-gradient(rgba(212, 175, 55, 0.2), transparent);
        bottom: 50px;
        left: -50px;
        z-index: 0;
    }
    
    .footer-top {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 50px;
        margin-bottom: 50px;
    }
    
    .footer-widget h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }
    
    .footer-widget h4:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 2px;
        background-color: var(--accent);
    }
    
    .footer-about p {
        margin-bottom: 20px;
        color: #ccc;
    }
    
    .social-links {
        display: flex;
        gap: 15px;
    }
    
    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: var(--white);
        transition: var(--transition);
    }
    
    .social-link:hover {
        background-color: var(--accent);
        color: var(--dark);
        transform: translateY(-5px);
    }
    
    .footer-links li {
        margin-bottom: 12px;
    }
    
    .footer-links a {
        color: #ccc;
        transition: var(--transition);
    }
    
    .footer-links a:hover {
        color: var(--accent);
        padding-left: 5px;
    }
    
    .footer-contact li {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
        color: #ccc;
    }
    
    .footer-contact i {
        color: var(--accent);
        margin-top: 5px;
    }
    
    .footer-newsletter p {
        margin-bottom: 20px;
        color: #ccc;
    }
    
    .newsletter-form {
        display: flex;
        overflow: hidden;
        border-radius: 50px;
    }
    
    .newsletter-form input {
        flex: 1;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .newsletter-form button {
        background-color: var(--accent);
        color: var(--dark);
        border: none;
        padding: 0 20px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .newsletter-form button:hover {
        background-color: var(--primary);
        color: var(--white);
    }
    
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        text-align: center;
        color: #aaa;
        font-size: 14px;
    }
    
    /* Back to Top */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: var(--primary);
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 99;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .back-to-top.active {
        opacity: 1;
        visibility: visible;
    }
    
    .back-to-top:hover {
        background-color: var(--accent);
        color: var(--dark);
        transform: translateY(-5px);
    }
    
    @media (max-width: 992px) {
        .footer-top {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .footer-top {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }
</style> 