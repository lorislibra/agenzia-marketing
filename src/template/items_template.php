<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("src/repositories/item_repo.php");

function show_items(){
    $connection = DbManager::build_connection_from_env();
    $item_repo = new ItemRepo($connection);
    $items = $item_repo->get_all();
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