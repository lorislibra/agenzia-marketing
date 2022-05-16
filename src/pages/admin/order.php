<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_id.php");
require_once("src/middleware/request.php");
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
            <?php var_dump($reservation); ?>
            <br>
            <?php var_dump($items); ?>

            <form action="/api/update_status.php" method="post">
                <input type="hidden" name="reservation_id" value="<?php echo($reservation->id); ?>">
                <select name="status" onchange="this.form.submit()">
                    <?php
                    foreach (OrderStatus::all() as $status) {
                        $status_text = $status->string();
                        $status_int = (int)$status;
                        echo("<option name=\"$status_int\">$status_text</option>");
                    }
                    ?>
                </select>
            </form>

            <form action="/api/update_comment.php" method="post">
                <input type="hidden" name="reservation_id" value="<?php echo($reservation->id); ?>">
                <input type="text" name="comment">
                <input type="submit" value="Aggiorna">
            </form>
        </div>
        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        
    </body>
</html>