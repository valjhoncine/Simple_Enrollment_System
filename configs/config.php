<?php

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/connection.php';
require_once FEATURES_DIRECTORY . '/users/User.php';

$routes = Routes::get(BASE_URL, FEATURES_DIRECTORY);
