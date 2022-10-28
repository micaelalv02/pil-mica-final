<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$producto = new Clases\Productos();
$ml = new Clases\MercadoLibre();
$f = new Clases\PublicFunction();

$filter = !empty($_POST['product']) ? ["cod = '" . $f->antihack_mysqli($_POST['product']) . "'"] : [];
$data = [
    "filter"=>$filter,
    "admin" => false,
    "category" => false,
    "subcategory" => false,
    "images" => false,
    "order" => "id ASC",
];
echo json_encode($producto->list($data,$_SESSION['lang']));
