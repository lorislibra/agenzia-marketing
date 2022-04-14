<?php

require_once("src/pages/index.php");
require_once("src/repositories/item_repo.php");

function show_items(){
    global $connection;

    $item_repo = new ItemRepo($connection);
    $items = $item_repo->get_all();

    foreach($items as $item){
        show_item($item->product->name);
    }
}

function show_item(string $name){
    echo $name;
}

?>