<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {    // Grupo del Dashboard (requiere autenticación)
    $routes->get('/dashboard', 'DashboardController::index'); // Página principal del dashboard
    
    // Módulo de Pedidos
    $routes->group('orders', function($routes) {
        $routes->resource('orders', [
            'controller' => 'OrderController'
        ]);
        // Rutas adicionales
        $routes->get('(:num)/invoice', 'OrderController::invoice/$1');
        $routes->post('(:num)/cancel', 'OrderController::cancel/$1');
    });
    
    // Módulo de Reportes
    $routes->group('reports', function($routes) {
        $routes->get('sales', 'ReportController::sales');
        $routes->get('users', 'ReportController::users');
        $routes->post('generate', 'ReportController::generate');
    }); 
    // Módulo de Reportes
    $routes->presenter('users', ['controller' => 'UserController', 'only' => ['index', 'new', 'create', 'edit', 'update', 'delete']]);
});