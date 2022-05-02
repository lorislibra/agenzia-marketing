<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_order.php");
require_once("src/middleware/request.php");

allowed_methods(["GET"]);
need_logged();

try {
    $dto = ShowOrderDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    header("location: /cart.php");
    $session->add_error("order", "invalid order");
    exit();
}

$connection = DbManager::build_connection_from_env();
$reservation_item_repo = new ReservationItemRepo($connection);
$reservation_repo = new ReservationRepo($connection);

$user = $session->get_user();

$error = "";

$reservation = $reservation_repo->get_by_id($dto->id);
if (!$reservation || $reservation->user_id != $user->id) {
    $session->add_error("order", "order didn't exist");
    exit();
}

echo("order id: " . $reservation->id);

?>