<?php
session_start();
require_once dirname(__DIR__) . '/configs/config.php';

$pageRequest = parse_url(rtrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH) ?? "login";

$activeSideNavigation = "";
switch ($pageRequest) {
    // login
    case BASE_URL:
    case getRouteUrl($routes, "login"):
        publicPage($routes);
        require getRouteFilePath($routes, "login");
        break;
    // register
    case getRouteUrl($routes, "register"):
        publicPage($routes);
        require getRouteFilePath($routes, "register");
        break;
    case getRouteUrl($routes, "logout"):
        authGuard($routes);
        require getRouteFilePath($routes, "logout");
        break;
    case getRouteUrl($routes, "dashboard"):
        $activeSideNavigation = "dashboard";
        authGuard($routes);
        require getRouteFilePath($routes, "dashboard");
        break;
    // not found
    default:
        http_response_code(404);
        require FEATURES_DIRECTORY . '/errors/404.php';
        break;
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
