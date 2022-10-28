<table class="table table-striped ">
    <thead class="thead-dark ">
        <th class="text-left"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["nombre"] ?></th>
        <th class="text-left hidden-xs hidden-sm"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["cantidad"] ?></th>
        <th class="text-left"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["precio"] ?></th>
        <th class="text-left"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total"] ?></th>
    </thead>
    <?php
    foreach ($pedido_info['detail'] as $key => $carroItem) {
        $precio =  isset($carroItem["precio"]) ? $carroItem["precio"] : '0';
        $producto_cod = ($carroItem["producto_cod"] != 'Descuento') ? $carroItem["producto_cod"] : $carroItem["cod_producto"];
        $carroItem['descuento'] = json_decode($carroItem['descuento'], true);
        $detalle = (isset($carroItem["descuento"]["products"])) ? '*' : '';
    ?>
        <tr>
            <td>
                <b><?= mb_strtoupper($carroItem["producto"]) ?> <?= $detalle ?></b>
                <br>
                <span class="fs-12"><b><u>COD:</u></b> <?= $producto_cod ?></span>
                <br>
                <span class="amount d-md-none <?= $none ?>"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["cantidad"] ?>: <?= $carroItem["cantidad"]; ?></span>
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
                    if ($carroItem["promo"] != '') {
                        echo "$" . ($carroItem["precio"] * $carroItem["promo"]);
                    } else {
                        echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                    }
                }
                ?>
            </td>
        </tr>
    <?php
        if (isset($carroItem["descuento"]["products"])) $discount = $carroItem;
    } ?>

    <tr>
        <td>
            <h6 class="bold"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total_compra"] ?></h6>
        </td>
        <td class="hidden-xs hidden-sm">
        </td>
        <td></td>
        <td>
            <h6 class="bold">$<?= number_format($pedido_info['data']['total'], "2", ",", "."); ?></h6>
        </td>
    </tr>
    <?php
    if (!empty($pedido_info['data']['entrega']) && $pedido_info['data']['entrega'] < $pedido_info['data']['total']) { ?>
        <tr>
            <td>
                <h6 class="bold"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["compra_parcial"] ?></h6>
            </td>
            <td class="hidden-xs hidden-sm">
            </td>
            <td></td>
            <td>
                <h6 class="bold">$<?= number_format($pedido_info['data']['entrega'], "2", ",", "."); ?></h6>
            </td>
        </tr>
    <?php } ?>
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
</table>