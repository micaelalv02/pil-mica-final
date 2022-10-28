<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$checkout = new Clases\Checkout();
$envios = new Clases\Envios();
$usuarios = new Clases\Usuarios();
$pedidos = new Clases\Pedidos();
$detalle = new Clases\DetallePedidos();

$envio = isset($_POST['envio']) ?  $f->antihack_mysqli($_POST['envio']) : '';








$nombre = isset($_POST['nombre']) ?  $f->antihack_mysqli($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ?  $f->antihack_mysqli($_POST['apellido']) : '';
$email = isset($_POST['email']) ?  $f->antihack_mysqli($_POST['email']) : '';
$telefono = isset($_POST['telefono']) ?  $f->antihack_mysqli($_POST['telefono']) : '';
$celular = isset($_POST['celular']) ?  $f->antihack_mysqli($_POST['celular']) : '';
$postal = isset($_POST['postal']) ?  $f->antihack_mysqli($_POST['postal']) : '';
$provincia = isset($_POST['provincia']) ?  $f->antihack_mysqli($_POST['provincia']) : '';
$localidad = isset($_POST['localidad']) ?  $f->antihack_mysqli($_POST['localidad']) : '';
$direccion = isset($_POST['direccion']) ?  $f->antihack_mysqli($_POST['direccion']) : '';
$fecha = isset($_POST['fecha']) ?  $f->antihack_mysqli($_POST['fecha']) : '';
$rango_fecha = isset($_POST['rango_fecha']) ?  $f->antihack_mysqli($_POST['rango_fecha']) : '';
$similar = isset($_POST['similar']) ?  $f->antihack_mysqli($_POST['similar']) : '';
if (!empty($envio)) {
    $envios->set("cod", $envio);
    $envios->set("idioma", $_SESSION['lang']);
    $envioData = $envios->view();
    $dataFecha = '';
    if ($envioData["data"]["opciones"] == 2 && !empty($fecha)) $dataFecha = $fecha;
    if ($envioData["data"]["opciones"] == 3 && !empty($rango_fecha)) $dataFecha = $rango_fecha;
    if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($telefono) && !empty($celular) && !empty($postal) && !empty($provincia) && !empty($localidad) && !empty($direccion)) {
        if (!empty($envioData)) {
            $detalleCompleto = array(
                'leyenda' => '',
                'descuento' => '',
                'envio' => array(
                    'tipo' => '',
                    'nombre' => '',
                    'apellido' => '',
                    'email' => '',
                    'provincia' => '',
                    'localidad' => '',
                    'direccion' => '',
                    'telefono' => '',
                    'fecha' => '',
                    'similar' => false
                ),
                'pago' => array(
                    'nombre' => '',
                    'apellido' => '',
                    'email' => '',
                    'dni' => '',
                    'provincia' => '',
                    'localidad' => '',
                    'direccion' => '',
                    'telefono' => '',
                    'factura' => false
                )
            );

            $data = array(
                "envio" => $envio,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "email" => $email,
                "telefono" => $telefono,
                "celular" => $celular,
                "postal" => $postal,
                "provincia" => $provincia,
                "localidad" => $localidad,
                "direccion" => $direccion,
                "fecha" => $dataFecha,
                "similar" => $similar,
            );

            if ($checkout->stage1($data)) {
                $carrito->deleteOnCheck("Envio-Seleccion");
                $carrito->deleteOnCheck("Metodo-Pago");

                if ($envioData['data']['limite']) {
                    if ($carrito->precioSinMetodoDePago() >= $envioData['data']['limite']) {
                        $envioData['data']["titulo"] = $envioData['data']["titulo"] . "¡GRATIS COMPRA SUPERIOR A $" . $envioData['data']['limite'] . "!";
                        $envioData['data']["precio"] = 0;
                    }
                }
                $opciones =  [
                    "texto" => '',
                    "subatributos" => '',
                    "combinacion" => ['cod_combinacion' => '', 'id' => '', 'idioma' => '', 'mayorista' => '', 'precio' => '', 'stock' => '']
                ];

                $carrito->set("id", "Envio-Seleccion");
                $carrito->set("cantidad", 1);
                $carrito->set("titulo", $envioData['data']["titulo"]);
                $carrito->set("precio", $envioData['data']["precio"]);
                $carrito->set("producto_cod", "Metodo de Envio");
                $carrito->set("tipo", "me");
                $carrito->set("opciones", $opciones);
                $carrito->add();

                //Por si eligio invitado, ver de guardarlo en la base o updatearlo
                if ($_SESSION['stages']['type'] == 'GUEST') {
                    $usuarios->set("nombre", $nombre);
                    $usuarios->set("apellido", $apellido);
                    $usuarios->set("doc", '');
                    $usuarios->set("email", $email);
                    $usuarios->set("password", '');
                    $usuarios->set("direccion", $direccion);
                    $usuarios->set("localidad", $localidad);
                    $usuarios->set("provincia", $provincia);
                    $usuarios->set("telefono", $telefono);
                    $usuarios->set("postal", $postal);
                    $usuarios->set("invitado", 1);
                    $usuarios->set("fecha", date("Y-m-d"));
                    $usuarios->set("estado", 1);
                    $usuarios->set("minorista", 1);
                    $usuarios->set("idioma", $_SESSION["lang"]);

                    $emailData = $usuarios->validate();
                    if ($emailData['status']) {
                        $cod = $emailData['data']['cod'];
                    } else {
                        $cod = substr(md5(uniqid(rand())), 0, 10);
                    }

                    $usuarios->set("cod", $cod);

                    if ($emailData['status']) {
                        $usuarios->guestSession();
                        $checkout->user($cod, 'GUEST');
                        $response = array("status" => true);
                        echo json_encode($response);
                    } else {
                        $usuarios->firstGuestSession();
                        $checkout->user($cod, 'GUEST');
                        $response = array("status" => true);
                        echo json_encode($response);
                    }
                } else {
                    $response = array("status" => true);
                    echo json_encode($response);
                }

                $_SESSION["usuarios"] = $usuarios->viewSession();
                $precio = $carrito->totalPrice();
                if (!empty($pagosData['data']['leyenda'])) {
                    $detalleCompleto['leyenda'] = $pagosData['data']['leyenda'];
                }
                $detalleCompleto['envio']['tipo'] = $_SESSION['carrito'][$carrito->checkKeyOnCart("Envio-Seleccion")]['titulo'];
                $detalleCompleto['envio']['nombre'] = $nombre;
                $detalleCompleto['envio']['apellido'] = $apellido;
                $detalleCompleto['envio']['email'] = $email;
                $detalleCompleto['envio']['provincia'] = $provincia;
                $detalleCompleto['envio']['localidad'] = $localidad;
                $detalleCompleto['envio']['direccion'] = $direccion;
                $detalleCompleto['envio']['telefono'] = $telefono;
                $detalleCompleto['envio']['postal'] = $postal;
                $detalleCompleto['envio']['celular'] = $celular;
                $detalleCompleto['envio']['fecha'] = $fecha;
                if (!empty($similar)) {
                    $detalleCompleto['envio']['similar'] = true;
                }

                $carro = $carrito->return();
                $timezone = -3;
                $fecha = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));
                $pedidos->set("cod", $_SESSION['last_cod_pedido']);
                #Agrega al pedido
                $detalleCompletoJSON = json_encode($detalleCompleto, JSON_UNESCAPED_UNICODE);
                $pedidos->set("cod", $_SESSION['last_cod_pedido']);
                $pedidos->set("entrega", $precio);
                $pedidos->set("total", $precio);
                $pedidos->estado =  1; // CARRITO NO CERRADO
                $pedidos->set("pago", "");
                $pedidos->set("usuario", $_SESSION['usuarios']['cod']);
                $pedidos->set("fecha", $fecha);
                $pedidos->set("detalle", $detalleCompletoJSON);
                $pedidos->set("observacion", "");
                $pedidos->set("visto", "");
                $pedidos->set("idioma", $_SESSION['lang']);
                if ($pedidos->view()) {
                    $pedidos->edit();
                } else {
                    $pedidos->add();
                    $pedidoId = $pedidos->view();
                    $_SESSION["last_cod_pedido"] = str_pad($pedidoId["data"]["id"], 10, "0", STR_PAD_LEFT);
                    $_SESSION['stages']['cod'] = str_pad($pedidoId["data"]["id"], 10, "0", STR_PAD_LEFT);
                    $pedidos->editSingle("cod", $_SESSION['last_cod_pedido']);
                }
                $detalle->reset($_SESSION['last_cod_pedido']);
                foreach ($carro as $carroItem) {
                    $producto_cod = isset($carroItem["producto_cod"]) ? $carroItem["producto_cod"] : "Descuento";
                    $descuento = json_encode($carroItem["descuento"]);
                    if ($carroItem["descuento"] == "") $descuento = "";
                    if ($descuento == "null")  $descuento = "";
                    #Agrega el detalle
                    $detalle->set("cod", $_SESSION['last_cod_pedido']);
                    $detalle->set("producto", isset($carroItem["opciones"]["texto"]) ? $carroItem["titulo"] . " " . $carroItem["opciones"]["texto"] : $carroItem["titulo"]);
                    $detalle->set("cantidad", $carroItem["cantidad"]);
                    $detalle->set("promo", $carroItem["promo"]);
                    $detalle->set("precio", $carroItem["precio"]);
                    $detalle->set("tipo", strToUpper($carroItem['tipo']));
                    $detalle->set("descuento", $descuento);
                    $detalle->set("cod_producto",  $carroItem["id"]);
                    $detalle->set("producto_cod",  $producto_cod);
                    $detalle->set("cod_combinacion", isset($carroItem["opciones"]["combinacion"]["cod_combinacion"]) ? $carroItem["opciones"]["combinacion"]["cod_combinacion"] : '');
                    $detalle->set("cuotas",'');
                    $detalle->add();
                }
            } else {
                $response = array("status" => false, "type" => "error", "message" => "[101] Ocurrió un error, recargar la página.");
                echo json_encode($response);
            }
        } else {
            $response = array("status" => false, "type" => "error", "message" => "[102] Ocurrió un error, recargar la página.");
            echo json_encode($response);
        }
    } else {
        $message = 'Completar los siguientes campos correctamente:<br>';
        if (empty($nombre)) {
            $message .= '- Nombre<br>';
        }
        if (empty($apellido)) {
            $message .= '- Apellido<br>';
        }
        if (empty($email)) {
            $message .= '- Email<br>';
        }
        if (empty($telefono)) {
            $message .= '- Telefono<br>';
        }
        if (empty($celular)) {
            $message .= '- Celular<br>';
        }
        if (empty($postal)) {
            $message .= '- Código Postal<br>';
        }
        if (empty($provincia)) {
            $message .= '- Provincia<br>';
        }
        if (empty($localidad)) {
            $message .= '- Localidad<br>';
        }
        if (empty($direccion)) {
            $message .= '- Direccion<br>';
        }
        $response = array("status" => false, "type" => "error", "message" => $message);
        echo json_encode($response);
    }
} else {
    $response = array("status" => false, "type" => "error", "message" => 'Seleccionar un tipo de envío.');
    echo json_encode($response);
}
