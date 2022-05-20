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

                <div class="order_det_info">
                    <b>Status: </b><br><br>
                    <form action="/api/update_status.php" method="post">
                        <input type="hidden" name="reservation_id" value="<?php echo($reservation->id); ?>">
                        <select class="filter_select" name="status" onchange="this.form.submit()">
                            <?php
                            foreach (OrderStatus::all() as $status) {
                                $status_text = $status->string();
                                $status_int = $status->value;
                                
                                $sel = ($reservation->status == $status) ? "selected" : "";
                                echo("<option $sel value=\"$status_int\">$status_text</option>");
                            }
                            ?>
                        </select>
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