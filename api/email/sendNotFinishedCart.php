<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$enviar = new Clases\Email();
$pedido = new Clases\Pedidos();
$config = new Clases\Config();
$contenido = new Clases\Contenidos();
$estadosPedidos  =  new Clases\EstadosPedidos();


$emailData = $config->viewEmail();

$fecha = date("Y-m-d H:i:s");
$fechaMenosSeis = date("Y-m-d H:i:s", strtotime($fecha . "- 6 hours"));
$fechaMasTres = date("Y-m-d H:i:s", strtotime($fechaMenosSeis . "+ 3 hours"));
$carroTotal = 0;
$mensajeCompraUsuario  = '';
$carritoNoCerrado = $pedido->list(["fecha BETWEEN '$fechaMenosSeis ' AND '$fechaMasTres'", "estado = '1'"], "", "");


$estado = $estadosPedidos->view(1);

if (isset($carritoNoCerrado) && !empty($carritoNoCerrado)) {
    foreach ($carritoNoCerrado as $carritoNoCerrado_) {

        $codPedido = isset($carritoNoCerrado_["data"]["cod"]) ? $carritoNoCerrado_["data"]["cod"] : "";

        if (!empty($codPedido)) {
            $pedido->set("cod", $codPedido);
            $pedidoData = $pedido->view();
            $detalle = json_decode($pedidoData['data']['detalle'], true);
            if (!empty($pedidoData['data'])) {
                $mensaje_carro = '<table border="1" style="text-align:left;width:100%;font-size:13px !important">';
                $mensaje_carro .= "<thead><th>Nombre producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>";
                foreach ($pedidoData['detail'] as $detail) {
                    $unserialized = unserialize($detail['descuento']);
                    if (!empty($unserialized) && isset($unserialized['cod'])) {
                        $descuentoCod = $unserialized["cod"];
                        $descuentoMonto = $unserialized["monto"];
                        $descuentoPrecio = $unserialized["precio-antiguo"];
                    } else {
                        $descuentoCod = '';
                        $descuentoMonto = '';
                        $descuentoPrecio = '';
                    }
                    $opciones = '';
                    $carroTotal += $detail['cantidad'] * $detail['precio'];
                    $mensaje_carro .= "<tr>";
                    $mensaje_carro .= "<td>" . $detail['producto'] . " <b>" . $descuentoMonto . "</b>" . $opciones . "</td>";
                    $mensaje_carro .= "<td>" . $detail["cantidad"] . "</td>";
                    if ($detail['precio'] != 0) {
                        $mensaje_carro .= "<td>$" . $detail['precio'] . " <span style='text-decoration: line-through'>" . $descuentoPrecio . "</span></td>";
                    } else {
                        $mensaje_carro .= "<td></td>";
                    }
                    $mensaje_carro .= "<td>$" . $detail['cantidad'] * $detail['precio'] . "</td>";

                    $mensaje_carro .= "</tr>";
                }
                $mensaje_carro .= '<tr><td></td><td></td><td></td><td>$' . $carroTotal . '</td></tr>';
                $mensaje_carro .= '</table>';

                $cuerpoMail = $estado['data']['mensaje'];
                $cuerpoMail = str_replace('(usuario)', ucfirst($pedidoData['user']['data']["nombre"]), $cuerpoMail);
                $cuerpoMail = str_replace('(link de pago)', isset($detalle['link']) ? '<a href="' . $detalle['link'] . '"> CLICK AQUÍ </a>' : '', $cuerpoMail);
                $cuerpoMail = str_replace('(armar carrito)', URL . "/pedido/" . $pedidoData['data']['cod'], $cuerpoMail);

                $mensajeCompraUsuario .= $cuerpoMail;
                $mensajeCompraUsuario .= "<h3>Pedido:</h3>";
                $mensajeCompraUsuario .= $mensaje_carro;

                if ($estado['data']['enviar'] == 1) {
                    $asunto = $estado['data']['asunto'];
                    $enviar->set("asunto", $asunto);
                    $enviar->set("receptor", $pedidoData['user']['data']["email"]);
                    $enviar->set("emisor", $emailData['data']['remitente']);
                    $enviar->set("mensaje", $mensajeCompraUsuario);
                    if ($enviar->emailEnviar()) {
                        echo "exitoso";
                    } else {
                        echo "error";
                    }
                }
            }
        }
    }
}
