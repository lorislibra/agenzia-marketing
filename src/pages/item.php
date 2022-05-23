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
    $session->add_error("item", $e->getMessage());
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

$product = $item->product;

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
            <img class="order_image" alt="<?php echo strtoupper($product->name); ?>" src="<?php echo $product->image; ?>">
            <p class="order_info" style="top: 12%;">Name: <b> <?php echo $product->name; ?> </b></p>
            <p class="order_info" style="top: 20%;">Brand: <b> <?php echo $product->brand; ?> </b></p>
            <p class="order_info" style="top: 28%;">Stock price: <b> €<?php echo ($product->price * $item->quantity); ?> </b></p>
            <p class="order_info" style="top: 36%;">Products per stock: <b> <?php echo $item->quantity; ?> </b></p>
            <p class="order_info" style="top: 44%;">Available stocks: <b> <?php echo $item->stock; ?> </b></p>
            <button id="btn_sub" class="order_number_add_sub" style="right: calc(40% + 30px);transform: translateX(50%);" onclick="modify_order_quantity(-1, <?php echo $item->stock; ?>)" disabled>-</button>
            <button id="btn_add" class="order_number_add_sub" style="right: calc(8% + 30px);transform: translateX(50%);" onclick="modify_order_quantity(1, <? echo $item->stock; ?>)">+</button>
            <a class="close_order_window" href="/items.php">✕</a>
            <form method="POST" action="/api/add_to_cart.php">
                <input type="hidden" name="item_id" value="<?php echo($item->id); ?>">
                <input id="order_number_input" type="number" name="quantity" onchange="check_value(<?php echo $item->stock; ?>)" min="1" value="1" max="<?php echo $item->stock; ?>">
                <button class="order_button">
                    ADD TO CART
                </button>
            </form>
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