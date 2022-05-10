<?php

require_once("src/components/lateral_menu.php");
require_once("src/components/item.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/session.php");
require_once("src/middleware/request.php");
require_once("src/dtos/show_items.php");

allowed_methods(["GET"]);
need_logged();

try {
    $dto = ShowItemsDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    $dto = new ShowItemsDto();
}

$connection = DbManager::build_connection_from_env();
$item_repo = new ItemRepo($connection);

$item_count = $item_repo->count_filters($dto);
$max_page = ceil($item_count / $dto->per_page);
$dto->page = min($dto->page, $max_page);

$items = $item_repo->get_all_filters($dto);

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
                <form action="" method="get">
                    <input name="query" value="<?php echo($dto->query); ?>">
                    <input type="submit" value="cerca">
                </form>
                <?php if ($items) echo(join(array_map("show_item", $items))); ?>
                <?php
                    $per_page = $dto->per_page;
                    $query = $dto->query;
                    $page = $dto->page;
                    for($i=1; $i <= $max_page; $i++) {
                        $dis = true ? " disabled " : "";
                        echo("<a $dis href=\"/items.php?page=$i&per_page=$per_page&query=$query\">$i</a>");
                    }
                    
                ?>

                <form action="" method="get">
                    <input type="hidden" name="page" value="<?php echo($dto->page); ?>">
                    <input type="hidden" name="query" value="<?php echo($dto->query); ?>">
                    <select name="per_page" onchange="this.form.submit()">
                        <?php 
                            for($i=5; $i <= 50; $i+=5) {
                                $sel = $i == $dto->per_page ? "selected" : "";
                                echo("<option $sel value=\"$i\">$i</option>");
                            }
                        ?>
                    </select>
                </form>
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
