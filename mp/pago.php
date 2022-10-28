<?php
require_once "../Config/Autoload.php";
Config\Autoload::run();

$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$config = new Clases\Config();
$mercadolibre = new Clases\MercadoLibre();
$cod_pago = isset($_GET["cod"]) ? $f->antihack_mysqli($_GET["cod"]) : $f->headerMove(URL . "/checkout/payment");
$config->set("id", 2);
$paymentsData = $config->viewPayment();
?>


<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/main-rocha.css">
    <script type="text/javascript" src="index.js" defer></script>
</head>
<script>
window.Mercadopago.setPublishableKey(<?= $paymentsData["data"]["variable1"] ?>);
window.Mercadopago.getIdentificationTypes();
</script>
<?php

  MercadoPago\SDK::setAccessToken("ENV_ACCESS_TOKEN");

  $payment_methods = MercadoPago::get("/v1/payment_methods");

?>
<body>
    <section class="payment-form dark">
        <div class="container text-center">
            <img src="<?= LOGO ?>" width="200" />
            <form action="/process_payment" method="post" id="paymentForm">
 
                <h3>Medio de pago</h3>
                <div>
                    <select class="form-control" id="paymentMethod" name="paymentMethod">
                        <option>Seleccione un medio de pago</option>

                        <!-- Create an option for each payment method with their name and complete the ID in the attribute 'value'. -->
                        <option value="--PaymentTypeId--">--PaymentTypeName--</option>
                    </select>
                </div>
                <h3>Detalles del comprador</h3>
                <div>
                    <div>
                        <label for="payerFirstName">Nombre</label>
                        <input id="payerFirstName" name="payerFirstName" type="text" value="Nome"></select>
                    </div>
                    <div>
                        <label for="payerLastName">Apellido</label>
                        <input id="payerLastName" name="payerLastName" type="text" value="Sobrenome"></select>
                    </div>
                    <div>
                        <label for="payerEmail">E-mail</label>
                        <input id="payerEmail" name="payerEmail" type="text" value="test@test.com"></select>
                    </div>
                    <div>
                        <label for="docType">Tipo de documento</label>
                        <select id="docType" name="docType" data-checkout="docType" type="text"></select>
                    </div>
                    <div>
                        <label for="docNumber">Número de documento</label>
                        <input id="docNumber" name="docNumber" data-checkout="docNumber" type="text" />
                    </div>
                </div>

                <div>
                    <div>
                        <input type="hidden" name="transactionAmount" id="transactionAmount" value="100" />
                        <input type="hidden" name="productDescription" id="productDescription" value="Nombre del Producto" />
                        <br>
                        <button type="submit">Pagar</button>
                        <br>
                    </div>
                </div>
            </form>
        </div>
        </div>
        </div>
    </section>
</body>

</html>