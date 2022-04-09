<?php


require_once "src/repositories/manager.php";
require_once "src/repositories/metadata.php";


class Region
{
    public static $table = "region";

    public int $id;
    public string $name;

    function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["id", "name"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $name = DbManager::get_column($metadata, $row, self::$table, "name");

        if ($id && $name) {
            return new self($id, $name);
        }

        throw new MissingColumnError();
    }
}

?>