<?php

use Helper\MVC\Controller\Controller;
use Helper\MVC\Controller\HomeController;
use Helper\MVC\Controller\KeywordsController;
use Helper\MVC\Controller\MenuController;
use Helper\App\Routes\Router;
use Helper\MVC\Controller\PathoController;

/*
 * -------------- FICHIER DE ROUTES --------------
 *
 * Enregistrer ici les routes grâce à la classe Router
 * avec les différentes méthodes statique suivantes:
 *
 * - Router::get(string $route, string $controller, string $action)
 *   Pour obtenir des informations
 *
 * - Router::post(string $route, string $controller, string $action)
 *   Pour créer de nouveaux objets
 *
 * - Router::put(string $route, string $controller, string $action)
 *   Pour modifier des objets existants
 *
 * - Router::delete(string $route, string $controller, string $action)
 *   Pour supprimer des objets existants
 *
 *
 * Les paramètres des routes sont exclusivement des entiers numérique
 * et sont transmis à la méthode du controller en tant que paramètres
 * dans l'ordre dans lequel ils sont définis dans la route
 *
 */

/* API */


Router::group('/api', function (){
    Router::get('/ping', Controller::class, 'ping');
    Router::group('/keywords', function () {
        Router::get('/ping', Controller::class, 'ping');
        Router::get('/get', KeywordsController::class, 'getAll');
        Router::get('/keyword', KeywordsController::class, 'filterKeyword');
    });
    Router::group('/patho', function () {
        Router::get('/ping', Controller::class, 'ping');
        Router::post('/get', HomeController::class, 'search');
        Router::get('/interface', PathoController::class, 'getInterfaces');
        Router::get('/get/@@', PathoController::class, 'get');
    });
    Router::get('/menu/account', MenuController::class, 'account');
});