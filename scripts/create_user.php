<?php

require_once "src/repositories/manager.php";

$connection = DbManager::build_connection_from_env();

$email = "email@gmail.com";

$connection->prepare("
INSERT INTO user () VALUES
(),
()
;
");


?>