<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/product.php");

class ItemRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
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
    function get_by_id(int $id): ?Item
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM item
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE item.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $items = $this->parse_fetch($stmt);
            $selected_item = $this->get_first_element($items);
            return $selected_item;
        }
        
        return null;
    }

    // get the items of a certain product by sku
    function get_by_sku(int $sku): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM item
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE product.sku = :sku;
        ");

        if ($stmt->execute(["sku" => $sku])) {
            $items = $this->parse_fetch($stmt);
            return $items;
        }
        
        return null;
    }

    // get all items
    function get_all(): ?array
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

    function remove_stock(int $item_id, int $quantity): bool
    {
        $stmt = $this->get_connection()->prepare("
        UPDATE TABLE item
        SET stock = stock - :quantity
        WHERE id = :id
        ");

        if ($stmt->execute(["id" => $item_id, "quantity" => $quantity])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }
}

?>