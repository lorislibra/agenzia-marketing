<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/cart_item.php");
require_once("src/entities/product.php");
require_once("src/dtos/add_to_cart.php");

class CartItemRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
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
    function get_by_user_id(int $user_id): ?array
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

    function add_cart_item_tx(int $user_id, AddToCartDto $dto)
    {   
        $connection = $this->get_connection();

        if (!$connection->beginTransaction()) {
            $connection->rollBack();
            throw new Exception("error during transaction"); 
        }

        $item_repo = new ItemRepo($connection);
        $item = $item_repo->get_by_id($dto->item_id);
        if (!$item) {
            $connection->rollBack();
            throw new Exception("item doesn't exist");
        }

        if ($item->stock < $dto->quantity) {
            $connection->rollBack();
            throw new Exception("not enough items");
        }

        if (!$this->add_cart_item($user_id, $dto)) {
            $connection->rollBack();
            throw new Exception("error adding to cart");
        }

        if (!$item_repo->remove_stock($dto->item_id, $dto->quantity)) {
            $connection->rollBack();
            throw new Exception("error removing stock");
        }

        $connection->commit();
    }

    function add_cart_item(int $user_id, AddToCartDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        INSERT INTO cart_item (user_id, item_id, quantity)
        VALUES (:user_id, :item_id, :quantity);
        ");

        if ($stmt->execute([
            "user_id" => $user_id,
            "item_id" => $dto->item_id,
            "quantity" => $dto->quantity
        ])) {
            return $stmt->rowCount() > 0;
        }
        
        return false;
    }
}

?>