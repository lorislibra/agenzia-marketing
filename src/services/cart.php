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

function remove_from_cart_item_tx(PDO $connection, int $user_id, AddToCartDto $dto)
{
    $item_repo = new ItemRepo($connection);
    $cart_item_repo = new CartItemRepo($connection);

    if (!$connection->beginTransaction()) {
        $connection->rollBack();
        throw new Exception("error during transaction");
    }

    $cart_item = $cart_item_repo->get($user_id, $dto->item_id);
    if (!$cart_item) {
        $connection->rollBack();
        throw new Exception("item isn't in the cart");
    }

    if ($cart_item->quantity - $dto->quantity > 0) {
        if (!$cart_item_repo->update($user_id, $dto)) {
            $connection->rollBack();
            throw new Exception("can't update the quantity");   
        }
    } else if ($cart_item->quantity - $dto->quantity == 0) {
        if (!$cart_item_repo->delete($user_id, $dto->item_id)) {
            $connection->rollBack();
            throw new Exception("can't delete the item from the cart");   
        }
    } else {
        $connection->rollBack();
        throw new Exception("not enought item in the cart");
    }

    if (!$item_repo->add_stock($dto->item_id, $dto->quantity)) {
        $connection->rollBack();
        throw new Exception("error adding stock");
    }

    $connection->commit();
}

?>