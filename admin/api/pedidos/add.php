<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$pedidos = new Clases\Pedidos();
$detalle = new Clases\DetallePedidos();
$productos = new Clases\Productos();
$carrito = new Clases\Carrito();
$pagos = new Clases\Pagos();
$detalleCombinacion = new Clases\DetalleCombinaciones();
$carroData = $carrito->return();
$pagoData = $carrito->checkPago();
$response = '';
$factura = isset($_POST["factura"]) ?  $funciones->antihack_mysqli($_POST["factura"]) : '0';
if (isset($carroData[$pagoData]['opciones'])) {
    unset($_SESSION["last_cod_pedido"]);
    $pagos->set("cod", $carroData[$pagoData]['opciones']);
    $pagos->set("idioma", $_SESSION["usuarios-ecommerce"]["idioma"]);
    $pagoData_ = $pagos->view();
    $factura_ = '';
    $response = array("status" => true, "url" => URL_ADMIN . '/index.php?op=pedidos&accion=ver');
    if (!empty($pagoData_)) {
        if ($pagoData_['data']['tipo'] == "1") {
            $response = array("status" => true, "type" => "API", "url" => URL . '/api/payments/mp.php');
        }
    }
    if (!empty($pagoData_['data']['leyenda'])) {
        $factura_ .= "<b>DESCRIPCIÓN DEL PAGO: </b>" . $pagoData_['data']['leyenda'] . "<br/>";
    } else {
        $factura_ .= '';
    }

    if ($factura == 1) {
        $factura_ .= "<b>SOLICITÓ FACTURA A CON EL CUIT: </b>" . $_SESSION["usuarios-ecommerce"]["doc"] . "<br/>";
    } else {
        $factura_ .= '';
    }

    if (isset($descuentoCheck['cod'])) {
        if (!empty($descuentoCheck['cod'])) {
            $factura_ .= "<b>SE UTILIZÓ EL CÓDIGO DE DESCUENTO: </b>" . $descuentoCheck['cod'];
        }
    }

    $precio = $carrito->totalPrice();
    $entrega = (!empty($pagoData_['data']['entrega'])) ? (($pagoData_['data']['entrega'] * $precio) / 100) : $precio;
    $fecha = date("Y-m-d");
    $pedidos->set("cod", $_SESSION["cod_pedido"]);
    $pedidos->set("entrega", $entrega);
    $pedidos->set("total", $precio);
    $pedidos->estado = 1;
    $pedidos->set("pago", $pagoData_["data"]["titulo"]);
    $pedidos->set("usuario", $_SESSION['usuarios-ecommerce']['cod']);
    $pedidos->set("detalle", $factura_);
    $pedidos->set("observacion", "");
    $pedidos->set("fecha", $fecha);
    $pedidos->set("visto", 0);
    $pedidos->set("idioma", $_SESSION['usuarios-ecommerce']['idioma']);
    $pedidos->add();
    $pedidoId = $pedidos->view();
    $_SESSION["last_cod_pedido"] = str_pad($pedidoId["data"]["id"], 10, "0", STR_PAD_LEFT);
    $pedidos->editSingle("cod", $_SESSION['last_cod_pedido']);
    foreach ($carroData as $carroItem) {
        if ($carroItem['id'] != "Envio-Seleccion" && $carroItem['id'] != "Metodo-Pago") {
            $productoData = $productos->list(["filter" => ["productos.cod='" . $carroItem['id'] . "'"]], $_SESSION['lang']);
            foreach ($productoData as $prod_) {
                $productos->set("cod", $prod_['data']["cod"]);
                if (is_array($carroItem['opciones'])) {
                    if (isset($carroItem['opciones']['combinacion'])) {
                        $detalleCombinacion->set("codCombinacion", $carroItem['opciones']['combinacion']['cod_combinacion']);
                        $detalleCombinacion->editSingle("stock", intval($carroItem['opciones']['combinacion']['stock']) - intval($carroItem['cantidad']));
                    } else {
                        $productos->editSingle("stock", $prod_['data']['stock'] - $carroItem['cantidad'], $_SESSION['lang']);
                    }
                } else {
                    $productos->editSingle("stock", $prod_['data']['stock'] - $carroItem['cantidad'], $_SESSION['lang']);
                }
            }
        }
        $detalle->set("cod", $_SESSION["last_cod_pedido"]);
        $detalle->set("cantidad", $carroItem["cantidad"]);
        $detalle->set("promo", $carroItem["promo"]);
        $detalle->set("cod_producto", $carroItem["id"]);
        $detalle->set("precio", $carroItem["precio"]);
        $detalle->set("producto_cod", $carroItem["producto_cod"]);
        $detalle->set("producto", $carroItem["titulo"]);
        $detalle->set("tipo", $carroItem["tipo"]);
        $detalle->set("cod_combinacion",  isset($carroItem["opciones"]["combinacion"]["cod_combinacion"]) ? $carroItem["opciones"]["combinacion"]["cod_combinacion"] : '');
        $detalle->set("descuento", $carroItem["descuento"]);
        $detalle->add();
    }
    $response["cod"] = $_SESSION["last_cod_pedido"];
    echo json_encode($response);
}
