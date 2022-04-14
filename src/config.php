<?php

function load_env(string $path) :void
{
    if(!file_exists($path)) {
        throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
    }

    if (!is_readable($path)) {
        throw new \RuntimeException(sprintf('%s file is not readable', $path));
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim(trim($value), "\"");

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

$dir = __DIR__;

$separator = "";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $separator = "\\";
} else {
    $separator = "/";
}

while (1) {
    if (file_exists($dir . $separator . ".env")) {
        load_env($dir . $separator . ".env");
        break;
    }

    if (str_ends_with($dir, "agenzia-marketing") || count(explode($separator, $dir)) == 1) {
        break;
    }

    $dir = dirname($dir);
}

if (strtolower(getenv("DEBUG")) == "true") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

?>