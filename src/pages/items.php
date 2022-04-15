<?php

require_once("src/templates/lateral_menu.php");
require_once("src/templates/items_template.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");

need_logged();

$connection = DbManager::build_connection_from_env();

$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all();

function make_order(): string
{
    global $item_repo;

    if(!empty($_GET["item"])){
        $item_id = $_GET["item"];
        $selected_item = $item_repo->get_by_id($item_id);

        if($selected_item != null){
            $make_order_html = '
                                <div class="order_window">
                                    <form method="GET" action="cart.php">
                                        <button class="order_button">
                                            ORDER
                                        </button>
                                    </form>
                                </div>
                                ';

            return $make_order_html;
        }

        return '';
    }

    return '';
}

?>

<html>
    <head>
        <title>Showcase</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body <?php if(!empty($_GET["item"])) { echo 'class="body_blurred"'; } ?>>
        <?php echo(show_lateral_menu("Items")); ?>
        <div class="body_main">
            <div class="items_list">
                <?php echo(show_items($items)); ?>
            </div>
        </div>
        <?php echo(make_order()); ?>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>
