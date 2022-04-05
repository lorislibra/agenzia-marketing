<?php
require "./env.php";

$con = new mysqli($_CONFIG_HOST, $_CONFIG_USERNAME, $_CONFIG_PASSWORD, $_CONFIG_DB_NAME, $_CONFIG_PORT);
if ($con->connect_error){
    die ($con->connect_error);
}

echo "Connected...\n";

$query = file_get_contents("sql/tables.sql");

if ($con->multi_query($query)){
    for ($i=0; $con->more_results(); $i++) { 
        $con->next_result();
        if ($con->errno){
            echo str_repeat("-", 20) .  "\nerror query #" . ($i+1) . "\n" . $con->error . "\n" . str_repeat("-", 20) . "\n";
        }
    }
}

$con->close();
?>