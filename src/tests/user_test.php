<?php

require_once("src/repositories/user_repo.php");
require_once("test.php");

$user_repo = new UserRepo($connection);

echo("User by email and password\n");
if ($user = $user_repo->get_by_email_password("dev@dev.com", "dev")) {
    var_dump($user);
} else {
    echo("no user\n");
}

line();

echo("All Users\n");
if ($users = $user_repo->get_all()) {
    var_dump($users);
} else {
    echo("no users\n");
}

line();

echo("users count\n");
$users = $user_repo->count();
var_dump($users);

?>