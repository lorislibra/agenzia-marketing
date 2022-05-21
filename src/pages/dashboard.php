<?php

require_once("src/components/lateral_menu.php");
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

$count_status = $reservation_repo->count_status_by_user_id($user->id);

$waiting_count = isset($count_status[OrderStatus::Waiting->value]) ? $count_status[OrderStatus::Waiting->value] : 0;
$shipping_count = isset($count_status[OrderStatus::Approved->value]) ? $count_status[OrderStatus::Approved->value] : 0;
$shipping_count = isset($count_status[OrderStatus::Shipping->value]) ? $count_status[OrderStatus::Shipping->value] : 0;
$arrived_count = isset($count_status[OrderStatus::Arrived->value]) ? $count_status[OrderStatus::Arrived->value] : 0;
?>

<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Dashboard", "user")); ?>
        <div class="body_main">
            <h1>
                <?php echo("Ordini eseguiti: $reservation_count"); ?>
            </h1>

            <?php var_dump($count_status); var_dump($waiting_count); ?>
        </div>

        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>