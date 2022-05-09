<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_repo.php");

allowed_methods(["GET"]);
need_warehouse();

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);

$reservation_count = $reservation_repo->count();

echo("totale ordini eseguiti: $reservation_count");

?>