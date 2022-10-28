<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$pedidos = new Clases\Pedidos();
$config = new Clases\Config();
$cod = isset($_SESSION['last_cod_pedido']) ? $funciones->antihack_mysqli($_SESSION['last_cod_pedido']) : '';
if (!empty($cod)) {
    $pedidos->set("cod", $cod);
    $pedidosData = $pedidos->view();

    if (!empty($pedidosData['data'])) {
        $config->set("id", 3);
        $paymentsData = $config->viewPayment();
        $mode = $paymentsData['data']['variable3']; //identificador de entorno obligatorio, la otra opción es "prod"
        $http_header = array('Authorization' => "TODOPAGO " . $paymentsData['data']['variable2']); //authorization key del ambiente requerido
        $merchant = $paymentsData['data']['variable1'];
        $connector = new TodoPago\Sdk($http_header, $mode);
        $operation_id = $_SESSION['last_cod_pedido'];
        $precio = number_format($pedidosData['data']['total'], "2", ".", "");
        $nombre = $_SESSION['usuarios']["nombre"];
        $apellido = $_SESSION['usuarios']["apellido"];
        $email = $_SESSION['usuarios']["email"];
        $celular = "0000000000";
        $postal = "2400";
        $localidad = "SAN FRANCISCO";
        $domicilio = "MORENO 357";
        $rand_user = rand(1, 9999);

        //comercio
        $optionsSAR_comercio = array(
            'Security' => $paymentsData['data']['variable2'],
            'Merchant' => $merchant,
            'URL_OK' => URL . '/api/payments/ipn-tp.php',
            'URL_ERROR' => URL . '/api/payments/ipn-tp.php'
        );

        //operacion
        $optionsSAR_operacion = array(
            'CSBTCITY' => $localidad, //Ciudad de facturación, REQUERIDO.
            'CSBTCOUNTRY' => 'AR', //País de facturación. REQUERIDO. Código ISO.
            'CSBTCUSTOMERID' => $rand_user, //Identificador del usuario al que se le emite la factura. REQUERIDO. No puede contener un correo electrónico.
            'CSBTIPADDRESS' => $_SERVER['REMOTE_ADDR'], //'192.0.0.4', //IP de la PC del comprador. REQUERIDO.
            'CSBTEMAIL' => $email, //Mail del usuario al que se le emite la factura. REQUERIDO.
            'CSBTFIRSTNAME' => $nombre, //Nombre del usuario al que se le emite la factura. REQUERIDO.
            'CSBTLASTNAME' => $apellido, //Apellido del usuario al que se le emite la factura. REQUERIDO.
            'CSBTPHONENUMBER' => $celular, //Teléfono del usuario al que se le emite la factura. No utilizar guiones, puntos o espacios. Incluir código de país. REQUERIDO.
            'CSBTPOSTALCODE' => $postal, //Código Postal de la dirección de facturación. REQUERIDO.
            'CSBTSTATE' => 'X', //Provincia de la dirección de facturación. REQUERIDO. Ver tabla anexa de provincias.
            'CSBTSTREET1' => $domicilio, //Domicilio de facturación (calle y nro). REQUERIDO.
            'CSBTSTREET2' => '', //Complemento del domicilio. (piso, departamento). OPCIONAL.
            'CSPTCURRENCY' => 'ARS', //Moneda. REQUERIDO.
            'CSPTGRANDTOTALAMOUNT' => "$precio", //Con decimales opcional usando el punto como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. REQUERIDO. (Ejemplos:$125,38-> 125.38 $12-> 12 o 12.00)
            'CSSTCITY' => $localidad, //Ciudad de envío de la orden. REQUERIDO.
            'CSSTCOUNTRY' => 'AR', //País de envío de la orden. REQUERIDO.
            'CSSTEMAIL' => $email, //Mail del destinatario, REQUERIDO.
            'CSSTFIRSTNAME' => $nombre, //Nombre del destinatario. REQUERIDO.
            'CSSTLASTNAME' => $apellido, //Apellido del destinatario. REQUERIDO.
            'CSSTPHONENUMBER' => $celular, //Número de teléfono del destinatario. REQUERIDO.
            'CSSTPOSTALCODE' => $postal, //Código postal del domicilio de envío. REQUERIDO.
            'CSSTSTATE' => 'X', //Provincia de envío. REQUERIDO. Son de 1 caracter
            'CSSTSTREET1' => $domicilio, //Domicilio de envío. REQUERIDO.
            //Retail: datos a enviar por cada producto, los valores deben estar separados con #:
            'CSITPRODUCTCODE' => 'default', //Código de producto. REQUERIDO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
            'CSITPRODUCTDESCRIPTION' => 'ORDEN ' . $operation_id, //Descripción del producto. REQUERIDO.
            'CSITPRODUCTNAME' => 'ORDEN ' . $operation_id, //Nombre del producto. REQUERIDO.
            'CSITPRODUCTSKU' => $operation_id, //Código identificador del producto. REQUERIDO.
            'CSITTOTALAMOUNT' => "$precio", //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el punto como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. REQUERIDO.
            'CSITQUANTITY' => '1', //Cantidad del producto. REQUERIDO.
            'CSITUNITPRICE' => "$precio", //Formato Idem CSITTOTALAMOUNT. REQUERIDO.
            //
            'MERCHANT' => $merchant, //dato fijo (número identificador del comercio)
            'OPERATIONID' => $operation_id, //número único que identifica la operación, generado por el comercio.
            'CURRENCYCODE' => 32, //por el momento es el único tipo de moneda aceptada
            'AMOUNT' => $precio,
            'EMAILCLIENTE' => $email
        );
        $values = $connector->sendAuthorizeRequest($optionsSAR_comercio, $optionsSAR_operacion);

        if (@$values['URL_Request'] != '') {
            $url = $values['URL_Request'];

            $detalle = json_decode($pedidosData['data']['detalle'], true);
            $detalle['link'] = $url;
            $detalleJSON = json_encode($detalle);
            $pedidos->set("detalle", $detalleJSON);
            $pedidos->changeValue('detalle');

            $checkout->close();
            $response = array("status" => true, "url" => $url);
            echo json_encode($response);
        } else {
            $response = array("status" => false, "message" => "NOSE");
            echo json_encode($response);
        }
    } else {
        $response = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
        echo json_encode($response);
    }
} else {
    $response = array("status" => false, "message" => "Ocurrió un error, recargar la página.");
    echo json_encode($response);
}
