<?php

define('BASE_DIRECTORY', dirname(__DIR__));
define('CONFIG_DIRECTORY', __DIR__);
define('INCLUDES_DIRECTORY', dirname(__DIR__) . '/includes');
define('FEATURES_DIRECTORY', dirname(__DIR__) . '/features');
define('PUBLIC_DIRECTORY', dirname(__DIR__) . '/public');

define('HTTP_GET', 'GET');
define('HTTP_POST', 'POST');
define('HTTP_PUT', 'PUT');
define('HTTP_DELETE', 'DELETE');

function env()
{
    return parse_ini_file(BASE_DIRECTORY . '/.env');
}

function navigateTo($routes, $routeName)
{
    $route = $routes[$routeName] ?? null;
    if ($route instanceof Route) {
        header("location: " . $route->url());
        exit;
    }
}
function getRouteUrl($routes, $routeName)
{
    $route = $routes[$routeName] ?? null;
    if ($route instanceof Route) {
        return $route->url();
    }
    return '/';
}
function getRouteFilePath($routes, $routeFilePath)
{
    $route = $routes[$routeFilePath] ?? null;
    if ($route instanceof Route) {
        return $route->path();
    }
    return '/';
}
