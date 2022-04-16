<?php

require_once("src/templates/lateral_menu.php");
require_once("src/templates/items_template.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");

allowed_methods(["GET"]);
need_logged();

if (is_get()) {
    
}

$connection = DbManager::build_connection_from_env();

$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all();

function make_order(): string
{
    global $item_repo;

    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $item_id = $_GET["id"];
        $item = $item_repo->get_by_id($item_id);
        $product = $item->product;

        if ($item != null) {
            $make_order_html = '
                                <div class="order_window">
                                    <img class="order_image" alt="' . strtoupper($product->name). '" src="' . $product->image . '">
                                    <p class="order_info" style="top: 6%;">Name: <b>' . $product->name . '</b></p>
                                    <p class="order_info" style="top: 18%;">Brand: <b>' . $product->brand . '</b></p>
                                    <p class="order_info" style="top: 30%;">Item price: <b>€' . number_format($product->price * $item->quantity, 2) . '</b></p>
                                    <p class="order_info" style="top: 42%;">Products per item: <b>' . $item->quantity . '</b></p>
                                    <button id="btn_sub" class="order_number_add_sub" style="left: 34.2%;" onclick="modify_order_quantity(-1, ' . $item->stock . ')" disabled>-</button>
                                    <button id="btn_add" class="order_number_add_sub" style="left: 59%;" onclick="modify_order_quantity(1, ' . $item->stock . ')">+</button>
                                    <a class="close_order_window" href="items.php">x</a>
                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="item_id" value="' . $item_id . '">
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

    return '';
}

?>

<html lang="en">
    <head>
        <title>Showcase</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body <?php if(!empty($_GET["item"])) { echo 'class="body_blurred"'; } ?>>
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
    </body>
</html>
