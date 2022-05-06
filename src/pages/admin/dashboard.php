<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");

allowed_methods(["GET"]);
need_warehouse();

$connection = DbManager::build_connection_from_env();

?>