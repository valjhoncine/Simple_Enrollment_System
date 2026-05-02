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

define('SESSION_USER', "SESSION_USER");
define('OLD_FORM_VAL', "OLD__FORM_VALUES");

define('INSERT_SUCCESS', "INSERT_SUCCESS");
define('INSERT_FAILED', "INSERT_FAILED");
define('UPDATE_SUCCESS', "UPDATE_SUCCESS");
define('UPDATE_FAILED', "UPDATE_FAILED");
define('REQUEST_RESOURCE_NOT_FOUND', "REQUEST_RESOURCE_NOT_FOUND");

define('PAGE_ACCESS_ROLES', [
    '0' => "Administrator",
    "1" => "Clerk",
    "2" => "Faculty",
    "3" => "Student"
]);

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
function getRouteMeta($routes, $routeName)
{
    $route = $routes[$routeName] ?? null;
    if ($route instanceof Route) {
        return $route->meta();
    }
    return '';
}
function getSessionErrorMessage($key): array
{
    if (isset($_SESSION[$key])) {
        $errors = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $errors;
    }
    return [];
}
function displayError($errors, $key)
{
    if (isset($errors[$key])) {
        $errorMessages = $errors[$key];
        foreach ($errorMessages ?? [] as $error) {
            echo "<div class='invalid-feedback d-block'>" . htmlspecialchars($error) . "</div>";
        }
    }
}
function setFormFieldIsInvalid($errors, $key): string
{
    if (isset($errors[$key])) {
        if (!empty($errors[$key])) {
            return "is-invalid";
        }
    }
    return "";
}
function getOldFormValue($key): string
{
    if (isset($_SESSION[OLD_FORM_VAL])) {
        $old = $_SESSION[OLD_FORM_VAL];
        if (isset($old[$key])) {
            unset($_SESSION[OLD_FORM_VAL][$key]);
            return $old[$key];
        }
    }
    return "";
}
function apiResponse($status, $data = null, $errors = null, $message = null)
{
    header('Content-Type: application/json');
    echo json_encode([
        "success" => $status,
        "message" => $message,
        "data" => $data,
        "error" => $errors
    ]);
    exit;
}
