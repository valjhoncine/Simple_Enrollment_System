<?php
session_start();
require_once dirname(__DIR__) . '/configs/config.php';

$pageRequest = parse_url(rtrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH) ?? "login";

$activeSideNavigation = "";
switch ($pageRequest) {
    // login
    case BASE_URL:
    case getRouteUrl($routes, "login"):
        require getRouteFilePath($routes, "login");
        break;
    // register
    case getRouteUrl($routes, "register"):
        require getRouteFilePath($routes, "register");
        break;
    case getRouteUrl($routes, "dashboard"):
        $activeSideNavigation = "dashboard";
        require getRouteFilePath($routes, "dashboard");
        break;
    // not found
    default:
        http_response_code(404);
        require FEATURES_DIRECTORY . '/errors/404.php';
        break;
}
