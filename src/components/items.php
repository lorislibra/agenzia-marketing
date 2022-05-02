<?php

// ib = item_box

function show_items(array $items){
    $html_code = '';
    
    foreach($items as $item){
        $html_code .= show_item($item);
    }

    return $html_code;
}

function show_item(Item $item){
    $product = $item->product;

    return '
    <form class="item_box" method="GET" action="items.php">
        <button class="hidden_input_btn" name="id" value="' . $item->id . '">
            <img class="ib_image" alt="' . strtoupper($product->name). '" src="' . $product->image . '">
            <span class="ib_name">' . strtoupper($product->name) . '</span>
        </button>
    </form>
    ';
}

?>