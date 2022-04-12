<?php

require_once("src/config.php");
require_once("src/utils/files.php");

$ftp_host = getenv("FTP_HOST");
$ftp_port = getenv("FTP_PORT");
$ftp_user = getenv("FTP_USER");
$ftp_passw = getenv("FTP_PASSW");
$ftp_project_path = getenv("FTP_PROJECT_PATH");

// set up basic connection
$ftp = ftp_connect($ftp_host, $ftp_port, 5);

// check connection
if (!$ftp || !ftp_login($ftp, $ftp_user, $ftp_passw)) {
    echo("Error connecting to $ftp_host for user $ftp_user\n");
    die();
}

echo "Connected to $ftp_host, for user $ftp_user\n";

$files = rglob("src/*");
array_push($files, ".env");

foreach ($files as $filename) {
    if (!is_dir($filename)) {
        if (!$upload = ftp_put($ftp, $ftp_project_path . "/" . basename($filename) , $filename, FTP_BINARY)) {
            echo ("FTP $filename upload has failed!\n");
        } else {
            echo("Uploaded $filename\n");
        }
    }
}

// close the FTP connection
ftp_close($ftp);

?>