<?php

require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/user_repo.php");
require_once("src/dtos/show_order.php");
require_once("src/middleware/request.php");

allowed_methods(["GET"]);
need_logged();

$connection = DbManager::build_connection_from_env();

$item_repo = new ItemRepo($connection);
$items = $item_repo->get_all_with_filters();

// no need to use error in session, because the error and the visualization are in the same page
$error = "";

try {
    $dto = ShowItemDto::from_array($_GET);
    $item = $item_repo->get_by_id($dto->id);
    if (!$item) {
        $error = "no item found";
    }
} catch (ValidateDtoError $e) {
    if (isset($_GET["id"])) {
        $error = "invalid item";
    }
    $item = null;
}


echo($session->get_error("order"));

?>