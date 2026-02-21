<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'Auth::landing');
$routes->get('/login', 'Auth::login');
$routes->post('/loginProcess', 'Auth::loginProcess');
$routes->get('/verify', 'Auth::verify');
$routes->post('/verifyProcess', 'Auth::verifyProcess');