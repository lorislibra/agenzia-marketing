<?php

class MissingColumnError extends Error { }

class QueryMetadata
{
    // array with with table and column linked with their position in the row
    private array $indexes;

    function __construct(PDOStatement $statement)
    {
        $this->indexes = array();
        
        for ($i=0; $i < $statement->columnCount(); $i++) { 
            $meta = $statement->getColumnMeta($i);
            $key = $this->format_index_key($meta["table"], $meta["name"]);
            $this->indexes[$key] = $i;
        }
    }

    public function format_index_key(string $table, string $column): string 
    {
        return $table . "_" . $column;
    }

    public function exists(string $table, array $columns): bool 
    {
        foreach ($columns as $column) {
            if (!$this->exist($table, $column)) {
                return false;
            }
        }
        return true;
    }

    public function exist(string $table, string $column): bool 
    {
        return array_key_exists($this->format_index_key($table, $column), $this->indexes);
    }

    public function get(string $table, string $column) 
    {
        $key = $this->format_index_key($table, $column);
        if (array_key_exists($key, $this->indexes)){
            return $this->indexes[$key];
        }

        return null;
    }

}


?>