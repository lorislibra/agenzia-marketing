<?php

require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/update_cart.php");
require_once("src/entities/reservation.php");
require_once("src/dtos/update_delivery_date.php");

function update_date_and_status_tx(PDO $connection, UpdateDeliveryDateDto $dto)
{   
    $reservation_repo = new ReservationRepo($connection);

    if (!$connection->beginTransaction()) {
        $connection->rollBack();
        throw new Exception("error during transaction");
    }

    if (!$reservation_repo->update_status($dto->reservation_id, OrderStatus::Shipping)) {
        throw new Exception("error updating status");
    }

    if (!$reservation_repo->update_delivery_date($dto)) {
        throw new Exception("error updating date");
    }

    $connection->commit();
}

?>