<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");
require_once("src/components/lateral_menu.php");
require_once("src/components/cart_items.php");
require_once("src/services/cart.php");

allowed_methods(["GET"]);
need_logged();

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();

$cart_repo = new CartItemRepo($connection);
try {
    $user_cart = $cart_repo->get_by_user_id($user->id);
    $user_cart = $user_cart[$user->id];
}
catch (Exception $e) {
    $session->add_error("cart", $e->getMessage());
    $user_cart = null;
}

?>

<html>
    <head>
        <title>Cart</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Cart")); ?>
        <div class="body_main">
            <div class="cart_list">
                <?php
                    echo(show_user_cart_items($user_cart));
                ?>
            </div>
        </div>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>