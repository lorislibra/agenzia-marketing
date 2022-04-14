<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("src/template/lateral_menu.php");
require_once("src/template/items_template.php");
require_once("src/repositories/item_repo.php");

$connection = DbManager::build_connection_from_env();

$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all();

?>

<html>
    <head>
        <title>Items</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Items")); ?>
        <div class="body_main">
            <div class="items_head">
                <!-- barra di ricerca -->
                <!-- filtri -->
            </div>
            <div class="items_list">
                <?php echo(show_items($items)); ?>
            </div>
        </div>
    </body>
</html>
