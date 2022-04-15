<?php

class SessionManager
{
    function __construct()
    {
        session_start();
        if (!isset($_SESSION["login_errors"])) {
            $_SESSION["login_errors"] = array();
        }
    }

    function is_logged(): bool
    {
        return $this->get_user() !== null;
    }

    function set_user(User $user)
    {
        $_SESSION["user"] = $user;
    }

    function get_user(): ?User
    {
        if (isset($_SESSION["user"])){
            return $_SESSION["user"];
        }
        return null;
    }

    function add_login_errors(string ...$error) {
        array_push($_SESSION["login_errors"], ...$error);
    }

    function get_login_errors(): Generator
    {
        while (!empty($_SESSION["login_errors"])) {
            yield array_shift($_SESSION["login_errors"]);      
        }
    }

    function logout()
    {
        unset($_SESSION["user"]);
        // session_destroy();
    }
}


?>