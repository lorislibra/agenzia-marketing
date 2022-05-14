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

    $role = $session->get_user()->role;

    switch ($role) {
        case Role::Developer:
        case Role::Warehouse:
            break;
        default:
            header("location: /dashboard.php");
            exit();
            break;
    }
}

function redirect_if_logged()
{
    global $session;

    if ($session->is_logged()) {

        $role = $session->get_user()->role;

        switch ($role) {
            case Role::Developer:
            case Role::Warehouse:
                header("location: /admin/dashboard.php");
                break;
            default:
            header("location: /dashboard.php");
                break;
        }

        exit();
    }
    
}

$session = new SessionManager();

?>