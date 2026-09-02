<?php
$router->get('/', 'StudentController::index');
$router->get('/student', 'StudentController::index');
$router->get('/student/profile', 'StudentController::profile')->middleware('student');
$router->get('/users', 'UsersController::index');