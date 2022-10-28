<?php

use function PHPSTORM_META\type;

$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$idiomas = new Clases\Idiomas;

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli(strtolower($_GET["idioma"])) : '';

$enviosData = $envios->list('', '', '', $idiomaGet);
$pagosData = $pagos->list('', '', '', $idiomaGet);
$listData = $config->listCheckout($idiomaGet);
$facturaData = $config->viewTaxFactura();

if (isset($_POST["agregar-checkout"])) {
    $type = isset($_POST["ckt-tipo"]) ? $funciones->antihack_mysqli($_POST["ckt-tipo"]) : '';
    $config->set("id", isset($_POST["ckt-id"]) ? $funciones->antihack_mysqli($_POST["ckt-id"]) : '');
    $config->set("tipo", $type);
    isset($_POST["ckt-estado"]) ? $config->set("estado", 1) : $config->estado = 0;
    isset($_POST["ckt-mostrarPrecio"]) ? $config->set("mostrar_precio", 1) : $config->mostrar_precio = 0;
    $config->set("envio", isset($_POST["ckt-envio"]) ? $funciones->antihack_mysqli($_POST["ckt-envio"]) : '');
    $config->set("pago", isset($_POST["ckt-pago"]) ? $funciones->antihack_mysqli($_POST["ckt-pago"]) : '');
    $config->set("idioma", isset($idiomaGet) ? $idiomaGet :  $_SESSION["defaultLang"]);


    $error = $config->addCheckout($type, $idiomaGet);
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&idioma=' . $idiomaGet . '&tab=checkout-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["agregar-impuesto"])) {
    $config->set("codImpuesto", isset($_POST["codImpuesto"]) ? $funciones->antihack_mysqli($_POST["codImpuesto"]) : 'factura');
    $config->set("tipoImpuesto", isset($_POST["tipoImpuesto"]) ? $funciones->antihack_mysqli($_POST["tipoImpuesto"]) : '');
    $config->set("valor", isset($_POST["valor"]) ? $funciones->antihack_mysqli($_POST["valor"]) : '');
    $error = $config->addTaxFactura();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&idioma=' . $idiomaGet . '&tab=checkout-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div>
    <div class="row">
        <ul class="nav nav-tabs ml-20">
            <?php
            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                $url =  URL_ADMIN . "/index.php?op=configuracion&accion=modificarTec&idioma=" . $idioma_["data"]["cod"] . "&tab=checkout-tab";
            ?>
                <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
            <?php } ?>
        </ul>
        <form method="post" class="col-md-12" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&idioma=<?= $idiomaGet ?>&tab=checkout-tab">
            <div class="card">
                <div class="card-body">
                    <div class="row ">
                        <div class="col-md-12">
                            <h3 class="text-center text-uppercase fs-20">Agregar/Modificar el recago de la factura</h3>
                            <hr style="border-style: dashed;">
                        </div>
                        <input type="hidden" name="codImpuesto" value="factura">
                        <div class="col-md-4">
                            <div class="custom-control custom-switch">
                                <label class="d-block">
                                    Valor del recargo
                                    <input type="text" stlye="width:100%" name="valor" value="<?= (isset($facturaData['data']['valor'])) ? $facturaData['data']['valor'] : '' ?>">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="d-block">
                                Tipo de recargo
                                <select name="tipoImpuesto" stlye="width:100%">
                                    <option>-- Elige Tipo de Recargo --</option>
                                    <option <?= (isset($facturaData['data']['tipo']) && $facturaData['data']['tipo'] == "efectivo") ? 'selected' : '' ?> value="efectivo">Efectivo</option>
                                    <option <?= (isset($facturaData['data']['tipo']) && $facturaData['data']['tipo'] == "porcentaje") ? 'selected' : '' ?> value="porcentaje">Porcentaje</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-4 mt-15">
                            <button class="btn btn-primary btn-block" name="agregar-impuesto">Actualizar Datos</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        foreach ($listData as $data) { ?>
            <form method="post" class="col-md-12" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&idioma=<?= $idiomaGet ?>&tab=checkout-tab">
                <input type="hidden" name="ckt-id" value="<?= $data['data']['id'] ?>">
                <input type="hidden" name="ckt-tipo" value="<?= $data['data']['tipo'] ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-12">
                                <h3 class="text-center text-uppercase text-bold fs-20"><?= ucfirst($data['data']['tipo']) ?></h3>
                                <hr style="border-style: dashed;">
                            </div>
                            <div class="col-md-6">
                                <label class="d-block">
                                    Método de envio predefinido
                                    <select name="ckt-envio">
                                        <option>-- Elige Método de Envio --</option>
                                        <?php foreach ($enviosData as $envio) { ?>
                                            <option <?= ($envio['data']['cod'] == $data['data']['envio']) ? 'selected' : '' ?> value="<?= $envio['data']['cod'] ?>"><?= $envio['data']['titulo'] . " - $" .  $envio['data']['precio'] ?></option>
                                        <?php  } ?>
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="d-block">
                                    Método de pago predefinido
                                    <select name="ckt-pago">
                                        <option>-- Elige Método de Pago --</option>
                                        <?php foreach ($pagosData as $pago) { ?>
                                            <option <?= ($pago['data']['cod'] == $data['data']['pago']) ? 'selected' : '' ?> value="<?= $pago['data']['cod'] ?>"><?= $pago['data']['titulo']  ?></option>
                                        <?php  } ?>
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-12 mt-10">
                                <div class="custom-control custom-switch">
                                    <li class="d-inline-block">
                                        <fieldset>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="ckt-estado" id="colorCheckbox3-<?= $data['data']['id'] ?>" <?= ($data['data']['estado'] == 1) ? 'checked' : '' ?>>
                                                <label style="width:200px;" for="colorCheckbox3-<?= $data['data']['id'] ?>">Saltar proceso de checkout</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block">
                                        <fieldset>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="ckt-mostrarPrecio" id="mostrarPrecio-<?= $data['data']['id'] ?>" <?= ($data['data']['mostrar_precio'] == 1) ? 'checked' : '' ?>>
                                                <label for="mostrarPrecio-<?= $data['data']['id'] ?>">Ocultar precios en detalle de compra</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                </div>
                            </div>
                            <div class="col-md-12 mt-10">
                                <button class="btn btn-primary" name="agregar-checkout">Actualizar Datos</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php
        } ?>
    </div>
</div>