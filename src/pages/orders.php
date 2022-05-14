<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/show_order.php");
require_once("src/middleware/request.php");

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
        <?php echo(show_lateral_menu("Items", "user")); ?>
            
        <div class="body_main">
            <?php var_dump($reservations); ?>    
        </div>
        
        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        
    </body>
</html>