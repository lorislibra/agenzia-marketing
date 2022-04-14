<?php

/*

TO DO:
    - Add filters
    - Let the user make an order

*/

require_once("src/templates/lateral_menu.php");
require_once("src/templates/items_template.php");
require_once("src/repositories/item_repo.php");

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
            <div class="items_research">
                <form method="POST" action="" class="form_research_content">
                    <input class="research_bar" type="text" name="content_filter" placeholder="Search..." autocomplete="off">
                    <input class="research_btn" type="submit" value="Go">
                </form>
                <form method="POST" action="" class="form_content_order">
                    <select class="content_order_selection" name="content_order">
                        <option value="name" <?php if(is_selected("name")) { echo "selected";}?>>Name</option>
                        <option value="category" <?php if(is_selected("category")) { echo "selected";}?>>Category</option>
                        <option value="price" <?php if(is_selected("price")) { echo "selected";}?>>Price</option>
                        <option value="brand" <?php if(is_selected("brand")) { echo "selected";}?>>Brand</option>
                    </select>
                    <input class="research_btn" type="submit" value="Order">
                </form>
            </div>
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
