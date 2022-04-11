<?php

require_once("src/repositories/manager.php");

function line() {
    echo(str_repeat("-", 50) . "\n");
}

$connection = DbManager::build_connection_from_env();

?>