<?php

require_once("src/entities/cart_item.php");

function show_user_cart_items(array $user_cart_items){
    $html_code = '';

    if($user_cart_items != null){
        foreach($user_cart_items as $cart_item){
            $html_code .= show_item($cart_item);
        }
    }

    return $html_code;
}

function show_item(CartItem $cart_item){
    /*
        Image - need CSS
        Name - need CSS
        Brand - need CSS
        N. of stocks - need CSS
        Products per stock - need CSS
        Order button - need CSS
    */
    $item = $cart_item->item;
    $product = $item->product;

    $html_code = '
                <div class="cart_item">
                    <img class="cart_image" alt="' . strtoupper($product->name) .'" src="' . $product->image . '">
                    <p class="cart_info" style="top: 10%;">Name: <b>' . $product->name . '</b></p>
                    <p class="cart_info" style="top: 22.5%;">Brand: <b>' . $product->brand . '</b></p>
                    <p class="cart_info" style="top: 35%;">N. of stocks: <b>' . $cart_item->quantity . '</b></p>
                    <p class="cart_info" style="top: 47.5%;">Prod. per stock: <b>' . $item->quantity . '</b></p>
                    <p class="cart_info" style="top: 60%;">Cost: <b>€' . number_format($cart_item->quantity * $item->quantity * $product->price, 2) . '</b></p>
                    <form method="POST" action="">
                        <input type="hidden" name="item_id" value="' . $cart_item->item_id . '">
                        <input type="hidden" name="user_id" value="' . $cart_item->user_id . '">
                        <button class="cart_order_button" style="vertical-align: middle;">
                            ORDER
                        </button>
                    </form>
                </div>
                ';

    return $html_code;
}

?>