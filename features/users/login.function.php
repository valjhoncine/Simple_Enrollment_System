<?php
require_once FEATURES_DIRECTORY . '/users/User.php';

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

    $user = new User($connection);

    $result = $user->authenticate($email, $password);

    if ($result) {
        $_SESSION[SESSION_USER] = [
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ];
        navigateTo($routes, "dashboard");
    } else {
        $_SESSION["INVALID_CREDENTIALS"] = "Invalid Credentials.";
        navigateTo($routes, "login");
    }
}
