<?php

require_once("session.php");
require_once("request.php");
require_once("src/entities/user.php");

function need_logged()
{
    global $session;

    if (!$session->is_logged()) {
        header("location: /login.php");
        exit();
    }
}

function need_warehouse()
{
    global $session;
    need_logged();

    if (!$session->get_user()->role->important_than(Role::Warehouse)) {
        header("location: /items.php");
        exit();
    }
}

function redirect_if_logged()
{
    global $session;

    if ($session->is_logged()) {
        header("location: /items.php");
        exit();
    }
    
}

$session = new SessionManager();

?>