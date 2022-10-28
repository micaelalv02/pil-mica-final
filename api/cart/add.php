<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();
$checkout = new Clases\Checkout();

$product = isset($_POST['product']) ? $f->antihack_mysqli($_POST['product']) : ''; //TODO POST
$amount = intval(isset($_POST['amount']) ? $f->antihack_mysqli($_POST['amount']) : $f->antihack_mysqli($_POST['stock'])); //TODO POST
$product = trim(str_replace(" ", "", $product));
if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
$data = [
    "filter" => ["productos.cod = " . "'$product'"],
    "attribute" => true,
    "combination" => true,
    "category" => true,
    "promos" => true
];
$productoData = $producto->list($data, $_SESSION['lang'], true);
$right = true;

if (empty($productoData)) {
    echo json_encode(["status" => false, "type" => "error", "message" => "Ocurrió un error, recargar la página."]);
    die();
} else {
    if (isset($productoData["atributo"][0]["atribute"]) && empty($_POST["combinationInfo"])) $right = false;
    if ($right) {
        $opciones =  [
            "texto" => '',
            "subatributos" => '',
            "combinacion" => ['cod_combinacion' => '', 'id' => '', 'idioma' => '', 'mayorista' => '', 'precio' => '', 'stock' => '']
        ];

        $carrito->deleteOnCheck("Envio-Seleccion");
        $carrito->deleteOnCheck("factura");
        $carrito->deleteOnCheck("Metodo-Pago");
        $carrito->set("id", $productoData['data']['cod']);
        $carrito->set("cantidad", $amount);
        $carrito->set("promo", '');
        $carrito->set("titulo", $productoData['data']['titulo']);
        $stock = $productoData["data"]["stock"];
        $carrito->set("stock", $stock);
        $carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
        $carrito->set("opciones", $opciones);
        $carrito->set("producto_cod", $productoData['data']['cod_producto']);
        $carrito->set("precio_inicial", $productoData["data"]['precio']);
        $carrito->set("precio", $productoData['data']['precio_final']);
        $carrito->set("link", $productoData['link']);
        $carrito->set("tipo", "pr"); //producto
        $carrito->set("descuento", '');
        //SI VIENE ATRIBUTO/COMBINACION
        if (!empty($_POST["combinationInfo"])) {
            $combinationJson = json_decode($_POST["combinationInfo"], true);
            if (!empty($combinationJson)) {
                $combination = $combinationJson["combination"];
                if ($combinationJson["combination"] == false) {
                    //SOLO SI ES CON ATRIBUTO Y SIN COMBINACION
                    $combination = ['cod_combinacion' => '', 'id' => '', 'idioma' => '', 'mayorista' => '', 'precio' => '', 'stock' => ''];
                }
                $opciones = array("subatributos" => $combinationJson["subAtribute"], "texto" => $combinationJson["attribute"], "combinacion" => $combination);
                $stock = $combinationJson["stock"];
                $tituloAttr = $productoData['data']['titulo'] . $combinationJson["attribute"];
                $carrito->set("titulo", $tituloAttr);
                $carrito->set("precio", $combinationJson["price"]);
                $carrito->set("stock", $stock);
                $carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
                $carrito->set("opciones", $opciones);
            }
        }

        if (isset($combinationJson["attribute"])) {
            $key = array_search($tituloAttr, array_column($_SESSION['carrito'], 'titulo'));
        } else {
            $key = array_search($product, array_column($_SESSION['carrito'], 'id'));
        }

        if ($key !== false) { // SI EXISTE EN EL CARRITO LE SUMA LA CANTIDAD QUE TENIA Y LO ELIMINA ASI VUELVE A REALIZAR EL ADD CON TODAS LAS VALIDACIONES SOBRE LA CANTIDAD TOTAL
            $amount = $amount + $_SESSION['carrito'][$key]['cantidad'];
            if ($amount <= $productoData['data']['stock']) $carrito->delete($key); // CORROBORA EL STOCK PARA NO BORRARLO DEL CARRITO SI PASA EL LIMITE
            $carrito->set("cantidad", $amount);
        }
        if ($amount <= $stock) {
            if (!empty($productoData["data"]['promoLleva']) && !empty($productoData["data"]['promoPaga']) && $amount >= $productoData["data"]['promoLleva']) {
                $multiplo = floor($amount / $productoData["data"]['promoLleva']);
                $carrito->set("promo", ($amount - ($productoData["data"]['promoLleva'] * $multiplo)) + ($multiplo * $productoData["data"]['promoPaga']));
                $carrito->set("titulo", "Promo " . $productoData['data']['promoLleva'] . "x" . $productoData['data']['promoPaga'] . ": " . $productoData['data']['titulo']);
            }
            if ($carrito->add()) {
                $checkout->destroy();
                $result = array("status" => true, "cod_producto" => $productoData['data']['cod'], "combinacion " => isset($combinationJson) ? json_encode($combinationJson) : '');
                echo json_encode($result);
            } else {
                $result = array("status" => false, "message" =>  $_SESSION["lang-txt"]["productos"]["stock_combinacion"]);
                echo json_encode($result);
            }
        } else {
            $result = array("status" => false, "message" =>  $_SESSION["lang-txt"]["productos"]["stock_combinacion"]);
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" =>  "!Error al agregar el producto: " . $productoData["data"]["titulo"] . " !");
        echo json_encode($result);
    }
}
