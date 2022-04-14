<?php

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
    $html_code = '
                <div class="item_box">
                    
                </div>
                ';
    
    return $html_code;
}

?>