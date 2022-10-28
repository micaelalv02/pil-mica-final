<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();


$id = isset($_POST['id']) ? $f->antihack_mysqli($_POST['id']) : ''; 

if($id != 'discount'){
    $carrito->delete($id);
}else{
    $carrito->deleteDiscount();
}


$total = $carrito->totalPrice();

echo json_encode($total);