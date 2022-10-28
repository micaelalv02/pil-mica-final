<?php
$descuento = new Clases\Descuentos();
$pedidos = new Clases\Pedidos();

$date = '';
$totalProduct = 0;
$from = isset($_GET["from"]) ? $funciones->antihack_mysqli($_GET["from"]) : '';
$to = isset($_GET["to"]) ? $funciones->antihack_mysqli($_GET["to"]) : '';
if (!empty($from)) {
    $to_ = !empty($to) ? "'" . $to . "'" : "NOW()";
    $date = " AND `pedidos`.`fecha` BETWEEN '" . $from . "' AND " . $to_;
}
echo "<pre>";
$discountedUsedProducts = $pedidos->getProductsFromOrder($date);
echo "</pre>";
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-md-5">
                <h4>
                    Descuentos
                </h4>
            </div>
            <form method="get" id="orderForm" name="orderForm" action="<?= CANONICAL ?>">
                <input type="hidden" name="op" value="descuentos" />
                <input type="hidden" name="accion" value="productos-descuento" />
            </form>
            <div class="col-md-2 pull-right">
                <input type="date" name="from" form="orderForm" value="<?= !empty($from) ? $from : '' ?>" required>
            </div>
            <div class="col-md-2 pull-right">
                <input type="date" name="to" form="orderForm" value="<?= !empty($to) ? $to : '' ?>">
            </div>
            <div class="col-md-3 pull-right">
                <button type="submit" form="orderForm" class="btn btn-info mr-10">BUSCAR</button>
                <button onclick="exportDiscount('<?= !empty($from) ? $from : '' ?>','<?= !empty($to) ? $to : '' ?>')" class="btn btn-warning">EXPORTAR</button>
            </div>
        </div>
        <hr />
        <?php
        if (!empty($discountedUsedProducts)) {
            foreach ($discountedUsedProducts as $key => $value) {
                $totalProduct = 0;
                if (!empty($key)) {
        ?>
                    <p>
                        <a class="btn btn-primary btn-block" data-toggle="collapse" href="#collapseExample<?= $key ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?= $key ?>">
                            <span class="pull-left"> <b>CÓDIGO DE DESCUENTO: </b> <?= $key ?></span>
                            <span class="pull-right"><b>CANTIDAD DE PEDIDOS: </b> <?= $value["cant_pedidos"] ?></span>
                            <span><b>PRODUCTOS: </b> <?= $value["cant_productos"] ?></span>
                        </a>
                    </p>
                    <div class="collapse" id="collapseExample<?= $key ?>">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-10">PRODUCTOS</div>
                                <div class="col-md-2">TOTAL</div>
                                <div class="col-md-12" style="border-top: 1px solid;margin:5px"></div>
                                <?php foreach ($value["productos"] as $product) { ?>
                                    <div class="col-md-10">
                                        <a target="_blank" href="<?= URL_ADMIN ?>/index.php?op=productos&accion=ver&pagina=0&busqueda=<?= $product["titulo"] ?>" style="color:#666">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            <?= $product["titulo"] ?>
                                        </a>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $product["cantidad"] ?>
                                        <?php $totalProduct += (int)$product["cantidad"] ?>
                                    </div>
                                    <div class="col-md-12" style="border-top: 1px solid;margin:5px"></div>
                                <?php } ?>
                                <div class="col-md-10 pull-left"></div>
                                <div class="col-md-2 pull-right"><?= $totalProduct ?></div>

                                <hr>
                                <div class="col-md-12">USUARIOS</div>
                                <div class="col-md-12" style="border-top: 1px solid;margin:5px"></div>
                                <?php
                                $i = 0;
                                foreach ($value["usuario"] as $user) {
                                    if ($i == 2) $i = 0;
                                ?>
                                    <div class="col-md-6">
                                        <a target="_blank" href="<?= URL_ADMIN ?>/index.php?op=usuarios&accion=ver&user=<?= $user["email"] ?>" style="color:#666">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            <?= $user["email"] ?>
                                        </a>
                                    </div>
                                    <?php if ($i == 1) { ?>
                                        <div class="col-md-12" style="border-top: 1px solid;margin:5px"></div>
                                    <?php } ?>
                                <?php $i++;
                                } ?>
                                <hr>
                                <div class="col-md-12"></div>
                                <div class="col-md-6 mt-50">PEDIDOS</div>
                                <div class="col-md-3 mt-50">PRECIO</div>
                                <div class="col-md-3 mt-50">FECHA</div>
                                <div class="col-md-12" style="border-top: 1px solid;margin:5px"></div>
                                <?php foreach ($value["pedido"]["fecha"] as $key_ =>  $pedido) { ?>
                                    <div class="col-md-6">
                                        <a target="_blank" href="<?= URL_ADMIN ?>/index.php?op=pedidos&accion=ver&collapse=collapse<?= $value["pedidos_cod"][$key_] ?>" style="color:#666">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            <?= $value["pedidos_cod"][$key_] ?>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        $<?= $value["pedido"]["precio"][$key_] ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $pedido ?>
                                    </div>
                                    <div class="col-md-12" style="border-top: 1px solid;margin:5px"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
        <?php }
            }
        } ?>
    </div>
</div>
<script>
    function exportDiscount(from, to) {
        $.ajax({
            url: '<?= URL_ADMIN ?>' + "/api/descuentos/exportar.php",
            type: "POST",
            data: {
                from: from,
                to: to
            },
            success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                window.location.href = data;
            }
        });
    }
</script>