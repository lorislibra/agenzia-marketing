<?php

require_once("src/components/lateral_menu.php");
require_once("src/components/item.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/session.php");
require_once("src/middleware/request.php");
require_once("src/dtos/show_item.php");

allowed_methods(["GET"]);
need_logged();

$connection = DbManager::build_connection_from_env();
$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all_with_filters();

?>

<html lang="en">
    <head>
        <title>Showcase</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Items")); ?>
        <div class="body_main">
            <div class="items_list">
                <?php if ($items) echo(join(array_map("show_item", $items))); ?>
            </div>
            <?php if ($error = $session->get_error("cart")) echo('<p class="login_errors">' . $error . '</p>'); ?>
        </div>

        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        
    </body>
</html>
