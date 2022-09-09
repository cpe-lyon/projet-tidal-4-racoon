<?php

namespace App;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;

class Twig
{
    private FilesystemLoader $loader;
    private Environment $twig;

    /**
     * Contructeur de la classe Twig
     */
    public function __construct() {
        $this->loader = new FilesystemLoader(Constant::DIR_TEMPLATES);
        $this->twig = new Environment($this->loader, [
            'cache' => Constant::DEBUG ? false : Constant::DIR_CACHE,
            'debug' => true,
        ]);
    }

    /**
     * Charge un template
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function load(string $template): TemplateWrapper
    {
        return $this->twig->load($template);
    }
}