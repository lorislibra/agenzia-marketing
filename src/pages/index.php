<?php

require_once("src/repositories/manager.php");
require_once("src/middleware/checks.php");

need_logged();
redirect_if_logged();

?>