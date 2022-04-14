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
                <div class="item_box">
                    <img class="ib_image" alt="' . strtoupper($product->name). '" src="' . /*$product->image*/"https://peroni.it/wp-content/uploads/2019/09/peroni-33-feat.jpg" . '">
                    <span class="ib_name">' . strtoupper($product->name) . '</span>
                </div>
                ';
    
    return $html_code;
}

?>