<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// ------------------------TSANTA----------------------------------
// $routes->get('/caisse', 'CaisseController::index');
// $routes->post('/caisse/selectionner', 'CaisseController::selectionner');
// $routes->get('/Achats', 'AchatController::index');








































// -------------------------------Miantra----------------------------------
// $routes->get('/', 'AuthController::index');
// $routes->post('/login', 'AuthController::login');
// $routes->get('/logout', 'AuthController::logout');
// $routes->post('/achat/valider', 'AchatController::valider');
$routes->get('/', 'UserController::index');
$routes->post('/user/login', 'UserController::login');
$routes->get('/client/dashboard', 'UserController::dashboardClient');
$routes->get('/admin/dashboard', 'UserController::dashboardAdmin');

$routes->group('prefix', function($routes) {
    $routes->get('/', 'PrefixController::index');
    $routes->get('form', 'PrefixController::form');
    $routes->get('form/(:num)', 'PrefixController::form/$1');
    $routes->post('save', 'PrefixController::save');
    $routes->get('delete/(:num)', 'PrefixController::delete/$1');
});

