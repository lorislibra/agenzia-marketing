<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/update_status.php");
require_once("src/dtos/show_id.php");

allowed_methods(["POST"]);
need_warehouse();

try {
    $dto = ShowIdDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("order", "order doesn't exist");
    header("location: /admin/orders.php");
    exit();
}

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);

if ($reservation = $reservation_repo->get_by_id($dto->id)) {
    switch ($reservation->status) {
        case OrderStatus::Waiting:
            if (!$reservation_repo->update_status($dto->id, OrderStatus::Approved)) {
                $session->add_error("order", "error updating status");
            }
            break;
        case OrderStatus::Shipping:
            if (!$reservation_repo->update_status($dto->id, OrderStatus::Arrived)) {
                $session->add_error("order", "error updating status");
            }
            break;

        default:
            $session->add_error("order", "invalid current status");
            break;
    }
} else {
    $session->add_error("order", "reservation doesn't exist");
}

header("location: /admin/order.php?id=".$dto->id);

?>