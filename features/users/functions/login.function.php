<?php
require_once FEATURES_DIRECTORY . '/users/UserService.php';

const LOGIN_VALIDATION_ERRORS = "LOGIN_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(LOGIN_VALIDATION_ERRORS);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"])) {
    $request = $_POST;
    if ($request["action"] != "login") {
        navigateTo($routes, "login");
    }

    $email = trim($request["email"]);
    $password = $request["password"];

    if ($email == "") {
        $errors["email"][] = "Email is required.";
    }
    if ($password == "") {
        $errors["password"][] = "Password is required.";
    }

    if (!empty($errors)) {
        $_SESSION[LOGIN_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "email" => $email,
        ];
        navigateTo($routes, "login");
    }

    $userService = new UserService($connection);

    $result = $userService->authenticate($email, $password);

    if ($result) {
        session_regenerate_id(true);
        $_SESSION[SESSION_USER] = $result;
        navigateTo($routes, "dashboard");
    } else {
        $_SESSION["INVALID_CREDENTIALS"] = "Invalid Credentials.";
        navigateTo($routes, "login");
    }
}
