<?php
//Clases
$pedidos = new Clases\Pedidos();

$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();

$filter = array("usuario = '" . $usuarioData['data']['cod'] . "'");
$pedidosData = $pedidos->list($filter, '', '');
?>
<?php
if (empty($pedidosData)) {
?>
    <div class="container centro">
        <h4><?= $_SESSION["lang-txt"]["sesion"]["sin_pedidos"] ?></h4>
    </div>
<?php
} else {
?>
    <div class="col-md-12 mb-10" style="margin-top:10px;">
        <?php foreach ($pedidosData as $key => $value) { ?>
            <?php $fecha = explode(" ", $value['data']["fecha"]); ?>
            <?php $fecha1 = explode("-", $fecha[0]); ?>
            <?php $fecha1 = $fecha1[2] . '/' . $fecha1[1] . '/' . $fecha1[0] . ' '; ?>
            <?php $fecha = $fecha1 . $fecha[1]; ?>
            <div class="panel panel-default mt-10" style="background: lightgray">
                <a data-toggle="collapse" href="#collapse<?= $value['data']["cod"] ?>" aria-expanded="false" aria-controls="collapse<?= $value['data']["cod"] ?>" class="collapsed color_a" style="width: 100%">
                    <div class="panel-heading boton-cuenta bold" role="tab" id="heading" style="padding: 10px;background: #8c8c8c;color: #fff;">
                        <div class="row pedido-centro text-uppercase">
                            <div class="col-md-9 dis ">
                                <span class="negro"><?= $_SESSION["lang-txt"]["pedidos"]["pedido"] ?> <?= $value['data']["cod"] ?></span>
                                <span class="hidden-xs hidden-sm negro">- <?= $_SESSION["lang-txt"]["pedidos"]["fecha"] ?> <?= $fecha ?></span>
                            </div>
                            <div class="col-md-3 dis pedido-right">
                                <div class='label label-default '><?= $_SESSION["lang-txt"]["pedidos"]["estado"] ?>: <?= $value["estados"]["data"]["titulo"] ?></div>
                            </div>
                        </div>
                    </div>
                </a>
                <div id="collapse<?= $value['data']["cod"] ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body panel-over" style="height: auto;background:#fff">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                Producto
                                            </th>
                                            <th class="hidden-xs hidden-sm">
                                                Cantidad
                                            </th>

                                            <th class="hidden-xs hidden-sm">
                                                <price class="hidden">
                                                    Precio
                                                </price>
                                            </th>
                                            <th>
                                                <price class="hidden">
                                                    Precio Final
                                                </price>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($value['detail'] as $value2) { ?>
                                            <?php if ($value2['cod'] == $value['data']["cod"]) { ?>
                                                <tr>
                                                    <td><?= $value2["producto"] ?>
                                                        <p class="visible-xs">
                                                            <?php
                                                            if (isset($value2['opciones']) && is_array($value2['opciones'])) {
                                                                if (isset($value2['opciones']['texto'])) {
                                                                    echo $value2['opciones']['texto'];
                                                                }
                                                            }
                                                            ?>
                                                        </p>
                                                        <p class="visible-xs">Cantidad: <?= $value2["cantidad"] ?></p>
                                                        <price class="hidden">
                                                            <p class="visible-xs">Precio: $<?= $value2["precio"] ?></p>
                                                        </price>
                                                    </td>
                                                    <td class="hidden-xs hidden-sm"><?= $value2["cantidad"] ?></td>
                                                    <td class="hidden-xs hidden-sm">
                                                        <price class="hidden">$<?= $value2["precio"] ?></price>
                                                    </td>
                                                    <td>
                                                        <?php if ($value2["promo"] != '') {
                                                            $precio = ($value2["precio"] * $value2["promo"]);
                                                        } else {
                                                            $precio = ($value2["precio"] * $value2["cantidad"]);
                                                        } ?>
                                                        <price class="hidden">$<?= $precio ?> </price>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        <tr>
                                            <td>
                                                <price class="hidden"><b><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total_compra"] ?></b></price>
                                            </td>
                                            <td class="hidden-xs hidden-sm"></td>
                                            <td class="hidden-xs hidden-sm"></td>
                                            <td>
                                                <price class="hidden"><b>$<?= $pedidosData[$key]["data"]["total"] ?></b></price>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <?php if (!empty($pedidosData[$key]['data']['entrega']) && $pedidosData[$key]['data']['entrega'] < $pedidosData[$key]['data']['total']) { ?>
                                            <tr>
                                                <td class=""></td>
                                                <td>
                                                    <price class="hidden"><b><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["compra_parcial"] ?></b></price>
                                                </td>
                                                <td class="hidden-xs hidden-sm"></td>
                                                <td class="hidden-xs hidden-sm"></td>

                                                <td>
                                                    <price class="hidden"><b>$<?= $pedidosData[$key]["data"]["entrega"] ?></b></price>
                                                </td>
                                                
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <?php if ($value["data"]["observacion"]) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b class="mb-10">OBSERVACIONES DEL PEDIDO:
                                            <p style="font-weight:400"><?= $value["data"]["observacion"] ?></p>
                                            <hr />
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <b class="mb-10">FORMA DE PAGO: </b> <?= $value['data']["pago"] ?>
                                </div>
                                <div class="col-md-12">
                                    <?php
                                    if (!empty($value['data']['detalle'])) {
                                        $detalle = json_decode($value['data']['detalle'], true);
                                        if (!empty($detalle['leyenda'])) {
                                            echo "<b>DESCRIPCIÓN DEL PAGO: </b>" . $detalle['leyenda'] . "<br/>";
                                        }
                                        if (!empty($detalle['descuento'])) {
                                            echo "<b>SE UTILIZÓ EL CÓDIGO DE DESCUENTO: </b>" . $detalle['descuento'];
                                        }
                                        if (!empty($detalle['link'])) {
                                            echo "<b>URL PARA PAGAR: </b><a href='" . $detalle['link'] . "' target='_blank'>CLICK AQUÍ</a>";
                                        }
                                    ?>
                                        <hr />
                                        <div class="row mb-15">
                                            <div class="col-md-6"><b>INFORMACIÓN DE ENVIO</b>
                                                <?= $pedidos->getInfoPedido($detalle, 'envio'); ?>
                                                <?php if ($detalle['envio']['similar']) echo "<span class='mb-0 fs-13'><b>Producto similar por faltante: </b> Si</p>"; ?>
                                            </div>

                                            <div class="col-md-6"><b>INFORMACIÓN DE FACTURACIÓN</b>
                                                <?= $pedidos->getInfoPedido($detalle, 'pago'); ?>
                                                <?php if ($detalle['pago']['factura']) echo "<p class='mb-0 fs-13'><b>Factura A al CUIT: </b>" . $detalle['pago']['dni'] . "</p>"; ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <a class="btn btn-primary d-block mt-10 mb-10" target="_blank" href="<?= URL ?>/pedido/<?= $value['data']["cod"] ?>">Duplicar Pedido</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php
}
?>
<script>
    function addToCartPrev(form) {
        console.log(form);
        $.ajax({
            url: "<?= URL ?>/curl/cart/add.php",
            type: "POST",
            data: $('#' + form).serialize(),
            success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                if (data['status'] == true) {
                    window.location.assign("<?= URL ?>/carrito");
                } else {
                    $('#error').html('Error');
                }
            },
            error: function() {
                $('#productModalError' + cod).html('');
                $('#productModalError' + cod).append('<div class="col-md-12"><div class="alert alert-warning">Ocurrió un error, recargar la página.</div></div>');
            }
        });
    }
</script>