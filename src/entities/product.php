<?php

require_once("src/repositories/manager.php");

class Product
{
    public static $table = "product";

    public string $sku;
    public string $brand;
    public string $category;
    public string $name;
    public string $image;
    public float $price;
    
    function __construct(string $sku, string $brand, string $category, string $name, string $image, float $price) {
        $this->sku = $sku;
        $this->brand = $brand;
        $this->category = $category;
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["sku", "brand", "category", "name", "price", "image"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $sku = DbManager::get_column($metadata, $row, self::$table, "sku");
        $brand = DbManager::get_column($metadata, $row, self::$table, "brand");
        $category = DbManager::get_column($metadata, $row, self::$table, "category");
        $name = DbManager::get_column($metadata, $row, self::$table, "name");
        $price = DbManager::get_column($metadata, $row, self::$table, "price");
        $image = DbManager::get_column($metadata, $row, self::$table, "image");

        return new self($sku, $brand, $category, $name, $image, $price);
    }
}

?>