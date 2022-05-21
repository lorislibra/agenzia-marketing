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
$approved_count = isset($count_status[OrderStatus::Approved->value]) ? $count_status[OrderStatus::Approved->value] : 0;
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

            <div class="dash_list">
                <div class="dash_area">
                    <p class="dash_name">Total orders</p>
                    <div class="dash_info">
                        <?php echo $reservation_count; ?> 
                    </div>
                </div>
                <div class="dash_area">
                    <p class="dash_name">Waiting orders</p>
                    <div class="dash_info">
                        <?php echo $waiting_count; ?>
                    </div>
                </div>
                <div class="dash_area">
                    <p class="dash_name">Approved orders</p>
                    <div class="dash_info">
                        <?php echo $approved_count; ?>
                    </div>
                </div>
                <div class="dash_area">
                    <p class="dash_name">Shipping orders</p>
                    <div class="dash_info">
                        <?php echo $shipping_count; ?>
                    </div>
                </div>
                <div class="dash_area">
                    <p class="dash_name">Arrived orders</p>
                    <div class="dash_info">
                        <?php echo $arrived_count; ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>