<?php

namespace Helper\App;

use PDO;

class App
{
    /**
     * Contructeur de la classe App
     */
    function __construct()
    {
        $this->init();
    }

    /**
     * Initialise les bases de l'application
     */
    private function init(): void
    {
        $this->initDebug();
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
     * Charge l'objet PDO pour la base de données
     */
    public function getDb(): PDO
    {
        return new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
    }
}