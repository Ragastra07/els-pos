<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
# $routes->get('/', 'Home::index');

// Default route to the login page
$routes->get('/', 'AuthController::login');

// Authentication routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

// Dashboard route
$routes->get('/dashboard', 'DashboardController::index');


// for testing database purposes only
$routes->get('/db-test', 'Home::dbTest');
