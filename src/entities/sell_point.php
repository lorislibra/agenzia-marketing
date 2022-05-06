<?php

require_once("src/repositories/manager.php");
require_once("src/entities/region.php");

// SellPoint class that reflec sell_point table in the database
class SellPoint
{
    public static $table = "sell_point";

    public int $id;
    public string $name;
    public string $address;
    public int $region_id;
    public ?Region $region;

    function __construct(int $id, string $address, string $name, ?Region $region, int $region_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->region = $region;
        $this->region_id = $region_id;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["id", "name", "region_id", "address"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $name = DbManager::get_column($metadata, $row, self::$table, "name");
        $address = DbManager::get_column($metadata, $row, self::$table, "address");
        $region_id = DbManager::get_column($metadata, $row, self::$table, "region_id");

        try {
            $region = Region::build_from_row($metadata, $row);
        } catch (MissingColumnError $e) { 
            $region = null;
        }

        return new self($id, $address, $name, $region, $region_id);
    }
}

?>