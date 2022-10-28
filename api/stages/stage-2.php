<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$checkout = new Clases\Checkout();
$config  = new Clases\Config();
$detalle = new Clases\DetallePedidos();

$facturaData = $config->viewTaxFactura();

$nombre = isset($_POST['nombre']) ?  $f->antihack_mysqli($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ?  $f->antihack_mysqli($_POST['apellido']) : '';
$email = isset($_POST['email']) ?  $f->antihack_mysqli($_POST['email']) : '';
$dni = isset($_POST['dni']) ?  $f->antihack_mysqli($_POST['dni']) : '';
$telefono = isset($_POST['telefono']) ?  $f->antihack_mysqli($_POST['telefono']) : '';
$celular = isset($_POST['celular']) ?  $f->antihack_mysqli($_POST['celular']) : '';
$provincia = isset($_POST['provincia']) ?  $f->antihack_mysqli($_POST['provincia']) : '';
$localidad = isset($_POST['localidad']) ?  $f->antihack_mysqli($_POST['localidad']) : '';
$direccion = isset($_POST['direccion']) ?  $f->antihack_mysqli($_POST['direccion']) : '';
$factura = isset($_POST['factura']) ?  $f->antihack_mysqli($_POST['factura']) : '';

$totalPrice = $carrito->precioSinMetodoDeEnvio();

if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($dni) && !empty($telefono) && !empty($celular) && !empty($provincia) && !empty($localidad) && !empty($direccion)) {

    $data = array(
        "nombre" => $nombre,
        "apellido" => $apellido,
        "email" => $email,
        "dni" => $dni,
        "telefono" => $telefono,
        "celular" => $celular,
        "provincia" => $provincia,
        "localidad" => $localidad,
        "direccion" => $direccion,
        "factura" => $factura,
    );
    if (isset($factura) && $factura == true && !empty($facturaData) && isset($facturaData["data"])) {
        if ($facturaData['data']['tipo'] == "porcentaje") {
            $percentInDecimal = $facturaData["data"]["valor"] / 100;
            $price = $percentInDecimal * $totalPrice;
        } else {
            $price = (isset($facturaData['data']["valor"])) ? $facturaData['data']["valor"] : 0;
        }
        $opciones =  [
            "texto" => '',
            "subatributos" => '',
            "combinacion" => ['cod_combinacion' => '', 'id' => '', 'idioma' => '', 'mayorista' => '', 'precio' => '', 'stock' => '']
        ];
        $carrito->set("id", "factura");
        $carrito->set("cantidad", 1);
        $carrito->set("titulo", "Factura");
        $carrito->set("precio", $price);
        $carrito->set("producto_cod", "factura");
        $carrito->set("opciones", $opciones);
        $carrito->set("tipo", "FA");
        $carrito->add();

        $detalle->set("cod", $_SESSION['last_cod_pedido']);
        $detalle->set("producto", "Factura");
        $detalle->set("cantidad", 1);
        $detalle->set("precio", $price);
        $detalle->set("tipo", "FA");
        $detalle->set("descuento", '');
        $detalle->set("cod_producto",  "factura");
        $detalle->set("producto_cod",  "factura");
        $detalle->set("cod_combinacion", '');
        $detalle->set("cuotas", '');
        $detalle->add();
    }

    if ($checkout->stage2($data)) {
        $response = array("status" => true);
        echo json_encode($response);
    } else {
        $response = array("status" => false, "type" => "error", "message" => "[201] Ocurrió un error, recargar la página.");
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
    if (empty($dni)) {
        $message .= '- DNI/CUIT<br>';
    }
    if (empty($telefono)) {
        $message .= '- Telefono<br>';
    }
    if (empty($celular)) {
        $message .= '- Celular<br>';
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
