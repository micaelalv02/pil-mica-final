<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$pedidos = new Clases\Pedidos();
$f = new Clases\PublicFunction();




$typeOrder = isset($_GET['order-status']) ?  $f->antihack_mysqli($_GET['order-status']) : 0;
$dataRange = isset($_GET['data-range-pick']) ?  explode(" - ", $f->antihack_mysqli($_GET['data-range-pick'])) : '';
$provincia_orders = isset($_GET['provincia_orders']) ?   $f->antihack_mysqli($_GET['provincia_orders']) : '';

if (!empty($provincia_orders)) $filter[] = "`usuarios`.`provincia` = '$provincia_orders'";
if($typeOrder != "all") $filter[] =  "`pedidos`.`estado` = '" . $typeOrder . "'";
$filter[] = "`pedidos`.`fecha` BETWEEN  STR_TO_DATE('" . $dataRange[0] . "','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('" . $dataRange[1] . "','%d/%m/%Y %H:%i:%s') ";

$pedidosList = $pedidos->getOrderPerState($filter);

echo json_encode($pedidosList);
