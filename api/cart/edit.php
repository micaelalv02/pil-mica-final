<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$producto = new Clases\Productos();

$cod = isset($_POST['id']) ? $f->antihack_mysqli($_POST['id']) : '';
$key = isset($_POST['key']) ? $f->antihack_mysqli($_POST['key']) : '';
$cantidad = isset($_POST['cantidad']) ? $f->antihack_mysqli($_POST['cantidad']) : '';

$data = [
    "filter" => ["productos.cod = " . "'$cod'"],
    "attribute" => true,
    "combination" => true,
    "promos" => true
];
$productoData = $producto->list($data, $_SESSION['lang'], true);

$titulo = $productoData['data']['titulo'];
if ($cantidad <= $productoData['data']['stock']) {
    if ($productoData["data"]['promoLleva'] != '' && $productoData["data"]['promoPaga'] != '' && $cantidad >= $productoData["data"]['promoLleva']) {
        $multiplo = floor($cantidad / $productoData["data"]['promoLleva']);
        $promo = ($cantidad - ($productoData["data"]['promoLleva'] * $multiplo)) + ($multiplo * $productoData["data"]['promoPaga']);
        $titulo = "Promo " . $productoData['data']['promoLleva'] . "x" . $productoData['data']['promoPaga'] . ": " . $productoData['data']['titulo'];
    } 
  
        if ($cantidad) {
        $carrito->set("cantidad", $cantidad);
        $carrito->set("promo", isset($promo) ? $promo : '');
        $carrito->set("titulo", $titulo);
        $carrito->edit($key);
    } else {
        $carrito->delete($key);
    }

    $carrito->deleteOnCheck("Envio-Seleccion");
    $carrito->deleteOnCheck("Metodo-Pago");

    echo json_encode(['status' => true]);
}
