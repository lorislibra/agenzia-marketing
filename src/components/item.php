<?php

require_once("src/entities/item.php");

function show_item(Item $item){
    $product = $item->product;

    return '
        <form class="item_box" method="GET" action="/item.php?id=' . $item->id . ' ">
            <button class="hidden_input_btn" name="id" value="' . $item->id . '">
                <img class="ib_image" alt="' . strtoupper($product->name). '" src="' . $product->image . '">
                <span class="ib_name">' . strtoupper($product->name) . '</span>
            </button>
        </form>
    ';
}

?>