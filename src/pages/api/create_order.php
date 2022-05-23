<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/create_order.php");
require_once("src/services/order.php");

allowed_methods(["POST"]);
need_logged();

try {
    $dto = CreateOrderDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("order", $e->getMessage());
    header("location: /cart.php");
    exit();
}

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();

try {
    $reservation_id = create_order_tx($connection, $user, $dto);
} catch (Exception $e) {
    $session->add_error("order", $e->getMessage());
    header("location: /cart.php");
    exit();
}

header("location: /order.php?id=$reservation_id");

?>