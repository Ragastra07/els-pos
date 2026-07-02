<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
# $routes->get('/', 'Home::index');

// Default route to the login page
$routes->get('/', 'AuthController::login');

// Authentication routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');

// Group routes that require authentication using the 'auth' filter
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/logout', 'AuthController::logout');

    // Dashboard route
    $routes->get('/dashboard', 'DashboardController::index');

    // Product routes
    $routes->get('/products', 'ProductController::index');

    // Sales transaction routes.
    $routes->get('/sales', 'SaleController::index');
    $routes->get('/sales/cashier', 'SaleController::cashier');
    $routes->get('/sales/create', 'SaleController::create');
    $routes->post('/sales/store', 'SaleController::store');
    $routes->get('/sales/receipt/(:num)', 'SaleController::receipt/$1');
    // Route to show details of a specific sale by its ID. request parameter (:num) is used to capture the sale ID from the URL.
    $routes->get('/sales/show/(:num)', 'SaleController::show/$1');
});
