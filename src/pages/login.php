<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");
require_once("src/dtos/signin.php");

redirect_if_logged();

// POST handler
if (is_post()) {
    // validate input and redirect if errors
    $user_dto = SignInDto::from_array($_POST);
    $errors = array();
    if (!$user_dto->validate($errors)) {
        $session->add_login_errors("Invalid email or password");
        header("location: /login.php");
        exit();
    }

    // check user in the db
    $connection = DbManager::build_connection_from_env();
    $userRepo = new UserRepo($connection);

    if ($user = $userRepo->get_by_email_password($user_dto)) {
        $session->set_user($user);
        header("location: /dashboard.php");
    } else {
        $session->add_login_errors("Invalid email or password");
        header("location: /login.php");
    }

    exit();
}

function show_errors(): string 
{
    global $session;
    $error_html = '';
    foreach ($session->get_login_errors() as $error) {
        $error_html .= '<p class="login_errors">' .$error . '</p>';
    }
    return $error_html;
}

?>

<html lang="en">
    <head>
        <title>Login</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <div class="back_img_login"></div>
        <div class="login_section">
            <img class="logo" src="https://peroni.it/wp-content/themes/birraperoni/assets/svg/peroni.svg">
            <form class="login_form" method="POST" action="">
                <input class="login_input" type="text" name="email" placeholder="Email" autocomplete="off">
                <input class="login_input" type="password" name="password" placeholder="Password" autocomplete="off">
                <button class="login_button" type="submit">LOGIN</button>
                <?php echo(show_errors()); ?>
            </form>
        </div>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>