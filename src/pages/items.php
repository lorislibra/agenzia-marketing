<?php

require_once("src/template/lateral_menu.php");
require_once("src/template/items_template.php");

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
                <?php echo(show_items()); ?>
            </div>
        </div>
    </body>
</html>
