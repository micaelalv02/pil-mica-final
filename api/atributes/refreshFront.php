<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();

$product = isset($_POST['product']) ? $funciones->antihack_mysqli($_POST['product']) : '';
$lang = isset($_POST['idioma']) ? $funciones->antihack_mysqli($_POST['idioma']) : '';
$amountAtributes = isset($_POST['amount-atributes']) ? $funciones->antihack_mysqli($_POST['amount-atributes']) : '';
$detalleData = false;
$atributosTxt = '';
$subAttrCod = '';
if ($amountAtributes == count($_POST['atribute'])) {
    if (!empty($product)) {
        $ERROR = '';
        $data = [
            "filter" => ["productos.cod = " . "'$product'"],
            "attribute" => true,
            "combination" => true
        ];
        $productoData = $producto->list($data, $lang, true);
        if ($productoData["data"]["stock"] <= 0 || $productoData["data"]["stock"] < $amountAtributes) {
            $detalleData = $_SESSION["lang-txt"]["productos"]["stock_combinacion"];
        }
        if (!empty($productoData['data'])) {
            //ATRIBUTOS
            $precio = $productoData["data"]["precio_final"];
            $stock = $productoData["data"]["stock"];
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
        } else {
            $ERROR = "Ocurrió un error (502), recargar la página.";
        }
    } else {
        $ERROR = "Ocurrió un error (503), recargar la página.";
    }
} else {
    $ERROR = "Ocurrió un error (504), recargar la página.";
}
if (!empty($ERROR)) {
    $result = array("status" => false, "message" => $ERROR);
    echo json_encode($result);
} else {
    $result = array("status" => true, "price" => $precio, "stock" => $stock, "attribute" => $atributosTxt, "subAtribute" => $subAttrCod, "combination" => $detalleData);
    echo json_encode($result);
}
