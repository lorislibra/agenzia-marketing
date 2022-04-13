<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user.php");
require_once("src/dtos/signin.php");

redirect_if_logged();

// POST handler
if (is_post()) {
    $connection = DbManager::build_connection_from_env();
    $userRepo = new UserRepo($connection);

    $dto = SignInDto::from_array($_POST);

    if ($user = $userRepo->get_by_email_password($dto->email, $dto->password)) {
        $session->set_user($user);
        header("location: /dashboard.php");
    } else {
        header("location: /login.php");
    }

    exit();
}

if (is_get()) {
    echo "login please";
}

?>