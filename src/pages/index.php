<?php

require_once("src/middleware/session.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user.php");
require_once("src/dtos/signin.php");

$session = new SessionManager();

if ($session->is_logged()){
    header("location: dashboard.php");
} else {
    header("location: login.php");
}


?>