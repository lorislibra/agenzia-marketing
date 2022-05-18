<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/components/lateral_menu.php");
require_once("src/dtos/show_orders.php");

allowed_methods(["GET"]);
need_warehouse();

try {
    $dto = ShowOrdersDto::from_array($_GET);
} catch (ValidateDtoError $e) {
    $dto = new ShowOrdersDto();
}

$user = $session->get_user();

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);

$order_count = $reservation_repo->count($user->id);
$max_page = ceil($order_count / $dto->per_page);
$dto->page = min($dto->page, $max_page);

$reservations = $reservation_repo->get_all_filters($dto);

?>

<html lang="en">
    <head>
        <title>Orders</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Orders", "admin")); ?>
        <div class="body_main">
            <div class="top_filters" style="justify-content: end;">
                <form action="" class="filter_form" method="get">
                    <input type="hidden" name="page" value="<?php echo($dto->page); ?>">
                    Items per page: <select name="per_page" class="filter_select" onchange="this.form.submit()">
                        <?php 
                            for($i=3; $i <= 30; $i+=3) {
                                $sel = $i == $dto->per_page ? "selected" : "";
                                echo("<option $sel value=\"$i\">$i</option>");
                            }
                        ?>
                    </select>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>User</th>
                        <th>Order date</th>
                        <th>Delivery date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($reservations as $reservation){
                            $delivery_date = !$reservation->date_delivery ? "---": $reservation->date_delivery->format('d/m/Y');
                            $link = '/admin/order.php?id=' . $reservation->id;

                            echo '
                                <tr>
                                    <td>' . $reservation->id . '</td>
                                    <td>' . $reservation->user->email . '</td>
                                    <td>' . $reservation->date_order->format('d/m/Y') . '</td>
                                    <td>' . $delivery_date . '</td>
                                    <td>' . $reservation->status->string() . '</td>
                                    <td class="order_td_details"><a class="order_btn_details" href="' . $link . '">View details</a></td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>

            <div class="pages_list">
                <?php
                    $per_page = $dto->per_page;
                    $page = $dto->page;
                    for($i = 1; $i <= $max_page; $i++) {
                        if(($page - $i <= 1 && $page - $i >= -1) || $i == 1 || $i == $max_page){
                            $href = ($i == $page) ? "" : "href=\"/admin/orders.php?page=$i&per_page=$per_page\"";
                            $class = ($i == $page) ? "sel_page_link" : "page_link";
                            $class .= ($i == 1) ? " first_page_link" : "";
                            $class .= ($i == $max_page) ? " last_page_link" : "";
                            echo("<a role=\"link\" class=\"$class\" $href>$i</a>");
                        }
                    }    
                ?>
            </div>
        </div>
        
        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        
    </body>
</html>