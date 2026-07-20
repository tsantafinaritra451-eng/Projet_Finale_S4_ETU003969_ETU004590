<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// ------------------------TSANTA----------------------------------
// $routes->get('/caisse', 'CaisseController::index');
// $routes->post('/caisse/selectionner', 'CaisseController::selectionner');
// $routes->get('/Achats', 'AchatController::index');
$routes->get('/admin/frais', 'FraisController::index');
$routes->post('/admin/frais/ajout', 'FraisController::ajoutBareme');
$routes->get('/admin/frais/supprimer/(:num)', 'FraisController::supprimerBareme/$1');
$routes->get('/admin/frais/modifier/(:num)', 'FraisController::index/$1');
$routes->post('/admin/frais/modifier/(:num)', 'FraisController::modifierBareme/$1');
$routes->get('/admin/clients', 'ClientController::index');
$routes->get('/admin/clients/situation/(:num)', 'ClientController::situation/$1');
$routes->get('/client/historique', 'ClientController::historiqueParClient');
$routes->get('/admin/commissions', 'OperateurController::index');
$routes->post('/admin/commissions/save', 'OperateurController::saveCommission');
$routes->get('/admin/montants_a_envoyer', 'UserController::montantsAEnvoyer');






























// -------------------------------Miantra----------------------------------
// $routes->get('/', 'AuthController::index');
// $routes->post('/login', 'AuthController::login');
// $routes->get('/logout', 'AuthController::logout');
// $routes->post('/achat/valider', 'AchatController::valider');
$routes->get('/', 'UserController::index');
$routes->post('/user/login', 'UserController::login');
$routes->get('/logout', 'UserController::logout');
$routes->get('/client/dashboard', 'UserController::dashboardClient');
$routes->get('/admin/dashboard', 'UserController::dashboardAdmin');

$routes->group('prefix', function ($routes) {
    $routes->get('/', 'PrefixController::index');
    $routes->get('form', 'PrefixController::form');
    $routes->get('form/(:num)', 'PrefixController::form/$1');
    $routes->post('save', 'PrefixController::save');
    $routes->get('delete/(:num)', 'PrefixController::delete/$1');
});

$routes->get('operation', 'OperationController::index');
$routes->post('operation/save', 'OperationController::save');

$routes->get('admin/gains', 'UserController::gains');

