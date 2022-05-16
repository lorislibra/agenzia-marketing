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

try {
    $dto = ShowIdDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    $session->add_error("order", "invalid order");
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
            <?php var_dump($reservation); ?>    
        </div>
        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>