<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

$routes->group('dashboard', function($routes) {
    $routes->get('/', 'DashboardController::index');

    $routes->resource('cajas', ['controller' => 'CajasController']);
});