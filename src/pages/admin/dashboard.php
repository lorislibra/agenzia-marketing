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

$count_status = $reservation_repo->count_status();

$waiting_count = isset($count_status[OrderStatus::Waiting->value]) ? $count_status[OrderStatus::Waiting->value] : 0;
$approved_count = isset($count_status[OrderStatus::Approved->value]) ? $count_status[OrderStatus::Approved->value] : 0;
$shipping_count = isset($count_status[OrderStatus::Shipping->value]) ? $count_status[OrderStatus::Shipping->value] : 0;
$arrived_count = isset($count_status[OrderStatus::Arrived->value]) ? $count_status[OrderStatus::Arrived->value] : 0;

$top_users = $reservation_repo->get_top_users();
// scorrilo e dentro c'è un array: index 0 --> oggetto user , index 1 --> numeri di ordini eseguiti

?>

<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Dashboard", "admin")); ?>
        <div class="back_img_login"></div>
        <div class="body_main">
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
                <div class="dash_area">
                    <p class="dash_name">Top users</p>
                    <div class="dash_ranking">
                        <div class="dash_ranking_place">
                            <p class="dash_ranking_place_info"><b>#</b></p>
                            <p class="dash_ranking_place_info"><b>User mail</b></p>
                            <p class="dash_ranking_place_info"><b>Orders</b></p>
                        </div>
                        <?php
                            $position = 1;
                            foreach($top_users as $top_user){
                                $top_user_mail = $top_user[0]->email;
                                $top_user_number_of_orders = $top_user[1];

                                $html = '';
                                $html .= '<div class="dash_ranking_place">';
                                $html .= '<p class="dash_ranking_place_info">' . $position . '</p>';
                                $html .= '<p class="dash_ranking_place_info">' . $top_user_mail . '</p>';
                                $html .= '<p class="dash_ranking_place_info"> ' . $top_user_number_of_orders . ' </p>';
                                $html .= '</div>';
                                echo $html;

                                $position++;
                            }
                        ?>
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