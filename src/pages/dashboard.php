<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");

allowed_methods(["GET"]);
need_logged();

?>