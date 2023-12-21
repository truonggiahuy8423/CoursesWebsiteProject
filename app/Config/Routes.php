<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('admin/home/(:any)', 'Admin\Home::index/$1');

$routes->add('/class', 'ClassController::index');
$routes->add('/schedule', 'ScheduleController::index');
// $routes->get('/home/courses', 'Admin\Home::index2');
$routes->get('/courses', 'Admin\CoursesController::index');
$routes->get('/students', 'Admin\StudentsController::index');
$routes->get('/Admin/StudentsController/getStudentInfo/(:num)', 'Admin\StudentsController::getStudentInfo/$1');
// app/Config/Routes.php

$routes->group('/courses', function ($routes) {
    $routes->add('information', 'Admin\CoursesController::information');
    $routes->add('attendance', 'Admin\CoursesController::attendance');
    $routes->add('chat', 'Courses::chat');
    $routes->add('resource', 'Admin\CoursesController::resource');
});
$routes->group('/students', function ($routes) {
    $routes->add('information', 'Admin\StudentsController::information');
});

$routes->get('/resource/assignment', 'Admin\CoursesController::assignment');
$routes->get('/students', 'Admin\StudentsController::index');
$routes->get('/lecturers', 'Admin\TeachersController::index');
$routes->get('/users', 'Admin\UsersController::index');
$routes->get('/profile/lecturer', 'ProfileController::index/true');
$routes->get('/profile/student', 'ProfileController::index/false');



