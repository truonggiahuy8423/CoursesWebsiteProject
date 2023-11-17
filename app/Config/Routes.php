<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('/login/(:any)/(:any)', 'LoginController::login/$1');
$routes->get('admin/home/(:any)', 'Admin\Home::index/$1');
