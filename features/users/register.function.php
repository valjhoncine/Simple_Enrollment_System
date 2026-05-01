<?php
require_once __DIR__ . '/UserService.php';

const REGISTER_VALIDATION_ERRORS = "REGISTER_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(REGISTER_VALIDATION_ERRORS);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"])) {
    $request = $_POST;
    if ($request["action"] != "register") {
        navigateTo($routes, "register");
    }

    $first_name = trim($request["first_name"]);
    $last_name = trim($request["last_name"]);
    $email = trim($request["email"]);
    $password = $request["password"];
    $password_confirmation = $request["password_confirmation"];

    if ($first_name == "") {
        $errors["first_name"][] = "First name is required.";
    }
    if ($last_name == "") {
        $errors["last_name"][] = "Last name is required.";
    }
    if ($email == "") {
        $errors["email"][] = "Email is required.";
    }
    if ($password == "") {
        $errors["password"][] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors["password"][] = "Password must be more than 8 characters.";
    }
    if ($password_confirmation == "") {
        $errors["password_confirmation"][] = "Confirm password is required.";
    } elseif (strlen($password_confirmation) < 8) {
        $errors["password_confirmation"][] = "Password must be more than 8 characters.";
    } elseif ($password !== $password_confirmation) {
        $errors["password"][] = "Passwords do not match.";
        $errors["password_confirmation"][] = "";
    }

    if (!empty($errors)) {
        $_SESSION[REGISTER_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
        ];
        navigateTo($routes, "register");
    }

    $userService = new UserService($connection);

    $result = $userService->getUserByEmail($email);
    if ($result) {
        $_SESSION[INSERT_FAILED] = "Failed to register, email may not be available.";
        navigateTo($routes, "register");
    }

    $result = $userService->save(
        $first_name,
        $last_name,
        $email,
        $password
    );

    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "Registration Success.";
        navigateTo($routes, "login");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to register, please try again.";
        navigateTo($routes, "register");
    }
}
