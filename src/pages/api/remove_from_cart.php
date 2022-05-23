<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/update_cart.php");
require_once("src/services/cart.php");

allowed_methods(["POST"]);
need_logged();

try {
    $dto = UpdateCartDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("cart", $e->getMessage());
    header("location: /cart.php");
    exit();
}

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();

try {
    remove_from_cart_item_tx($connection, $user->id, $dto);
} catch (Exception $e) {
    $session->add_error("cart", $e->getMessage());
}

header("location: /cart.php");

?>