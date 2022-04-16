<?php

require_once("src/repositories/manager.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");

allowed_methods(["GET"]);
need_logged();
redirect_if_logged();

?>