<?php

namespace Helper\App\Routes;

class Route
{
    private string $route;
    private string $controller;
    private string $action;
    private string $method;
    private array $params;

    public function __construct(string $route, string $controller, string $action, string $method = 'GET')
    {
        $this->route = $route;
        $this->controller = $controller;
        $this->action = $action;
        $this->method = $method;

        // Parse the route to get the params
        $this->params = $this->parseRoute($route);
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    private function parseRoute(string $route): array
    {
        $params = [];
        $route = explode('/', $route);
        foreach ($route as $key => $value) {
            if (str_contains($value, '@')) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    public function getParams(): array
    {
        $params = [];
        $pos = [];
        foreach (explode('/', $this->route) as $key => $value) {
            if($value === '@@'){
                $pos[$key] = $key;
            }
        }

        foreach (explode('/', Router::getInstance()->getURI()) as $key => $value) {
            if(isset($pos[$key])){
                $params[] = $value;
            }
        }
        return $params;
    }

}