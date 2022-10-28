<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$productos = new Clases\Productos();
$f = new Clases\PublicFunction();
$string = $f->antihack_mysqli($_POST['string']);
$lang = $f->antihack_mysqli($_POST['lang']);
$productosArray = $productos->list(["filter" => ["(`productos`.`cod_producto` LIKE '%$string%' OR `productos`.`titulo` LIKE '%$string%')"],"promos"=> true], $lang);
echo json_encode(["status" => true, "productos" => $productosArray]);
