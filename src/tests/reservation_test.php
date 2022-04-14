<?php

require_once("src/repositories/reservation_repo.php");
require_once("test.php");

$reservation_repo = new ReservationRepo($connection);

if ($reservations = $reservation_repo->get_all()) {
    var_dump($reservations);
} else {
    echo("no reservations\n");
}

line();

if ($reservations = $reservation_repo->get_by_id(1)) {
    var_dump($reservations);
} else {
    echo("no reservation\n");
}

?>