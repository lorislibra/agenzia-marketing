<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");

allowed_methods(["GET"]);
need_warehouse();

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);
$reservations = $reservation_repo->get_all();

var_dump($reservations);

?>