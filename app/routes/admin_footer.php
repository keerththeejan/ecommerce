<?php
// Admin Footer Routes
$router->map('GET', '/admin/footer', function() {
    // Redirect to the management page by default
    header('Location: ' . BASE_URL . 'admin/footer/manage');
    exit();
});

// Footer Management Routes
$router->map('GET', '/admin/footer/manage', 'FooterController@manage');
$router->map('GET', '/admin/footer/manage/[i:id]', 'FooterController@manage');
$router->map('GET', '/admin/footer/add', 'FooterController@add');
$router->map('POST', '/admin/footer/store', 'FooterController@store');
$router->map('GET', '/admin/footer/edit/[i:id]', 'FooterController@edit');
$router->map('POST', '/admin/footer/update/[i:id]', 'FooterController@update');
$router->map('GET', '/admin/footer/delete/[i:id]', 'FooterController@delete');

// AJAX Endpoints
$router->map('POST', '/admin/footer/update-status/[i:id]', 'FooterController@updateStatus');
$router->map('POST', '/admin/footer/update-order', 'FooterController@updateOrder');
$router->map('POST', '/admin/footer/preview', 'FooterController@preview');
