<?php

function is_post(): bool
{
    return $_SERVER["REQUEST_METHOD"] == "POST";
}

function is_get(): bool
{
    return $_SERVER["REQUEST_METHOD"] == "GET";
}

function allowed_methods(array $methods)
{
    if (!in_array($_SERVER["REQUEST_METHOD"], array_map(fn(string $method): string => strtoupper($method), $methods))) {
        http_response_code(405);
        exit();
    }
}

?>