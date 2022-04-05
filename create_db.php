<?php
require "src/config.php";

$host = $_ENV["DB_HOST"];
$port = $_ENV["DB_PORT"];
$user = $_ENV["DB_USER"];
$passw = $_ENV["DB_PASSW"];
$db_name = $_ENV["DB_NAME"];

$con = new mysqli($host, $user, $passw, $db_name, $port);
if ($con->connect_error){
    die ($con->connect_error . "\n");
}

echo "Connected...\n";

$query = file_get_contents($argv[1]);

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