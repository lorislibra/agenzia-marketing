<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_id.php");
require_once("src/middleware/request.php");
require_once("src/components/item.php");
require_once("src/components/lateral_menu.php");

allowed_methods(["GET"]);
need_warehouse();

try {
    $dto = ShowIdDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    $session->add_error("order", "invalid order");
    header("location: /admin/orders.php");
    exit();
}

$connection = DbManager::build_connection_from_env();
$reservation_item_repo = new ReservationItemRepo($connection);
$reservation_repo = new ReservationRepo($connection);

$user = $session->get_user();

$reservation = $reservation_repo->get_by_id($dto->id);
if (!$reservation) {
    $session->add_error("order", "order didn't exist");
    header("location: /admin/orders.php");
    exit();
}

$items = $reservation_item_repo->get_by_reservation_id($reservation->id);
$items = $items[$reservation->id];

$disable_date = $reservation->status == OrderStatus::Waiting || $reservation->status == OrderStatus::Arrived;

?>

<html lang="en">
    <head>
        <title>Order</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Order", "admin")); ?>

        <div class="body_main">

            <?php if ($err = $session->get_error("order")) echo("<p>$err</p>"); ?>

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

                <div class="order_det_info">
                    <form action="/api/update_delivery_date.php" method="post"">
                        <input type="hidden" name="reservation_id" value="<?php echo($reservation->id); ?>">
                        <b>Delivery date:</b><br><br>
                        <input type="date" <?php if ($disable_date) echo("disabled") ?> name="delivery_date" onchange="this.form.submit()" value="<?php
                            if ($reservation->date_delivery){
                                echo $reservation->date_delivery->format('Y-m-d');
                            }
                        ?>">
                    </form>
                    
                </div>

                <p class="order_det_info" style="flex: right;">
                    <?php
                        echo '<b>Sell point:</b><br><br> ' . $reservation->sell_point->name;
                    ?>
                </p>

                <div class="order_det_info">
                    <b>Status: </b><br><br>
                    <?php echo($reservation->status->string()); ?>
                    <form action="/api/update_status.php" method="post">
                        <input type="hidden" name="id" value="<?php echo($reservation->id); ?>">
                        <?php 
                        switch ($reservation->status) {
                            case OrderStatus::Waiting:
                                echo("<input type=\"submit\" value=\"Approva\">");
                                break;
                            case OrderStatus::Shipping:
                                echo("<input type=\"submit\" value=\"Conferma arrivo\">");
                            default:
                                break;
                        }
                        ?>
                    </form>
                </div>
                
                <div class="order_det_comments"> 
                    <b>Comments: </b><br><br>           
                    <form action="/api/update_comment.php" method="post">
                        <input type="hidden" name="reservation_id" value="<?php echo($reservation->id); ?>">
                        <input type="text" name="comment" class="filter_input" style="width: 40%; text-align: center;"
                            <?php 
                                if($reservation->comment) {
                                    echo 'value="' . $reservation->comment . '"'; 
                                }
                                else{
                                    echo 'placeholder="---"';
                                }
                            ?>>
                        <br><br><input type="submit" class="filter_submit" style="padding: 15px; border-radius: 5px;" value="Aggiorna">
                    </form>
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