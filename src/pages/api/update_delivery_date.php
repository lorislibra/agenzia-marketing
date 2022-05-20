<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/update_delivery_date.php");

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

if (!$reservation_repo->update_delivery_date($dto)) {
    $session->add_error("order", "error updating delivery date");
}

header("location: /admin/order.php?id=".$dto->reservation_id);

?>