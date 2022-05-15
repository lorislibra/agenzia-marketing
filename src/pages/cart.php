<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/repositories/sell_point_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");
require_once("src/components/lateral_menu.php");
require_once("src/components/cart_item.php");
require_once("src/services/cart.php");

allowed_methods(["GET"]);
need_logged();

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();
$cart_repo = new CartItemRepo($connection);
$sell_point_repo = new SellPointRepo($connection);
$error = "";
$user_cart = null;

try {
    $user_cart = $cart_repo->get_by_user_id($user->id);
    // if the user is in the array there is at least an item
    if (array_key_exists($user->id, $user_cart)) {
        $user_cart = $user_cart[$user->id];
    }
}
catch (Exception $e) {
    $error = $e->getMessage();
}

$sell_points = $sell_point_repo->get_all();

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
                <?php if ($user_cart) echo(join(array_map("show_cart_item", $user_cart))); ?>
            </div>
            <?php if ($error) echo($error); ?>
            <?php if ($error = $session->get_error("order")) echo($error); ?>
        </div>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>