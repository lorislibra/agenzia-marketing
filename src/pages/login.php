<?php

require_once("src/middleware/session.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user.php");
require_once("src/dtos/signin.php");


$session = new SessionManager();

if ($session->is_logged()){
    header("location: dashboard.php");
}

// check if the method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connection = DbManager::build_connection_from_env();
    $userRepo = new UserRepo($connection);

    $dto = SignInDto::from_array($_POST);

    if ($user = $userRepo->get_by_email_password($dto->email, $dto->password)) {
        $session->set_user($user);
        header("location: dashboard.php");
    }else{
        header("location: login.php");
    }
    
    return;
}

?>