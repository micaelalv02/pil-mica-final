<?php
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();
$descuento = new Clases\Descuentos();
$checkout = new Clases\Checkout();

$usuarioData = $usuario->viewSession();
$refreshCartDescuento = $descuento->refreshCartDescuento($carrito->return(), $usuarioData);
$carro = $carrito->return();
$precio = 0;

?>
<table class="table table-striped ">
    <thead class="thead-dark ">
        <th class="text-left"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["nombre"] ?></th>
        <th class="text-left hidden-xs hidden-sm"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["cantidad"] ?></th>
        <th class="text-left"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["precio"] ?></th>
        <th class="text-left"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total"] ?></th>
    </thead>
    <?php
    foreach ($carro as $key => $carroItem) {
        $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
        $opciones = @implode(" - ", $carroItem["opciones"]);
        if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
            $clase = "text-bold";
            $none = "hidden";
        } else {
            $clase;
            $none = "";
        }
    ?>
        <tr>
            <td>
                <b><?= mb_strtoupper($carroItem["titulo"]); ?></b>
                <?php
                if ($carroItem['descuento']['status']) {
                    foreach ($carroItem['descuento']['products'] as $itemDescuento) {
                        echo '<br> - <span class="item-titulo-descuento">' . $itemDescuento['titulo'] . ' <b class="item-monto-descuento">' . $itemDescuento['monto'] . '</b></span>';
                    }
                }
                ?>
                <br>
                <?php
                if (is_array($carroItem['opciones'])) {
                    if (isset($carroItem['opciones']['texto'])) {
                        echo $carroItem['opciones']['texto'];
                    }
                }
                ?>
                <?php if (!$carroItem['descuento']['status']) { ?>
                    <span class="amount d-md-none <?= $none ?>"><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["cantidad"] ?>: <?= $carroItem["cantidad"]; ?></span>
                <?php } ?>
            </td>
            <td class="hidden-xs hidden-sm">
                <?php if (!$carroItem['descuento']['status']) { ?>
                    <span class="amount <?= $none ?>"><?= $carroItem["cantidad"]; ?></span>
                <?php } ?>
            </td>
            <td>
                <span class="amount <?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span>
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

    }
    ?>
    <tr>
        <td>
            <h4><?= $_SESSION["lang-txt"]["checkout"]["carrito"]["total_compra"] ?></h4>
        </td>
        <td class="hidden-xs hidden-sm">
        </td>
        <td></td>
        <td>
            <h4>$<?= number_format($carrito->totalPrice(), "2", ",", "."); ?></h4>
        </td>
    </tr>
</table>