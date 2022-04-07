<?php

class Region
{
    public static $table = "region";

    public int $id;
    public string $name;

    function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

?>