<?php

namespace Helper\MVC;

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

    public function errorIndex(): Page
    {
        $this->redirect('/');
    }

    public function error(string $errorCode): Page
    {
        switch ($errorCode) {
            case HTTP::NOT_FOUND:
                $this->params = [
                    'title' => 'Error 404',
                    'error' => [
                        'code' => '404',
                        'message' => 'Page not found',
                        ],
                ];
                break;
            case HTTP::FORBIDDEN:
                $this->params = [
                    'title' => 'Error 403',
                    'error' => [
                        'code' => '403',
                        'message' => 'Forbidden',
                        ],
                ];
                break;
            case HTTP::INTERNAL_SERVER_ERROR:
                $this->params = [
                    'title' => 'Error 500',
                    'error' => [
                        'code' => '500',
                        'message' => 'Internal Server Error',
                        ],
                ];
                break;
            default:
                $this->params = [
                    'title' => 'Error',
                    'error' => [
                        'code' => '???',
                        'message' => 'Unknown error',
                        ],
                ];
                break;
        }
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

    protected function urlInput(array $params, int $place, mixed $default = null): int|null
    {
        if (isset($params[$place]) && ctype_digit($params[$place])) {
            return intval($params[$place]);
        } else {
            return $default;
        }
    }
}