<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$productos_visitados = new Clases\ProductosVisitados();
$productos = new Clases\Productos();
$funciones = new Clases\PublicFunction();
$excel = new Clases\Excel();
$filter = '';

$from = isset($_GET["from"]) ? $funciones->antihack_mysqli($_GET["from"]) : '';
$to = isset($_GET["to"]) ? $funciones->antihack_mysqli($_GET["to"]) : '';
$idioma = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
if (!empty($from)) {
    $to_ = !empty($to) ? "'" . $to . "'" : "NOW()";
    $filter = "WHERE productos_visitados.fecha BETWEEN '" . $from . "' AND " . $to_;
}
$productosVisitados = $productos_visitados->getAllData($filter,$idioma);
$ruta = '';
if (!empty($productosVisitados)) {
    $ruta = $excel->exportVisitedProducts($productosVisitados);
}
echo json_encode($ruta);
