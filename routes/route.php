<?php

use Helper\App\Routes\Router;
use Helper\MVC\Controller;
use Helper\MVC\HomeController;

Router::get('/error/404', Controller::class, 'error404');
Router::get('/index.php', Controller::class, 'errorIndex');
Router::get('/', HomeController::class, 'index');
// Router::get('/account/@@', HomeController::class, 'account');
