<?php

require_once "src/middleware/session.php";
require_once "src/repositories/manager.php";
require_once "src/repositories/user.php";
require_once "src/dtos/signin.php";

$connection = DbManager::build_connection_from_env();
$userRepo = new UserRepo($connection);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dto = SignInDto::from_array($_POST);

    if ($user = $userRepo->get_by_email_password($dto->email, $dto->password)) {
        header("location: dashboard.php");
    } else {
        
    }
}

?>