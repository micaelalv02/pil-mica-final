<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$productos = new Clases\Productos();

$string = $_POST['string'];

$productosArray = $productos->list(["filter" => ["`productos`.`cod_producto` LIKE '%$string%' OR `productos`.`titulo` LIKE '%$string%'"]],$_SESSION['lang']);
echo json_encode(["status" => true, "productos" => $productosArray]);
