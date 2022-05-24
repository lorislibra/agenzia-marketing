<?php

require_once("src/entities/cart_item.php");

function show_cart_item(CartItem $cart_item){
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

    // TODO: create only 1 button for all items in the cart
    //       with a combobox to select the sell point
    //       currently it uses always the sell point with id 1

    return '
        <div class="cart_item">
            <a href="https://5ailorislibralato.barsanti.edu.it/item.php?id=' . $item->id . '">
                <img class="cart_image" alt="' . strtoupper($product->name) .'" src="' . $item->image . '">
            </a>
            <p class="cart_info" style="top: 10%;">Name: <b>' . $product->name . '</b></p>
            <p class="cart_info" style="top: 22.5%;">Brand: <b>' . $product->brand . '</b></p>
            <p class="cart_info" style="top: 35%;">N. of stocks: <b>' . $cart_item->quantity . '</b></p>
            <p class="cart_info" style="top: 47.5%;">Prod. per stock: <b>' . $item->quantity . '</b></p>
            <p class="cart_info" style="top: 60%;">Cost: <b>€' . number_format($cart_item->quantity * $item->quantity * $product->price, 2) . '</b></p>
            <form method="POST" action="/api/remove_from_cart.php">
                <input type="hidden" name="item_id" value="'. $item->id .'">
                <input type="hidden" name="quantity" value="'. $cart_item->quantity .'">
                <input class="cart_order_button" style="position: absolute; left: 60%; bottom: 2%; padding: 10px; border-radius: 10px; transform: translateX(-50%);" type="submit" value="REMOVE">
            </form>
        </div>
    ';
}

?>