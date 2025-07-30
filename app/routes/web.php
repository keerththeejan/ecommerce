<?php
// Home Page
$router->map('GET', '/', 'HomeController@index');

// Product Routes
$router->map('GET', '/products', 'ProductController@index');
$router->map('GET', '/products/[i:id]', 'ProductController@show');
$router->map('GET', '/category/[i:id]', 'CategoryController@show');

// Cart Routes
$router->map('GET', '/cart', 'CartController@index');
$router->map('POST', '/cart/add', 'CartController@add');
$router->map('POST', '/cart/update', 'CartController@update');
$router->map('GET', '/cart/remove/[i:id]', 'CartController@remove');
$router->map('GET', '/cart/clear', 'CartController@clear');

// Checkout Routes
$router->map('GET', '/checkout', 'CheckoutController@index');
$router->map('POST', '/checkout/process', 'CheckoutController@process');
$router->map('GET', '/checkout/success', 'CheckoutController@success');
$router->map('GET', '/checkout/cancel', 'CheckoutController@cancel');

// User Routes
$router->map('GET|POST', '/login', 'UserController@login');
$router->map('GET|POST', '/register', 'UserController@register');
$router->map('GET', '/logout', 'UserController@logout');

// Static Pages
$router->map('GET', '/about', 'PageController@about');
$router->map('GET', '/contact', 'PageController@contact');
$router->map('POST', '/contact/send', 'PageController@sendContact');

// Search
$router->map('GET', '/search', 'SearchController@index');
