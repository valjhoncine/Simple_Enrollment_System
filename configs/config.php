<?php

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/connection.php';

$routes = Routes::get(BASE_URL, FEATURES_DIRECTORY);
