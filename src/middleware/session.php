<?php

class SessionManager
{
    function __construct()
    {
        session_start();
    }

    function is_logged(): bool
    {
        return $this->get_user() !== null;
    }

    function set_user(User $user)
    {
        $_SESSION["current_user"] = $user;
    }

    function get_user(): ?User
    {
        if (isset($_SESSION["current_user"])){
            return $_SESSION["current_user"];
        }
        return null;
    }

    function add_error(string $page, $error) {
        $page = strtolower($page);
        $_SESSION[$page]["error"] = $error;
    }

    function get_error(string $page)
    {
        $page = strtolower($page);
        $val = $_SESSION[$page]["error"];
        $_SESSION[$page]["error"] = "";
        return $val;
    }

    function logout()
    {
        unset($_SESSION["current_user"]);
    }
}


?>