<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();

$cod = isset($_POST['cod']) ?  $f->antihack_mysqli($_POST['cod']) : '';

$user = isset($_SESSION['usuarios']['cod']) ? $_SESSION['usuarios']['cod'] : '';
$data = [
    "filter" => ["productos.cod = " . "'$cod'"],
    "admin" => false,
    "category" => true,
    "subcategory" => true,
    "tercercategory" => true,
    "attribute" => true,
    "combination" => true,
    "bultos" => true,
    "promos" => true,
    "images" => true,
];
$productoData = $producto->list($data, $_SESSION['lang'], true);


if (!empty($productoData)) {
    echo json_encode(["product" => $productoData,"user" => $user]);
} else {
    echo json_encode(false);
}
