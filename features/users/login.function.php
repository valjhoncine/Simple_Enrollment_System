<?php
require_once FEATURES_DIRECTORY . '/users/User.php';

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"])) {
    $request = $_POST;
    if ($request["action"] != "login") {
        navigateTo($routes, "login");
    }
    navigateTo($routes, "dashboard");
}
