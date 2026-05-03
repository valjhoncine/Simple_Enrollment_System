<?php
require_once dirname(__DIR__) . '/configs/config.php';
session_start();

$pageRequest = parse_url(rtrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH) ?? "login";

$activeSideNavigation = "";

$routeMap = [];
foreach ($routes as $route) {
    $routeMap[rtrim($route->url(), '/')] = $route;
}

if (isset($routeMap[$pageRequest])) {
    $route = $routeMap[$pageRequest];
    
    if ($route->middleware() === ROUTE_PUBLIC) {
        publicPage($routes);
    } elseif ($route->middleware() === ROUTE_PROTECTED) {
        authGuard($routes);
    }
    $activeSideNavigation = $route->meta();
    require $route->path();
} else {
    http_response_code(404);
    require FEATURES_DIRECTORY . '/errors/404.php';
}

function authGuard($routes)
{
    if (!isset($_SESSION[SESSION_USER])) {
        navigateTo($routes, "login");
    }
}
function publicPage($routes)
{
    if (isset($_SESSION[SESSION_USER])) {
        navigateTo($routes, "dashboard");
    }
}
