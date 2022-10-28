<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$carrito = new Clases\Carrito();
$config = new Clases\Config();
$estado_pedido = new Clases\EstadosPedidos();
$pedidos = new Clases\Pedidos();

#Variables GET
$op = isset($_GET["op"]) ? $_GET["op"] : '';
#Si no se iniciaron los stages (se inician en el carrito) se redirige al carrito
(empty($_SESSION['stages']) || !($carrito->checkPaymentsLimits())) ? $f->headerMove(URL . '/carrito') : null;


// #Si el cod guardado no coincide con el de sesión, se redirige al carrito para que se actualice
($_SESSION['stages']['cod'] != $_SESSION['last_cod_pedido']) ? $f->headerMove(URL . '/carrito') : null;

#El carrito no puede estar vacío mientras está en los stages, sino se redirige al carrito
if (empty($_SESSION['carrito'])) {
    $checkout->destroy();
    $carrito->destroy();
    $f->headerMove(URL . '/carrito');
}

#Variable que almacena el progeso de los stages
$progress = $checkout->progress();


#Información de cabecera
$template->set("title", "Proceso de compra | " . TITULO);
$template->themeInitStages();
?>

<div class="checkout-estudiorocha navCart">
    <div class="login-register-section section pt-20 pb-70 pb-lg-50 pb-md-40 pb-sm-30 pb-xs-20">
        <div class="container-fluid">
            <div class="row" style="width:100%">
                <div id="main-checkout" class="col-md-<?= ($op != 'detail') ? 8 : 12 ?>">
                    <ul class="progress-indicator" style="width: 100%">
                        <li class="<?= ($progress["stage-1"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"]) ? "<a href='" . URL . "/checkout/shipping'> " . $_SESSION["lang-txt"]["checkout"]["envio"] . " </a>" : $_SESSION["lang-txt"]["checkout"]["envio"]; ?>
                        </li>

                        <li class="<?= ($progress["stage-1"] && $progress["stage-2"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"] && $progress["stage-2"]) ? "<a href='" . URL . "/checkout/billing'> " . $_SESSION["lang-txt"]["checkout"]["facturacion"] . " </a>" : $_SESSION["lang-txt"]["checkout"]["facturacion"]; ?>
                        </li>

                        <li class="<?= ($progress["stage-1"] && $progress["stage-2"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"] && $progress["stage-2"] && $progress["stage-3"]) ? "<a href='" . URL . "/checkout/payment'> " . $_SESSION["lang-txt"]["checkout"]["pago"] . " </a>" : $_SESSION["lang-txt"]["checkout"]["pago"]; ?>
                        </li>

                        <li class="<?= ($progress["stage-1"] && $progress["stage-2"] && $progress["stage-3"]) ? "completed" : ''; ?>">
                            <span class="bubble"></span>
                            <?= ($progress["stage-1"] && $progress["stage-2"] && $progress["stage-3"]) ? "<a href='" . URL . "/checkout/detail'> " . $_SESSION["lang-txt"]["checkout"]["detalle"] . " </a>" : $_SESSION["lang-txt"]["checkout"]["detalle"]; ?>
                        </li>
                    </ul>
                    <?php
                    if ($op != '') {
                        if (!empty($progress)) {
                            include("assets/inc/checkout/" . $op . ".php");
                        } else {
                            include("assets/inc/checkout/shipping.php");
                        }
                    } else {
                        $f->headerMove(URL . '/carrito');
                    }
                    ?>
                </div>
                <?php if ($op != 'detail') { ?>
                    <div class="col-md-4 hidden-xs hidden-sm">
                        <ul class="progress-indicator" style="width: 100%">
                            <li class="completed">
                                <span class="bubble"></span>
                                <?= $_SESSION["lang-txt"]["checkout"]["tu_compra"] ?>
                            </li>
                        </ul>
                        <cart></cart>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="skip-loader">
    <div style="display: flex;align-items: center;justify-content: center;top:0;left:0;width:100%;height:100vh;background:rgba(255,255,255,.5)">
        <div>
            <img style="margin-bottom: 20px;" src=" <?= LOGO ?>" width="300px" alt="">
            <div style="margin-bottom: 10px;margin-left: 10%;"><?= $_SESSION["lang-txt"]["checkout"]["generando_pedido"] ?></div>
            <img style="margin-left: 40%;" src="<?= URL ?>/assets/images/loader-skip-checkout.svg" width="50px" alt="">
        </div>
    </div>
</div>

<?php
$template->themeEndStages();
?>
<script>
    deleteShippingOption();
    deletePaymentOption();
    if (screen.width < 1024) {
        $('#main-checkout').removeClass('col-md-8').addClass('col-md-12');
    }
</script>
<script>
    $(window).on("load", function() {
        $(".skip-loader").fadeOut(500);
    });
</script>
<script src="<?= URL ?>/assets/js/checkout/script.js"></script>
<script src="<?= URL ?>/assets/js/checkout/stages.js"></script>
<script src="<?= URL ?>/assets/js/services/email.js"></script>

<?php

if ($op == 'detail') {
    $pedidos->set("cod", $_SESSION['last_cod_pedido']);
    $pedidoData = $pedidos->view();
    $estado_pedido->set("idioma", $_SESSION['lang']);
    $estadoData = $estado_pedido->view($pedidoData['data']['estado']);
?>
    <script>
        const checkoutFinal = async () => {
            await saveToPdf("<?= $_SESSION["last_cod_pedido"] ?>");
            await sendBuyTimer('<?= URL ?>', '<?= $_SESSION['last_cod_pedido'] ?>', '<?= $pedidoData['data']['estado'] ?>', '<?= $estadoData['data']['enviar'] ?>', true);
        }
        checkoutFinal();
    </script>
<?php
    if ($_SESSION['stages']['status'] == 'CLOSED') {
        $carrito->destroy();
        $checkout->destroy();
        unset($_SESSION["cod_pedido"]);
        if (!empty($_SESSION["usuarios"]['invitado'])) {
            if ($_SESSION["usuarios"]["invitado"] == 1) {
                unset($_SESSION["usuarios"]);
            }
        }
    }
}
?>