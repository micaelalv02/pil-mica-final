<?php
ob_start();
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";

Config\Autoload::run();

$f = new Clases\PublicFunction();
$pedidos = new Clases\Pedidos();
$carrito = new Clases\Carrito();

use Dompdf\Dompdf;

$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);
$cod = isset($_GET["cod"]) ? $f->antihack_mysqli($_GET["cod"]) : '';

$pedidos->set("cod", $cod);
$pedido_info = $pedidos->view();
$dompdf = new Dompdf();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<style>
    * {
        font-size: 13px !important;
        line-height: 1.2;
        font-family: Arial, Helvetica, sans-serif;
    }

    thead {
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
        margin-bottom: 10px;
    }

    tbody {
        margin-top: 20px;
    }

    th {
        font-size: 20px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    th,
    tr,
    td {
        padding: 10px 0px !important;
        text-align: left !important;
    }
</style>
<div class="section pt-50  pb-70 pb-lg-50 pb-md-40 pb-sm-30 pb-xs-20">
    <div class="container">
        <h1 style="font-size:30px !important"><?= TITULO ?></h1>
        <div class="row">
            <div class="col-md-12 col-sm-12" style="width:100%;display:block; ">
                <div class="customer-login-register register-pt-0">
                    <div class="form-register-title" id="print" style="width:100%;display:block; ">
                        <h4 style="margin-top:10px;font-weight: bold;"><?= $_SESSION["lang-txt"]["checkout"]["detail"]["pedido"] ?> N°: <?= $cod ?></h4>
                        <div class="row">
                            <div class="col-md-12">
                                <hr style="border: 0;border-top: 1px solid #666;">
                                <div style="padding-top:20px"><b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["metodo_pago"] ?>:</b> <?= mb_strtoupper($pedido_info['data']["pago"]); ?> </div>
                                <?php
                                if (!empty($pedido_info['data']['detalle'])) {
                                    $detalle = json_decode($pedido_info['data']['detalle'], true);
                                    if (!empty($detalle['leyenda'])) {
                                        echo "<b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["descripcion_pago"] . ": </b>" . $detalle['leyenda'] . "<br/>";
                                    }
                                    if (!empty($detalle['descuento'])) {
                                        echo "<b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["cod_descuento"] . ": </b>" . $detalle['descuento'];
                                    }
                                    if (!empty($detalle['link'])) {
                                        echo "<b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["url_pago"] . ": </b><a href='" . $detalle['link'] . "' target='_blank'>" . $_SESSION["lang-txt"]["checkout"]["detail"]["click_aqui"] . "</a>";
                                    }
                                }
                                ?>
                                <hr style="border: 0;border-top: 1px solid #666;">
                                <div class="row mb-15" style="margin-top:30px">
                                    <div class="col-md-6" style="width:48%;float:left">
                                        <hr>
                                        <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["informacion_envio"] ?></b>
                                        <br>
                                        <?= $envioData = $pedidos->getInfoPedido($detalle, 'envio'); ?>
                                        <p class='mb-0 fs-13'><b><?= $_SESSION["lang-txt"]["checkout"]["similar"] ?>: </b><?= $detalle['envio']['similar'] ? "Si" : "No" ?></p>
                                    </div>
                                    <div class="col-md-6" style="width:51%;float:right;">
                                        <hr>
                                        <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["informacion_facturacion"] ?></b>
                                        <br>
                                        <?= $pagoData = $pedidos->getInfoPedido($detalle, 'pago'); ?>
                                        <?php
                                        if ($detalle['pago']['factura']) {
                                            echo "<p class='mb-0 fs-13'><b>" . $_SESSION["lang-txt"]["checkout"]["detail"]["factura_cuit"] . ": </b>" . $detalle['pago']['dni'] . "</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <p style="page-break-after: always;"></p>
                            <div class="col-md-12" style="width:100%;margin-top:30px">
                                <b><?= $_SESSION["lang-txt"]["checkout"]["detail"]["tu_compra"] ?></b>
                                <hr style="border: 0;border-top: 1px solid #666;">
                                <table class="table table-striped " style="width:100%">
                                    <thead class="thead-dark " width="100%" style="width:100%;margin-bottom:20px;">
                                        <th class="text-left" width="50%"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["nombre"] ?></th>
                                        <th class="text-left " width="15%"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["cantidad"] ?></th>
                                        <th class="text-left" width="15%"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["precio"] ?></th>
                                        <th class="text-left" width="15%"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total"] ?></th>
                                    </thead>

                                    <tbody style="width:100%;margin-top:30px; padding-top:30px;" width="100%">
                                        <tr></tr>
                                        <?php
                                        $none = '';
                                        foreach ($pedido_info['detail'] as $key => $carroItem) {

                                            $precio =  isset($carroItem["precio"]) ? $carroItem["precio"] : '0';
                                            $producto_cod = ($carroItem["producto_cod"] != 'Descuento') ? $carroItem["producto_cod"] : $carroItem["cod_producto"];
                                            $carroItem['descuento'] = json_decode($carroItem['descuento'], true);
                                            $detalle = (isset($carroItem["descuento"]["products"])) ? '*' : '';
                                        ?>
                                            <tr style="width:100%">
                                                <td>
                                                    <b><?= mb_strtoupper($carroItem["producto"]) ?> <?= $detalle ?></b>
                                                    <br>
                                                    <span class="fs-12"><b><u>COD:</u></b> <?= $producto_cod ?></span>
                                                    <br>
                                                </td>
                                                <td class="hidden-xs hidden-sm">
                                                    <span class="amount <?= $none ?>"><?= $carroItem["cantidad"]; ?></span>
                                                </td>
                                                <td>
                                                    <span class="amount <?= $none ?>"><?= "$" . $precio ?></span>
                                                    <?php if (isset($carroItem["descuento"]["precio-antiguo"])) { ?>
                                                        <span class="<?= $none ?> descuento-precio">$<?= $carroItem["descuento"]["precio-antiguo"]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($carroItem["precio"] != 0) {
                                                        echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                            if (isset($carroItem["descuento"]["products"])) $discount = $carroItem;
                                        } ?>
                                        <tr>
                                            <td>
                                                <h4><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total_compra"] ?></h4>
                                            </td>
                                            <td class="hidden-xs hidden-sm">
                                            </td>
                                            <td></td>
                                            <td>
                                                <h4>$<?= number_format($pedido_info["data"]["total"], "2", ",", "."); ?></h4>
                                            </td>
                                        </tr>
                                        <?php if (isset($discount)) {
                                        ?>
                                            <thead class="thead-dark">
                                                <th class="text-left"> * <?= strtoupper($discount['producto']) ?></th>
                                                <th>Descuento</th>
                                                <th>Desc. u.</th>
                                                <th>Desc. Total</th>
                                            </thead>
                                            <?php
                                            foreach ($discount['descuento']['products'] as $detalle) { ?>
                                                <tr>
                                                    <td>
                                                        <?= $detalle['titulo'] ?>
                                                    </td>
                                                    <td>
                                                        $<?= $detalle['monto'] ?>
                                                    </td>
                                                    <?php if (isset($detalle['descuentoUnidad'])) { ?>
                                                        <td>
                                                            $<?= $detalle['descuentoUnidad'] ?>
                                                        </td>
                                                        <td>
                                                            $<?= $detalle['descuentoTotal'] ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                        <?php  }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

file_put_contents(dirname(__DIR__, 2) . "/export/pedidos/" . $cod . ".html", ob_get_contents());
$dataHTML = file_get_contents(dirname(__DIR__, 2)  . "/export/pedidos/" . $cod . ".html", false, stream_context_create($arrContextOptions));

$dompdf->loadHtml($dataHTML);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$data = $dompdf->output();
file_put_contents(dirname(__DIR__, 2) . "/export/pedidos/" . $cod . ".pdf", $data);
$f->headerMove(URL . "/export/pedidos/" . $cod . ".pdf");
