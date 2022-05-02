<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/repositories/item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");
require_once("src/services/cart.php");

allowed_methods(["POST"]);
need_logged();

try {
    $dto = AddToCartDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("cart", "invalid add to cart");
    header("location: /items.php");
    exit();
}

$user = $session->get_user();
$connection = DbManager::build_connection_from_env();

try {
    add_cart_item_tx($connection, $user->id, $dto);
} catch (Exception $e) {
    $session->add_error("cart", $e->getMessage());
}

header("location: /cart.php");

?>