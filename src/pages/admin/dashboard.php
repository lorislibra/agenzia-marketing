<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/components/lateral_menu.php");

allowed_methods(["GET"]);
need_warehouse();

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);

$reservation_count = $reservation_repo->count();

?>

<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Dashboard", "admin")); ?>
        <div class="body_main">
            <h1>
                <?php echo("Ordini eseguiti in totale: $reservation_count"); ?>
            </h1>
        </div>

        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>