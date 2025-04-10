<?php
$pageTitle = "Liên hệ - The Coffee House";
require_once __DIR__ . '/components/header.php';
?>

<div class="contact-page">
    <!-- Contact Hero Section -->
    <div class="hero-section" style="background-image: url('/public/images/contact-hero.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="display-4 text-white mb-4">Liên hệ với chúng tôi</h1>
                    <p class="lead text-white">Chúng tôi luôn sẵn sàng lắng nghe ý kiến của bạn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info Section -->
    <section class="contact-info py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="contact-card text-center p-4">
                        <div class="contact-icon mb-3">
                            <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Địa chỉ</h3>
                        <p>123 Nguyễn Du<br>Quận 1, TP. Hồ Chí Minh</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="contact-card text-center p-4">
                        <div class="contact-icon mb-3">
                            <i class="fas fa-phone fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Điện thoại</h3>
                        <p>Hotline: 1800 6936<br>Tel: (028) 7107 8079</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="contact-card text-center p-4">
                        <div class="contact-icon mb-3">
                            <i class="fas fa-envelope fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Email</h3>
                        <p>hi@thecoffeehouse.com<br>support@thecoffeehouse.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="section-title">Gửi tin nhắn cho chúng tôi</h2>
                    <p class="section-description mb-4">
                        Hãy chia sẻ với chúng tôi những góp ý, thắc mắc của bạn về sản phẩm và dịch vụ.
                    </p>
                    <form id="contactForm" action="/contact/send" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Chủ đề</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="map-container">
                        <h2 class="section-title">Vị trí của chúng tôi</h2>
                        <div class="map-wrapper">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.325247630613!2d106.69632067465353!3d10.786337989318513!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3a9d8d1bb3%3A0xc4eba6f25cf30a1!2sThe%20Coffee%20House!5e0!3m2!1sen!2s!4v1690341008盤4!5m2!1sen!2s" 
                                width="100%" 
                                height="450" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.hero-section {
    background-size: cover;
    background-position: center;
    padding: 150px 0;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.hero-section .container {
    position: relative;
    z-index: 1;
}

.section-title {
    color: #6F4E37;
    margin-bottom: 30px;
    font-weight: 600;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
}

.contact-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    height: 100%;
    transition: transform 0.3s ease;
}

.contact-card:hover {
    transform: translateY(-5px);
}

.contact-icon {
    color: #6F4E37;
}

.text-primary {
    color: #6F4E37 !important;
}

.btn-primary {
    background-color: #6F4E37;
    border-color: #6F4E37;
}

.btn-primary:hover {
    background-color: #5a3f2d;
    border-color: #5a3f2d;
}

.form-control:focus {
    border-color: #6F4E37;
    box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
}

.map-container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.map-wrapper {
    border-radius: 10px;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Thực hiện validation
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();
        
        if (!name || !email || !message) {
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
            return;
        }
        
        // Nếu validation pass, submit form
        this.submit();
    });
});
</script>

<?php require_once __DIR__ . '/components/footer.php'; ?>