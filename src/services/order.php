<?php

require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/sell_point_repo.php");
require_once("src/dtos/create_order.php");
require_once("src/entities/user.php");

function create_order_tx(PDO $connection, User $user, CreateOrderDto $dto): int
{
    $cart_item_repo = new CartItemRepo($connection);
    $reservation_repo = new ReservationRepo($connection);
    $reservation_item_repo = new ReservationItemRepo($connection);
    $sell_point_repo = new SellPointRepo($connection);

    if (!$connection->beginTransaction()) {
        $connection->rollBack();
        throw new Exception("error during transaction");
    }

    $sell_point = $sell_point_repo->get_by_id($dto->sell_point_id);
    if (!$sell_point) {
        $connection->rollBack();
        throw new Exception("no sell point found");
    }

    $user_valid_region = false;
    foreach ($user->regions as $region) {
        if ($region->id == $sell_point->region_id) {
            $user_valid_region = true;
            break;
        }
    }

    if (!$user_valid_region) {
        $connection->rollBack();
        throw new Exception("user can't order for this sell point");
    }

    if (!$reservation_repo->add($user->id, $dto)) {
        $connection->rollBack();
        throw new Exception("error creating order");
    }

    $reservation_id = $connection->lastInsertId();

    if(!$reservation_item_repo->add_from_cart($user->id, $reservation_id)) {
        $connection->rollBack();
        throw new Exception("error adding item to the order");
    }

    if(!$cart_item_repo->delete_by_user_id($user->id)) {
        $connection->rollBack();
        throw new Exception("error cleaning the cart");
    }

    $connection->commit();
    
    return $reservation_id;
}

?>