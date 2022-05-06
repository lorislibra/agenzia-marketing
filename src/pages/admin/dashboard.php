<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");

allowed_methods(["GET"]);
need_warehouse();

?>