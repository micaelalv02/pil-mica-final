<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();

$carrito = new Clases\Carrito();
$checkout = new Clases\Checkout();

#Se cargan los productos del carrito
$carro = $carrito->return();

if (!empty(floatval($carrito->totalPrice()))) {
    $type = !empty($_SESSION['usuarios']) ? "USER" : "GUEST";
    $cod_user = ($type == "USER") ?  $_SESSION['usuarios']['cod'] : "";
    if ($type == "USER") {
        $link = $checkout->checkSkip($_SESSION['usuarios']['minorista']);
    } else {
        $link = "login";
    }
    $checkout->initial($type, $cod_user);

    echo json_encode($link);
}else{
    echo json_encode(false);  
}