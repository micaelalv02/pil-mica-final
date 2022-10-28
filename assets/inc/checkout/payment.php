<?php
$carrito = new Clases\Carrito();
$pagos = new Clases\Pagos();
$imagenes = new Clases\Imagenes();

$tipoUsuario = ($_SESSION['stages']['type'] == 'GUEST' || $_SESSION['usuarios']['minorista'] == 1) ? 1 : 2; // 1 Minorista - 2 Mayorista
$carrito->deleteOnCheck("Metodo-Pago");
if (isset($_SESSION['stages'])) {
    if ($_SESSION['stages']['status'] == 'OPEN' && !empty($_SESSION['stages']['stage-2']) && !empty($_SESSION['stages']['user_cod'])) {
?>
        <div class="row">
            <div class="col-md-12 pt-10">
                <hr>
                <p class="text-uppercase bold fs-20 text-center"><?= $_SESSION["lang-txt"]["checkout"]["payment"]["seleccionar_pago"] ?></p>
                <hr>
                <form id="payment-f" method="post" data-url="<?= URL ?>" data-cod="<?= $_SESSION['last_cod_pedido'] ?>" onsubmit="addPayment()">
                    <div id="formPago" class="background-shipping container">
                        <ul class="row pt-40 pl-0-mobile">
                            <?php
                            $listPagos = $pagos->list(["estado = 1", "(tipo_usuario = $tipoUsuario OR tipo_usuario = 0)", "(" . $carrito->precioSinMetodoDePago() . " >= minimo OR minimo IS NULL) AND (" . $carrito->precioSinMetodoDePago() . " <= maximo OR maximo = 0 OR maximo IS NULL)"], "", "", $_SESSION['lang']);
                            foreach ($listPagos as $key => $pago) {
                                $img = $imagenes->view($pago["data"]["cod"]);
                                if (!$img) $img["ruta"] = "assets/archivos/sin_imagen.jpg";
                                $precio_total = $carrito->checkPriceOnPayments($pago);
                            ?>
                                <div class='col text-center pagoDesktop'>
                                    <label style="text-align: center;" onclick="checkedBox('<?= $pago['data']['cod'] ?>');">
                                        <div class='box-img'>
                                            <img class="round-img-style" src='<?= URL . "/" . $img["ruta"]  ?>' alt='<?= $pago['data']['titulo'] ?>'>
                                            <input type='radio' class="d-none" <?= ((count($listPagos) < 2)  ? "checked" : '') ?> id='pago-<?= $pago['data']["cod"] ?>' name='cod' value='<?= $pago['data']["cod"] ?>'>
                                        </div>
                                        <span class="text-uppercase bold boxChecked fs-14 " id="<?= $pago['data']['cod'] ?>"><?= $pago['data']['titulo'] . " $" . $precio_total; ?></span>
                                        <p class="fs-12" style="min-height: 72px"><?= !empty($pago['data']['leyenda']) ? $pago['data']['leyenda'] : ''; ?></p>
                                        <p class="btn btn-primary btn-seleccionar"><?= $_SESSION["lang-txt"]["checkout"]["payment"]["seleccionar"] ?></p>
                                    </label>
                                </div>


                                <label class="text-uppercase btn btn-default btn-block pt-20 pb-20 pagoMobile">
                                    <input type="radio" <?= ((count($listPagos) < 2)  ? "checked" : '') ?> name="cod" value="<?= $pago['data']['cod'] ?>" id="r-<?= $key ?>" required>
                                    <?= $pago['data']['titulo'] ?> <price class='hidden'> &nbsp| <span style="color:red;font-weight: 700px;"><?= "$" . $precio_total ?></span></price>
                                    <p> &nbsp<?= !empty($pago['data']['leyenda']) ? $pago['data']['leyenda'] : ''; ?> </p>
                                    <br />
                                </label>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="row">
                        <div id="btn-payment-d" class="col-md-12 col-xs-12 mt-10 mb-50">
                            <a href="<?= URL ?>/checkout/billing" class="btn btn-default" style="line-height: 46px"><i class="fa fa-chevron-left"></i> <?= $_SESSION["lang-txt"]["checkout"]["payment"]["volver"] ?></a>
                            <button class="btn btn-next-checkout pull-right text-uppercase" type="submit" id="btn-payment-1">
                                <?= $_SESSION["lang-txt"]["checkout"]["payment"]["finalizar"] ?> <i class="fa fa-check-circle"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
    } else {
        if ($_SESSION['stages']['status'] == 'CLOSED') {
            $f->headerMove(URL . '/checkout/detail');
        } else {
            if (empty($_SESSION['stages']['user_cod'])) {
                $f->headerMove(URL . '/login');
            } else {
                $f->headerMove(URL . '/checkout/billing');
            }
        }
    }
} else {
    $f->headerMove(URL . '/carrito');
}
?>
<div id="modalS" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div id="textS" class="text-center">
                </div>
            </div>
        </div>
    </div>
</div>