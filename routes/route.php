<?php

use Helper\App\Routes\Router;
use Helper\MVC\Controller;
use Helper\MVC\HomeController;
use Helper\MVC\ProfilController;

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

Router::get('/error/@@', Controller::class, 'error');
Router::get('/index.php', Controller::class, 'errorIndex');
Router::get('/', HomeController::class, 'index');
Router::get('/account/@@/rock/@@', HomeController::class, 'account');
Router::get('/profil', ProfilController::class, 'profil');
Router::post('/profil', ProfilController::class, 'profil');
