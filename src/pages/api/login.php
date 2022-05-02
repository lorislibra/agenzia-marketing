<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");
require_once("src/dtos/signin.php");
require_once("src/middleware/request.php");

allowed_methods(["POST"]);
redirect_if_logged();

// validate input and redirect if errors
try {
    $user_dto = SignInDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("login", "Invalid email or password");
    header("location: /login.php");
    exit();
}

// check user in the db
$connection = DbManager::build_connection_from_env();
$userRepo = new UserRepo($connection);

if ($user = $userRepo->get_by_email_password($user_dto)) {
    $session->set_user($user);
    header("location: /items.php");
} else {
    $session->add_error("login", "Invalid email or password");
    header("location: /login.php");
}

?>