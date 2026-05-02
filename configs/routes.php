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
            'users-create' => new Route(
                $baseUrl .  '/users/create',
                $featuresDirectory . '/users/lists/users-create.page.php',
                ROUTE_PROTECTED,
                "users"
            ),
            'courses' => new Route(
                $baseUrl .  '/courses',
                $featuresDirectory . '/courses/pages/courses.page.php',
                ROUTE_PROTECTED,
                "courses"
            ),
            'courses-create' => new Route(
                $baseUrl .  '/courses/create',
                $featuresDirectory . '/courses/pages/courses-create.page.php',
                ROUTE_PROTECTED,
                "courses"
            ),
            'courses-edit' => new Route(
                $baseUrl .  '/courses/edit',
                $featuresDirectory . '/courses/pages/courses-edit.page.php',
                ROUTE_PROTECTED,
                "courses"
            ),
        ];
    }
}
define('BASE_URL', env()["BASE_URL"]);
