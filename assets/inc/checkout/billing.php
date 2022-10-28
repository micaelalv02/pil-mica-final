<?php

if (isset($_SESSION['stages'])) {
    if ($_SESSION['stages']['status'] == 'OPEN' && !empty($_SESSION['stages']['stage-1']) && !empty($_SESSION['stages']['user_cod'])) {
?>
        <div class="row">
            <div class="col-md-12 pt-10">
                <hr>
                <p class="text-uppercase bold fs-20 text-center"><?= $_SESSION["lang-txt"]["checkout"]["billing"]["informacion_facturacion"] ?></p>
                <hr>
            </div>
            <div class="col-md-12 mt-20">
                <form id="billing-f" method="post" data-cod="<?= $_SESSION['last_cod_pedido'] ?>" data-url="<?= URL ?>" onsubmit="addBilling()">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["nombre"] ?>:</label>
                            <input class="form-control mb-10" type="text" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['nombre'] : $_SESSION['stages']['stage-1']['data']['nombre']; ?>" name="nombre" data-validation="required" required />
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["apellido"] ?>:</label>
                            <input class="form-control  mb-10" type="text" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['apellido'] : $_SESSION['stages']['stage-1']['data']['apellido']; ?>" name="apellido" data-validation="required" required />
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["email"] ?>:</label>
                            <input class="form-control  mb-10" type="email" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['email'] : $_SESSION['stages']['stage-1']['data']['email']; ?>" name="email" data-validation="required" required />
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["dni"] ?>:</label>
                            <input class="form-control mb-10" type="text" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['dni'] : '' ?>" name="dni" data-validation="required" required />
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["telefono"] ?>:</label>
                            <input class="form-control mb-10" type="text" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['telefono'] : $_SESSION['stages']['stage-1']['data']['telefono']; ?>" name="telefono" data-validation="required" required />
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["celular"] ?>:</label>
                            <input class="form-control mb-10" type="text" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['celular'] : $_SESSION['stages']['stage-1']['data']['celular']; ?>" name="celular" data-validation="required" required />
                        </div>
                        <div class="col-md-4 col-xs-12 form-row-wide">

                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["provincia"] ?></label>
                            <!-- Dropdown -->
                            <select id='provincia' data-url="<?= URL ?>" class="form-control" name="provincia" data-validation="required" required>
                                <?php
                                if (!empty($_SESSION['stages']['stage-2']['data']['provincia'])) {
                                ?>
                                    <option value="<?= $_SESSION['stages']['stage-2']['data']['provincia'] ?>">
                                        <?= $_SESSION['stages']['stage-2']['data']['provincia'] ?>
                                    </option>
                                    <?php
                                } else {
                                    if (!empty($_SESSION['stages']['stage-1']['data']['provincia'])) {
                                    ?>
                                        <option value="<?= $_SESSION['stages']['stage-1']['data']['provincia'] ?>">
                                            <?= $_SESSION['stages']['stage-1']['data']['provincia'] ?>
                                        </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="" selected><?= $_SESSION["lang-txt"]["checkout"]["billing"]["seleccionar_provincia"] ?></option>
                                <?php
                                    }
                                }
                                $f->provincias();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12 form-row-wide">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["localidad"] ?></label>
                            <!-- Dropdown -->
                            <select id='localidad' class="form-control" name="localidad" data-validation="required" required>
                                <?php
                                if (!empty($_SESSION['stages']['stage-2']['data']['localidad'])) {
                                ?>
                                    <option value="<?= $_SESSION['stages']['stage-2']['data']['localidad'] ?>" selected>
                                        <?= $_SESSION['stages']['stage-2']['data']['localidad'] ?>
                                    </option>
                                    <?php
                                } else {
                                    if (!empty($_SESSION['stages']['stage-1']['data']['localidad'])) {
                                    ?>
                                        <option value="<?= $_SESSION['stages']['stage-1']['data']['localidad'] ?>" selected>
                                            <?= $_SESSION['stages']['stage-1']['data']['localidad'] ?>
                                        </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="" selected><?= $_SESSION["lang-txt"]["checkout"]["billing"]["seleccionar_localidad"] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label><?= $_SESSION["lang-txt"]["checkout"]["billing"]["direccion"] ?>:</label>
                            <input class="form-control mb-10" type="text" value="<?= (!empty($_SESSION['stages']['stage-2'])) ? $_SESSION['stages']['stage-2']['data']['calle'] : $_SESSION['stages']['stage-1']['data']['calle']; ?>" name="direccion" data-validation="required" required />
                        </div>
                        <label class="col-md-12 col-xs-12 mt-10 mb-10 fs-15 text-uppercase">
                            <hr />
                            <input type="checkbox" name="factura" value="1" <?php
                                                                            if (!empty($_SESSION['stages']['stage-2'])) {
                                                                                if (!empty($_SESSION['stages']['stage-2']['data']['factura'])) {
                                                                                    echo "checked";
                                                                                }
                                                                            }
                                                                            ?>>
                            <?= $_SESSION["lang-txt"]["checkout"]["billing"]["factura_a"] ?>
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr />
                            <a href="<?= URL ?>/checkout/shipping" class="btn btn-default" style="line-height: 46px"><i class="fa fa-chevron-left"></i> <?= $_SESSION["lang-txt"]["checkout"]["billing"]["volver"] ?></a>
                            <button class="btn btn-next-checkout pull-right text-uppercase" type="submit" id="btn-billing-1"><?= $_SESSION["lang-txt"]["checkout"]["billing"]["siguiente"] ?> <i class="fa fa-chevron-circle-right"></i></button>
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
                $f->headerMove(URL . '/checkout/shipping');
            }
        }
    }
} else {
    $f->headerMove(URL . '/carrito');
}
?>