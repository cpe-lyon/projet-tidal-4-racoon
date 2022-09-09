<?php

namespace App;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\TemplateWrapper;

class Page
{
    private static Twig $twig;
    private TemplateWrapper $template;
    private array $params;

    /**
     * Contructeur de la classe Page
     */
    public function __construct(string $template, array $params = []) {
        try {
            // On charge le template
            $this->template = self::getTwig()->load($template);
            $this->params = $params;
        // Si le template n'existe pas, on affiche la page 404
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            try {
                // On charge le template
                $this->template = self::getTwig()->load('error.tpl.html');
            // La page 404 n'existe pas, ce n'est pas normal
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                echo $e->getMessage();
                exit();
            }
            // On ajoute les paramètres de la page 404
            $this->params = [
                'error' => [
                    'code' => '404',
                    'message' => 'La page que tu recherches s\'est fait piquer',
                ],
            ];
        }
    }

    /**
     * On récupère l'instance de Twig (on la créé si elle n'existe pas)
     */
    public static function getTwig(): Twig
    {
        if (!isset(self::$twig)) {
            self::$twig = new Twig;
        }

        return self::$twig;
    }

    /**
     * Retourne la page
     */
    public function display(): string
    {
        return $this->template->render($this->params);
    }

    /**
     * Ajoute un paramètre à la page
     */
    public function addParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * Ajoûte des paramètres à la page
     */
    public function addParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Retourne un paramètre de la page
     */
    public function getParam(string $key): mixed
    {
        return $this->params[$key];
    }

    /**
     * Retourne les paramètres de la page
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Retire un paramètre de la page
     */
    public function removeParam(string $key): void
    {
        unset($this->params[$key]);
    }

    /**
     * Retire les paramètres de la page
     */
    public function removeParams(): void
    {
        $this->params = [];
    }
}