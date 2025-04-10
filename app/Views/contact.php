<?php
$pageTitle = "Liên hệ - The Coffee House";
require_once __DIR__ . '/components/header.php';
?>
<style>
/* Common Styles */
:root {
    --primary-color: #6F4E37;
    --primary-hover: #5a3f2d;
    --secondary-color: #B87333;
    --light-color: #f8f8f8;
    --dark-color: #333333;
    --text-color: #444444;
    --border-radius: 8px;
    --transition: all 0.3s ease;
    --box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

body {
    font-family: 'Roboto', sans-serif;
    color: var(--text-color);
    line-height: 1.8;
    background-color: #f9f9f9;
}

/* Typography */
h1, h2, h3, h4, h5, h6 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
}

.section-title {
    color: var(--primary-color);
    margin-bottom: 20px;
    position: relative;
    font-weight: 700;
    font-size: 2.5rem;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 25px;
}

/* Hero Section */
.hero-section {
    background-size: cover;
    background-position: center;
    padding: 180px 0 120px;
    position: relative;
    background-image: url('/public/images/contact-hero.jpg');
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
}

.hero-section .container {
    position: relative;
    z-index: 1;
}

.hero-section h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-section p {
    font-size: 1.25rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 2rem;
}

/* Contact Cards */
.contact-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    height: 100%;
    transition: var(--transition);
    border-top: 4px solid var(--primary-color);
    padding: 2rem;
    text-align: center;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.contact-icon-container {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: rgba(111, 78, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.contact-icon-container i {
    font-size: 2rem;
    color: var(--primary-color);
}

.contact-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.contact-link {
    color: var(--text-color);
    font-weight: 500;
    text-decoration: none;
    font-size: 1.1rem;
    transition: var(--transition);
    display: block;
    margin-bottom: 0.5rem;
}

.contact-link:hover {
    color: var(--primary-color);
    transform: translateX(5px);
}

/* Form Styles */
.form-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 2.5rem;
}

.form-floating>label {
    padding-left: 1rem;
    color: #666;
}

.form-control, .form-select {
    border-radius: var(--border-radius);
    padding: 1rem;
    border: 1px solid #ddd;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    padding: 0.8rem 2rem;
    font-weight: 500;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-2px);
}

/* Map Container */
.map-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 2rem;
    height: 100%;
}

.map-container iframe {
    border-radius: var(--border-radius);
    border: none;
    width: 100%;
    height: 400px;
}

/* Social Icons */
.social-icons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
}

.social-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    transition: var(--transition);
    text-decoration: none;
    font-size: 1.25rem;
}

.social-icon:hover {
    background-color: var(--primary-color);
    color: white;
    transform: translateY(-3px);
}

/* Operating Hours */
.hours-list {
    background-color: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.hours-item {
    padding: 12px 0;
    border-bottom: 1px dashed #eee;
}

.hours-item:last-child {
    border-bottom: none;
}

.day {
    font-weight: 500;
}

.time {
    font-weight: 600;
}

.notice {
    background-color: rgba(111, 78, 55, 0.1);
    padding: 12px 15px;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
}

.image-container {
    position: relative;
    overflow: hidden;
}

.image-container img {
    transition: transform 0.5s ease;
}

.image-container:hover img {
    transform: scale(1.05);
}

/* Branch Locations */
.branch-card {
    transition: var(--transition);
}

.branch-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--box-shadow);
}

.branch-list li {
    font-size: 0.95rem;
}

/* CTA Section */
.cta-section {
    background-size: cover;
    background-position: center;
    position: relative;
    padding: 80px 0;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
}

.cta-section .container {
    position: relative;
    z-index: 1;
}

.app-badges {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

/* Accordion Styles */
.accordion-button:not(.collapsed) {
    background-color: rgba(111, 78, 55, 0.1);
    color: var(--primary-color);
}

.accordion-button:focus {
    border-color: rgba(111, 78, 55, 0.5);
    box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
}

/* Animation */
.animate-card {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s forwards;
}

.animate-card:nth-child(2) {
    animation-delay: 0.2s;
}

.animate-card:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 991.98px) {
    .hero-section {
        padding: 120px 0 80px;
    }
    
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
}

@media (max-width: 767.98px) {
    .hero-section {
        padding: 100px 0 60px;
    }
    
    .contact-card {
        padding: 1.5rem;
    }
    
    .form-container, .map-container {
        padding: 1.5rem;
    }
}

@media (max-width: 575.98px) {
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .contact-icon-container {
        width: 60px;
        height: 60px;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>
<div class="contact-page">
    <!-- Hero Section với Parallax Effect -->
    <div class="hero-section parallax-bg" style="background-image: url('/public/images/contact-hero.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="display-4 text-white fw-bold mb-4">Liên hệ với chúng tôi</h1>
                    <p class="lead text-white">Chúng tôi luôn sẵn sàng lắng nghe ý kiến của bạn</p>
                    <a href="#contact-form" class="btn btn-primary btn-lg rounded-pill mt-3">
                        <i class="fas fa-envelope me-2"></i>Gửi tin nhắn
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-container bg-light py-2">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Contact Info Section with Animation -->
    <section class="contact-info py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Thông tin liên hệ</h2>
                <div class="divider mx-auto"></div>
                <p class="section-description">Hãy kết nối với chúng tôi bằng một trong những cách dưới đây</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-card text-center p-4 animate-card">
                        <div class="contact-icon-container mb-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Trụ sở chính</h3>
                        <p class="mb-1">123 Nguyễn Du</p>
                        <p class="mb-1">Quận 1, TP. Hồ Chí Minh</p>
                        <p>Việt Nam</p>
                        <a href="https://goo.gl/maps/xxxxxxxx" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
                            <i class="fas fa-directions me-1"></i>Chỉ đường
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card text-center p-4 animate-card">
                        <div class="contact-icon-container mb-3">
                            <i class="fas fa-phone-alt fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Điện thoại</h3>
                        <p class="mb-2">
                            <span class="d-block small text-muted">Hotline (miễn phí 24/7)</span>
                            <a href="tel:18006936" class="contact-link">1800 6936</a>
                        </p>
                        <p>
                            <span class="d-block small text-muted">Văn phòng</span>
                            <a href="tel:02871078079" class="contact-link">(028) 7107 8079</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card text-center p-4 animate-card">
                        <div class="contact-icon-container mb-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Email</h3>
                        <p class="mb-2">
                            <span class="d-block small text-muted">Chăm sóc khách hàng</span>
                            <a href="mailto:hi@thecoffeehouse.com" class="contact-link">hi@thecoffeehouse.com</a>
                        </p>
                        <p>
                            <span class="d-block small text-muted">Hỗ trợ kỹ thuật</span>
                            <a href="mailto:support@thecoffeehouse.com" class="contact-link">support@thecoffeehouse.com</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Social Media Section -->
            <div class="social-media-section text-center mt-5">
                <h3 class="h5 mb-4">Kết nối với chúng tôi</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Operating Hours -->
    <section class="operating-hours py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Giờ làm việc</h2>
                    <div class="divider mb-4"></div>
                    <div class="hours-list">
                        <div class="hours-item d-flex justify-content-between">
                            <span class="day">Thứ Hai - Thứ Sáu:</span>
                            <span class="time">7:00 - 22:00</span>
                        </div>
                        <div class="hours-item d-flex justify-content-between">
                            <span class="day">Thứ Bảy - Chủ Nhật:</span>
                            <span class="time">8:00 - 22:00</span>
                        </div>
                        <div class="hours-item d-flex justify-content-between">
                            <span class="day">Lễ, Tết:</span>
                            <span class="time">8:00 - 21:00</span>
                        </div>
                    </div>
                    <div class="notice mt-4">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        <span>Hotline hỗ trợ 24/7 kể cả ngày lễ và cuối tuần</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="image-container rounded shadow-lg overflow-hidden">
                        <img src="/public/images/coffee-shop.jpg" alt="The Coffee House" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section with FAQ -->
    <section id="contact-form" class="contact-form-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="form-container p-4 p-lg-5 bg-white rounded shadow">
                        <h2 class="section-title">Gửi tin nhắn cho chúng tôi</h2>
                        <div class="divider mb-4"></div>
                        <p class="section-description mb-4">
                            Hãy chia sẻ với chúng tôi những góp ý, thắc mắc của bạn về sản phẩm và dịch vụ.
                            Đội ngũ của chúng tôi sẽ phản hồi trong vòng 24 giờ.
                        </p>
                        <form id="contactForm" action="/contact/send" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Họ và tên" required>
                                        <label for="name">Họ và tên *</label>
                                        <div class="invalid-feedback">Vui lòng nhập họ tên của bạn</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                        <label for="email">Email *</label>
                                        <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Số điện thoại">
                                        <label for="phone">Số điện thoại</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="contactReason" name="contactReason" required>
                                            <option value="" selected disabled>Chọn lý do liên hệ</option>
                                            <option value="feedback">Phản hồi/Góp ý</option>
                                            <option value="complaint">Khiếu nại</option>
                                            <option value="partnership">Hợp tác kinh doanh</option>
                                            <option value="inquiry">Thắc mắc chung</option>
                                            <option value="other">Khác</option>
                                        </select>
                                        <label for="contactReason">Lý do liên hệ *</label>
                                        <div class="invalid-feedback">Vui lòng chọn lý do liên hệ</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Chủ đề" required>
                                        <label for="subject">Chủ đề *</label>
                                        <div class="invalid-feedback">Vui lòng nhập chủ đề</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="message" name="message" placeholder="Nội dung" style="height: 150px" required></textarea>
                                        <label for="message">Nội dung tin nhắn *</label>
                                        <div class="invalid-feedback">Vui lòng nhập nội dung tin nhắn</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                                        <label class="form-check-label" for="agreeTerms">
                                            Tôi đồng ý với <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal">chính sách bảo mật</a> của The Coffee House *
                                        </label>
                                        <div class="invalid-feedback">Bạn cần đồng ý với chính sách bảo mật của chúng tôi</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                        </button>
                                    </div>
                                    <p class="form-text text-center mt-3">
                                        <small>Các trường đánh dấu * là bắt buộc</small>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="map-container bg-white p-4 p-lg-5 rounded shadow h-100">
                        <h2 class="section-title">Vị trí của chúng tôi</h2>
                        <div class="divider mb-4"></div>
                        <div class="map-wrapper rounded overflow-hidden mb-4">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.325247630613!2d106.69632067465353!3d10.786337989318513!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3a9d8d1bb3%3A0xc4eba6f25cf30a1!2sThe%20Coffee%20House!5e0!3m2!1sen!2s!4v1690341008!5m2!1sen!2s" 
                                width="100%" 
                                height="400" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        
                        <!-- Accordion FAQ -->
                        <div class="faq-section mt-4">
                            <h3 class="h5 mb-3">Câu hỏi thường gặp</h3>
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h4 class="accordion-header" id="faqHeading1">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                                            Tôi có thể đặt giao hàng tận nơi không?
                                        </button>
                                    </h4>
                                    <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Có, The Coffee House có dịch vụ giao hàng tận nơi. Bạn có thể đặt hàng qua ứng dụng di động của chúng tôi hoặc gọi Hotline 1800 6936 để được hỗ trợ.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h4 class="accordion-header" id="faqHeading2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                            Làm thế nào để trở thành thành viên của The Coffee House?
                                        </button>
                                    </h4>
                                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Bạn có thể đăng ký thành viên thông qua ứng dụng di động của The Coffee House. Tải ứng dụng, đăng ký tài khoản và bắt đầu tích lũy điểm với mỗi đơn hàng để nhận nhiều ưu đãi hấp dẫn.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h4 class="accordion-header" id="faqHeading3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                            Làm thế nào để tôi có thể ứng tuyển vào The Coffee House?
                                        </button>
                                    </h4>
                                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Bạn có thể xem các vị trí tuyển dụng hiện tại và nộp đơn ứng tuyển trực tuyến thông qua trang "Tuyển dụng" trên website của chúng tôi hoặc gửi CV về email: careers@thecoffeehouse.com
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Branch Locations -->
    <section class="branches py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Hệ thống cửa hàng</h2>
                <div class="divider mx-auto"></div>
                <p class="section-description">Tìm cửa hàng The Coffee House gần bạn nhất</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="branch-card bg-white p-3 rounded shadow-sm h-100">
                        <h3 class="h5 mb-3">TP. Hồ Chí Minh</h3>
                        <ul class="branch-list list-unstyled">
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 123 Nguyễn Du, Quận 1</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 456 Lê Lợi, Quận 1</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 789 CMT8, Quận 3</li>
                            <li><i class="fas fa-store text-primary me-2"></i> 101 Nguyễn Văn Trỗi, Phú Nhuận</li>
                        </ul>
                        <a href="/locations/hcm" class="btn btn-outline-primary btn-sm mt-3">Xem tất cả</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="branch-card bg-white p-3 rounded shadow-sm h-100">
                        <h3 class="h5 mb-3">Hà Nội</h3>
                        <ul class="branch-list list-unstyled">
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 123 Bà Triệu, Hoàn Kiếm</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 456 Thái Hà, Đống Đa</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 789 Trần Duy Hưng, Cầu Giấy</li>
                            <li><i class="fas fa-store text-primary me-2"></i> 101 Nguyễn Chí Thanh, Ba Đình</li>
                        </ul>
                        <a href="/locations/hanoi" class="btn btn-outline-primary btn-sm mt-3">Xem tất cả</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="branch-card bg-white p-3 rounded shadow-sm h-100">
                        <h3 class="h5 mb-3">Đà Nẵng</h3>
                        <ul class="branch-list list-unstyled">
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 123 Nguyễn Văn Linh, Hải Châu</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 456 Trần Phú, Hải Châu</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 789 Lê Duẩn, Thanh Khê</li>
                            <li><i class="fas fa-store text-primary me-2"></i> 101 Ngô Quyền, Sơn Trà</li>
                        </ul>
                        <a href="/locations/danang" class="btn btn-outline-primary btn-sm mt-3">Xem tất cả</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="branch-card bg-white p-3 rounded shadow-sm h-100">
                        <h3 class="h5 mb-3">Các tỉnh khác</h3>
                        <ul class="branch-list list-unstyled">
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 123 Lê Hồng Phong, Hải Phòng</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 456 Phan Bội Châu, Nha Trang</li>
                            <li class="mb-2"><i class="fas fa-store text-primary me-2"></i> 789 Lý Tự Trọng, Cần Thơ</li>
                            <li><i class="fas fa-store text-primary me-2"></i> 101 Quang Trung, Đà Lạt</li>
                        </ul>
                        <a href="/locations" class="btn btn-outline-primary btn-sm mt-3">Xem tất cả</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="/store-locator" class="btn btn-primary btn-lg rounded-pill">
                    <i class="fas fa-search-location me-2"></i>Tìm cửa hàng
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5" style="background-image: url('/public/images/cta-bg.jpg');">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8 text-center">
                    <h2 class="text-white mb-4">Tải ứng dụng The Coffee House</h2>
                    <p class="text-white mb-4">Trải nghiệm đặt hàng dễ dàng, tích điểm đổi quà và nhận nhiều ưu đãi độc quyền</p>
                    <div class="app-badges">
                        <a href="#" class="app-badge me-3">
                            <img src="/public/images/app-store-badge.png" alt="App Store" height="50">
                        </a>
                        <a href="#" class="app-badge">
                            <img src="/public/images/google-play-badge.png" alt="Google Play" height="50">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyPolicyModal" tabindex="-1" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyPolicyModalLabel">Chính sách bảo mật</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="mb-3">1. Mục đích thu thập thông tin</h5>
                <p>The Coffee House thu thập thông tin cá nhân của khách hàng nhằm mục đích:</p>
                <ul>
                    <li>Cung cấp các dịch vụ đến khách hàng.</li>
                    <li>Gửi các thông báo về các hoạt động tương tác của khách hàng với The Coffee House.</li>
                    <li>Ngăn ngừa các hoạt động phá hủy tài khoản người dùng hoặc các hoạt động giả mạo khách hàng.</li>
                    <li>Liên lạc và giải quyết khiếu nại với khách hàng.</li>
                    <li>Xác nhận và vận chuyển đơn hàng.</li>
                    <li>Nghiên cứu, đánh giá và cải tiến dịch vụ, phân tích mức độ sử dụng và xu hướng tiêu dùng.</li>
                </ul>
                
                <h5 class="mb-3 mt-4">2. Thời gian lưu trữ thông tin</h5>
                <p>Thông tin của khách hàng sẽ được lưu trữ trong hệ thống nội bộ của The Coffee House cho đến khi khách hàng có yêu cầu hủy bỏ. Trong mọi trường hợp, thông tin cá nhân của khách hàng sẽ được bảo mật trên máy chủ của The Coffee House.</p>
                
                <h5 class="mb-3 mt-4">3. Cam kết bảo mật thông tin khách hàng</h5>
                <p>The Coffee House cam kết sẽ không chia sẻ, bán hoặc cho thuê thông tin cá nhân của khách hàng cho bất kỳ bên thứ ba nào ngoài The Coffee House với bất kỳ mục đích nào mà không có sự đồng ý của khách hàng.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form Validation
    const form = document.getElementById('contactForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Offset for fixed header
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const parallaxBg = document.querySelector('.parallax-bg');
        if (parallaxBg) {
            const scrollPosition = window.pageYOffset;
            parallaxBg.style.backgroundPositionY = (50 + scrollPosition * 0.1) + '%';
        }
    });
    
    // Reveal animation on scroll
    const revealElements = document.querySelectorAll('.contact-card, .branch-card, .section-header, .form-container, .map-container');
    
    const revealOnScroll = function() {
        for (let i = 0; i < revealElements.length; i++) {
            const windowHeight = window.innerHeight;
            const elementTop = revealElements[i].getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < windowHeight - elementVisible) {
                revealElements[i].classList.add('animate-card');
            }
        }
    };
    
    window.addEventListener('scroll', revealOnScroll);
    
    // Trigger once on load
    revealOnScroll();
    
    // Select region/city filter for branches
    const cityFilter = document.getElementById('cityFilter');
    if (cityFilter) {
        cityFilter.addEventListener('change', function() {
            const selectedCity = this.value;
            const branchCards = document.querySelectorAll('.branch-card');
            
            branchCards.forEach(card => {
                if (selectedCity === 'all' || card.dataset.city === selectedCity) {
                    card.closest('.col-md-6').style.display = 'block';
                } else {
                    card.closest('.col-md-6').style.display = 'none';
                }
            });
        });
    }
    
    // Contact reason conditional fields
    const contactReason = document.getElementById('contactReason');
    const additionalFields = document.getElementById('additionalFields');
    
    if (contactReason && additionalFields) {
        contactReason.addEventListener('change', function() {
            const selectedReason = this.value;
            
            // Clear previous additional fields
            additionalFields.innerHTML = '';
            
            // Add conditional fields based on selection
            if (selectedReason === 'complaint') {
                const orderField = document.createElement('div');
                orderField.className = 'form-floating mb-3';
                orderField.innerHTML = `
                    <input type="text" class="form-control" id="orderNumber" name="orderNumber" placeholder="Mã đơn hàng">
                    <label for="orderNumber">Mã đơn hàng (nếu có)</label>
                `;
                additionalFields.appendChild(orderField);
            } else if (selectedReason === 'partnership') {
                const companyField = document.createElement('div');
                companyField.className = 'form-floating mb-3';
                companyField.innerHTML = `
                    <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Tên công ty">
                    <label for="companyName">Tên công ty</label>
                `;
                additionalFields.appendChild(companyField);
                
                const positionField = document.createElement('div');
                positionField.className = 'form-floating mb-3';
                positionField.innerHTML = `
                    <input type="text" class="form-control" id="position" name="position" placeholder="Chức vụ">
                    <label for="position">Chức vụ</label>
                `;
                additionalFields.appendChild(positionField);
            }
        });
    }
    
    // Success message after form submission
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    if (status === 'success') {
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            Tin nhắn của bạn đã được gửi thành công! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
            formContainer.insertBefore(successAlert, formContainer.firstChild);
        }
    }
});
</script>

<?php
require_once __DIR__ . '/components/footer.php';
?>
</body>


