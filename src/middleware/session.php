<?php

class SessionManager
{
    function __construct()
    {
        session_start();
    }

    function is_logged(): bool {
        return $this->get_user() !== null;
    }

    function set_user(User $user) {
        $_SESSION["user"] = $user;
    }

    function get_user(): ?User{
        if (isset($_SESSION["user"])){
            return $_SESSION["user"];
        }
        return null;
    }
}


?>