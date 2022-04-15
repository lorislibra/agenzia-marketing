<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");
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
        $session->add_login_error("invalid email or password");
        header("location: /login.php");
    }

    exit();
}

function show_errors(): string 
{
    $session->
}

?>

<html>
    <head>
        <title>Login</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <div class="login_section">
            <img class="lm_title" src="https://peroni.it/wp-content/themes/birraperoni/assets/svg/peroni.svg">
            <form method="POST" action="">
                <input type="text" name="email" placeholder="Email" autocomplete="off">
                <input type="password" name="password" placeholder="Password" autocomplete="off">
                <input type="submit" value="LOGIN">
            </form>
            <p></p>
        </div>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>