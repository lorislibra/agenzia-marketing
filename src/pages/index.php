<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("src/middleware/checks.php");
    require_once("src/repositories/manager.php");

    $connection = DbManager::build_connection_from_env();

    //need_logged();
    //redirect_if_logged();
?>