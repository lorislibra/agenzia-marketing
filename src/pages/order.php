<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_id.php");
require_once("src/middleware/request.php");
require_once("src/components/item.php");
require_once("src/components/lateral_menu.php");
require_once("src/dtos/show_items.php");

allowed_methods(["GET"]);
need_logged();

try {
    $dto = ShowIdDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    $session->add_error("order", $e->getMessage());
    header("location: /cart.php");
    exit();
}

$connection = DbManager::build_connection_from_env();
$reservation_item_repo = new ReservationItemRepo($connection);
$reservation_repo = new ReservationRepo($connection);

$user = $session->get_user();

$reservation = $reservation_repo->get_by_id($dto->id);
if (!$reservation || $reservation->user_id != $user->id) {
    $session->add_error("order", "order didn't exist");
    header("location: /cart.php");
    exit();
}

$items = $reservation_item_repo->get_by_reservation_id($reservation->id);
$items = $items[$reservation->id];

?>

<html lang="en">
    <head>
        <title>Order</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Order", "user")); ?>
        
        <div class="body_main">
            <div class="items_list">
                <?php 
                    foreach($items as $item){
                        echo show_order_item($item);
                    }
                ?>
            </div>
            
            <div class="order_det_infos">
                <p class="order_det_info">
                <?php
                    echo '<b>Order date:</b><br><br> ' . $reservation->date_order->format('d/m/Y');
                ?>
                </p>
                <p class="order_det_info">
                    <?php
                        echo '<b>Delivery date:</b><br><br> ';
                        if ($reservation->date_delivery){
                            echo $reservation->date_delivery->format('d/m/Y');
                        }
                        else{
                            echo "---";
                        }
                    ?>
                </p>
                <p class="order_det_info" style="flex: right;">
                    <?php
                        echo '<b>Sell point:</b><br><br> ' . $reservation->sell_point->name;
                    ?>
                </p>
                <p class="order_det_info">
                    <?php
                        echo "<b>Status:</b><br><br> " . $reservation->status->string();
                    ?>
                </p>
                <p class="order_det_comments">
                    <?php
                        echo '<b>Comments:</b><br><br> ';
                        if($reservation->comment){
                            echo $reservation->comment;
                        }
                        else{
                            echo '---';
                        }
                    ?>
                </p>
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