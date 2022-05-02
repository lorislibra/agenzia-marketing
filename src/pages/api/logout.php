<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/session.php");
require_once("src/repositories/manager.php");
require_once("src/middleware/request.php");

allowed_methods(["POST"]);

$session->logout();
header("location: /login.php");

?>