<?php

use PhpOption\None;

require_once("src/repositories/manager.php");
require_once("region.php");

// SellPoint class that reflec sell_point table in the database
class SellPoint
{
    public static $table = "sell_point";

    public int $id;
    public string $address;
    public ?Region $region;

    function __construct(int $id, string $address, ?Region $region)
    {
        $this->id = $id;
        $this->address = $address;
        $this->region = $region;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["id", "region_id", "address"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $address = DbManager::get_column($metadata, $row, self::$table, "address");

        if ($id !== null && $address !== null) {
            return new self($id, $address, null);
        }

        return new MissingColumnError();
    }
}

?>