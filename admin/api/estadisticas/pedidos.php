<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$pedidos = new Clases\Pedidos();
$f = new Clases\PublicFunction();

$typeUser = isset($_GET['type-user']) ?  $f->antihack_mysqli($_GET['type-user']) : 0;
$typeOrder = isset($_GET['type-order']) ?  $f->antihack_mysqli($_GET['type-order']) : 0;
$dataRange = isset($_GET['data-range-pick']) ?  explode(" - ", $f->antihack_mysqli($_GET['data-range-pick'])) : '';


if ($typeUser != 2) $filter[] =  "usuarios.minorista = '" . $typeUser . "'";
if($typeOrder != "all") $filter[] =  "`pedidos`.`estado` = '" . $typeOrder . "'";
$filter[] = "pedidos.fecha BETWEEN  STR_TO_DATE('" . $dataRange[0] . "','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('" . $dataRange[1] . "','%d/%m/%Y %H:%i:%s') ";



$pedidosList = $pedidos->listEstadisticas($filter, 'pedidos.fecha DESC', '');

echo json_encode($pedidosList);