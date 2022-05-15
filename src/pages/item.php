<?php

require_once("src/components/lateral_menu.php");
require_once("src/components/item.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/session.php");
require_once("src/middleware/request.php");
require_once("src/dtos/show_id.php");

allowed_methods(["GET"]);
need_logged();

try {
    $dto = ShowIdDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    $session->add_error("item", "invalid item");
    header("location: /items.php");
    exit();
}

// if the user put two query argument with the same name apache put in the $_GET array only the last element
// es: ?id=1&id=3 => $_GET["id"] == 3

$connection = DbManager::build_connection_from_env();
$item_repo = new ItemRepo($connection);
$item = $item_repo->get_by_id($dto->id);

if (!$item || $item->stock == 0) {
    $session->add_error("item", "item doesn't exist");
    header("location: /items.php");
    exit();
}

function item_component(Item $item): string
{
    $product = $item->product;
    return '
        <div class="order_window">
            <img class="order_image" alt="' . strtoupper($product->name). '" src="' . $product->image . '">
            <p class="order_info" style="top: 12%;">Name: <b>' . $product->name . '</b></p>
            <p class="order_info" style="top: 30%;">Brand: <b>' . $product->brand . '</b></p>
            <p class="order_info" style="top: 48%;">Products per item: <b>' . $item->quantity . '</b></p>
            <button id="btn_sub" class="order_number_add_sub" style="left: 30%;" onclick="modify_order_quantity(-1, ' . $item->stock . ')" disabled>-</button>
            <button id="btn_add" class="order_number_add_sub" style="left: 53.4%;" onclick="modify_order_quantity(1, ' . $item->stock . ')">+</button>
            <a class="close_order_window" href="/items.php">✕</a>
            <form method="POST" action="/api/add_to_cart.php">
                <input type="hidden" name="item_id" value="' . $item->id . '">
                <input id="order_number_input" type="number" name="quantity" onchange="check_value(' . $item->stock . ')" min="1" value="1" max="' . $item->stock . '">
                <button class="order_button">
                    ADD TO CART
                </button>
            </form>
        </div>';
}

?>

<html lang="en">
    <head>
        <title><?php echo($item->product->name) ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Item", "user")); ?>
        <div class="body_main">
            <?php echo(item_component($item)); ?>
            <?php if ($error = $session->get_error("cart")) echo('<p class="login_errors">' . $error . '</p>'); ?>
        </div>

        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        
    </body>
</html>