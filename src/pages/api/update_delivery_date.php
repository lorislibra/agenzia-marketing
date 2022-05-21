<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/update_delivery_date.php");
require_once("src/services/order_date.php");

allowed_methods(["POST"]);
need_warehouse();

try {
    $dto = UpdateDeliveryDateDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("order", "order doesn't exist");
    header("location: /admin/orders.php");
    exit();
}

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);

if ($reservation = $reservation_repo->get_by_id($dto->reservation_id)) {
    switch ($reservation->status) {
        // update date if the status is shipping
        case OrderStatus::Shipping:
            if (!$reservation_repo->update_delivery_date($dto)) {
                $session->add_error("order", "error updating delivery date");
            }
            break;

        // update date and status if the status is approved
        case OrderStatus::Approved:
            try {
                update_date_and_status_tx($connection, $dto);
            } catch (Exception $e) {
                $session->add_error("order", $e->getMessage());
            }
            break;

        default:
            $session->add_error("order", "can't change order during this status");
            break;
    }
} else {
    $session->add_error("order", "reservation doesn't exist");
}



header("location: /admin/order.php?id=".$dto->reservation_id);

?>