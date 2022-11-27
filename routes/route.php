<?php

use Helper\MVC\Controller\Controller;
use Helper\MVC\Controller\HomeController;
use Helper\MVC\Controller\ProfileController;
use Helper\MVC\Controller\TestController;
use Helper\App\Routes\Router;

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

/* VUE */

Router::get('/error/@@', Controller::class, 'error');
Router::get('/index.php', Controller::class, 'errorIndex');
Router::get('/', HomeController::class, 'index');
Router::get('/test', TestController::class, 'index');

Router::get('/profil', ProfileController::class, 'profile');
Router::post('/profil', ProfileController::class, 'profile');

Router::get('/profil/confirm/@@/@@', ProfileController::class, 'confirmProfile');

Router::get('/inscription', ProfileController::class, 'register');
Router::post('/inscription', ProfileController::class, 'register');

Router::get('/connexion', ProfileController::class, 'login');
Router::post('/connexion', ProfileController::class, 'login');

Router::get('/about', HomeController::class, 'about');
