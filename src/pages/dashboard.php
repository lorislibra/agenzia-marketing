<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");
require_once("src/repositories/reservation_repo.php");

allowed_methods(["GET"]);
need_logged();

$connection = DbManager::build_connection_from_env();
$user = $session->get_user();
$reservation_repo = new ReservationRepo($connection);
$reservation_count = $reservation_repo->count_by_user_id($user->id);

echo("Ordini eseguiti: $reservation_count");

?>