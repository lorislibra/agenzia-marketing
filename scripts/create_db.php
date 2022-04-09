<?php

require_once "src/repositories/manager.php";

$connection = DbManager::build_connection_from_env();
echo "Connected!\n";

$query_string = file_get_contents($argv[1]);
echo "File loaded!\n";

try {
    $res = $connection->exec($query_string);
} catch (Throwable $th) {
    echo $th->getMessage() . "\n";
}


?>