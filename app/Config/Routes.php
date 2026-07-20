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

    




































// -------------------------------Miantra----------------------------------
// $routes->get('/', 'AuthController::index');
// $routes->post('/login', 'AuthController::login');
// $routes->get('/logout', 'AuthController::logout');
// $routes->post('/achat/valider', 'AchatController::valider');