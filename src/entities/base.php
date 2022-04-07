<?php

use PhpOption\None;

function get_column_index(PDOStatement $statement, string $table, string $column): ?int {
    $lenght = $statement->columnCount();
    for ($i=0; $i < $lenght; $i++) { 
        $meta = $statement->getColumnMeta($i);
        if ($meta["name"] == $column && $meta["table"] == $table){
            return $i;
        }
    }
    return null;
}

function get_column(PDOStatement $statement, array $result, string $table, string $column) {
    if (($index = get_column_index($statement, $table, $column)) !== null){
        return $result[$index];
    }
    return null;
}


?>