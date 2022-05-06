<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_order.php");
require_once("src/middleware/request.php");

allowed_methods(["GET"]);
need_logged();

$user = $session->get_user();

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);
$reservations = $reservation_repo->get_by_user_id($user->id);

var_dump($reservations);

?>