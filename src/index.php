<?php

require_once "src/config.php";
require_once "src/repositories/user.php";
require_once "src/repositories/manager.php";

$host = $_ENV["DB_HOST"];
$port = $_ENV["DB_PORT"];
$user = $_ENV["DB_USER"];
$passw = $_ENV["DB_PASSW"];
$db_name = $_ENV["DB_NAME"];

$connection = DbManager::build_connection($host, $user, $passw, $db_name);
$userRepo = new UserRepo($connection);

foreach ($userRepo->get_by_email_password("test@gmail.com", "test") as $value) {
    var_dump($value);
}

?>