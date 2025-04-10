<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../config/Database.php';

// Khởi tạo kết nối database
$database = new Database();
$db = $database->getConnection();

// Khởi tạo đối tượng Product
$product = new Product($db);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T&2K Coffee - Hương Vị Đắm Say</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css" />
    <?php include_once('app/Views/components/header.php'); ?>
    <style>
        :root {
            --primary: #6F4E37;
            --secondary: #C0A080;
            --accent: #D4AF37;
            --dark: #2C1810;
            --light: #F5F5F5;
            --white: #FFFFFF;
            --gray: #EFEFEF;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            background-image: linear-gradient(to right, rgba(247, 247, 247, 0.8), rgba(255, 255, 255, 0.8)), url('/public/images/home/coffee-pattern.png');
            background-size: 200px;
            background-attachment: fixed;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Hero Section */
        .hero {
            height: 680px;
            position: relative;
            overflow: hidden;
            margin-top: 30px;
            margin-bottom: 60px;
        }
        
        .hero .container {
            position: relative;
            height: 100%;
            padding-top: 10px;
            max-width: 100%;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }
        
        .hero-swiper {
            width: 100%;
            height: 100%;
        }
        
        .hero-slide {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            border-radius: 0;
            overflow: hidden;
        }
        
        .hero-slide-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 20%;
            z-index: -1;
            filter: brightness(0.7);
            border-radius: 0;
        }
        
        .hero-content {
            max-width: 800px;
            text-align: center;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 30px;
            padding-top: 15px;
            backdrop-filter: blur(3px);
            width: 80%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }
        
        .hero-desc {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 2rem;
            line-height: 1.5;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary:hover {
            background-color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--white);
            border: 2px solid var(--white);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline:hover {
            background-color: var(--white);
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .hero-arrows {
            color: var(--white);
            font-size: 18px;
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .swiper-button-next, 
        .swiper-button-prev {
            width: 40px !important;
            height: 40px !important;
        }
        
        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 16px !important;
            font-weight: bold;
        }
        
        .swiper-pagination {
            bottom: 20px !important;
        }
        
        .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background-color: var(--white);
            opacity: 0.5;
        }
        
        .swiper-pagination-bullet-active {
            opacity: 1;
            background-color: var(--accent);
            width: 12px;
            height: 12px;
        }
        
        .scroll-down {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            color: var(--primary);
            font-size: 18px;
            cursor: pointer;
            z-index: 10;
            background-color: var(--white);
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: var(--shadow);
            animation: bounce 2s infinite;
            border: 2px solid rgba(111, 78, 55, 0.2);
            transition: all 0.3s ease;
        }
        
        .scroll-down:hover {
            background-color: var(--primary);
            color: var(--white);
            transform: translateX(-50%) scale(1.1);
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-10px) translateX(-50%);
            }
            60% {
                transform: translateY(-5px) translateX(-50%);
            }
        }
        
        .section-divider {
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--accent), transparent);
            margin: 0 auto;
            position: relative;
            opacity: 0.5;
        }
        
        /* Section transition decorative element */
        .curved-decoration {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 50px;
            fill: rgba(255, 255, 255, 0.9);
            z-index: 5;
        }
        
        /* WiFi Badge */
        .wifi-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: var(--accent);
            color: var(--dark);
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 5;
            box-shadow: var(--shadow);
            font-size: 14px;
        }
        
        .wifi-badge i {
            font-size: 18px;
        }
        
        /* Section Styling */
        section {
            padding: 100px 0;
            position: relative;
        }
        
        section:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            margin: 30px 0;
        }
        
        section:nth-child(even) {
            background: transparent;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-subtitle {
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
            display: block;
        }
        
        .section-title {
            font-size: 42px;
            font-weight: 700;
            color: var(--dark);
            position: relative;
            display: inline-block;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--accent);
        }
        
        /* Featured Products */
        .featured-products {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .featured-products::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: rgba(212, 175, 55, 0.1);
            top: -100px;
            left: -100px;
            z-index: -1;
        }
        
        .featured-products::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background-color: rgba(111, 78, 55, 0.1);
            bottom: -100px;
            right: -100px;
            z-index: -1;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .product-card {
            background-color: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--accent);
            color: var(--dark);
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .product-desc {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .product-actions {
            display: flex;
        }
        
        .btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }
        
        .view-all {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }
        
        /* Special Offers */
        .special-offers {
            position: relative;
            overflow: hidden;
        }
        
        .special-offers:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/public/images/home/coffee-pattern.png');
            opacity: 0.05;
            z-index: 0;
        }
        
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            position: relative;
            z-index: 1;
        }
        
        .offer-card {
            background-color: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            padding: 30px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
            background-image: linear-gradient(to bottom right, rgba(255, 255, 255, 0.9), rgba(245, 245, 245, 0.9));
        }
        
        .offer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .offer-icon {
            font-size: 40px;
            color: var(--accent);
            margin-bottom: 20px;
            display: inline-block;
            background-color: rgba(212, 175, 55, 0.15);
            padding: 20px;
            border-radius: 50%;
        }
        
        .offer-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .offer-code {
            display: inline-block;
            background-color: var(--gray);
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            margin: 10px 0;
            color: var(--primary);
            letter-spacing: 1px;
        }
        
        .offer-desc {
            margin-top: 15px;
            color: #666;
        }
        
        /* About Us */
        .about-us {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .about-us::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(rgba(192, 160, 128, 0.1), transparent 70%);
            border-radius: 50%;
            top: -200px;
            left: -200px;
            z-index: -1;
        }
        
        .about-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        
        .about-image {
            position: relative;
            border-radius: 25px;
            overflow: visible;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            gap: 25px;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
            background-color: var(--white);
            z-index: 1;
        }
        
        .about-img-main {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            transition: transform 0.5s ease;
            box-shadow: var(--shadow);
        }
        
        .about-img-secondary {
            width: 90%;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            transition: all 0.5s ease;
            align-self: flex-end;
            margin-top: -60px;
            border: 8px solid var(--white);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
            filter: brightness(1.05);
        }
        
        .about-image:after {
            content: '';
            position: absolute;
            top: 50px;
            right: -30px;
            width: 80px;
            height: 80px;
            background-color: var(--accent);
            opacity: 0.2;
            border-radius: 20px;
            z-index: -1;
            transform: rotate(45deg);
        }
        
        .about-image:hover .about-img-main {
            transform: scale(1.05);
        }
        
        .about-image:hover .about-img-secondary {
            transform: scale(1.08) translateY(-15px);
        }
        
        .about-image:before {
            content: '';
            position: absolute;
            top: 30px;
            left: -30px;
            width: 100%;
            height: 75%;
            border: 6px solid var(--accent);
            border-radius: 25px;
            z-index: -1;
        }
        
        .about-image-decor {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 4px dashed var(--accent);
            border-radius: 50%;
            top: 30%;
            left: -75px;
            z-index: -1;
        }
        
        .about-image-badge {
            position: absolute;
            bottom: 60px;
            left: 40px;
            background-color: var(--primary);
            color: var(--white);
            width: 85px;
            height: 85px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 3;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            border: 4px solid var(--white);
        }
        
        .about-image-badge i {
            font-size: 26px;
            margin-bottom: 3px;
            color: var(--accent);
        }
        
        .about-image-badge span {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .about-content h3 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark);
            line-height: 1.3;
        }
        
        .about-content p {
            margin-bottom: 20px;
            color: #555;
        }
        
        .highlight {
            color: var(--primary);
            font-weight: 600;
        }
        
        .about-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .feature-icon {
            font-size: 24px;
            color: var(--accent);
            background-color: rgba(212, 175, 55, 0.15);
            padding: 10px;
            border-radius: 50%;
        }
        
        .feature-info h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .feature-info p {
            font-size: 14px;
            margin-bottom: 0;
        }
        
        /* Gallery */
        .gallery {
            overflow: hidden;
            position: relative;
        }
        
        .gallery::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(rgba(111, 78, 55, 0.1), transparent);
            border-radius: 50%;
            top: -150px;
            right: -150px;
            z-index: 0;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            position: relative;
            z-index: 1;
        }
        
        .gallery-item {
            border-radius: 10px;
            overflow: hidden;
            height: 250px;
            position: relative;
            cursor: pointer;
            box-shadow: var(--shadow);
            border: 3px solid white;
            transition: all 0.4s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }
        
        .gallery-icon {
            color: var(--white);
            font-size: 30px;
            transform: scale(0);
            transition: var(--transition);
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-item:hover .gallery-icon {
            transform: scale(1);
        }
        
        /* Testimonials */
        .testimonials {
            position: relative;
            overflow: hidden;
        }
        
        .testimonials::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(rgba(212, 175, 55, 0.1), transparent 70%);
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
            z-index: 0;
        }
        
        .testimonial-swiper {
            padding: 30px 10px 50px;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-card {
            background-color: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.95), rgba(252, 252, 252, 0.95));
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .testimonial-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 3px solid var(--accent);
        }
        
        .testimonial-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: #555;
        }
        
        .testimonial-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .testimonial-title {
            font-size: 14px;
            color: #777;
        }
        
        .testimonial-rating {
            margin-top: 15px;
            color: var(--accent);
            font-size: 18px;
        }
        
        /* Contact */
        .contact-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .contact-item {
            background-color: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }
        
        .contact-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .contact-icon {
            font-size: 30px;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .contact-label {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .contact-text {
            color: #555;
        }
        
        .contact-form {
            background-color: var(--white);
            padding: 40px;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }
        
        .form-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--dark);
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .btn-block {
            width: 100%;
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
        
        /* Responsive */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 48px;
            }
            
            .about-wrapper {
                grid-template-columns: 1fr;
            }
            
            .about-image {
                margin-bottom: 70px;
                width: 100%;
                max-width: 550px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .about-img-secondary {
                margin-top: -50px;
                width: 85%;
            }
            
            .about-image-badge {
                bottom: 50px;
                left: 30px;
                width: 75px;
                height: 75px;
            }
            
            .about-image-badge i {
                font-size: 22px;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .hero {
                height: 500px;
                margin-top: 60px;
            }
            
            .hero-title {
                font-size: 36px;
            }
            
            .hero-subtitle {
                font-size: 16px;
            }
            
            .hero-desc {
                font-size: 14px;
            }
            
            section {
                padding: 60px 0;
            }
            
            .section-title {
                font-size: 32px;
            }
            
            .contact-info {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .hero {
                height: 450px;
            }
            
            .hero-slide-bg {
                object-position: center 30%;
            }
            
            .hero-content {
                max-width: 100%;
                padding: 0 15px;
            }
            
            .hero-title {
                font-size: 28px;
                margin-bottom: 10px;
            }
            
            .hero-subtitle {
                font-size: 14px;
                margin-bottom: 5px;
            }
            
            .hero-desc {
                font-size: 13px;
                margin-bottom: 15px;
            }
            
            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .hero-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
            
            .btn-outline {
                margin-left: 0;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .about-features {
                grid-template-columns: 1fr;
            }
            
            .about-image {
                width: 100%;
                max-width: 100%;
            }
            
            .about-img-secondary {
                width: 80%;
                margin-top: -40px;
            }
            
            .about-image-decor {
                width: 100px;
                height: 100px;
                left: -50px;
            }
            
            .about-image-badge {
                width: 65px;
                height: 65px;
                bottom: 40px;
                left: 20px;
            }
            
            .about-image-badge i {
                font-size: 20px;
            }
            
            .about-image-badge span {
                font-size: 10px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .offers-grid {
                grid-template-columns: 1fr;
            }
            
            .wifi-badge {
                top: 10px;
                right: 10px;
                padding: 5px 10px;
                font-size: 12px;
            }
            
            .wifi-badge i {
                font-size: 14px;
            }
            
            .scroll-down {
                bottom: -15px;
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
        }
        
        /* Lightbox Styles */
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border: 3px solid var(--white);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }
        
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: var(--white);
            font-size: 30px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: var(--transition);
        }
        
        .lightbox-close:hover {
            background-color: var(--primary);
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="swiper hero-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide hero-slide">
                        <img src="/public/images/Banner/banner_doan6.jpg" alt="T&2K Coffee" class="hero-slide-bg">
                        <div class="hero-content">
                            <span class="hero-subtitle">Chào mừng đến với</span>
                            <h1 class="hero-title">T&2K Coffee</h1>
                            <p class="hero-desc">Chúng tôi phục vụ những loại cà phê và đồ uống ngon nhất, mang đến cho bạn trải nghiệm thư giãn tuyệt vời.</p>
                            <div class="hero-buttons">
                                <a href="/menu" class="btn btn-primary">Xem Menu</a>
                                <a href="/contact" class="btn btn-outline">Liên hệ</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide hero-slide">
                        <img src="/public/images/Banner/banner_doan7.jpg" alt="Khuyến mãi đặc biệt" class="hero-slide-bg">
                        <div class="hero-content">
                            <span class="hero-subtitle">Ưu đãi mùa hè</span>
                            <h1 class="hero-title">Khuyến mãi đặc biệt</h1>
                            <p class="hero-desc">Giảm 15% cho tất cả đơn hàng trong mùa hè này. Hãy đến và trải nghiệm ngay!</p>
                            <div class="hero-buttons">
                                <a href="/products" class="btn btn-primary">Đặt hàng ngay</a>
                                <a href="/promotions" class="btn btn-outline">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide hero-slide">
                        <img src="/public/images/Banner/banner_doan8.png" alt="Hương vị đặc trưng" class="hero-slide-bg">
                        <div class="hero-content">
                            <span class="hero-subtitle">Hương vị đặc trưng</span>
                            <h1 class="hero-title">Cà phê nguyên chất</h1>
                            <p class="hero-desc">Trải nghiệm hương vị đậm đà từ những hạt cà phê được chọn lọc kỹ lưỡng.</p>
                            <div class="hero-buttons">
                                <a href="/products" class="btn btn-primary">Thử ngay</a>
                                <a href="/menu" class="btn btn-outline">Xem menu</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide hero-slide">
                        <img src="/public/images/Banner/banner_doan9.png" alt="Không gian độc đáo" class="hero-slide-bg">
                        <div class="hero-content">
                            <span class="hero-subtitle">Thưởng thức</span>
                            <h1 class="hero-title">Không gian độc đáo</h1>
                            <p class="hero-desc">Đắm mình trong không gian hiện đại kết hợp nét truyền thống tại T&2K Coffee.</p>
                            <div class="hero-buttons">
                                <a href="/about" class="btn btn-primary">Khám phá</a>
                                <a href="/contact" class="btn btn-outline">Đặt bàn</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev hero-arrows"></div>
                <div class="swiper-button-next hero-arrows"></div>
            </div>
            <div class="wifi-badge">
                <i class="fas fa-wifi"></i>
                <span>Wifi: T2Kcoffee2025</span>
            </div>
            <a href="#featured-products" class="scroll-down">
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- Featured Products -->
    <section id="featured-products" class="featured-products">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Đặc sắc</span>
                <h2 class="section-title">Sản phẩm nổi bật</h2>
            </div>
            <div class="products-grid" data-aos="fade-up" data-aos-delay="200">
                <?php
                // Lấy sản phẩm nổi bật từ database
                $featuredProducts = $product->readFeatured();
                while ($prod = $featuredProducts->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($prod['name']); ?>"
                             onerror="this.src='/public/images/default-product.jpg'">
                        <?php if (!empty($prod['is_new'])): ?>
                            <span class="product-badge">New</span>
                        <?php endif; ?>
                        <?php if (!empty($prod['is_popular'])): ?>
                            <span class="product-badge">Best Seller</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($prod['name']); ?></h3>
                        <p class="product-desc"><?php echo htmlspecialchars($prod['description']); ?></p>
                        <div class="product-price"><?php echo number_format($prod['base_price'], 0, ',', '.'); ?>đ</div>
                        <div class="product-actions">
                            <a href="/menu/product/<?php echo $prod['product_id']; ?>" class="btn btn-primary btn-sm">Chi tiết</a>
                            <button class="btn btn-outline btn-sm" data-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="view-all" data-aos="fade-up" data-aos-delay="300">
                <a href="/menu" class="btn btn-primary">Xem tất cả sản phẩm</a>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- Special Offers -->
    <section class="special-offers">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Ưu đãi</span>
                <h2 class="section-title">Khuyến mãi đặc biệt</h2>
            </div>
            <div class="offers-grid" data-aos="fade-up" data-aos-delay="200">
                <div class="offer-card">
                    <div class="offer-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <h3 class="offer-title">Giảm 10% cho khách hàng mới</h3>
                    <div class="offer-code">WELCOME10</div>
                    <p class="offer-desc">Giảm 10% tổng hóa đơn cho khách hàng lần đầu đặt hàng</p>
                </div>
                <div class="offer-card">
                    <div class="offer-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="offer-title">Miễn phí vận chuyển</h3>
                    <div class="offer-code">FREESHIP</div>
                    <p class="offer-desc">Miễn phí vận chuyển cho đơn hàng từ 100,000đ</p>
                </div>
                <div class="offer-card">
                    <div class="offer-icon">
                        <i class="fas fa-sun"></i>
                    </div>
                    <h3 class="offer-title">Khuyến mãi hè 2025</h3>
                    <div class="offer-code">SUMMER25</div>
                    <p class="offer-desc">Giảm 15% cho các đơn hàng trong mùa hè</p>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- About Us -->
    <section class="about-us">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Giới thiệu</span>
                <h2 class="section-title">Về chúng tôi</h2>
            </div>
            <div class="about-wrapper">
                <div class="about-image" data-aos="fade-right">
                    <img src="/public/images/Banner/banner_doan6.jpg" alt="Về quán coffee" class="about-img-main">
                    <img src="/public/images/Banner/banner_doan7.jpg" alt="Không gian quán coffee" class="about-img-secondary">
                    <div class="about-image-decor"></div>
                    <div class="about-image-badge">
                        <i class="fas fa-coffee"></i>
                        <span>Est. 2022</span>
                    </div>
                </div>
                <div class="about-content" data-aos="fade-left">
                    <h3>Coffee T&2K - Nơi Hương Vị Gặp Gỡ Cảm Xúc</h3>
                    <p>Thành lập từ năm 2022, <span class="highlight">Coffee T&2K</span> tự hào mang đến cho khách hàng những trải nghiệm cà phê độc đáo và chất lượng nhất.</p>
                    <p>Chúng tôi lựa chọn những hạt cà phê tốt nhất từ Tây Nguyên và các vùng trồng cà phê nổi tiếng khác để đảm bảo hương vị tuyệt hảo cho từng tách cà phê.</p>
                    <p>Không gian quán được thiết kế theo phong cách hiện đại, ấm cúng, là nơi lý tưởng để gặp gỡ bạn bè, làm việc hay đơn giản là tận hưởng một khoảnh khắc thư giãn.</p>
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="feature-info">
                                <h4>Địa chỉ</h4>
                                <p>123 Đường ABC, Quận 1, TP.HCM</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="feature-info">
                                <h4>Điện thoại</h4>
                                <p>0123.456.789</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="feature-info">
                                <h4>Giờ mở cửa</h4>
                                <p>7:00 - 22:00 hàng ngày</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-coffee"></i>
                            </div>
                            <div class="feature-info">
                                <h4>Đặc sản</h4>
                                <p>Cà phê nguyên chất Tây Nguyên</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>
    
    <!-- Gallery -->
    <section class="gallery">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Hình ảnh</span>
                <h2 class="section-title">Thư viện ảnh</h2>
            </div>
            <div class="gallery-grid" data-aos="fade-up" data-aos-delay="200">
                <div class="gallery-item">
                    <img src="/public/images/home/khonggianquan.jpg" alt="Không gian quán">
                    <div class="gallery-overlay">
                        <div class="gallery-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="/public/images/products/latte Nóng.jpg" alt="Đồ uống">
                    <div class="gallery-overlay">
                        <div class="gallery-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="/public/images/products/banhngot.jpg" alt="Bánh ngọt">
                    <div class="gallery-overlay">
                        <div class="gallery-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="/public/images/products/phache.jpg" alt="Pha chế">
                    <div class="gallery-overlay">
                        <div class="gallery-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>
    
    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Phản hồi</span>
                <h2 class="section-title">Khách hàng nói gì về chúng tôi</h2>
            </div>
            <div class="swiper testimonial-swiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="testimonial-image">
                                <img src="/public/images/home/testimonial-1.jpg" alt="Khách hàng">
                            </div>
                            <p class="testimonial-text">Cà phê ở đây thật sự rất ngon, không gian thoáng mát và nhân viên phục vụ rất nhiệt tình. Mình sẽ quay lại đây nhiều lần nữa!</p>
                            <h4 class="testimonial-name">Nguyễn Văn A</h4>
                            <p class="testimonial-title">Khách hàng thân thiết</p>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="testimonial-image">
                                <img src="/public/images/home/testimonial-2.jpg" alt="Khách hàng">
                            </div>
                            <p class="testimonial-text">Quán có không gian rất đẹp, phù hợp để làm việc và họp nhóm. Menu đa dạng với nhiều lựa chọn. Đặc biệt trà sữa trân châu rất ngon!</p>
                            <h4 class="testimonial-name">Trần Thị B</h4>
                            <p class="testimonial-title">Sinh viên</p>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="testimonial-image">
                                <img src="/public/images/home/testimonial-3.jpg" alt="Khách hàng">
                            </div>
                            <p class="testimonial-text">Mình rất thích không khí ở đây, yên tĩnh và ấm cúng. Wifi mạnh, phù hợp để làm việc. Cà phê ngon và giá cả phải chăng.</p>
                            <h4 class="testimonial-name">Lê Văn C</h4>
                            <p class="testimonial-title">Nhân viên văn phòng</p>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>
    
    <!-- Contact -->
    <section class="contact">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Liên hệ</span>
                <h2 class="section-title">Kết nối với chúng tôi</h2>
            </div>
            <div class="contact-info" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="contact-label">Địa chỉ</h4>
                    <p class="contact-text">123 Đường ABC, Quận 1, TP.HCM</p>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4 class="contact-label">Điện thoại</h4>
                    <p class="contact-text">0123.456.789</p>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4 class="contact-label">Email</h4>
                    <p class="contact-text">info@t2kcoffee.com</p>
                </div>
            </div>
            <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                <h3 class="form-title">Gửi tin nhắn cho chúng tôi</h3>
                <form action="/contact/submit" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="form-label">Tiêu đề</label>
                        <input type="text" id="subject" name="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Nội dung</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Gửi tin nhắn</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Back to Top -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AOS Animation
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
            
            const backToTop = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    backToTop.classList.add('active');
                } else {
                    backToTop.classList.remove('active');
                }
            });
            
            // Hero Slider
            const heroSwiper = new Swiper('.hero-swiper', {
                loop: true,
                grabCursor: true,
                effect: 'fade',
                speed: 1000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
            
            // Testimonial Slider
            const testimonialSwiper = new Swiper('.testimonial-swiper', {
                loop: true,
                spaceBetween: 30,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
            
            // Add to cart functionality
            const addToCartButtons = document.querySelectorAll('.btn.btn-outline.btn-sm');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    addToCart(productId, 1);
                });
            });

            function addToCart(productId, quantity) {
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Sản phẩm đã được thêm vào giỏ hàng!');
                        // Update cart count if needed
                        updateCartCount();
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi thêm vào giỏ hàng');
                });
            }

            // Function to update cart count
            function updateCartCount() {
                fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartBadge = document.querySelector('.cart-badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.count;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating cart count:', error);
                });
            }

            // Gallery functionality
            const galleryItems = document.querySelectorAll('.gallery-item');
            galleryItems.forEach(item => {
                item.addEventListener('click', function() {
                    const imgSrc = this.querySelector('img').getAttribute('src');
                    openLightbox(imgSrc);
                });
            });

            function openLightbox(src) {
                const lightbox = document.createElement('div');
                lightbox.classList.add('lightbox');
                
                const img = document.createElement('img');
                img.src = src;
                
                const closeBtn = document.createElement('span');
                closeBtn.classList.add('lightbox-close');
                closeBtn.innerHTML = '&times;';
                closeBtn.addEventListener('click', () => {
                    document.body.removeChild(lightbox);
                });
                
                lightbox.appendChild(closeBtn);
                lightbox.appendChild(img);
                document.body.appendChild(lightbox);
                
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        document.body.removeChild(lightbox);
                    }
                });
            }

            // Back to top functionality
            const backToTopBtn = document.getElementById('backToTop');
            backToTopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
<?php include_once('app/Views/components/footer.php'); ?>


