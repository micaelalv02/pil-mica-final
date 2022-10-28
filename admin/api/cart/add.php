<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
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
$atribute = isset($_POST['atribute']) ? $f->antihackMulti($_POST['atribute']) : ''; //TODO POST
$product = trim(str_replace(" ", "", $product));
$titulo = '';

$detalleData = false;
$atributosTxt = '';
$subAttrCod = '';
$data = [
    "filter" => ["productos.cod = " . "'$product'"],
    "attribute" => true,
    "combination" => true,
    "category" => true,
    "promos" => true
];
$productoData = $producto->list($data, $_SESSION['lang'], true);

$precio = $productoData["data"]["precio_final"];
$stock = $productoData["data"]["stock"];
if (isset($_POST['atribute'])) {
    foreach ($_POST['atribute'] as $attrPost => $subAttrPost) {
        foreach ($productoData["atributo"] as $key => $attrProduct) {
            if ($attrPost == $attrProduct["atribute"]["cod"]) {
                $atributosTxt .= " | <b>" . $attrProduct["atribute"]['value'] . ":</b> ";
                foreach ($attrProduct["atribute"]["subatributes"] as $subAttrProduct) {
                    if ($subAttrPost == $subAttrProduct["cod"]) {
                        $atributosTxt .=  $subAttrProduct["value"];
                        $subAttrCod .= $subAttrProduct["cod"];
                    }
                }
            }
        }
    }
    $atributosTxt .= " |";
    $subAttrCod .= ",";
    if (!empty($_POST['combination'])) {
        //COMBINACIONES
        $resultValidate = $combinacion->check($_POST['atribute'], $product);
        if ($resultValidate['result'] === 1) {
            $detalleCombinacion->set("codCombinacion", $resultValidate['combination']);
            $detalleCombinacion->set("idioma", $lang);
            $detalleData = $detalleCombinacion->view();
            if (!empty($detalleData)) {
                $precio = $detalleData['precio'];
                $stock = ($detalleData['stock'] != 0) ? $detalleData['stock'] : "0";

                //VALIDAR SI EXISTE USUARIO Y EMPEZAR LAS VALIDACIONES
                if (!empty($_SESSION['usuarios'])) {
                    if ($_SESSION['usuarios']['invitado'] != 1 && $_SESSION["usuarios"]["minorista"] == 1) {
                        $precio = $detalleData['precio'];
                        $stock = ($detalleData['stock'] != 0) ? $detalleData['stock'] : "0";
                    } else {
                        if (!empty($detalleData['mayorista'])) {
                            $precio = $detalleData['mayorista'];
                            $stock = ($detalleData['stock'] != 0) ? $detalleData['stock'] : "0";
                        }
                    }
                }
            } else {
                $ERROR = 'Ocurrió un error (501), intente nuevamente.';
            }
        } else {
            $detalleData = $_SESSION["lang-txt"]["productos"]["stock_combinacion"];
        }
    }
}

//BUSCA POR TITULO PARA PODER DIFERENCIAR LOS ATRIBUTOS
$keyProduct = array_search($productoData['data']['titulo'] . $atributosTxt, array_column($_SESSION['carrito'], 'titulo'));
if ($keyProduct !== false) { // SI EXISTE EN EL CARRITO LE SUMA LA CANTIDAD QUE TENIA Y LO ELIMINA ASI VUELVE A REALIZAR EL ADD CON TODAS LAS VALIDACIONES SOBRE LA CANTIDAD TOTAL
    $amount = $amount + $_SESSION['carrito'][$keyProduct]['cantidad'];
    if ($amount <= $stock) $carrito->delete($keyProduct); // CORROBORA EL STOCK PARA NO BORRARLO DEL CARRITO SI PASA EL LIMITE
}


if (empty($productoData)) {
    echo json_encode(["status" => false, "type" => "error", "message" => "Ocurrió un error, recargar la página."]);
    die();
} else {
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
    $carrito->set("titulo", $productoData['data']['titulo'] . $atributosTxt);
    $stock = $productoData["data"]["stock"];
    $carrito->set("stock", $stock);
    $carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
    $carrito->set("opciones", $opciones);
    $carrito->set("producto_cod", $productoData['data']['cod_producto']);
    $carrito->set("precio_inicial", $productoData["data"]['precio']);
    $carrito->set("precio", $precio);
    $carrito->set("link", $productoData['link']);
    $carrito->set("tipo", "pr"); //producto
    $carrito->set("descuento", "");
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
            $carrito->set("precio", $combinationJson["price"]);
            $carrito->set("stock", $stock);
            $carrito->set("peso", number_format($productoData['data']['peso'], 2, ".", ""));
            $carrito->set("opciones", $opciones);
        }
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
}
