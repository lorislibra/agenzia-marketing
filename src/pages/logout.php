<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/session.php");
require_once("src/repositories/manager.php");

if (is_post()) {
    $session->logout();
}

// in get requests ignore logout
redirect_if_logged();
header("location: /login.php");

?>