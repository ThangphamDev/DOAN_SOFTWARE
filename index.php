<?php
session_start();

require_once __DIR__.'/vendor/autoload.php';

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

switch ($path) {
    case '/':
        require __DIR__.'/app/Views/home.php';
        break;
        
    case '/menu':
        require __DIR__.'/app/Views/menu/menu.php';
        break;
        
    case '/products':
        require __DIR__.'/app/Views/product/product.php';
        break;
        
    case '/cart':
        require __DIR__.'/app/Views/cart/cart.php';
        break;
        
    case '/login':
        require __DIR__.'/app/Views/auth/login.php';
        break;
        
    case '/checkout':
        require __DIR__.'/app/Views/checkout/checkout.php';
        break;
        
    case '/orders':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require __DIR__.'/app/Views/orders/orders.php';
        break;
        
    case '/profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require __DIR__.'/app/Views/profile/profile.php';
        break;
        
    case '/logout':
        session_destroy();
        header('Location: /');
        exit;
        break;
        
    default:
        http_response_code(404);
        require __DIR__.'/app/Views/404.php';
        break;
} 