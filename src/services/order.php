<?php

require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/repositories/cart_item_repo.php");
require_once("src/dtos/create_order.php");

function create_order_tx(PDO $connection, int $user_id, CreateOrderDto $dto)
{
    $cart_item_repo = new CartItemRepo($connection);
    $reservation_repo = new ReservationRepo($connection);
    $reservation_item_repo = new ReservationItemRepo($connection);

    if (!$connection->beginTransaction()) {
        $connection->rollBack();
        throw new Exception("error during transaction");
    }
    
    if (!$reservation_repo->add($user_id, $dto)) {
        $connection->rollBack();
        throw new Exception("error creating order");
    }

    $reservation_id = $connection->lastInsertId();

    if(!$reservation_item_repo->add_from_cart($user_id, $reservation_id)) {
        $connection->rollBack();
        throw new Exception("error adding item to the order");
    }

    if(!$cart_item_repo->delete_by_user_id($user_id)) {
        $connection->rollBack();
        throw new Exception("error cleaning the cart");
    }

    $connection->commit();
}

?>