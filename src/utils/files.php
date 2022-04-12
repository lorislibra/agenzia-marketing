<?php

function rglob(string $patterns, $flags = null): array {
    $result = glob($patterns, $flags);
    foreach ($result as $item) {
        if (is_dir($item)) {
            array_push($result, ...rglob($item . '/*', $flags));
        }
    }

    return $result;
}

?>