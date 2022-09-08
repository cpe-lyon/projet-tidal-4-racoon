<?php

namespace App;

use PDO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    private Environment $twig;

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
        $this->initClass();
        $this->initDebug();
        $this->initTwig();
    }

    /**
     * Active les erreurs PHP si le mode debug est activé
     */
    private function initDebug(): void
    {
        if (Constant::DEBUG) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }

    /**
     * Charge l'autoload de composer
     * et la classe de constantes
     */
    private function initClass(): void
    {
        require_once './../vendor/autoload.php';
        require_once './Constant.php';
    }

    /**
     * Charge l'objet PDO pour la base de données
     */
    public function getDb(): PDO
    {
        return new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
    }

    /**
     * Initialise l'objet Twig pour les templates
     */
    public function initTwig(): void
    {
        $loader = new FilesystemLoader(Constant::DIR_TEMPLATES);
        $this->twig = new Environment($loader, [
            'cache' => Constant::DEBUG ? false : Constant::DIR_CACHE,
            'debug' => true,
        ]);
    }

    /**
     * Retourne l'objet Twig
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}