<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$pedidos = new Clases\Pedidos();
$f = new Clases\PublicFunction();

$typeUser = isset($_GET['type-user-LTV']) ?  $f->antihack_mysqli($_GET['type-user-LTV']) : 0;
$orderStatus = isset($_GET['type-order-status']) ?  $f->antihack_mysqli($_GET['type-order-status']) : 0;
$dataRange = isset($_GET['data-range-pick']) ?  explode(" - ", $f->antihack_mysqli($_GET['data-range-pick'])) : '';
// $filter[] = "estados_pedidos.estado != 0 AND estados_pedidos.estado != 3";
if ($typeUser != 2) $filter[] =  "`usuarios`.`minorista` = '" . $typeUser . "'";
$filter[] = " pedidos.fecha NOT BETWEEN  STR_TO_DATE('" . $dataRange[0] . "','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('" . $dataRange[1] . "','%d/%m/%Y %H:%i:%s') ";

if ($typeUser != 2) $filter_[] =  "`usuarios`.`minorista` = '" . $typeUser . "'";
$filter_[] = "estados_pedidos.estado != 0 AND estados_pedidos.estado != 3";
$filter_[] = " pedidos.fecha BETWEEN  STR_TO_DATE('" . $dataRange[0] . "','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('" . $dataRange[1] . "','%d/%m/%Y %H:%i:%s') ";

if (!empty($orderStatus)) $filter[] = "estados_pedidos.id = '" . $orderStatus . "'";


$usersCod = $pedidos->getUsersCod($filter_, "", "");

$comma_separated = implode("','", $usersCod);
$comma_separated = "'" . $comma_separated . "'";

$filter[] = "pedidos.usuario NOT IN (" . $comma_separated . ")";
$pedidosList = $pedidos->gestionLTV($filter);

echo json_encode($pedidosList);
