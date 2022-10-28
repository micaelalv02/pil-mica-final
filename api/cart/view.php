<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();
$descuento = new Clases\Descuentos();
$productos = new Clases\Productos();

$usuarioData = $usuario->viewSession();
$descuento->refreshCartDescuento($carrito->return(), $usuarioData);
$carro = $carrito->return();
if (!empty($carro)) {
    foreach ($carro as $key => $item) {
        $cart[$key]['cart'] = $item;
    }
    echo json_encode(["items" => $cart, "total" => $carrito->totalPrice()]);
} else {
    echo json_encode(false);
}