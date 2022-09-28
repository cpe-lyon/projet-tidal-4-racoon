<?php

namespace Helper\App\Routes;

use Defr\PhpMimeType\MimeType;
use Helper\App\Constant;
use Helper\MVC\Controller;
use Exception;

/*
 * Class Routeur pour gérer les Routes existantes 
 */
class Router
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    static private ?Router $router = null;
    static private string $prefix = '';

    private array $routesGET;
    private array $routesPOST;
    private array $routesPUT;
    private array $routesDELETE;

    private ?string $method = null;
    private ?string $route = null;

    /*
     * Le constructeur ne devrait être appelé qu'une
     * fois dans le cas où l'objet d'existerait pas encore 
     */
    public function __construct()
    {
        self::$router = $this;
        $this->routesGET = [];
        $this->routesPOST = [];
        $this->routesPUT = [];
        $this->routesDELETE = [];
        $this->getMethod();
    }

    /*
     * Récupère l'instance existante ou en créé une
     * dans cas où elle n'existe pas
     */
    public static function getInstance(): Router
    {
        if (self::$router === null) {
            self::$router = new Router();
        }
        return self::$router;
    }


    /*
     * Enregistre une route GET auprès du Router (VERSION STATIQUE)
     */
    public static function get(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerGet($route, $controller, $action);
    }

    /*
     * Enregistre une route POST auprès du Router (VERSION STATIQUE)
     */
    public static function post(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerPost($route, $controller, $action);
    }

    /*
     * Enregistre une route PUT auprès du Router (VERSION STATIQUE)
     */
    public static function put(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerPut($route, $controller, $action);
    }

    /*
     * Enregistre une route DELETE auprès du Router (VERSION STATIQUE)
     */
    public static function delete(string $route, string $controller, string $action): void
    {
        self::getInstance()->registerDelete($route, $controller, $action);
    }

    /*
     * Enregistre une route GET auprès du Router
     */
    public function registerGet(string $route, string $controller, string $action): void
    {
        $this->routesGET[self::$prefix . $route] = new Route(self::$prefix . $route, $controller, $action, 'GET');
    }

    /*
     * Enregistre une route POST auprès du Router
     */
    public function registerPost(string $route, string $controller, string $action): void
    {
        $this->routesPOST[self::$prefix . $route] = new Route(self::$prefix . $route, $controller, $action, 'POST');
    }

    /*
     * Enregistre une route PUT auprès du Router
     */
    public function registerPut(string $route, string $controller, string $action): void
    {
        $this->routesPUT[self::$prefix . $route] = new Route(self::$prefix . $route, $controller, $action, 'PUT');
    }

    /*
     * Enregistre une route DELETE auprès du Router
     */
    public function registerDelete(string $route, string $controller, string $action): void
    {
        $this->routesDELETE[self::$prefix . $route] = new Route(self::$prefix . $route, $controller, $action, 'DELETE');
    }

    /*
     * Récupère les routes en fonction de la méthode HTTP
     */
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
     * Récupère la route pour la méthode HTTP précisé
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
        if(str_starts_with($route, '/src/')){
            if(file_exists(Constant::DIR_ROOT . substr($route, 1))){
                $attachment_location = Constant::DIR_ROOT . substr($route, 1);
                $filename = basename($attachment_location);
                header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
                header("Cache-Control: public"); // needed for internet explorer
                header("Content-Type: " . MimeType::get($filename));
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length:".filesize($attachment_location));
                readfile($attachment_location);
                die();
            }
        }
        Controller::redirectError(404);
    }

    /*
     * Récupère la méthode HTTP de la requête 
     */
    public function getMethod(): string
    {
        if ($this->method === null) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }
        return $this->method;
    }

    /*
     * Récupère URI de la requête
     * redirection si ils finit par un / (TODO: Retirer)
     */
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

    public static function group(string $prefix, callable $routes):void
    {
        $savePrefix = self::$prefix;
        self::$prefix = self::$prefix . $prefix;
        $routes();
        self::$prefix = $savePrefix;
    }

    /**
     * Permet de récupérer la requête, 
     * récupérer la route lié à cette requête,
     * et retourner le résultat de l'action lié au contrôleur 
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
            return $controller->$action(...$params);
        }
    }
}
