<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/dtos/add_to_cart.php");

function add_cart_item_tx(PDO $connection, int $user_id, AddToCartDto $dto)
{   
    $item_repo = new ItemRepo($connection);
    $cart_item_repo = new CartItemRepo($connection);

    if (!$connection->beginTransaction()) {
        $connection->rollBack();
        throw new Exception("error during transaction");
    }

    $item = $item_repo->get_by_id($dto->item_id);
    if (!$item) {
        $connection->rollBack();
        throw new Exception("item doesn't exist");
    }

    if ($item->stock < $dto->quantity) {
        $connection->rollBack();
        throw new Exception("not enough items");
    }

    if (!$cart_item_repo->add_or_update($user_id, $dto)) {
        $connection->rollBack();
        throw new Exception("error adding to cart");
    }

    if (!$item_repo->remove_stock($dto->item_id, $dto->quantity)) {
        $connection->rollBack();
        throw new Exception("error removing stock");
    }

    $connection->commit();
}

?>