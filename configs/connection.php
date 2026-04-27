<?php

$env = env();
$host = $env["DB_SERVER"];
$user = $env["DB_USERNAME"];
$pass = $env["DB_PASSWORD"];
$dbname = $env["DB_NAME"];
$port = $env["DB_PORT"];

$connection = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
