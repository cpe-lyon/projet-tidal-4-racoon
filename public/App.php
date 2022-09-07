<?php

namespace App;

use PDO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    private Environment $twig;

    function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initClass();
        $this->initDebug();
        $this->initTwig();
    }

    private function initDebug(): void
    {
        if (Constant::DEBUG) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }

    private function initClass(): void
    {
        require_once './../vendor/autoload.php';
        require_once './Constant.php';
    }

    public function getDb(): PDO
    {
        return new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
    }

    public function initTwig(): void
    {
        $loader = new FilesystemLoader(Constant::DIR_TEMPLATES);
        $this->twig = new Environment($loader, [
            'cache' => Constant::DEBUG ? false : Constant::DIR_CACHE,
            'debug' => true,
        ]);
    }

    public function getTwig(): Environment
    {
        return $this->twig;
    }
}