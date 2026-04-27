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
            'login' => new Route($baseUrl .  '/', $featuresDirectory . '/users/pages/login.page.php'),
            'register' => new Route($baseUrl .  '/register', $featuresDirectory . '/users/pages/register.page.php'),
        ];
    }
}
define('BASE_URL', env()["BASE_URL"]);
$routes = Routes::get(BASE_URL, FEATURES_DIRECTORY);
