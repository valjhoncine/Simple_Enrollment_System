<?php
session_start();
require_once dirname(__DIR__) . '/configs/config.php';

$pageRequest = parse_url(rtrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH) ?? "login";

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
    // not found
    default:
        http_response_code(404);
        require dirname(__DIR__) . '/includes/not_found/page-not-found.php';
        break;
}
