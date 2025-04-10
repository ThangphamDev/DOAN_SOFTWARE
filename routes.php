// Cart API Routes
$router->post('/api/cart/add', 'CartController@addToCart');
$router->put('/api/cart/update', 'CartController@updateCartItem');
$router->delete('/api/cart/remove', 'CartController@removeFromCart');
$router->get('/api/cart', 'CartController@getCart');
$router->delete('/api/cart/clear', 'CartController@clearCart');

// ... existing routes ... 