<?php

require_once "src/repositories/user.php";
require_once "src/repositories/manager.php";

$connection = DbManager::build_connection_from_env();
$userRepo = new UserRepo($connection);

if ($user = $userRepo->get_by_email_password("dev@dev.com", "dev")){
    print_r($user);
}else{
    echo("no user\n");
}

echo str_repeat("-", 50) . "\n";

if ($users = $userRepo->get_all()){
    print_r($users);
}else{
    echo("no users\n");
}

?>