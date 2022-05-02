<?php

require_once("src/repositories/manager.php");

$connection = DbManager::build_connection_from_env();
echo("Connected!\n");

$query_string = file_get_contents($argv[1]);

$connection->exec($query_string);
echo("Created!\n");

require_once("sample.php");

?>