<?php

require_once "src/repositories/product.php";
require_once "product.php";

class Item
{
    public static $table = "item";

    public int $id;
    public int $quantity;
    public int $stock;
    public string $category;
    public product $product;

    
    
    
    function __construct(int $id, int $quantity, int $stock, string $category) {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->stock = $stock;
        $this->category = $category;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["id", "product_sku", "quantity", "stock", "category"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $quantity = DbManager::get_column($metadata, $row, self::$table, "quantity");
        $stock = DbManager::get_column($metadata, $row, self::$table, "stock");
        $category = DbManager::get_column($metadata, $row, self::$table, "category");
        

        if ($id && $quantity && $stock && $category) {
            return new self($id, $quantity, $stock, $category);
        }

        throw new MissingColumnError();
        
    }
}

?>