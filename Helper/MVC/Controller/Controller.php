<?php

namespace Helper\MVC\Controller;

use Helper\App\Routes\Request;
use Helper\App\Routes\Types\HTTP;
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

    #[NoReturn] public function errorIndex(): Page
    {
        $this->redirect('/');
    }

    public function error(Request $request, int $errorCode): Page
    {
        $this->params = match ($errorCode) {
            HTTP::NOT_FOUND             => [
                'title' => 'Error 404',
                'error' => [
                    'code'    => '404',
                    'message' => 'Page not found',
                ],
            ],
            HTTP::FORBIDDEN             => [
                'title' => 'Error 403',
                'error' => [
                    'code'    => '403',
                    'message' => 'Forbidden',
                ],
            ],
            HTTP::INTERNAL_SERVER_ERROR => [
                'title' => 'Error 500',
                'error' => [
                    'code'    => '500',
                    'message' => 'Internal Server Error',
                ],
            ],
            default                     => [
                'title' => 'Error',
                'error' => [
                    'code'    => '???',
                    'message' => 'Unknown error',
                ],
            ],
        };
        return new Page('error.tpl.twig', $this->params);
    }

    #[NoReturn] public static function redirectError(int $errorCode): void
    {
        (new self)->redirect('/error/' . $errorCode);
    }

    #[NoReturn] public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    public function ping(): string
    {
        return 'pong';
    }
}