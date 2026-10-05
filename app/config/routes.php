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

$router->get('/create-migration/{migration_class}', 'MigrationController::create_migration');
$router->get('/migrate', 'MigrationController::migrate');
$router->get('/rollback', 'MigrationController::rollback');
$router->get('/rollback-all', 'MigrationController::rollback_all');
$router->get('/refresh', 'MigrationController::refresh');
$router->get('/status', 'MigrationController::status');

$router->get('/api/health', 'ApiController::health');
$router->match('/api/auth/register', 'ApiController::register', 'POST|OPTIONS');
$router->match('/api/auth/login', 'ApiController::login', 'POST|OPTIONS');
$router->match('/api/auth/logout', 'ApiController::logout', 'POST|OPTIONS');
$router->match('/api/products', 'ApiController::products', 'GET|POST|OPTIONS');
$router->match('/api/products/{id}', 'ApiController::product', 'GET|PUT|PATCH|DELETE|OPTIONS')->where_number('id');