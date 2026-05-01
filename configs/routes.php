<?php

class Route
{
    private $url;
    private $path;

    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }

    public function url()
    {
        return $this->url;
    }
    public function path()
    {
        return $this->path;
    }
}

class Routes
{
    public static function get($baseUrl, $featuresDirectory)
    {
        return [
            'login' => new Route($baseUrl .  '/', $featuresDirectory . '/users/login.page.php'),
            'register' => new Route($baseUrl .  '/register', $featuresDirectory . '/users/register.page.php'),
            'logout' => new Route($baseUrl .  '/logout', $featuresDirectory . '/users/logout.function.php'),
            'dashboard' => new Route($baseUrl .  '/dashboard', $featuresDirectory . '/dashboard/dashboard.page.php'),
        ];
    }
}
define('BASE_URL', env()["BASE_URL"]);
