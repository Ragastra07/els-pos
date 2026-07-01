<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// for testing database purposes only
$routes->get('/db-test', 'Home::dbTest');
