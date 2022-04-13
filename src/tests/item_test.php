<?php

require_once("src/repositories/item.php");
require_once("src/repositories/cart_item.php");
require_once("test.php");

$item_repo = new ItemRepo($connection);

if ($items = $item_repo->get_all()) {
    var_dump($items);
} else {
    echo("no items\n");
}

line();

$cart_item_repo = new CartItemRepo($connection);

if ($cart_items = $cart_item_repo->get_by_user_id(1)) {
    var_dump($cart_items);
} else {
    echo("no items\n");
}

?>