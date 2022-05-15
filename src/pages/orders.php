<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_id.php");
require_once("src/middleware/request.php");
require_once("src/components/lateral_menu.php");

allowed_methods(["GET"]);
need_logged();

$user = $session->get_user();

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);
$reservations = $reservation_repo->get_by_user_id($user->id);

?>

<html lang="en">
    <head>
        <title>Orders</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Orders", "user")); ?>
            
        <div class="body_main">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Order date</th>
                        <th>Delivery date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($reservations as $reservation){
                            $delivery_date = ($reservation->date_delivery->format('d/m/Y') == "30/11/-0001") ? "---": $reservation->date_delivery->format('d/m/Y');

                            echo '
                                <tr>
                                    <td>' . $reservation->id . '</td>
                                    <td>' . $reservation->date_order->format('d/m/Y') . '</td>
                                    <td>' . $delivery_date . '</td>
                                    <td>' . $reservation->status->string() . '</td>
                                    <td></td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        
        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        
    </body>
</html>