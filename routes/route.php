<?php

use Helper\MVC\Controller\Controller;
use Helper\MVC\Controller\HomeController;
use Helper\MVC\Controller\ProfilController;
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
Router::get('/profil', ProfilController::class, 'profile');
Router::post('/profil', ProfilController::class, 'profile');
Router::get('/inscription', ProfilController::class, 'register');
Router::get('/connexion', ProfilController::class, 'login');
Router::get('/about', HomeController::class, 'about');
