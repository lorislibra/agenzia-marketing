<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");
require_once("src/dtos/signin.php");
require_once("src/middleware/request.php");

allowed_methods(["GET"]);
redirect_if_logged();

function show_error(): string 
{
    global $session;

    if ($error = $session->get_error("login")) {
        return '<p class="login_errors">' . $error . '</p>';
    }

    return "";
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
            <form class="login_form" method="POST" action="api/login.php">
                <input class="login_input" type="text" name="email" placeholder="Email" autocomplete="off">
                <input class="login_input" type="password" name="password" placeholder="Password" autocomplete="off">
                <button class="login_button" type="submit">LOGIN</button>
                <?php echo(show_error()); ?>
            </form>
        </div>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>