<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();

$funciones = new Clases\PublicFunction();
$decidir = new Clases\Decidir();
$pedido = new Clases\Pedidos();


$order = $funciones->antihack_mysqli($_POST["order"]);
$pedido->set("cod", $order);
$pedidoData  = $pedido->view();
$carroTotal = 0;
$mensaje_carro = 'Pedido: ' . $order . '\n';
foreach ($pedidoData["detail"] as $carriItem) {
    $carroTotal += $carriItem["cantidad"] * $carriItem["precio"];
    $mensaje_carro .= $carriItem["producto"] . ' (' . $carriItem["cantidad"] . ') :' . $carriItem["cantidad"] * $carriItem["precio"] . '\n';
}
$mensaje_carro .= '\n TOTAL: ' . $carroTotal;


if (!empty($pedidoData["data"])) {
    $paymentMethodId = $funciones->antihack_mysqli($_POST["card_type"]);
    unset($_POST["order"]);
    unset($_POST["card_type"]);
    unset($_POST["installments"]);

    $installments = $decidir->getInstallmentsForPayment($pedidoData["detail"]);
    if ($installments) {
        $data = $decidir->getPaymentToken($_POST);

        $data = json_decode($data, true);
        if (isset($data["status"]) && $data["status"] == "active") {
            $data = $decidir->processPayment(substr(md5(uniqid(rand())), 0, 10), $paymentMethodId, $data["id"], $data["bin"], $pedidoData["data"]["total"], $installments, $mensaje_carro);
            $data = json_decode($data, true);
            if (isset($data["error_type"])) {
                echo json_encode(["status" => false, "message" => $data["error_type"]]);
            } else {
                if ($data["status"] == "approved") echo json_encode(["status" => true, "message" => "¡Pago procesado!"]);
                if ($data["status"] == "preapporved") echo json_encode(["status" => true, "message" => "¡Pago pendiente!"]);
                if ($data["status"] == "review") echo json_encode(["status" => true, "message" => "¡Pago en proceso!"]);
                if ($data["status"] == "rejected") echo json_encode(["status" => false, "message" => "¡Pago rechazado!"]);
            }
        } else {
            echo json_encode(["status" => false, "message" => "Ocurrio un error", "error" => $data["message"]]);
        }
    } else {
        echo json_encode(["status" => false, "goBack" => true, "message" => "Ocurrio un error con el pedido"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Ocurrio un error con el pedido"]);
}
