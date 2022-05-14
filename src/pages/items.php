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
            <div class="top_filters">
                <form action="" class="filter_form" style="width: 40%;" method="get">
                    <input type="submit" class="filter_submit" value="&#x1F50E;&#xFE0E;">
                    <input name="query" class="filter_input" value="<?php echo($dto->query); ?>">
                </form>
                <form action="" class="filter_form" method="get">
                    <input type="hidden" name="page" value="<?php echo($dto->page); ?>">
                    <input type="hidden" name="query" value="<?php echo($dto->query); ?>">
                    Items per page: <select name="per_page" class="filter_select" onchange="this.form.submit()">
                        <?php 
                            for($i=5; $i <= 50; $i+=5) {
                                $sel = $i == $dto->per_page ? "selected" : "";
                                echo("<option $sel value=\"$i\">$i</option>");
                            }
                        ?>
                    </select>
                </form>
            </div>

            <div class="items_list">
                <?php if ($items) echo(join(array_map("show_item", $items))); ?>
            </div>
            
            <div class="pages_list">
                <?php
                    $per_page = $dto->per_page;
                    $query = $dto->query;
                    $page = $dto->page;
                    for($i = 1; $i <= $max_page; $i++) {
                        if(($page - $i <= 1 && $page - $i >= -1) || $i == 1 || $i == $max_page){
                            $href = ($i == $page) ? "" : "href=\"/items.php?page=$i&per_page=$per_page&query=$query\"";
                            $class = ($i == $page) ? "sel_page_link" : "page_link";
                            $class .= ($i == 1) ? " first_page_link" : "";
                            $class .= ($i == $max_page) ? " last_page_link" : "";
                            echo("<a role=\"link\" class=\"$class\" $href>$i</a>");
                        }
                    }    
                ?>
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
