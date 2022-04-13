<?php

function is_post(): bool
{
    return $_SERVER["REQUEST_METHOD"] == "POST";
}

function is_get(): bool
{
    return $_SERVER["REQUEST_METHOD"] == "GET";
}

?>