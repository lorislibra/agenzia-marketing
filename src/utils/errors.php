<?php

require_once("src/middleware/session.php");

function show_error(string $page)
{
    global $session;
    if ($error = $session->get_error($page)) {
        echo("alert(\"$error\");");
    }
}

?>