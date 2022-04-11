<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/product.php");

class ItemRepo extends DbManager
{

    public function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp item from the row
            $item = Item::build_from_row($metadata, $row);

            try {
                // build the temp product from the row
                $product = Product::build_from_row($metadata, $row);
            } catch (MissingColumnError $e) { 
                $product = null;
            }

              // if the product is in the row, make it the item "product" field
              if ($product) {
                $item->product = $product;
            }

            // add the item in the list
            $list[$item->id] = $item;
            
        }

        return $list;
    }
    
    #region VERIFIED
    #endregion

    #region DONE
    
    // get an item by its id
    public function get_by_id(int $id): ?Item
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM item
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE item.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $items = $this->parse_fetch($stmt);

            // if there are more than 0 items return the first
            if (count($items)) {
                return $items[array_key_first($items)];
            }
        }
        
        return null;
    }

    // get the items of a certain product by sku
    public function get_by_sku(int $sku): array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM item
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE product.sku = :sku;
        ");

        if ($stmt->execute(["sku" => $sku])) {
            $items = $this->parse_fetch($stmt);

            // if there are more than 0 items return the first
            if (count($items)) {
                return $items[array_key_first($items)];
            }
        }
        
        return null;
    }

    // get all items
    public function get_all(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM item
        LEFT JOIN product ON item.product_sku = product.sku;
        ");

        if ($stmt->execute()) {
            $items = $this->parse_fetch($stmt);
            return $items;
        }

        return null;
    }

    #endregion

    #region PARTIAL
    #endregion

    #region TODO
    #endregion
}

?>