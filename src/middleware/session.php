<?php

class SessionManager
{
    function __construct()
    {
        session_start();

        foreach (["login", "cart"] as $page) {
            $page = strtolower($page);
            if (!isset($_SESSION[$page])) {
                $_SESSION[$page] = array(
                    "error" => ""
                );
            }
        }
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

    function add_error(string $page, string $error) {
        $page = strtolower($page);
        $_SESSION[$page]["error"] = $error;
    }

    function get_error(string $page): string
    {
        $page = strtolower($page);
        return $_SESSION[$page]["error"];
    }

    function logout()
    {
        unset($_SESSION["current_user"]);
    }
}


?>