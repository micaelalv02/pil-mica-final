<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$productos = new Clases\Productos();
$f = new Clases\PublicFunction();
$detalles = new Clases\DetallePedidos();

$provincia = isset($_GET['filter-provincia']) ? $f->antihack_mysqli($_GET['filter-provincia']) : '';
$categoria = isset($_GET['filter-categoria']) ? $f->antihack_mysqli($_GET['filter-categoria']) : '';
$fecha = isset($_GET['filter-fecha']) ? explode(" - ", $f->antihack_mysqli($_GET['filter-fecha'])) : '';
$limite = isset($_GET['filter-limite']) ?  $f->antihack_mysqli($_GET['filter-limite']) : 300;
$status = isset($_GET['filter_order_status']) ?  $f->antihack_mysqli($_GET['filter_order_status']) : '';
$subcategoria = "";
if (strpos($categoria, "cat-") !== false) {
    $categoria = str_replace("cat-", "", $categoria);
}
if (strpos($categoria, "sub-") !== false) {
    $subcategoria = str_replace("sub-", "", $categoria);
    $categoria = "";
}
if($limite == "all") $limite = "";
$topAprobado = $detalles->topBuyPerProvince($limite,$status, $provincia, $categoria, $subcategoria, $fecha);

echo json_encode($topAprobado);