<?php

use app\controllers\SymptomeController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

$router->get('/', [SymptomeController::class, 'accueil']);

/* Symptome */
$router->post('/symptome/create', [SymptomeController::class, 'createSymptome']);
$router->get('/symptome/edit/@id', [SymptomeController::class, 'editSymptome']);
$router->post('/symptome/update/@id', [SymptomeController::class, 'updateSymptome']);
$router->get('/symptome/delete/@id', [SymptomeController::class, 'deleteSymptome']);

/* Medoc */
$router->post('/medicament/create', [SymptomeController::class, 'createMedicament']);
$router->get('/medicament/edit/@id', [SymptomeController::class, 'editMedicament']);
$router->post('/medicament/update/@id', [SymptomeController::class, 'updateMedicament']);
$router->get('/medicament/delete/@id', [SymptomeController::class, 'deleteMedicament']);

/* Ordonnance */
$router->post('/ordonnance', [SymptomeController::class, 'genererOrdonnance']);

/* Triage */
$router->get('/symptomes/tri', [SymptomeController::class, 'symptomesTries']);
$router->get('/symptome/rechercher', [SymptomeController::class, 'rechercherSymptome']);
 
}, [ SecurityHeadersMiddleware::class ]);
