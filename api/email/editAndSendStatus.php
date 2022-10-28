<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$pedido = new Clases\Pedidos();
$config = new Clases\Config();
$estadosPedidos  =  new Clases\EstadosPedidos();

$emailData = $config->viewEmail();
$codPedido = isset($_POST['codPedido']) ? $funciones->antihack_mysqli($_POST['codPedido']) : '';
$enviar_Admin = isset($_POST['flag']) ? $funciones->antihack_mysqli($_POST['flag']) : false;
$subEstadoPedidoCod = isset($_POST['estadoPedido']) ? $funciones->antihack_mysqli($_POST['estadoPedido']) : '';
$enviarMail = isset($_POST['enviar']) ? $funciones->antihack_mysqli($_POST['enviar']) : '';
$estado = $estadosPedidos->view($subEstadoPedidoCod, $_SESSION['lang']);
$mensajeCompraUsuario = '';
$carroTotal = 0;
if (!empty($codPedido)) {
    $pedido->set("cod", $codPedido);
    $pedido->editSingle("estado", $subEstadoPedidoCod);
    if ($enviarMail == 1) {
        $pedidoData = $pedido->view();
        $detalle = json_decode(preg_replace('/[\x00-\x1F]/', '<br/>', $pedidoData['data']['detalle']), true);

        #SE GENERA LA INFORMACION DEL PEDIDO
        $envioData = $pedido->getInfoPedido($detalle, 'envio');
        $pagoData = $pedido->getInfoPedido($detalle, 'pago');
        #END GENERAR INFO PEDIDO
        if (!empty($pedidoData['data'])) {
            #SE GENERA LA TABLA DEL CARRITO
            $mensaje_carro = '<table border="1" style="text-align:left;width:100%;font-size:13px !important">';
            $mensaje_carro .= "<thead><th>" . $_SESSION["lang-txt"]["api"]["email"]["nombre_producto"] . "</th><th>" . $_SESSION["lang-txt"]["api"]["email"]["cantidad"] . "</th><th>" . $_SESSION["lang-txt"]["api"]["email"]["precio"] . "</th><th>" . $_SESSION["lang-txt"]["api"]["email"]["total"] . "</th></thead>";
            foreach ($pedidoData['detail'] as $detail) {
                $unserialized = @unserialize($detail['descuento']);
                if (!empty($unserialized) && isset($unserialized['cod'])) {
                    $descuentoMonto = $unserialized["monto"];
                    $descuentoPrecio = $unserialized["precio-antiguo"];
                } else {
                    $descuentoMonto = '';
                    $descuentoPrecio = '';
                }
                $opciones = '';
                if (!empty($detail['producto_cod'])) {
                    $opciones = "<br>" . $detail['producto_cod'];
                }
                $carroTotal += $detail['cantidad'] * $detail['precio'];
                $mensaje_carro .= "<tr>";
                $mensaje_carro .= "<td>" . $detail['producto'] . " <b>" . $descuentoMonto . "</b>" . $opciones . "</td>";
                $mensaje_carro .= "<td>" . $detail["cantidad"] . "</td>";
                if ($detail['precio'] != 0) {
                    $mensaje_carro .= "<td>$" . $detail['precio'] . " <span style='text-decoration: line-through'>" . $descuentoPrecio . "</span></td>";
                } else {
                    $mensaje_carro .= "<td></td>";
                }
                if ($detail["promo"] != '') {
                    $mensaje_carro .= "<td>$" . $detail['promo'] * $detail['precio'] . "</td>";
                } else {
                    $mensaje_carro .= "<td>$" . $detail['cantidad'] * $detail['precio'] . "</td>";
                }
                $mensaje_carro .= "</tr>";
            }
            $mensaje_carro .= '<tr><td></td><td></td><td></td><td>$' . $carroTotal . '</td></tr>';
            $mensaje_carro .= '<tr><td><h4>' . $_SESSION["lang-txt"]["checkout"]["carrito"]["total_compra"] . '</h4></td><td></td><td></td><td>$' . $carroTotal . '</td></tr>';
            if (!empty($pedidoData['data']['entrega']) && $pedidoData['data']['entrega'] < $pedidoData['data']['total']) {
                $mensaje_carro .= '<tr><td><h4>' . $_SESSION["lang-txt"]["checkout"]["carrito"]["compra_parcial"] . '</h4></td><td></td><td></td><td>$' . $pedidoData['data']['entrega'] . '</td></tr>';
            }
            $mensaje_carro .= '</table>';
            #END TABLA CARRITO

            // $mensajeCompraUsuario .= $cuerpoMail;
            $mensajeCompraUsuario .= "<h3>" . $_SESSION["lang-txt"]["api"]["email"]["pedido_realizado"] . "</h3>";
            $mensajeCompraUsuario .= $mensaje_carro;

            if ($detalle['pago']['factura']) {
                $mensajeCompraUsuario .= '<p style="float:left><b>' . $_SESSION["lang-txt"]["api"]["email"]["factura_cuit"] . ' </b>' . $detalle['pago']['dni'] . '</p>';
            }

            $mensajeCompraUsuario .= '<h6 style="width:100%;float:left">' . $_SESSION["lang-txt"]["api"]["email"]["metodo_pago"] . ' ' . mb_strtoupper($pedidoData['data']["pago"]) . '</h6>';
            if (isset($detalle['link']) && $estado['data']['estado'] != '2') {
                $mensajeCompraUsuario .= '<h6>' . $_SESSION["lang-txt"]["api"]["email"]["link_pago"] . ' <a href="' . $detalle['link'] . '"> ' . $_SESSION["lang-txt"]["api"]["email"]["click_aqui"] . '</a></h6>';
            }

            $mensajeCompraUsuario .= '<br/><hr/>';
            $mensajeCompraUsuario .= '<div style="width:50%;float:left;margin-bottom:50px">';
            $mensajeCompraUsuario .= '<h3>' . $_SESSION["lang-txt"]["api"]["email"]["informacion_envio"] . '</h3>';
            $mensajeCompraUsuario .= $envioData;
            $mensajeCompraUsuario .= '</div>';
            $mensajeCompraUsuario .= '<div style="width:50%;float:right;margin-bottom:50px">';
            $mensajeCompraUsuario .= '<h3>' . $_SESSION["lang-txt"]["api"]["email"]["informacion_pago"] . '</h3>';
            $mensajeCompraUsuario .= $pagoData;
            $mensajeCompraUsuario .= '</div>';

            $mensajeCompraUsuario = str_replace('(content)', $mensajeCompraUsuario, $estado['data']['mensaje']);


            if ($enviar_Admin) {
                //ADMIN EMAIL
                $mensajeCompra = '¡Nueva compra desde la web!<br/>A continuación te dejamos el detalle del pedido.<hr/> <h3>Pedido realizado:</h3>';
                $mensajeCompra .= $mensaje_carro;

                if ($detalle['pago']['factura']) {
                    $mensajeCompra .= '<p><b>Factura A al CUIT: </b>' . $detalle['pago']['dni'] . '</p>';
                }
                $mensajeCompra .= '<br/><hr/>';
                $mensajeCompra .= '<h5 style="width:100%;float:left"><b>MÉTODO DE PAGO ELEGIDO:</b>' . mb_strtoupper($pedidoData['data']["pago"]) . '</h5>';
                $mensajeCompra .= '<div style="width:50%;float:right">';
                $mensajeCompra .= '<h3>Información de envio:</h3>';
                $mensajeCompra .= $envioData;
                $mensajeCompra .= '</div>';

                $mensajeCompra .= '<div style="width:50%;float:right">';
                $mensajeCompra .= '<h3>Información de pago:</h3>';
                $mensajeCompra .= $pagoData;
                $mensajeCompra .= '</div>';

                $enviar->set("asunto", "NUEVA COMPRA ONLINE");
                $enviar->set("receptor", $emailData['data']['remitente']);
                $enviar->set("emisor", $emailData['data']['remitente']);
                $enviar->set("mensaje", $mensajeCompra);
                $enviar->emailEnviar();
            }




            if ($estado['data']['enviar'] == 1) {
                $asunto = $estado['data']['asunto'];
                $enviar->set("asunto", $asunto);
                $enviar->set("receptor", $pedidoData['user']['data']["email"]);
                $enviar->set("emisor", $emailData['data']['remitente']);
                $enviar->set("mensaje", $mensajeCompraUsuario);
                $enviar->set("pedido", $pedidoData['data']['cod']);
                if ($enviar->emailEnviar()) {
                    $result = array("status" => true, "message" => $_SESSION["lang-txt"]["api"]["email"]["email_enviado"]);
                } else {
                    $result = array("status" => false, "message" => $_SESSION["lang-txt"]["api"]["email"]["email_enviado"]);
                }
                echo json_encode($result);
            }
        }
    } else {
        $result = array("status" => true, "message" => $_SESSION["lang-txt"]["api"]["email"]["estado_cambiado"]);
        echo json_encode($result);
    }
}
