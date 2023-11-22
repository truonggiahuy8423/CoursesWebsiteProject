<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('/login/(:any)/(:any)', 'LoginController::login/$1');
$routes->get('admin/home/(:any)', 'Admin\Home::index/$1');

$routes->add('/class', 'ClassController::index');
$routes->add('/schedule', 'ScheduleController::index');
// $routes->get('/home/courses', 'Admin\Home::index2');
$routes->get('/courses', 'Admin\CoursesController::index');
$routes->get('/students', 'Admin\StudentsController::index');
$routes->get('/lecturers', 'Admin\TeachersController::index');
$routes->get('/users', 'Admin\UsersController::index');
$routes->get('/profile', 'ProfileController::index');



