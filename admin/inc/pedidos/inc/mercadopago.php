<?php
include($_SERVER['DOCUMENT_ROOT'] . "/develop/vendor/mercadopago/sdk/lib/mercadopago.php");
$config = new Clases\Config();
$config->set("id", 1);
$paymentsData = $config->viewPayment();
$mp = new MP ($paymentsData['data']['variable1'], $paymentsData['data']['variable2']);
$preference_data = array("items" => array(array("id" => $_SESSION["cod_pedido"], "title" => "COMPRA CÓDIGO N°:" . $_SESSION["cod_pedido"], "quantity" => 1, "currency_id" => "ARS", "unit_price" => $precio)), "payer" => array("name" => $_SESSION["usuarios-ecommerce"]["nombre"], "surname" => $_SESSION["usuarios-ecommerce"]["apellido"], "email" => $_SESSION["usuarios-ecommerce"]["email"]), "back_urls" => array("success" => URL_ADMIN . "/compra-finalizada.php?estado=2", "pending" => URL_ADMIN . "/compra-finalizada.php?estado=1", "failure" => URL_ADMIN . "/compra-finalizada.php?estado=0"), "notification_url" => URL_ADMIN . "/ipn.php", "external_reference" => $_SESSION["cod_pedido"], "auto_return" => "all", //"client_id" => $usuarioSesion["cod"],
    "payment_methods" => array("excluded_payment_methods" => array(), "excluded_payment_types" => array(array("id" => "ticket"), array("id" => "atm"))));
$preference = $mp->create_preference($preference_data);
if (isset($preference['response']['sandbox_init_point'])) {
    $factura_ .= "<b>LINK PARA REALIZAR EL PAGO: </b>" . $preference['response']['sandbox_init_point'] . "<br/>";
    $url = $preference['response']['sandbox_init_point'];
} else {
    $factura_ .= '';
}