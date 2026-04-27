<?php
require_once FEATURES_DIRECTORY . '/users/User.php';

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST) {
    $request = $_POST;
    $action = $request["action"] ?? "";

    switch ($action) {
        case "login":
            navigateTo($routes, "login");
            break;
        case "register":
            navigateTo($routes, "register");
            break;
        default:
            navigateTo($routes, "login");
            break;
    }
}
