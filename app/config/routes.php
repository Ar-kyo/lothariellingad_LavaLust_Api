<?php
$router->get('/', 'StudentController::index');
$router->get('/student', 'StudentController::index');
$router->get('/student/profile', 'StudentController::profile')->middleware('student');
$router->get('/users', 'UsersController::index');

$router->get('/login', 'AuthController::login');
$router->post('/login', 'AuthController::login');
$router->get('/logout', 'AuthController::logout');

$router->get('/products', 'ProductController::index')->middleware('product_auth');
$router->get('/products/create', 'ProductController::create')->middleware('product_auth');
$router->post('/products/store', 'ProductController::store')->middleware('product_auth');
$router->get('/products/edit/{id}', 'ProductController::edit')->where_number('id')->middleware('product_auth');
$router->post('/products/update/{id}', 'ProductController::update')->where_number('id')->middleware('product_auth');
$router->get('/products/delete/{id}', 'ProductController::delete')->where_number('id')->middleware('product_auth');