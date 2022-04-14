<?php

// ib = item_box

function show_items(array $items){
    $html_code = '';

    if($items != null){
        foreach($items as $item){
            $html_code .= show_item($item);
        }
    }

    return $html_code;
}

function show_item(Item $item){
    $product = $item->product;

    $html_code = '
                <a class="item_box" onclick="open_order_window()" href="#order_window">
                    <img class="ib_image" alt="' . strtoupper($product->name). '" src="' . $product->image . '">
                    <span class="ib_name">€' . number_format($product->price, 2) . '<br>' . strtoupper($product->name) . '</span>
                </a>
                ';
    
    return $html_code;
}

?>