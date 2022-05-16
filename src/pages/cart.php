<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/repositories/sell_point_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/update_cart.php");
require_once("src/components/lateral_menu.php");
require_once("src/components/cart_item.php");
require_once("src/services/cart.php");

allowed_methods(["GET"]);
need_logged();

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();
$cart_repo = new CartItemRepo($connection);
$sell_point_repo = new SellPointRepo($connection);


$user_cart = $cart_repo->get_by_user_id($user->id);
$sell_points = $sell_point_repo->get_all_by_regions($user->regions);

if (array_key_exists($user->id, $user_cart)) {
    $user_cart = $user_cart[$user->id];
}

?>

<html>
    <head>
        <title>Cart</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Cart", "user")); ?>
        <div class="body_main">
            <div class="cart_list">
                <?php 
                    // no need to check if $user_cart exists because of array_map
                    echo(join(array_map("show_cart_item", $user_cart)));
                ?>
            </div>

            <?php 
                if ($error = $session->get_error("order")) echo($error);
        
                if(count($user_cart)) {
                    echo '<form method="POST" class="order_form" action="api/create_order.php">
                    <select class="cart_select" name="sell_point_id">';

                    foreach ($sell_points as $sell_point) {
                        $name = $sell_point->name . " " . $sell_point->address;
                        $id = $sell_point->id;
                        echo "<option value=\"$id\">$name</option>";
                    }

                    echo '</select>
                    <input type="submit" class="cart_order_button" style="vertical-align: middle;" value="ORDER">
                    </form>';
                } else {
                    echo('<h1 class="cart_notify">There are no items in the cart</h1>');
                }
            ?>
            
        </div>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>