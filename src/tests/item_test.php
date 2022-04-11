<?php

require_once("src/repositories/item.php");
require_once("test.php");

$item_repo = new ItemRepo($connection);

if ($items = $item_repo->get_all()) {
    var_dump($items);
} else {
    echo("no items\n");
}

line();

if ($items = $item_repo->get_all_item_in_cart(1)) {
    var_dump($items);
} else {
    echo("no items\n");
}

?>