<?php
require_once dirname(__DIR__,2)."/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$carrito = new Clases\Carrito();

$array = [
    "total" => $carrito->totalPrice(),
    "amount" => count($carrito->return())
];
echo json_encode($array);