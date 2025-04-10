<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($product) ? htmlspecialchars($product->name) . ' - CAFET2K' : 'Chi tiết sản phẩm - CAFET2K'; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
  <link rel="stylesheet" href="/public/css/styles.css">
  <link rel="stylesheet" href="/public/css/style.css">
  <style>
    :root {
      --primary: #8B5A2B;
      --secondary: #D4A762;
      --accent: #4A2C2A;
      --light: #F8F5F0;
      --dark: #2C1D14;
      --border-radius: 12px;
      --box-shadow: 0 8px 30px rgba(0,0,0,0.12);
      --transition: all 0.3s ease;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light);
      color: var(--dark);
    }
    
    .breadcrumb {
      background: transparent;
      padding: 0;
      margin-bottom: 30px;
    }
    
    .breadcrumb-item a {
      color: var(--primary);
      text-decoration: none;
      transition: var(--transition);
    }
    
    .breadcrumb-item a:hover {
      color: var(--secondary);
    }
    
    .breadcrumb-item.active {
      color: var(--accent);
      font-weight: 500;
    }
    
    .breadcrumb-item+.breadcrumb-item::before {
      content: "›";
      color: var(--secondary);
    }
    
    /* Product List Styles */
    .section-title {
      color: var(--accent);
      font-weight: 700;
      position: relative;
      margin-bottom: 30px;
      padding-bottom: 15px;
    }
    
    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 80px;
      height: 4px;
      background: var(--secondary);
      border-radius: 2px;
    }
    
    .filter-bar {
      background: #fff;
      border-radius: var(--border-radius);
      padding: 20px;
      box-shadow: var(--box-shadow);
      margin-bottom: 30px;
    }
    
    .filter-buttons {
      display: flex;
      gap: 10px;
    }
    
    .filter-buttons .btn {
      padding: 8px 16px;
      border-radius: 30px;
      font-weight: 500;
    }
    
    .filter-buttons .btn.active {
      background: var(--primary);
      color: #fff;
    }
    
    .sort-dropdown {
      position: relative;
    }
    
    .sort-dropdown .sort-label {
      font-weight: 500;
      color: var(--accent);
      margin-right: 10px;
    }
    
    .sort-dropdown select {
      border-radius: 30px;
      border: 1px solid #ddd;
      padding: 8px 16px;
      background-color: #fff;
      font-weight: 500;
      appearance: none;
      padding-right: 30px;
      cursor: pointer;
    }
    
    .sort-dropdown::after {
      content: '\f107';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary);
      pointer-events: none;
    }
    
    /* Product Cards */
    .product-card {
      background: #fff;
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: var(--transition);
      height: 100%;
      position: relative;
      display: flex;
      flex-direction: column;
    }
    
    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--box-shadow);
    }
    
    .product-image-wrapper {
      position: relative;
      height: 220px;
      overflow: hidden;
    }
    
    .product-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.8s ease;
    }
    
    .product-card:hover img {
      transform: scale(1.1);
    }
    
    .product-badge {
      position: absolute;
      top: 15px;
      left: 15px;
      background: var(--secondary);
      color: #fff;
      padding: 5px 12px;
      border-radius: 30px;
      font-size: 0.8rem;
      font-weight: 600;
      z-index: 10;
    }
    
    .product-actions {
      margin-bottom: 30px;
      display: flex;
      gap: 15px;
    }
    
    .product-actions .btn {
      padding: 12px 25px;
      border-radius: 30px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .product-actions .btn i {
      font-size: 1.1rem;
    }
    
    .product-actions .btn-primary {
      background: var(--primary);
      border-color: var(--primary);
      color: #fff;
    }
    
    .product-actions .btn-primary:hover {
      background: #7B4A1B;
      border-color: #7B4A1B;
    }
    
    .product-actions .btn-success {
      background: #28a745;
      border-color: #28a745;
      color: #fff;
    }
    
    .product-actions .btn-success:hover {
      background: #218838;
      border-color: #218838;
    }
    
    .product-card .card-body {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex: 1;
    }
    
    .product-category {
      font-size: 0.85rem;
      color: #888;
      margin-bottom: 10px;
    }
    
    .product-card .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--accent);
      margin-bottom: 10px;
      height: 42px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }
    
    .product-card .card-title a {
      color: var(--accent);
      text-decoration: none;
      transition: var(--transition);
    }
    
    .product-card .card-title a:hover {
      color: var(--primary);
    }
    
    .product-rating {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
    }
    
    .rating-stars {
      color: #FFD700;
      font-size: 0.9rem;
    }
    
    .rating-count {
      font-size: 0.85rem;
      color: #666;
    }
    
    .product-pricing {
      margin-top: auto;
      display: flex;
      align-items: baseline;
      justify-content: space-between;
    }
    
    .product-price {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--primary);
    }
    
    .product-pricing .btn {
      padding: 6px 12px;
      border-radius: 30px;
      font-size: 0.9rem;
      font-weight: 500;
    }
    
    /* Product Detail Styles */
    .product-detail-container {
      background: #fff;
      border-radius: var(--border-radius);
      padding: 30px;
      box-shadow: var(--box-shadow);
      margin-bottom: 40px;
    }
    
    .product-gallery {
      position: relative;
      margin-bottom: 30px;
    }
    
    .product-main-image {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .product-main-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .product-main-image:hover img {
      transform: scale(1.05);
    }
    
    .product-thumbs {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }
    
    .product-thumb {
      width: 80px;
      height: 80px;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      border: 2px solid transparent;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .product-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .product-thumb:hover img {
      transform: scale(1.1);
    }
    
    .product-thumb.active {
      border-color: #8B4513;
    }
    
    .product-thumb::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.1);
      opacity: 1;
      transition: opacity 0.3s ease;
    }
    
    .product-thumb.active::after,
    .product-thumb:hover::after {
      opacity: 0;
    }
    
    .product-info .brand {
      color: var(--primary);
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 10px;
      display: block;
    }
    
    .product-info .title {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 20px;
      line-height: 1.3;
    }
    
    .product-meta {
      display: flex;
      align-items: center;
      gap: 30px;
      margin-bottom: 20px;
      color: #666;
      font-size: 0.95rem;
    }
    
    .product-meta-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .product-price-detail {
      margin-bottom: 25px;
    }
    
    .current-price {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin-right: 15px;
    }
    
    .original-price {
      font-size: 1.2rem;
      color: #999;
      text-decoration: line-through;
    }
    
    .price-discount {
      background: #FFEAEA;
      color: #E74C3C;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: 600;
      font-size: 0.85rem;
      margin-left: 15px;
    }
    
    .product-description {
      margin-bottom: 30px;
      color: #666;
      line-height: 1.8;
    }
    
    .variant-options {
      margin-bottom: 30px;
    }
    
    .variant-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--accent);
      margin-bottom: 12px;
    }
    
    .variant-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    
    .variant-btn {
      padding: 8px 18px;
      border-radius: 30px;
      border: 2px solid #eee;
      background: transparent;
      font-size: 0.95rem;
      font-weight: 500;
      color: #555;
      cursor: pointer;
      transition: var(--transition);
    }
    
    .variant-btn.active {
      border-color: var(--primary);
      background: var(--primary);
      color: #fff;
    }
    
    .variant-btn:hover:not(.active) {
      border-color: #ddd;
      background: #f9f9f9;
    }
    
    .quantity-control {
      display: flex;
      align-items: center;
      border: 2px solid #eee;
      border-radius: 30px;
      overflow: hidden;
      width: 150px;
      margin-bottom: 30px;
    }
    
    .quantity-btn {
      width: 45px;
      height: 45px;
      border: none;
      background: transparent;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: #555;
      cursor: pointer;
      transition: var(--transition);
    }
    
    .quantity-btn:hover {
      background: #f5f5f5;
      color: var(--primary);
    }
    
    .quantity-input {
      width: 60px;
      height: 45px;
      border: none;
      text-align: center;
      font-weight: 600;
      color: var(--accent);
    }
    
    .quantity-input:focus {
      outline: none;
    }
    
    .cart-buttons {
      display: flex;
      gap: 15px;
      margin-bottom: 30px;
    }
    
    .cart-buttons .btn {
      padding: 15px 30px;
      border-radius: 30px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .btn-primary {
      background: var(--primary);
      border-color: var(--primary);
    }
    
    .btn-primary:hover {
      background: #7B4A1B;
      border-color: #7B4A1B;
    }
    
    .btn-outline-primary {
      border-color: var(--primary);
      color: var(--primary);
    }
    
    .btn-outline-primary:hover {
      background: var(--primary);
      color: #fff;
    }
    
    .product-share {
      margin-top: 30px;
      padding-top: 30px;
      border-top: 1px solid #eee;
    }
    
    .share-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--accent);
      margin-bottom: 15px;
    }
    
    .social-icons {
      display: flex;
      gap: 15px;
    }
    
    .social-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #555;
      transition: var(--transition);
    }
    
    .social-icon:hover {
      background: var(--primary);
      color: #fff;
    }
    
    /* Product Reviews */
    .reviews-container {
      background: #fff;
      border-radius: var(--border-radius);
      padding: 30px;
      box-shadow: var(--box-shadow);
      margin-bottom: 40px;
    }
    
    .review-summary {
      display: flex;
      align-items: center;
      gap: 40px;
      margin-bottom: 40px;
    }
    
    .review-average {
      text-align: center;
    }
    
    .average-rating {
      font-size: 4rem;
      font-weight: 700;
      color: var(--accent);
      line-height: 1;
    }
    
    .rating-max {
      font-size: 1.2rem;
      color: #999;
    }
    
    .average-stars {
      color: #FFD700;
      font-size: 1.5rem;
      margin-top: 10px;
    }
    
    .review-bars {
      flex: 1;
    }
    
    .review-bar {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 10px;
    }
    
    .review-bar-label {
      width: 30px;
      text-align: right;
      font-weight: 500;
      color: #555;
    }
    
    .review-bar-wrap {
      flex: 1;
      height: 10px;
      background: #eee;
      border-radius: 5px;
      overflow: hidden;
    }
    
    .review-bar-fill {
      height: 100%;
      background: var(--secondary);
    }
    
    .review-bar-count {
      width: 40px;
      color: #999;
      font-size: 0.9rem;
    }
    
    .review-list {
      border-top: 1px solid #eee;
      padding-top: 30px;
    }
    
    .review-item {
      padding-bottom: 30px;
      margin-bottom: 30px;
      border-bottom: 1px solid #eee;
    }
    
    .review-item-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }
    
    .reviewer-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .reviewer-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      overflow: hidden;
      background: #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #999;
      font-size: 1.2rem;
    }
    
    .reviewer-name {
      font-weight: 600;
      color: var(--accent);
    }
    
    .review-date {
      font-size: 0.85rem;
      color: #999;
    }
    
    .review-rating {
      color: #FFD700;
      font-size: 1rem;
    }
    
    .review-content {
      color: #555;
      line-height: 1.7;
    }
    
    .review-photos {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }
    
    .review-photo {
      width: 80px;
      height: 80px;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
    }
    
    .review-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .add-review-form {
      margin-top: 40px;
      padding-top: 30px;
      border-top: 1px solid #eee;
    }
    
    .form-rating {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 20px;
    }
    
    .rating-label {
      font-weight: 600;
      color: var(--accent);
    }
    
    .star-rating {
      display: flex;
      flex-direction: row-reverse;
      gap: 5px;
    }
    
    .star-rating input {
      display: none;
    }
    
    .star-rating label {
      cursor: pointer;
      color: #ddd;
      font-size: 1.5rem;
    }
    
    .star-rating input:checked ~ label {
      color: #FFD700;
    }
    
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #FFD700;
    }
    
    /* Related Products */
    .related-products {
      margin-top: 60px;
    }
    
    .product-slider {
      margin: 0 -15px;
    }
    
    .product-slider .slick-slide {
      padding: 15px;
    }
    
    .product-slider .slick-arrow {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      z-index: 10;
    }
    
    .product-slider .slick-arrow:hover {
      background: var(--primary);
      color: #fff;
    }
    
    .product-slider .slick-prev {
      left: -15px;
    }
    
    .product-slider .slick-next {
      right: -15px;
    }
    
    /* Quick View Modal */
    .modal-quick-view .modal-dialog {
      max-width: 1000px;
    }
    
    .modal-quick-view .modal-content {
      border-radius: var(--border-radius);
      border: none;
      overflow: hidden;
    }
    
    .modal-quick-view .modal-body {
      padding: 0;
    }
    
    .modal-quick-view .quick-view-content {
      display: flex;
    }
    
    .quick-view-gallery {
      width: 45%;
      background: #f9f9f9;
      padding: 30px;
    }
    
    .quick-view-info {
      width: 55%;
      padding: 30px;
    }
    
    .modal-close {
      position: absolute;
      top: 15px;
      right: 15px;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      background: #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #555;
      z-index: 10;
      cursor: pointer;
      transition: var(--transition);
    }
    
    .modal-close:hover {
      background: var(--primary);
      color: #fff;
    }
    
    /* Responsive Styles */
    @media (max-width: 1199px) {
      .product-image-wrapper {
        height: 200px;
      }
      
      .current-price {
        font-size: 1.8rem;
      }
      
      .product-info .title {
        font-size: 2rem;
      }
    }
    
    @media (max-width: 991px) {
      .product-image-wrapper {
        height: 180px;
      }
      
      .product-detail-container {
        padding: 20px;
      }
      
      .product-info .title {
        font-size: 1.8rem;
      }
      
      .current-price {
        font-size: 1.6rem;
      }
      
      .cart-buttons .btn {
        padding: 12px 25px;
      }
      
      .modal-quick-view .quick-view-content {
        flex-direction: column;
      }
      
      .quick-view-gallery, .quick-view-info {
        width: 100%;
      }
    }
    
    @media (max-width: 767px) {
      .filter-bar {
        flex-direction: column;
        gap: 15px;
      }
      
      .sort-dropdown {
        width: 100%;
      }
      
      .sort-dropdown select {
        width: 100%;
      }
      
      .product-image-wrapper {
        height: 200px;
      }
      
      .product-info .title {
        font-size: 1.5rem;
      }
      
      .current-price {
        font-size: 1.4rem;
      }
      
      .cart-buttons {
        flex-direction: column;
      }
      
      .review-summary {
        flex-direction: column;
        gap: 20px;
      }
      
      .product-thumb {
        width: 60px;
        height: 60px;
      }
    }
    
    @media (max-width: 575px) {
      .product-image-wrapper {
        height: 180px;
      }
      
      .product-info .title {
        font-size: 1.3rem;
      }
      
      .product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .product-thumb {
        width: 60px;
        height: 60px;
      }
    }
    
    /* Animation Classes */
    .animate-fadein {
      animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    /* Mobile Fixed Bar */
    .mobile-fixed-bar {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: #fff;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      padding: 15px;
      z-index: 1000;
    }
    
    .mobile-fixed-bar .btn {
      padding: 12px 20px;
      border-radius: 30px;
      font-weight: 600;
    }
    
    @media (max-width: 767px) {
      .mobile-fixed-bar {
        display: flex;
        gap: 10px;
      }
      
      .mobile-fixed-bar .btn {
        flex: 1;
      }
      
      body {
        padding-bottom: 80px;
      }
    }

    /* No Reviews */
    .no-reviews {
      text-align: center;
      padding: 30px 0;
      background: #f8f9fa;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .no-reviews i {
      font-size: 3rem;
      color: #adb5bd;
      margin-bottom: 15px;
    }

    .no-reviews p {
      margin: 5px 0;
      color: #6c757d;
    }

    .no-reviews p:first-of-type {
      font-size: 1.1rem;
      font-weight: 500;
      color: #495057;
    }

    /* Thêm phần CSS cho thông báo */
    .notification {
      position: fixed;
      top: 80px;
      right: 20px;
      padding: 12px 20px;
      border-radius: 6px;
      background-color: #ffffff;
      color: #333;
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
      z-index: 10000;
      display: flex;
      align-items: center;
      transform: translateX(120%);
      transition: transform 0.3s ease-in-out;
      max-width: 400px;
      width: 90%;
    }

    .notification.show {
      transform: translateX(0);
    }

    .notification.success {
      border-left: 4px solid #28a745;
    }

    .notification.error {
      border-left: 4px solid #dc3545;
    }

    .notification i {
      margin-right: 12px;
      font-size: 18px;
    }

    .notification.success i {
      color: #28a745;
    }

    .notification.error i {
      color: #dc3545;
    }

    .notification-content {
      flex-grow: 1;
    }

    .notification-title {
      font-weight: 600;
      margin-bottom: 3px;
      font-size: 15px;
    }

    .notification-message {
      font-size: 13px;
      margin: 0;
      line-height: 1.4;
    }

    .notification-close {
      margin-left: 10px;
      background: none;
      border: none;
      color: #999;
      cursor: pointer;
      font-size: 16px;
    }

    .notification-close:hover {
      color: #555;
    }

    /* Số lượng giỏ hàng */
    .cart-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .cart-count.update {
      animation: pulse 0.5s ease-in-out;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.3); }
      100% { transform: scale(1); }
    }
  </style>
</head>
<body>
  <!-- Sample Navigation Bar -->
  <?php include "app/Views/components/header.php"; ?>

  <!-- Main Content -->
  <div class="container my-5 content-container">
    
    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/menu">Menu</a></li>
        <?php if (isset($product) && isset($product->category_id) && isset($product->category_name)): ?>
        <li class="breadcrumb-item"><a href="/menu/category/<?php echo $product->category_id; ?>"><?php echo htmlspecialchars($product->category_name); ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo isset($product) ? htmlspecialchars($product->name) : 'Chi tiết sản phẩm'; ?></li>
      </ol>
    </nav>

    <!-- Product Detail Layout -->
    <div class="product-detail-container">
      <div class="row">
        <!-- Product Image -->
        <div class="col-md-5">
          <div class="product-gallery">
            <div class="product-main-image" style="display: flex; justify-content: center; align-items: center; border: 1px solid #ddd; background: white;">
              <?php
              // Kiểm tra và gán giá trị mặc định cho $product
              if (!isset($product)) {
                $product = new stdClass();
                $product->image_url = '';
                $product->name = 'Sản phẩm';
                $product->gallery = [];
                $product->base_price = 0;
                $product->description = 'Không có mô tả';
                $product->product_id = 0;
              }
              
              // Đảm bảo đường dẫn ảnh đúng
              $image_url = $product->image_url;

              // Kiểm tra nếu đường dẫn ảnh không rỗng thì mới xử lý
              if (!empty($image_url)) {
                // Kiểm tra nếu đường dẫn ảnh không bắt đầu bằng / thì thêm vào
                if (!str_starts_with($image_url, '/')) {
                  $image_url = '/' . $image_url;
                }
                
                // Kiểm tra nếu đường dẫn không bắt đầu bằng /public thì thêm vào
                if (!str_starts_with($image_url, '/public')) {
                  $image_url = '/public' . $image_url;
                }
              }

              // In ra đường dẫn ảnh để debug
              echo "<!-- Debug: image_url = " . htmlspecialchars($image_url) . " -->";
              ?>
              <?php if (!empty($image_url)): ?>
              <img src="<?php echo htmlspecialchars($image_url); ?>" 
                   class="img-fluid" 
                   alt="<?php echo htmlspecialchars($product->name); ?>"
                   id="mainImage"
                   data-zoom-image="<?php echo htmlspecialchars($image_url); ?>"
                   style="max-height: 400px; max-width: 100%; object-fit: contain;"
                   >
              <?php endif; ?>
            </div>
            <div class="product-thumbs">
              <?php if (isset($product->gallery) && is_array($product->gallery) && !empty($product->gallery)): ?>
                <?php foreach ($product->gallery as $index => $image): ?>
                  <div class="product-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                       data-image="<?php echo htmlspecialchars($image->url); ?>"
                       data-zoom-image="<?php echo htmlspecialchars($image->url); ?>">
                    <img src="<?php echo htmlspecialchars($image->url); ?>" 
                         alt="<?php echo htmlspecialchars($product->name) . ' - ' . ($index + 1); ?>">
                  </div>
                <?php endforeach; ?>
              <?php elseif (!empty($image_url)): ?>
                <!-- Fallback thumbnail if no gallery images but has main image -->
                <div class="product-thumb active">
                  <img src="<?php echo htmlspecialchars($image_url); ?>" 
                       alt="<?php echo htmlspecialchars($product->name); ?>">
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-md-7">
          <div class="product-info">
            <span class="brand">The Coffee House</span>
            <h1 class="title"><?php echo isset($product) ? htmlspecialchars($product->name) : 'Sản phẩm'; ?></h1>
            
            <div class="product-meta">
              <div class="product-meta-item">
                <i class="fas fa-star text-warning"></i>
                <span><?php echo isset($averageRating) ? number_format($averageRating, 1) : '0.0'; ?> (<?php echo isset($reviewCount) ? $reviewCount : 0; ?> đánh giá)</span>
              </div>
              <div class="product-meta-item">
                <i class="fas fa-shopping-basket"></i>
                <span><?php echo isset($product) && isset($product->sold_count) ? $product->sold_count : 0; ?> đã bán</span>
              </div>
              <div class="product-meta-item">
                <i class="fas fa-eye"></i>
                <span><?php echo isset($product) && isset($product->view_count) ? $product->view_count : 0; ?> lượt xem</span>
              </div>
            </div>
            
            <div class="product-price-detail">
              <span class="current-price"><?php echo isset($product) ? number_format($product->base_price, 0, ',', '.') : '0'; ?>đ</span>
              <?php if (isset($product) && isset($product->original_price) && $product->original_price > $product->base_price): ?>
                <span class="original-price"><?php echo number_format($product->original_price, 0, ',', '.'); ?>đ</span>
                <span class="price-discount">Giảm <?php echo round(($product->original_price - $product->base_price) / $product->original_price * 100); ?>%</span>
              <?php endif; ?>
            </div>
            
            <div class="product-description">
              <p><?php echo isset($product) ? htmlspecialchars($product->description) : ''; ?></p>
            </div>
            
            <!-- Ẩn input product_id -->
            <input type="hidden" name="product_id" value="<?php echo isset($product) ? $product->product_id : 0; ?>">
            
            <?php 
            // Khởi tạo mảng để lưu các loại biến thể
            $variantTypes = [];
            
            // Nếu có biến thể
            if (isset($variants) && $variants->rowCount() > 0): 
                // Tạo mảng để lưu trữ biến thể theo loại
                $variantsByType = [];
                $variants->execute(); // Reset con trỏ
                
                // Lặp qua từng biến thể và phân loại
                while($variant = $variants->fetch(PDO::FETCH_ASSOC)): 
                    $variantType = strtolower($variant['variant_type']);
                    if (!isset($variantsByType[$variantType])) {
                        $variantsByType[$variantType] = [];
                        $variantTypes[] = $variantType;
                    }
                    $variantsByType[$variantType][] = $variant;
                endwhile;
                
                // Hiển thị cho từng loại biến thể
                foreach ($variantTypes as $type):
                    if (isset($variantsByType[$type]) && !empty($variantsByType[$type])):
            ?>
            <div class="variant-options">
                <h5 class="variant-title"><?php echo ucfirst($type); ?></h5>
                <div class="variant-buttons">
                    <?php 
                    $firstVariant = true;
                    foreach ($variantsByType[$type] as $variant): 
                    ?>
                    <button type="button" class="variant-btn <?php echo $firstVariant ? 'active' : ''; ?>" 
                            data-type="<?php echo htmlspecialchars($variant['variant_type']); ?>"
                            data-value="<?php echo htmlspecialchars($variant['variant_value']); ?>" 
                            data-price="<?php echo $variant['additional_price']; ?>">
                        <?php echo htmlspecialchars($variant['variant_value']); ?> 
                        <?php if ($variant['additional_price'] > 0): ?>
                        (+<?php echo number_format($variant['additional_price'], 0, ',', '.'); ?>đ)
                        <?php endif; ?>
                    </button>
                    <?php 
                        $firstVariant = false;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php 
                    endif;
                endforeach;
            endif;
            ?>
            
            <div class="d-flex align-items-center mb-4">
              <h5 class="variant-title mb-0 me-3">Số lượng:</h5>
              <div class="quantity-control">
                <button type="button" class="quantity-btn" id="decreaseQuantity">
                  <i class="fas fa-minus"></i>
                </button>
                <input type="number" id="quantity" class="quantity-input" value="1" min="1">
                <button type="button" class="quantity-btn" id="increaseQuantity">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            
            <div class="product-actions">
              <form action="/cart/add" method="POST" class="add-to-cart-form w-100">
                <input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($product->name); ?>">
                <input type="hidden" name="price" value="<?php echo $product->base_price; ?>">
                <input type="hidden" name="image_url" value="<?php echo htmlspecialchars($product->image_url); ?>">
                <input type="hidden" name="quantity" id="form-quantity" value="1">
                <input type="hidden" name="ajax" value="1">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
                
                <?php if (isset($variantsByType) && !empty($variantsByType)): ?>
                  <?php foreach ($variantsByType as $type => $variants): ?>
                    <?php if (isset($variants[0])): ?>
                      <input type="hidden" class="variant-input" 
                             name="variants[<?php echo $type; ?>][value]" 
                             value="<?php echo htmlspecialchars($variants[0]['variant_value']); ?>">
                      <input type="hidden" class="variant-input" 
                             name="variants[<?php echo $type; ?>][price]" 
                             value="<?php echo $variants[0]['additional_price']; ?>">
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="d-flex gap-3">
                  <button type="submit" class="btn btn-primary flex-grow-1" id="addToCartBtn">
                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                  </button>
                  <button type="button" class="btn btn-success flex-grow-1" id="buyNowBtn">
                    <i class="fas fa-bolt"></i> Mua ngay
                  </button>
                </div>
              </form>
            </div>
            
            <div class="product-share">
              <h5 class="share-title">Chia sẻ sản phẩm:</h5>
              <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Product Reviews -->
    <div class="reviews-container">
      <h3 class="section-title">Đánh giá sản phẩm</h3>
      
      <?php if (isset($reviews) && $reviews && $reviews->rowCount() > 0): ?>
      <div class="review-summary">
        <div class="review-average">
          <div class="average-rating"><?php echo number_format($averageRating, 1); ?></div>
          <div class="rating-max">/ 5</div>
          <div class="average-stars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <?php if ($i <= floor($averageRating)): ?>
                <i class="fas fa-star"></i>
              <?php elseif ($i - 0.5 <= $averageRating): ?>
                <i class="fas fa-star-half-alt"></i>
              <?php else: ?>
                <i class="far fa-star"></i>
              <?php endif; ?>
            <?php endfor; ?>
          </div>
          <div class="review-count">Dựa trên <?php echo $reviewCount; ?> đánh giá</div>
        </div>
        
        <div class="review-bars">
          <div class="review-bar">
            <span class="review-bar-label">5</span>
            <div class="review-bar-wrap">
              <div class="review-bar-fill" style="width: 85%"></div>
            </div>
            <span class="review-bar-count">102</span>
          </div>
          <div class="review-bar">
            <span class="review-bar-label">4</span>
            <div class="review-bar-wrap">
              <div class="review-bar-fill" style="width: 10%"></div>
            </div>
            <span class="review-bar-count">12</span>
          </div>
          <div class="review-bar">
            <span class="review-bar-label">3</span>
            <div class="review-bar-wrap">
              <div class="review-bar-fill" style="width: 4%"></div>
            </div>
            <span class="review-bar-count">5</span>
          </div>
          <div class="review-bar">
            <span class="review-bar-label">2</span>
            <div class="review-bar-wrap">
              <div class="review-bar-fill" style="width: 1%"></div>
            </div>
            <span class="review-bar-count">1</span>
          </div>
          <div class="review-bar">
            <span class="review-bar-label">1</span>
            <div class="review-bar-wrap">
              <div class="review-bar-fill" style="width: 0%"></div>
            </div>
            <span class="review-bar-count">0</span>
          </div>
        </div>
      </div>
      
      <div class="review-list">
        <?php 
        $reviews->execute(); // Reset pointer to be safe
        while($review = $reviews->fetch(PDO::FETCH_ASSOC)): 
        ?>
        <div class="review-item">
          <div class="review-item-header">
            <div class="reviewer-info">
              <div class="reviewer-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div>
                <div class="reviewer-name"><?php echo htmlspecialchars($review['full_name'] ?? ($review['username'] ?? 'Khách hàng')); ?></div>
                <div class="review-date"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></div>
              </div>
            </div>
            <div class="review-rating">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <?php if ($i <= $review['rating']): ?>
                  <i class="fas fa-star"></i>
                <?php else: ?>
                  <i class="far fa-star"></i>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
          </div>
          <div class="review-content">
            <p><?php echo htmlspecialchars($review['comment']); ?></p>
          </div>
          <?php if (!empty($review['images'])): ?>
          <div class="review-photos">
            <?php 
            $images = explode(',', $review['images']);
            foreach($images as $image): 
            ?>
            <div class="review-photo">
              <img src="<?php echo htmlspecialchars($image); ?>" alt="Review photo">
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endwhile; ?>
      </div>
      <?php else: ?>
      <div class="no-reviews">
        <i class="far fa-comments"></i>
        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        <p>Hãy là người đầu tiên đánh giá!</p>
      </div>
      <?php endif; ?>
      
      <div class="add-review-form">
        <h4 class="mb-4">Thêm đánh giá của bạn</h4>
        <form>
          <div class="form-rating mb-3">
            <span class="rating-label">Đánh giá:</span>
            <div class="star-rating">
              <input type="radio" id="star5" name="rating" value="5"/>
              <label for="star5"><i class="fas fa-star"></i></label>
              <input type="radio" id="star4" name="rating" value="4"/>
              <label for="star4"><i class="fas fa-star"></i></label>
              <input type="radio" id="star3" name="rating" value="3"/>
              <label for="star3"><i class="fas fa-star"></i></label>
              <input type="radio" id="star2" name="rating" value="2"/>
              <label for="star2"><i class="fas fa-star"></i></label>
              <input type="radio" id="star1" name="rating" value="1"/>
              <label for="star1"><i class="fas fa-star"></i></label>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="reviewTitle" class="form-label">Tiêu đề đánh giá</label>
            <input type="text" class="form-control" id="reviewTitle" placeholder="Nhập tiêu đề đánh giá">
          </div>
          
          <div class="mb-3">
            <label for="reviewContent" class="form-label">Nội dung đánh giá</label>
            <textarea class="form-control" id="reviewContent" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm"></textarea>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Thêm ảnh (tùy chọn)</label>
            <input type="file" class="form-control" multiple accept="image/*">
          </div>
          
          <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
        </form>
      </div>
    </div>
    
    <!-- Related Products -->
    <div class="related-products">
      <h3 class="section-title">Sản phẩm liên quan</h3>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
        <?php if (isset($relatedProducts) && $relatedProducts->rowCount() > 0):
          while($related = $relatedProducts->fetch(PDO::FETCH_ASSOC)): 
            // Xử lý đường dẫn ảnh cho sản phẩm liên quan
            $relatedImageUrl = $related['image_url'] ?? '';
            if (!empty($relatedImageUrl)) {
              if (!str_starts_with($relatedImageUrl, '/')) {
                $relatedImageUrl = '/' . $relatedImageUrl;
              }
              if (!str_starts_with($relatedImageUrl, '/public')) {
                $relatedImageUrl = '/public' . $relatedImageUrl;
              }
            }
          ?>
        <div class="col">
          <div class="card product-card h-100">
            <div class="product-image-wrapper">
              <?php if (!empty($relatedImageUrl)): ?>
              <img src="<?php echo htmlspecialchars($relatedImageUrl); ?>" 
                   class="card-img-top" 
                   alt="<?php echo htmlspecialchars($related['name']); ?>">
              <?php endif; ?>
              <?php if (isset($related['is_bestseller']) && $related['is_bestseller']): ?>
              <div class="product-badge">Bestseller</div>
              <?php elseif (isset($related['is_new']) && $related['is_new']): ?>
              <div class="product-badge">Mới</div>
              <?php endif; ?>
              <div class="product-actions">
                <button class="btn" title="Thêm vào yêu thích"><i class="far fa-heart"></i></button>
                <button class="btn" title="Xem nhanh"><i class="far fa-eye"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="product-category"><?php echo htmlspecialchars($related['category_name'] ?? 'Cà phê'); ?></div>
              <h5 class="card-title"><a href="/menu/product/<?php echo $related['product_id']; ?>"><?php echo htmlspecialchars($related['name']); ?></a></h5>
              <div class="product-rating">
                <div class="rating-stars">
                  <?php
                  $rating = isset($related['avg_rating']) ? floatval($related['avg_rating']) : 0;
                  for ($i = 1; $i <= 5; $i++):
                    if ($i <= floor($rating)): ?>
                      <i class="fas fa-star"></i>
                    <?php elseif ($i - 0.5 <= $rating): ?>
                      <i class="fas fa-star-half-alt"></i>
                    <?php else: ?>
                      <i class="far fa-star"></i>
                    <?php endif;
                  endfor; ?>
                </div>
                <div class="rating-count"><?php echo number_format($rating, 1); ?> (<?php echo $related['review_count'] ?? 0; ?>)</div>
              </div>
              <div class="product-pricing">
                <div class="product-price"><?php echo number_format($related['base_price'] ?? $related['price'] ?? 0, 0, ',', '.'); ?>đ</div>
                <button class="btn btn-sm btn-outline-primary add-to-cart-btn" data-product-id="<?php echo $related['product_id']; ?>">Thêm vào giỏ</button>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; else: ?>
        <div class="col-12 text-center py-4">
          <p>Không có sản phẩm liên quan</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Mobile Fixed Bar -->
  <div class="mobile-fixed-bar">
    <button type="button" class="btn btn-outline-primary flex-grow-1" id="mobileAddToCartBtn">
      <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
    </button>
    <button type="button" class="btn btn-success flex-grow-1" id="mobileBuyNowBtn">
      <i class="fas fa-bolt"></i> Mua ngay
    </button>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h5 class="mb-4">Về chúng tôi</h5>
          <p>Coffee Shop là điểm đến lý tưởng cho những người yêu thích cà phê. Chúng tôi cung cấp các loại cà phê chất lượng cao cùng với không gian thoải mái để bạn có thể thưởng thức.</p>
        </div>
        <div class="col-md-2 mb-4 mb-md-0">
          <h5 class="mb-4">Liên kết</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="/" class="text-light text-decoration-none">Trang chủ</a></li>
            <li class="mb-2"><a href="/products" class="text-light text-decoration-none">Sản phẩm</a></li>
            <li class="mb-2"><a href="/about" class="text-light text-decoration-none">Giới thiệu</a></li>
            <li class="mb-2"><a href="/contact" class="text-light text-decoration-none">Liên hệ</a></li>
          </ul>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
          <h5 class="mb-4">Liên hệ</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
            <li class="mb-2"><i class="fas fa-phone me-2"></i> (028) 1234 5678</li>
            <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@coffeeshop.com</li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5 class="mb-4">Đăng ký nhận tin</h5>
          <form>
            <div class="input-group mb-3">
              <input type="email" class="form-control" placeholder="Email của bạn" required>
              <button class="btn btn-primary" type="submit">Đăng ký</button>
            </div>
          </form>
          <div class="social-links mt-4">
            <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-light"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light">
      <div class="row">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0">&copy; 2025 Coffee Shop. Tất cả quyền được bảo lưu.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#" class="text-light text-decoration-none me-3">Điều khoản sử dụng</a>
          <a href="#" class="text-light text-decoration-none">Chính sách bảo mật</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- JavaScript Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Khởi tạo các biến cần thiết
      const productId = <?php echo isset($product) ? $product->product_id : 0; ?>;
      const basePrice = <?php echo isset($product) ? $product->base_price : 0; ?>;
      const quantityInput = document.getElementById('quantity');
      const selectedVariants = {};
      let totalAdditionalPrice = 0;

      // Lưu trạng thái ban đầu của các variant đã được chọn
      document.querySelectorAll('.variant-btn.active').forEach(btn => {
        const type = btn.dataset.type;
        const value = btn.dataset.value;
        const price = parseFloat(btn.dataset.price || 0);
        
        selectedVariants[type] = {
          value: value,
          price: price
        };
        
        totalAdditionalPrice += price;
      });
      
      // Cập nhật hiển thị giá
      updatePriceDisplay();
      
      // Xử lý khi click vào các nút tùy chọn biến thể
      document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const type = this.dataset.type;
          const value = this.dataset.value;
          const price = parseFloat(this.dataset.price || 0);
          
          // Bỏ active trạng thái cũ
          document.querySelectorAll(`.variant-btn[data-type="${type}"]`).forEach(b => {
            b.classList.remove('active');
          });
          
          // Active trạng thái mới
          this.classList.add('active');
          
          // Trừ giá cũ nếu có
          if (selectedVariants[type]) {
            totalAdditionalPrice -= selectedVariants[type].price;
          }
          
          // Cập nhật với giá mới
          selectedVariants[type] = {
            value: value,
            price: price
          };
          
          totalAdditionalPrice += price;
          
          // Cập nhật input hidden tương ứng
          let valueInput = document.querySelector(`.variant-input[name="variants[${type}][value]"]`);
          let priceInput = document.querySelector(`.variant-input[name="variants[${type}][price]"]`);
          
          if (!valueInput) {
            valueInput = document.createElement('input');
            valueInput.type = 'hidden';
            valueInput.className = 'variant-input';
            valueInput.name = `variants[${type}][value]`;
            document.querySelector('.add-to-cart-form').appendChild(valueInput);
          }
          
          if (!priceInput) {
            priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.className = 'variant-input';
            priceInput.name = `variants[${type}][price]`;
            document.querySelector('.add-to-cart-form').appendChild(priceInput);
          }
          
          valueInput.value = value;
          priceInput.value = price;
          
          // Cập nhật hiển thị giá
          updatePriceDisplay();
        });
      });

      // Cập nhật hiển thị giá
      function updatePriceDisplay() {
        const currentPriceElement = document.querySelector('.current-price');
        if (currentPriceElement) {
          const totalPrice = basePrice + totalAdditionalPrice;
          currentPriceElement.innerText = formatCurrency(totalPrice) + 'đ';
        }
      }
      
      // Định dạng tiền tệ
      function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      // Khởi tạo các nút
      const addToCartBtn = document.getElementById('addToCartBtn');
      const buyNowBtn = document.getElementById('buyNowBtn');
      const mobileAddToCartBtn = document.getElementById('mobileAddToCartBtn');
      const mobileBuyNowBtn = document.getElementById('mobileBuyNowBtn');
      const decreaseBtn = document.getElementById('decreaseQuantity');
      const increaseBtn = document.getElementById('increaseQuantity');

      // Xử lý sự kiện khi thay đổi số lượng
      quantityInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
          this.value = 1;
        } else if (value > 10) {
          this.value = 10;
        }
        
        // Cập nhật giá trị trong form
        document.getElementById('form-quantity').value = this.value;
      });

      // Xử lý sự kiện cho nút tăng/giảm số lượng
      if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
          let current = parseInt(quantityInput.value);
          if (current > 1) {
            quantityInput.value = current - 1;
            document.getElementById('form-quantity').value = current - 1;
          }
        });
      }

      if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
          let current = parseInt(quantityInput.value);
          if (current < 10) {
            quantityInput.value = current + 1;
            document.getElementById('form-quantity').value = current + 1;
          }
        });
      }

      // Xử lý form thêm vào giỏ hàng
      const addToCartForm = document.querySelector('.add-to-cart-form');
      if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Cập nhật giá trị quantity
          document.getElementById('form-quantity').value = quantityInput.value;
          
          // Lấy dữ liệu từ form
          const formData = {
            product_id: parseInt(this.querySelector('input[name="product_id"]').value),
            name: this.querySelector('input[name="name"]').value,
            price: parseFloat(this.querySelector('input[name="price"]').value),
            image_url: this.querySelector('input[name="image_url"]').value,
            quantity: parseInt(this.querySelector('input[name="quantity"]').value),
            ajax: 1,
            variants: []
          };
          
          // Thêm các biến thể đã chọn vào mảng variants
          for (const type in selectedVariants) {
            formData.variants.push({
              type: type,
              value: selectedVariants[type].value,
              price: selectedVariants[type].price
            });
          }
          
          // Log để debug
          console.log('Form data:', formData);
          console.log('Selected variants:', selectedVariants);
          
          // Gửi request đến endpoint
          fetch('/cart/add', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
          })
          .then(response => {
            console.log('Status code:', response.status);
            return response.json();
          })
          .then(data => {
            console.log('Response data:', data);
            if (data.success) {
              // Cập nhật số lượng giỏ hàng
              updateCartCount(data.cart_count);
              
              // Hiển thị thông báo thành công
              showToast('success', `Đã thêm ${quantityInput.value} sản phẩm vào giỏ hàng`);
            } else {
              // Hiển thị thông báo lỗi
              showToast('error', data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
          });
        });
      }

      // Xử lý sự kiện cho nút mua ngay
      if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
          // Cập nhật giá trị quantity
          document.getElementById('form-quantity').value = quantityInput.value;
          
          // Lấy dữ liệu từ form
          const formData = {
            product_id: parseInt(addToCartForm.querySelector('input[name="product_id"]').value),
            name: addToCartForm.querySelector('input[name="name"]').value,
            price: parseFloat(addToCartForm.querySelector('input[name="price"]').value),
            image_url: addToCartForm.querySelector('input[name="image_url"]').value,
            quantity: parseInt(addToCartForm.querySelector('input[name="quantity"]').value),
            ajax: 1,
            variants: []
          };
          
          // Thêm các biến thể đã chọn vào mảng variants
          for (const type in selectedVariants) {
            formData.variants.push({
              type: type,
              value: selectedVariants[type].value,
              price: selectedVariants[type].price
            });
          }
          
          // Thêm tham số redirect để chuyển đến trang thanh toán
          formData.redirect = 'checkout';
          
          // Log để debug
          console.log('Buy now data:', formData);
          
          // Gửi request đến endpoint
          fetch('/cart/add', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
          })
          .then(response => {
            console.log('Status code:', response.status);
            return response.json();
          })
          .then(data => {
            console.log('Response data:', data);
            if (data.success) {
              // Chuyển đến trang thanh toán
              window.location.href = '/checkout';
            } else {
              showToast('error', data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
          });
        });
      }

      // Cập nhật hiển thị số lượng giỏ hàng
      function updateCartCount(count) {
        console.log('Updating cart count to:', count);
        
        // Tìm tất cả các badge số lượng giỏ hàng
        document.querySelectorAll('.cart-badge, .cart-count').forEach(badge => {
          if (badge) {
            badge.textContent = count;
            badge.classList.add('update');
            setTimeout(() => {
              badge.classList.remove('update');
            }, 500);
          }
        });
      }
      
      // Hiển thị toast notification
      function showToast(type, message) {
        // Tạo container cho toast nếu chưa có
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
          toastContainer = document.createElement('div');
          toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
          document.body.appendChild(toastContainer);
        }
        
        // Tạo toast element
        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'}`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastElement);
        
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Xóa toast sau khi ẩn
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
      }

      // Xử lý ảnh thumbnail khi click
      document.querySelectorAll('.product-thumb').forEach(thumb => {
        thumb.addEventListener('click', function() {
          const mainImage = document.getElementById('mainImage');
          const imageUrl = this.dataset.image;
          const zoomImageUrl = this.dataset.zoomImage;
          
          mainImage.src = imageUrl;
          mainImage.dataset.zoomImage = zoomImageUrl;
          
          document.querySelectorAll('.product-thumb').forEach(t => {
            t.classList.remove('active');
          });
          
          this.classList.add('active');
        });
      });
    });
  </script>
</body>
</html>