Skip to content
Search or jump to…
Pull requests
Issues
Marketplace
Explore
 
@loLLo32 
lorislibra
/
agenzia-marketing
Public
Code
Issues
Pull requests
Actions
Projects
Wiki
Security
Insights
agenzia-marketing/src/pages/cart.php /
@lorislibra
lorislibra add to cart transaction
Latest commit e30b7a4 yesterday
 History
 2 contributors
@lorislibra@loLLo32
89 lines (73 sloc)  2.29 KB
   
<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");
require_once("src/templates/lateral_menu.php");

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
        <?php echo($session->get_error("cart")); ?>
        </div>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>
© 2022 GitHub, Inc.
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About
Loading complete