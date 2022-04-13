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

ftp_mkdir($ftp, "$ftp_project_path/src");

foreach ($files as $filename) {
    $remote_path = $ftp_project_path . "/" . $filename;
    if (!is_dir($filename)) {
        if (!$upload = ftp_put($ftp, $remote_path , $filename, FTP_BINARY)) {
            echo ("FTP $filename upload has failed!\n");
        } else {
            echo("Uploaded $filename\n");
        }
    } else if ($res = ftp_mkdir($ftp, $remote_path)) {
        echo("Directory $remote_path created\n");
    } else {
        echo("Error creating directory $remote_path\n");
    }
}

// close the FTP connection
ftp_close($ftp);

?>