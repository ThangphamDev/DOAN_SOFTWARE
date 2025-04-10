<?php
$pageTitle = "Giới thiệu - The Coffee House";
require_once __DIR__ . '/components/header.php';
?>

<div class="about-page">
    <!-- Hero Section -->
    <div class="hero-section" style="background-image: url('/public/images/about-hero.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="display-4 text-white mb-4">Câu chuyện về The Coffee House</h1>
                    <p class="lead text-white">Nơi mỗi tách cà phê là một trải nghiệm đáng nhớ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Story Section -->
    <section class="our-story py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="section-title">Câu chuyện của chúng tôi</h2>
                    <p class="section-description">
                        The Coffee House được thành lập với tình yêu và đam mê với cà phê Việt Nam. 
                        Chúng tôi tin rằng cà phê không chỉ đơn thuần là thức uống, mà còn là cầu nối 
                        gắn kết con người với nhau, là nơi chia sẻ những câu chuyện thú vị và là điểm 
                        tựa cho những ý tưởng mới.
                    </p>
                    <p>
                        Với khát vọng mang đến những tách cà phê chất lượng nhất, chúng tôi đã không 
                        ngừng tìm kiếm và chọn lọc những hạt cà phê tốt nhất từ các vùng nguyên liệu 
                        nổi tiếng, kết hợp cùng bí quyết rang xay độc đáo để tạo nên những tách cà phê 
                        đậm đà, tinh tế.
                    </p>
                </div>
                <div class="col-md-6">
                    <img src="/public/images/about-story.jpg" alt="Our Story" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="our-values py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title">Giá trị cốt lõi</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="value-card text-center p-4">
                        <div class="value-icon mb-3">
                            <i class="fas fa-coffee fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4">Chất lượng</h3>
                        <p>Cam kết mang đến những sản phẩm chất lượng cao nhất cho khách hàng</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="value-card text-center p-4">
                        <div class="value-icon mb-3">
                            <i class="fas fa-heart fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4">Đam mê</h3>
                        <p>Làm việc với tình yêu và sự nhiệt huyết với cà phê</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="value-card text-center p-4">
                        <div class="value-icon mb-3">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4">Con người</h3>
                        <p>Xây dựng môi trường thân thiện, tôn trọng và phát triển</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievement Section -->
    <section class="achievements py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="achievement-item">
                        <h3 class="display-4 text-primary mb-2">50+</h3>
                        <p class="lead">Cửa hàng</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="achievement-item">
                        <h3 class="display-4 text-primary mb-2">1M+</h3>
                        <p class="lead">Khách hàng</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="achievement-item">
                        <h3 class="display-4 text-primary mb-2">5M+</h3>
                        <p class="lead">Ly cà phê</p>
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

.value-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.value-card:hover {
    transform: translateY(-5px);
}

.value-icon {
    color: #6F4E37;
}

.achievement-item {
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.text-primary {
    color: #6F4E37 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<?php require_once __DIR__ . '/components/footer.php'; ?>