<?php
    require_once("src/template/lateral_menu.php");
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

            </div>
            <div class="items_list">
                <?php //echo(show_items()); ?>
            </div>
        </div>
    </body>
</html>
