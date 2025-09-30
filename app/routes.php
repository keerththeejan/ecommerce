<?php
// ... existing routes ...

// About Store Admin Routes
$router->get('about-store', 'AboutStoreController@index');
$router->get('about-store/create', 'AboutStoreController@create');
$router->post('about-store/store', 'AboutStoreController@store');
$router->get('about-store/edit/:id', 'AboutStoreController@edit');
$router->post('about-store/update/:id', 'AboutStoreController@update');
$router->get('about-store/delete/:id', 'AboutStoreController@delete');

// Frontend About Page
$router->get('about', 'AboutController@index');

// ... rest of the routes ...
