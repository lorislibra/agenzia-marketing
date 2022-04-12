<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/cart_item.php");
require_once("src/entities/product.php");

class CartItemRepo extends DbManager
{

    public function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp cartItem from the row
            $cart_item = CartItem::build_from_row($metadata, $row);

            // add the cart_item in the list
            $list[$cart_item->user_id][$cart_item->item->id] = $cart_item; 
                       
        }

        return $list;
    }

    // get cart_items of a user by its id
    public function get_by_user_id(int $user_id): array
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