<?php

namespace Helper\App\Routes;

use Helper\MVC\Controller;
use Exception;

class Router
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    static private ?Router $router = null;

    private array $routesGET;
    private array $routesPOST;
    private array $routesPUT;
    private array $routesDELETE;

    private ?string $method = null;
    private ?string $route = null;


    public function __construct()
    {
        self::$router = $this;
        $this->routesGET = [];
        $this->routesPOST = [];
        $this->routesPUT = [];
        $this->routesDELETE = [];
        $this->getMethod();
    }

    public static function getInstance(): Router
    {
        if (self::$router === null) {
            self::$router = new Router();
        }
        return self::$router;
    }


    public static function get(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerGet($route, $controller, $action);
    }

    public static function post(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerPost($route, $controller, $action);
    }

    public static function put(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerPut($route, $controller, $action);
    }

    public static function delete(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerDelete($route, $controller, $action);
    }



    public function registerGet(string $route, string $controller, string $action): void
    {
        $this->routesGET[$route] = new Route($route, $controller, $action, 'GET');
    }

    public function registerPost(string $route, string $controller, string $action): void
    {
        $this->routesPOST[$route] = new Route($route, $controller, $action, 'POST');
    }

    public function registerPut(string $route, string $controller, string $action): void
    {
        $this->routesPUT[$route] = new Route($route, $controller, $action, 'PUT');
    }

    public function registerDelete(string $route, string $controller, string $action): void
    {
        $this->routesDELETE[$route] = new Route($route, $controller, $action, 'DELETE');
    }

    public function getRoutes(string $method): array
    {
        return match ($method) {
            self::METHOD_GET => $this->routesGET,
            self::METHOD_POST => $this->routesPOST,
            self::METHOD_PUT => $this->routesPUT,
            self::METHOD_DELETE => $this->routesDELETE,
            default => [],
        };
    }

    /**
     * @throws Exception
     */
    public function getRoute(string $route, string $method = null): Route|string|null
    {
        if ($method === null) {
            $method = $_SERVER['REQUEST_METHOD'];
        }
        $routes = $this->getRoutes($method);

        $route = explode('/', $route);
        foreach ($route as &$value) {
            if(ctype_digit($value)){
                $value = '@@';
            }
        }
        $route = implode('/', $route);
        if (array_key_exists($route, $routes)) {
            return $routes[$route];
        }
        if(!str_starts_with($route, '/src/')){
            if(file_exists($route)){
                $attachment_location = $_SERVER["DOCUMENT_ROOT"] . $route;
                $filename = basename($attachment_location);
                header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
                header("Cache-Control: public"); // needed for internet explorer
                header("Content-Type: " . mime_content_type($attachment_location));
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length:".filesize($attachment_location));
                header("Content-Disposition: attachment; filename=$filename");
                readfile($attachment_location);
                die();
            }
        }
        return $routes['/error/404'];
    }

    public function getMethod(): string
    {
        if ($this->method === null) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }
        return $this->method;
    }

    public function getURI(): string
    {
        if ($this->route === null) {
            $this->route = $_SERVER['REQUEST_URI'];
        }
        while ($this->route !== '/' && str_ends_with($this->route, '/')) {
            (new Controller())->redirect(substr($this->route, 0, -1));
        }
        return $this->route;
    }

    /**
     * @throws Exception
     */
    public function resolve()
    {
        $route = $this->getURI();
        $method = $this->getMethod();

        $route = $this->getRoute($route, $method);
        if ($route) {
            $controller = $route->getController();
            // transform string controller into object controller
            $controller = new $controller();
            $action = $route->getAction();
            $params = $route->getParams();
            return $controller->$action($params);
        }
        throw new Exception('Page inexistant', 404);
    }
}