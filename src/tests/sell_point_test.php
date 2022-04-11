<?php

require_once("src/repositories/sell_point.php");
require_once("test.php");

$sell_point_repo = new SellPointRepo($connection);

echo("all sell point\n");
if ($sell_points = $sell_point_repo->get_all()) {
    var_dump($sell_points);
} else {
    echo("no sell points\n");
}

?>