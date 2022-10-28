<?php


$pedidos = new Clases\Pedidos();
$usuarios = new Clases\Usuarios();
$f = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$detalleCombinacion = new Clases\DetalleCombinaciones();
$estadosPedidos = new Clases\EstadosPedidos();
$pedido = new Clases\Pedidos();

if (isset($_SESSION['stages'])) {
    // $pedidos->checkMercadoPago();
    $pedidos->set("cod", $_SESSION['last_cod_pedido']);
    $pedido_info = $pedidos->view();
    if ($_SESSION['stages']['status'] == 'CLOSED') {
?>
        <div class="container">
            <div class="section pt-50  pb-70 pb-lg-50 pb-md-40 pb-sm-30 pb-xs-20">
                <div class="customer-login-register register-pt-0">
                    <form id="payment-f" method="post">
                        <div class="form-register-title" style="width:100%;display:block;margin-top:30px">
                            <h2><?= $_SESSION["lang-txt"]["checkout"]["detail"]["compra_finalizada"] ?></h2>
                            <h4><?= $_SESSION["lang-txt"]["checkout"]["detail"]["pedido"] ?> N°: <?= $_SESSION['last_cod_pedido'] ?></h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["estado"] ?>:</b> <?= mb_strtoupper($pedido_info['estados']['data']['titulo']); ?><br />
                                    <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["metodo_pago"] ?>:</b> <?= mb_strtoupper($pedido_info['data']["pago"]); ?><br />
                                    <?php
                                    if (!empty($pedido_info['data']['detalle'])) {
                                        $detalle = json_decode(preg_replace('/[\x00-\x1F]/', '<br/>', $pedido_info['data']['detalle']), true);
                                        if (!empty($detalle['leyenda'])) {
                                            echo "<b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["descripcion_pago"] . ": </b>" . $detalle['leyenda'] . "<br/>";
                                        }
                                        if (!empty($detalle['descuento'])) {
                                            echo "<b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["cod_descuento"] . ": </b>" . $detalle['descuento'];
                                        }
                                        if (!empty($detalle['link'])) {
                                            echo "<b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["url_pago"] . ": </b><a href='" . $detalle['link'] . "' target='_blank'>" . $_SESSION["lang-txt"]["checkout"]["detail"]["click_aqui"] . "</a>";
                                        }
                                    ?>
                                        <div class="row mb-15">
                                            <div class="col-md-6">
                                                <hr>
                                                <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["informacion_envio"] ?></b>
                                                <br>
                                                <?= $envioData = $pedido->getInfoPedido($detalle, 'envio'); ?>
                                                <p class='mb-0 fs-13'><b><?= $_SESSION["lang-txt"]["checkout"]["similar"] ?>: </b><?= $detalle['envio']['similar'] ? "Si" : "No" ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <hr>
                                                <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["informacion_facturacion"] ?></b>
                                                <br>
                                                <?= $pagoData = $pedido->getInfoPedido($detalle, 'pago'); ?>
                                                <?php
                                                if ($detalle['pago']['factura']) {
                                                    echo "<p class='mb-0 fs-13'><b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["factura_cuit"] . ": </b>" . $detalle['pago']['dni'] . "</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-md-12" style="width:100%;display:block;margin-top:30px">
                                    <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["tu_compra"] ?></b>
                                    <hr>
                                    <?php include("assets/inc/checkout/pedidoDetail.php"); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div id="btn-payment-d" class="col-md-12 col-xs-12 mt-10 mb-50">
                                    <a class="btn btn-success btn-block text-center fs-20" style="line-height: 2.71!important;" href="<?= URL ?>" id="btn-payment-1">
                                        <?= $_SESSION["lang-txt"]["checkout"]["detail"]["volver_inicio"] ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    } else {
        $f->headerMove(URL . '/carrito');
    }
} else {
    $f->headerMove(URL . '/carrito');
}
?>
<script src="<?= URL ?>/assets/js/checkout/script.js"></script>
<script src="<?= URL ?>/assets/js/checkout/stages.js"></script>