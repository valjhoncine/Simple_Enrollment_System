<?php
require FEATURES_DIRECTORY . '/users/UserService.php';

if ($_SERVER['REQUEST_METHOD'] === HTTP_GET && isset($_GET['action']) && $_GET['action'] === 'users') {

    $userService = new UserService($connection);
    $users = $userService->getUsers();

    apiResponse(true, $users);
}
