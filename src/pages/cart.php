<?php

use JetBrains\PhpStorm\ExpectedValues;

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");
require_once("src/templates/lateral_menu.php");
require_once("src/templates/cart_items_template.php");

allowed_methods(["GET", "POST"]);
need_logged();

function add_cart_item_tx(PDO $connection, int $user_id, AddToCartDto $dto)
{   
    $item_repo = new ItemRepo($connection);
    $cart_item_repo = new CartItemRepo($connection);

    if (!$connection->beginTransaction()) {
        $connection->rollBack();
        throw new Exception("error during transaction"); 
    }

    $item = $item_repo->get_by_id($dto->item_id);
    if (!$item) {
        $connection->rollBack();
        throw new Exception("item doesn't exist");
    }

    if ($item->stock < $dto->quantity) {
        $connection->rollBack();
        throw new Exception("not enough items");
    }

    if (!$cart_item_repo->add_or_update_cart_item($user_id, $dto)) {
        $connection->rollBack();
        throw new Exception("error adding to cart");
    }

    if (!$item_repo->remove_stock($dto->item_id, $dto->quantity)) {
        $connection->rollBack();
        throw new Exception("error removing stock");
    }

    $connection->commit();
}

if (is_post()) {
    try {
        $dto = AddToCartDto::from_array($_POST);
    } catch (ValidateDtoError $e) {
        $session->add_error("cart", "invalid add to cart");
        header("location: /items.php");
        exit();
    }

    $user = $session->get_user();
    $connection = DbManager::build_connection_from_env();

    try {
        add_cart_item_tx($connection, $user->id, $dto);
    } catch (Exception $e) {
        $session->add_error("cart", $e->getMessage());
    }

    header("location: /cart.php");
    exit();
}

if(is_get()){
    $user = $session->get_user();
    $connection = DbManager::build_connection_from_env();

    $cart_repo = new CartItemRepo($connection);
    $user_cart = null;
    try{
        $user_cart = $cart_repo->get_by_user_id($user->id);
    }
    catch(Exception $e){
        $session->add_error("cart", $e->getMessage());
    }
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
            <?php
                if($user_cart != null){
                    echo(show_user_cart_items($user_cart));
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