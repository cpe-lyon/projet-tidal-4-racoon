<?php

namespace Helper\MVC;

use Helper\Twig\Page;
use JetBrains\PhpStorm\NoReturn;

class Controller
{
    public array $params = [];

    public function __construct(array $params = [])
    {
    }

    public function addParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }

    public function addParam(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }

    function errorIndex(): Page
    {
        $this->redirect('/');
    }

    function error404(): Page
    {
        return Page::error404();
    }

    #[NoReturn] public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function urlInput(array $params, int $place, mixed $default = null): int|null
    {
        if (isset($params[$place]) && ctype_digit($params[$place])) {
            return intval($params[$place]);
        } else {
            return $default;
        }
    }
}