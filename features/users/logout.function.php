<?php

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"])) {
    if ($_POST["action"] === "logout") {
        session_unset();
        session_destroy();
    }
    navigateTo($routes, "login");
}
