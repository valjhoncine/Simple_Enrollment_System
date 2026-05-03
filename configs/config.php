<?php

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/connection.php';

spl_autoload_register(function ($class) {
    $base = BASE_DIRECTORY . '/entities';
    $file = $base . '/' . $class . '.php';
    if(file_exists($file)){
        require_once $file;
    }
});

spl_autoload_register(function ($class) {
    $base = BASE_DIRECTORY . '/entities/dto';
    $file = $base . '/' . $class . '.php';
    if(file_exists($file)){
        require_once $file;
    }
});

$routes = Routes::get(BASE_URL, FEATURES_DIRECTORY);
