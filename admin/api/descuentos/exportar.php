<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$funciones = new Clases\PublicFunction();
$excel = new Clases\Excel();
$pedidos = new Clases\Pedidos();

$from = isset($_POST["from"]) ? $funciones->antihack_mysqli($_POST["from"]) : '';
$to = isset($_POST["to"]) ? $funciones->antihack_mysqli($_POST["to"]) : '';

$date = '';
if (!empty($from)) {
    $to_ = !empty($to) ? "'" . $to . "'" : "NOW()";
    $date = " AND `pedidos`.`fecha` BETWEEN '" . $from . "' AND " . $to_;
}
$discountedUsedProducts = $pedidos->getProductsFromOrder($date);
if (!empty($discountedUsedProducts)) {
    echo json_encode($excel->exportDiscount($discountedUsedProducts));
}
