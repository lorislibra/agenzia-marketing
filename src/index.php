<?php

require_once "src/repositories/user.php";
require_once "src/repositories/manager.php";

$connection = DbManager::build_connection_from_env();
$userRepo = new UserRepo($connection);

foreach ($userRepo->get_by_email_password("test@gmail.com", "test") as $value) {
    var_dump($value);
}

?>