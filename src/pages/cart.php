<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");

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

    if (!$cart_item_repo->add_cart_item($user_id, $dto)) {
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
        header("location: /items.php");
        exit();
    }
    
    $user = $session->get_user();
    $connection = DbManager::build_connection_from_env();

    try {
        add_cart_item_tx($connection, $user->id, $dto);
    } catch (Exception $e) {
        $session->add_error("cart", "error adding to the cart");
    }

    header("location: /cart.php");
    exit();
}

?>

<html>
    <head>
        <title>Cart</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>