<?php
// Admin Routes
$router->map('GET', '/admin', 'AdminController@dashboard');
$router->map('GET|POST', '/admin/login', 'AdminController@login');
$router->map('GET', '/admin/logout', 'AdminController@logout');

// Admin Dashboard
$router->map('GET', '/admin/dashboard', 'AdminController@dashboard');

// Categories
$router->map('GET', '/admin/categories', 'CategoryController@index');
$router->map('GET', '/admin/categories/create', 'CategoryController@create');
$router->map('POST', '/admin/categories/store', 'CategoryController@store');
$router->map('GET|POST', '/admin/categories/edit/[i:id]', 'CategoryController@edit');
$router->map('POST', '/admin/categories/update/[i:id]', 'CategoryController@update');
$router->map('GET', '/admin/categories/delete/[i:id]', 'CategoryController@delete');

// Products
$router->map('GET', '/admin/products', 'ProductController@index');
$router->map('GET', '/admin/products/create', 'ProductController@create');
$router->map('POST', '/admin/products/store', 'ProductController@store');
$router->map('GET|POST', '/admin/products/edit/[i:id]', 'ProductController@edit');
$router->map('POST', '/admin/products/update/[i:id]', 'ProductController@update');
$router->map('GET', '/admin/products/delete/[i:id]', 'ProductController@delete');

// Orders
$router->map('GET', '/admin/orders', 'OrderController@index');
$router->map('GET', '/admin/orders/view/[i:id]', 'OrderController@view');
$router->map('POST', '/admin/orders/update-status/[i:id]', 'OrderController@updateStatus');

// Users
$router->map('GET', '/admin/users', 'UserController@index');
$router->map('GET', '/admin/users/create', 'UserController@create');
$router->map('POST', '/admin/users/store', 'UserController@store');
$router->map('GET|POST', '/admin/users/edit/[i:id]', 'UserController@edit');
$router->map('POST', '/admin/users/update/[i:id]', 'UserController@update');
$router->map('GET', '/admin/users/delete/[i:id]', 'UserController@delete');

// Footer Management
$router->map('GET', '/admin/footer', 'FooterController@manage');
$router->map('GET', '/admin/footer/manage', 'FooterController@manage');
$router->map('GET', '/admin/footer/add', 'FooterController@add');
$router->map('POST', '/admin/footer/store', 'FooterController@store');
$router->map('GET', '/admin/footer/edit/[i:id]', 'FooterController@edit');
$router->map('POST', '/admin/footer/update/[i:id]', 'FooterController@update');
$router->map('GET', '/admin/footer/delete/[i:id]', 'FooterController@delete');
$router->map('GET', '/admin/footer/getSectionFields', 'FooterController@getSectionFields');
