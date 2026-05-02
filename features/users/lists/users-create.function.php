<?php
require FEATURES_DIRECTORY . '/users/UserService.php';

const REGISTER_VALIDATION_ERRORS = "REGISTER_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(REGISTER_VALIDATION_ERRORS);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'register') {
    $request = $_POST;

    $first_name = trim($request["first_name"]);
    $last_name = trim($request["last_name"]);
    $email = trim($request["email"]);
    $role = trim($request["role"]);
    if ($first_name == "") {
        $errors["first_name"][] = "First name is required.";
    }
    if ($last_name == "") {
        $errors["last_name"][] = "Last name is required.";
    }
    if ($email == "") {
        $errors["email"][] = "Email is required.";
    }
    if (!array_key_exists($role, PAGE_ACCESS_ROLES)) {
        $errors['role'][] = "Role is required.";
    }

    if (!empty($errors)) {
        $_SESSION[REGISTER_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
        ];
        navigateTo($routes, "users-create");
    }

    $userService = new UserService($connection);

    $result = $userService->getUserByEmail($email);
    if ($result) {
        $_SESSION[INSERT_FAILED] = "Failed to create account, email may not be available.";
        navigateTo($routes, "users-create");
    }

    $password = ucfirst($last_name) . '@' . date("Y");
    $result = $userService->save(
        $first_name,
        $last_name,
        $email,
        $password,
        $role
    );

    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "New user account created successfully.";
        navigateTo($routes, "users-create");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create account, please try again.";
        navigateTo($routes, "users-create");
    }
}
