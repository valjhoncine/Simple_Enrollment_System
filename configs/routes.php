<?php

define("ROUTE_PUBLIC", "PUBLIC");
define("ROUTE_PROTECTED", "PRIVATE");

class Route
{
    private $url;
    private $path;
    private $middleware;
    private $meta;

    public function __construct($url, $path, $middleware, $meta = "")
    {
        $this->url = $url;
        $this->path = $path;
        $this->middleware = $middleware;
        $this->meta = $meta;
    }

    public function url()
    {
        return $this->url;
    }
    public function path()
    {
        return $this->path;
    }
    public function middleware()
    {
        return $this->middleware;
    }
    public function meta()
    {
        return $this->meta;
    }
}

class Routes
{
    public static function get($baseUrl, $featuresDirectory)
    {
        return [
            'login' => new Route(
                $baseUrl .  '/',
                $featuresDirectory . '/users/login.page.php',
                ROUTE_PUBLIC
            ),
            'register' => new Route(
                $baseUrl .  '/register',
                $featuresDirectory . '/users/register.page.php',
                ROUTE_PUBLIC
            ),
            'logout' => new Route(
                $baseUrl .  '/logout',
                $featuresDirectory . '/users/logout.function.php',
                ROUTE_PROTECTED
            ),
            'dashboard' => new Route(
                $baseUrl .  '/dashboard',
                $featuresDirectory . '/dashboard/dashboard.page.php',
                ROUTE_PROTECTED,
                "dashboard"
            ),
            'users' => new Route(
                $baseUrl .  '/users',
                $featuresDirectory . '/users/lists/users.page.php',
                ROUTE_PROTECTED,
                "users"
            ),
        ];
    }
}
define('BASE_URL', env()["BASE_URL"]);
