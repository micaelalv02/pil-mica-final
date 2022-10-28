<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$pagos = new Clases\Pagos();
$checkout = new Clases\Checkout();
$pedidos = new Clases\Pedidos();
$config = new Clases\Config();
$user = new Clases\Usuarios();
$cod = isset($_POST['cod']) ? $f->antihack_mysqli($_POST['cod']) : '';
if (!empty($cod)) {
    $pedidos->set("cod", $cod);
    $pedidosData = $pedidos->view();
    $user->set("cod", $pedidosData['data']['usuario']);
    $userData = $user->view()["data"];
    $nombre = $userData["nombre"];
    $apellido = $userData["apellido"];
    $email = $userData["email"];

    if (!empty($pedidosData['data'])) {
        $config->set("id", 1);
        $paymentsData = $config->viewPayment();
        $mp = new MP($paymentsData['data']['variable1'], $paymentsData['data']['variable2']);
        $preference_data = array(
            "items" => array(
                array(
                    "id" => $pedidosData['data']['cod'],
                    "title" => "COMPRA CÓDIGO N°:" . $pedidosData['data']['cod'],
                    "quantity" => 1,
                    "currency_id" => "ARS",
                    "unit_price" => floatval($pedidosData['data']['total'])
                )
            ),
            "payer" => array(
                "name" => $nombre,
                "surname" => $apellido,
                "email" => $email
            ),
            "back_urls" => array(
                "success" => URL . "/checkout/detail",
                "pending" => URL . "/checkout/detail",
                "failure" => URL . "/checkout/detail"
            ),
            "notification_url" => URL . "/api/payments/ipn.php",
            "external_reference" => $pedidosData['data']['cod'],
            "auto_return" => "all"
        );
        $preference = $mp->create_preference($preference_data);

        $detalle = json_decode($pedidosData['data']['detalle'], true);
        $url = $preference['response']['init_point'];
        $response = array("status" => true, "url" => $url);
        echo json_encode($response);
    } else {
        $response = array("status" => false, "message" => "Ocurrió un error, recargar la página.1");
        echo json_encode($response);
    }
} else {
    $response = array("status" => false, "message" => "Ocurrió un error, recargar la página.2");
    echo json_encode($response);
}