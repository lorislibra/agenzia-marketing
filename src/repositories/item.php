<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/cart_item.php");
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

            // add the item in the list
            $list[$item->id] = $item;           
        }

        return $list;
    }
    
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

    public function get_all_item_in_cart(int $user_id): array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM cart_item
        LEFT JOIN item ON item.id = cart_item.item_id
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE cart_item.user_id = :user_id;
        ");

        if ($stmt->execute(["user_id" => $user_id])) {
            $items = $this->parse_fetch($stmt);
            return $items;
        }

        return null;
    }
}

?>