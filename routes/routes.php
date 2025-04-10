<?php
// Định nghĩa các routes
$routes = [
    // Trang chủ
    '/' => [
        'controller' => 'HomeController',
        'action' => 'index'
    ],
    
    // Auth routes
    '/login' => [
        'controller' => 'AuthController',
        'action' => 'loginForm'
    ],
    '/login/submit' => [
        'controller' => 'AuthController',
        'action' => 'login',
        'method' => 'POST'
    ],
    '/register' => [
        'controller' => 'AuthController',
        'action' => 'registerForm'
    ],
    '/register/submit' => [
        'controller' => 'AuthController',
        'action' => 'register',
        'method' => 'POST'
    ],
    '/logout' => [
        'controller' => 'AuthController',
        'action' => 'logout'
    ],
    
    // Menu
    '/menu' => [
        'controller' => 'MenuController',
        'action' => 'index'
    ],
    
    // Product routes
    '/products' => [
        'controller' => 'ProductController',
        'action' => 'index'
    ],
    '/products/create' => [
        'controller' => 'ProductController',
        'action' => 'create'
    ],
    '/products/store' => [
        'controller' => 'ProductController',
        'action' => 'store',
        'method' => 'POST'
    ],
    '/products/{id}' => [
        'controller' => 'ProductController',
        'action' => 'show'
    ],
    '/products/{id}/edit' => [
        'controller' => 'ProductController',
        'action' => 'edit'
    ],
    '/products/{id}/update' => [
        'controller' => 'ProductController',
        'action' => 'update',
        'method' => 'POST'
    ],
    '/products/{id}/delete' => [
        'controller' => 'ProductController',
        'action' => 'delete'
    ],
    
    // Cart routes
    '/cart' => [
        'controller' => 'CartController',
        'action' => 'index'
    ],
    '/cart/add' => [
        'controller' => 'CartController',
        'action' => 'add',
        'method' => 'POST'
    ],
    '/cart/update' => [
        'controller' => 'CartController',
        'action' => 'update',
        'method' => 'POST'
    ],
    '/cart/remove' => [
        'controller' => 'CartController',
        'action' => 'remove',
        'method' => 'POST'
    ],
    
    // Checkout routes
    '/checkout' => [
        'controller' => 'OrderController',
        'action' => 'checkout'
    ],
    '/checkout/process' => [
        'controller' => 'OrderController',
        'action' => 'store',
        'method' => 'POST'
    ],
    '/checkout/success' => [
        'controller' => 'OrderController',
        'action' => 'success'
    ],
    
    // Order routes
    '/orders' => [
        'controller' => 'OrderController',
        'action' => 'index'
    ],
    '/orders/{id}' => [
        'controller' => 'OrderController',
        'action' => 'show'
    ],
    '/orders/view/{id}' => [
        'controller' => 'OrderController',
        'action' => 'viewOrder',
        'pattern' => '/orders/view/([0-9]+)'
    ],
    '/orders/cancel/{id}' => [
        'controller' => 'OrderController',
        'action' => 'cancelOrder',
        'pattern' => '/orders/cancel/([0-9]+)'
    ],
    
    // Profile routes
    '/profile' => [
        'controller' => 'ProfileController',
        'action' => 'index'
    ],
    '/profile/update' => [
        'controller' => 'ProfileController',
        'action' => 'update',
        'method' => 'POST'
    ],
    '/profile/settings' => [
        'controller' => 'ProfileController',
        'action' => 'updateSettings',
        'method' => 'POST'
    ],
    '/profile/address/add' => [
        'controller' => 'ProfileController',
        'action' => 'addAddress',
        'method' => 'POST'
    ],
    '/profile/address/delete/{id}' => [
        'controller' => 'ProfileController',
        'action' => 'deleteAddress'
    ],
    '/profile/address/default/{id}' => [
        'controller' => 'ProfileController',
        'action' => 'setDefaultAddress'
    ],
    
    // Admin routes
    '/admin' => [
        'controller' => 'AdminController',
        'action' => 'dashboard'
    ],
    
    // Admin Product routes
    '/admin/products' => [
        'controller' => 'AdminController',
        'action' => 'products'
    ],
    '/admin/products/create' => [
        'controller' => 'AdminController',
        'action' => 'createProduct'
    ],
    '/admin/products/store' => [
        'controller' => 'AdminController',
        'action' => 'storeProduct',
        'method' => 'POST'
    ],
    '/admin/products/{id}/edit' => [
        'controller' => 'AdminController',
        'action' => 'editProduct'
    ],
    '/admin/products/{id}/update' => [
        'controller' => 'AdminController',
        'action' => 'updateProduct',
        'method' => 'POST'
    ],
    '/admin/products/{id}/delete' => [
        'controller' => 'AdminController',
        'action' => 'deleteProduct'
    ],
    
    // Admin Category routes
    '/admin/categories' => [
        'controller' => 'AdminController',
        'action' => 'categories'
    ],
    '/admin/categories/create' => [
        'controller' => 'AdminController',
        'action' => 'createCategory'
    ],
    '/admin/categories/store' => [
        'controller' => 'AdminController',
        'action' => 'storeCategory',
        'method' => 'POST'
    ],
    '/admin/categories/{id}/edit' => [
        'controller' => 'AdminController',
        'action' => 'editCategory'
    ],
    '/admin/categories/{id}/update' => [
        'controller' => 'AdminController',
        'action' => 'updateCategory',
        'method' => 'POST'
    ],
    '/admin/categories/{id}/delete' => [
        'controller' => 'AdminController',
        'action' => 'deleteCategory'
    ],
    
    // Admin Order routes
    '/admin/orders' => [
        'controller' => 'AdminController',
        'action' => 'orders'
    ],
    '/admin/orders/{id}' => [
        'controller' => 'AdminController',
        'action' => 'orderDetail'
    ],
    '/admin/orders/{id}/status' => [
        'controller' => 'AdminController',
        'action' => 'updateOrderStatus',
        'method' => 'POST'
    ],
    
    // Admin User routes
    '/admin/users' => [
        'controller' => 'AdminController',
        'action' => 'users'
    ],
    '/admin/users/{id}' => [
        'controller' => 'AdminController',
        'action' => 'userDetail'
    ],
    '/admin/users/{id}/role' => [
        'controller' => 'AdminController',
        'action' => 'updateUserRole',
        'method' => 'POST'
    ],
    
    // Admin Reports
    '/admin/reports' => [
        'controller' => 'AdminController',
        'action' => 'reports'
    ]
];

return $routes;