<?php
require_once "../Config/Autoload.php";
Config\Autoload::run();

$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$pedidos = new Clases\Pedidos();
$config = new Clases\Config();
$cod_pago = isset($_GET["codPago"]) ? $f->antihack_mysqli($_GET["codPago"]) : $f->headerMove(URL . "/checkout/payment");
$cod_pedido = isset($_GET["codPedido"]) ? $f->antihack_mysqli($_GET["codPedido"]) : $f->headerMove(URL . "/checkout/payment");
$config->set("id", 2);
$paymentsData = $config->viewPayment();

$pedidos->set('cod',$cod_pedido);
$pedido = $pedidos->view();
$price = (!empty($pedido['data']['entrega']) && $pedido['data']['entrega'] < $pedido['data']['total']) ? $pedido['data']['entrega'] : $pedido['data']['total'];
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/main-rocha.css">
    <script type="text/javascript" src="index.js" defer></script>
</head>

<body>
    <section class="payment-form dark">
        <div class="container text-center">
            <img src="<?= LOGO ?>" width="200" />
            <form id="form-checkout" class="mt-3">
                <input type="hidden" value="<?= URL ?>" id="url" />
                <input type="hidden" value="<?= $cod_pago ?>" id="cod_pago" />
                <input type="hidden" value="<?= $_SESSION["cod_pedido"] ?>" id="cod" />
                <input type="hidden" value="<?= max(number_format($price, "2", ".", ""), 0); ?>" id="amount" />
                <input type="hidden" value="<?= $paymentsData["data"]["variable1"] ?>" id="publickey" />
                <h3 class="text-uppercase fs-20">DATOS DE TU TARJETA</h3>
                <hr />
                <div id="message"></div>
                <div class="row d-none">
                    <div class="form-group col ">
                        <input id="form-checkout__cardholderEmail" name="cardholderEmail" value="<?= $_SESSION["usuarios"]["email"] ?>" type="email" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-5">
                        <select id="form-checkout__identificationType" name="identificationType" class="form-control"></select>
                    </div>
                    <div class="form-group col-sm-7">
                        <input id="form-checkout__identificationNumber" name="docNumber" type="text" value="<?= $_SESSION["usuarios"]["doc"] ?>" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-8">
                        <input id="form-checkout__cardholderName" name="cardholderName" type="text" class="form-control" />
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group expiration-date">
                            <input id="form-checkout__cardExpirationMonth" maxlength="2" name="cardExpirationMonth" type="number" class="form-control mr-2" />
                            <span style="margin-top:5px">/</span>
                            <input id="form-checkout__cardExpirationYear" maxlength="2" name="cardExpirationYear" type="number" class="form-control ml-2" />
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <input id="form-checkout__cardNumber" name="cardNumber" type="number" class="form-control" />
                    </div>
                    <div class="form-group col-sm-4">
                        <input id="form-checkout__securityCode" name="securityCode" maxlength="4" type="text" class="form-control" />
                    </div>
                    <div id="issuerInput" class="form-group col-sm-12 d-none">
                        <select id="form-checkout__issuer" name="issuer" class="form-control"></select>
                    </div>
                    <div class="form-group col-sm-12">
                        <select id="form-checkout__installments" name="installments" type="text" class="form-control"></select>
                    </div>
                    <div class="form-group col-sm-12">
                        <button id="form-checkout__submit" type="submit" class="btn btn-primary btn-block">Pagar</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
        </div>
    </section>
</body>

</html>