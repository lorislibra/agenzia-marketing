<?php

require_once("src/templates/lateral_menu.php");
require_once("src/templates/items_template.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");

need_logged();

$connection = DbManager::build_connection_from_env();

$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all();

function is_selected(string $order_value){
    if(!empty($_POST["content_order"]) && $_POST["content_order"] == $order_value){
        return true;
    }

    return false;
}

?>

<html>
    <head>
        <title>Showcase</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Items")); ?>
        <div class="body_main">
            <div class="items_list">
                <?php echo(show_items($items)); ?>
            </div>
        </div>
        <script>
            if(window.history.replaceState){
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>
