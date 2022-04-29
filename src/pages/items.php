<?php

require_once("src/templates/lateral_menu.php");
require_once("src/templates/items_template.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/session.php");
require_once("src/middleware/request.php");
require_once("src/dtos/show_item.php");

allowed_methods(["GET"]);
need_logged();

$connection = DbManager::build_connection_from_env();

$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all_with_filters();

try {
    $dto = ShowItemDto::from_array($_GET);
    $item = $item_repo->get_by_id($dto->id);
} catch (ValidateDtoError $e) {
    $session->add_error("items", "invalid item");
    $item = null;
}

function make_order(): string
{
    global $item;

    if ($item && $item->stock > 0) {
        $product = $item->product;
        $make_order_html = '
                            <div class="order_window">
                                <img class="order_image" alt="' . strtoupper($product->name). '" src="' . $product->image . '">
                                <p class="order_info" style="top: 12%;">Name: <b>' . $product->name . '</b></p>
                                <p class="order_info" style="top: 30%;">Brand: <b>' . $product->brand . '</b></p>
                                <p class="order_info" style="top: 48%;">Products per item: <b>' . $item->quantity . '</b></p>
                                <button id="btn_sub" class="order_number_add_sub" style="left: 30%;" onclick="modify_order_quantity(-1, ' . $item->stock . ')" disabled>-</button>
                                <button id="btn_add" class="order_number_add_sub" style="left: 53.4%;" onclick="modify_order_quantity(1, ' . $item->stock . ')">+</button>
                                <a class="close_order_window" href="items.php">✕</a>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="item_id" value="' . $item->id . '">
                                    <input id="order_number_input" type="number" name="quantity" onchange="check_value(' . $item->stock . ')" min="1" value="1" max="' . $item->stock . '">
                                    <button class="order_button">
                                        ADD TO CART
                                    </button>
                                </form>
                            </div>
                            ';

        return $make_order_html;
    }

    return '';
}

function show_error(): string 
{
    global $session;

    if ($error = $session->get_error("items")) {
        return '<p class="login_errors">' . $error . '</p>';
    }

    return "";
}

?>

<html lang="en">
    <head>
        <title>Showcase</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body <?php if($item && $item->stock > 0) { echo 'class="body_blurred"'; } ?>>
        <?php echo(show_lateral_menu("Items")); ?>
        <div class="body_main">
            <div class="items_list">
                <?php echo(show_items($items)); ?>
            </div>
        </div>
        <?php echo(make_order()); ?>
        <script src="js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        <?php echo(show_error()); ?>
    </body>
</html>
