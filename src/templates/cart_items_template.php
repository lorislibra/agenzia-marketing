<?php

require_once("src/entities/cart_item.php");

function show_user_cart_items(array $user_cart_items){
    $html_code = '';
    
    if($user_cart_items != null){
        foreach($user_cart_items as $cart_items){
            foreach($cart_items as $cart_item){
                $html_code .= show_item($cart_item);
            }
        }
    }

    return $html_code;
}

function show_item(CartItem $cart_item){
    $html_code = '
                ' . $cart_item->item->product->name . '
                ' . $cart_item->quantity . '
                ';

    return $html_code;
}

?>