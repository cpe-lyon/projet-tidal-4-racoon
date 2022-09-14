<?php

namespace Helper\App;

use Helper\App\Routes\Response;
use Helper\App\Routes\Router;
use Exception;
use PDO;

class App
{
    private Router $router;

    /**
     * Contructeur de la classe App
     */
    function __construct()
    {
        $this->router = new Router();

        $this->init();
    }

    /**
     * Initialise les bases de l'application
     */
    private function init(): void
    {
        $this->initDebug();
        $this->initRoute();
    }

    /**
     * Active les erreurs PHP si le mode debug est activé
     */
    private function initDebug(): void
    {
        ini_set('display_errors', Constant::DEBUG ? '1' : '0');
        ini_set('display_startup_errors', Constant::DEBUG ? '1' : '0');
        error_reporting(Constant::DEBUG ? E_ALL : 0);
    }

    /**
     * Active les erreurs PHP si le mode debug est activé
     */
    private function initRoute(): void
    {
        require_once Constant::DIR_ROOT . 'routes/route.php';
    }

    /**
     * Charge l'objet PDO pour la base de données
     */
    public function getDb(): PDO
    {
        return new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
    }


    public function run(): void
    {
        try {
            $response = new Response($this->router->resolve());
            $response->respond();
        } catch (Exception $e) {
            $response = new Response($e);
            $response->respond();
        }
    }
}
