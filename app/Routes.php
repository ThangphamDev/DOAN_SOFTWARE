<?php

// ... existing code ...
// Orders
$router->get('/orders/view/([0-9]+)', 'OrderController@viewOrder');
$router->get('/orders/cancel/([0-9]+)', 'OrderController@cancelOrder');
// ... existing code ... 