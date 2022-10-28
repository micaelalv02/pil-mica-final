<?php
$f = new Clases\PublicFunction;
$envios = new Clases\Envios();
$carrito = new Clases\Carrito();
$imagenes = new Clases\Imagenes();

$selected = false;
if (isset($_SESSION['stages'])) {
    if ($_SESSION['stages']['status'] == 'OPEN') {
?>
        <div class="row">
            <div class="col-md-12 pt-10">
                <hr>
                <p class="text-uppercase bold fs-20 text-center"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["como_enviar"] ?></p>
                <hr>
            </div>
            <div class="col-md-12 col-sm-12">
                <form id="shipping-f" method="post" data-url="<?= URL ?>" onsubmit="addShipping()">
                    <?php
                    $tipoUsuario = ($_SESSION['stages']['type'] == 'GUEST' || isset($_SESSION['usuarios']['minorista']) && $_SESSION['usuarios']['minorista'] == 1) ? 1 : 2; // 1 Minorista - 2 Mayorista
                    $pesoFinal = $carrito->finalWeight();
                    $tope = $f->roundUpToAny($pesoFinal, 5);
                    $metodos_de_envios = $envios->list(["((peso BETWEEN " . $pesoFinal . " AND " . $tope . ") OR peso=0)", "estado = 1", "(tipo_usuario = 0 || tipo_usuario = $tipoUsuario)"], '', '', $_SESSION['lang']);
                    $precioFinal = $carrito->totalPrice();
                    ?>
                    <div id="formEnvio" class=" mt-10 background-shipping ">
                        <div class="row pt-40">
                            <?php
                            if (!empty($_SESSION['stages']['stage-1'])) {
                                $selected = $_SESSION['stages']['stage-1']["cod"];
                            } else {
                                $selected = false;
                            }
                            $optionDisabled = false; ?>
                            <?php foreach ($metodos_de_envios as $metodos_de_envio_) {
                                $img = $imagenes->view($metodos_de_envio_["data"]["cod"]);
                                if (!$img) $img["ruta"] = "assets/archivos/sin_imagen.jpg";
                                $metodos_de_envio_precio = '';
                                if ($metodos_de_envio_['data']['limite'] != "0") {
                                    if ($precioFinal >= $metodos_de_envio_['data']['limite']) {
                                        $metodos_de_envio_precio =  $_SESSION["lang-txt"]["checkout"]["shipping"]["envio_gratis"];
                                        $metodos_de_envio_['data']["precio"] = 0;
                                    }
                                }
                                if (!empty($metodos_de_envio_['data']["precio"])) {
                                    $metodos_de_envio_precio = "-> $" . $metodos_de_envio_['data']["precio"];
                                }
                            ?>
                                <div class='col text-center envioDesktop'>
                                    <label style="text-align: center;" onclick="checkedBox('<?= $metodos_de_envio_['data']['cod'] ?>');hideByOption('<?= $metodos_de_envio_['data']['opciones'] ?>')" for='envio-<?= $metodos_de_envio_['data']["cod"] ?>'>
                                        <div class='box-img'>
                                            <img class="round-img-style" src='<?= URL . "/" . $img["ruta"]  ?>' alt='<?= $metodos_de_envio_['data']['titulo'] ?>'>
                                            <input type='radio' class="d-none" id='envio-<?= $metodos_de_envio_['data']["cod"] ?>' name='envio' <?= ((count($metodos_de_envios) < 2) ? "checked='checked'" : '') ?> value='<?= $metodos_de_envio_['data']["cod"] ?>'>
                                        </div>
                                        <span class="text-uppercase bold boxChecked fs-14 <?= ($selected == $metodos_de_envio_['data']['cod']) ? "selected" : '' ?>" id="<?= $metodos_de_envio_['data']['cod'] ?>"><?= $metodos_de_envio_['data']['titulo'] . " " . $metodos_de_envio_precio; ?></span>
                                        <p class="fs-12" style="min-height: 72px"><?= $metodos_de_envio_["data"]["descripcion"] ?></p>
                                        <p class="btn btn-primary btn-seleccionar"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["seleccionar"] ?></p>
                                    </label>
                                </div>
                            <?php
                                $selectedData = false;
                                if ($selected == false) $optionDisabled = true;
                                if ($selected == $metodos_de_envio_['data']['cod']) $selectedData = true;
                                $option[] = ["opciones" => $metodos_de_envio_["data"]["opciones"], "selected" => $selectedData, "value" => $metodos_de_envio_['data']['cod'], "title" => $metodos_de_envio_['data']['titulo'] . ' ' . $metodos_de_envio_precio];
                            } ?>
                            <div class="container">
                                <select name="envio" class="form-control text-uppercase envioMobile" onchange="hideByOption($(this).find(':selected').attr('data-opciones'))">
                                    <?php
                                    if ($selected == false) {
                                        echo  '<option selected disabled>' . $_SESSION["lang-txt"]["checkout"]["shipping"]["elegir_metodo"] . '</option>';
                                    }
                                    foreach ($option as $option_) {
                                        echo  '<option data-opciones="' . $option_['opciones'] . ' "' . $option_["selected"] . ' value="' . $option_["value"] . '">' . $option_["title"] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h2 class="fs-20 bold text-center">
                        <?= $_SESSION["lang-txt"]["checkout"]["shipping"]["informacion_envio"] ?>
                        <hr />
                    </h2>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["nombre"] ?>:</label>
                            <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['nombre']) ? $_SESSION["stages"]['stage-1']['data']['nombre'] : (isset($_SESSION['usuarios']['nombre']) ? $_SESSION['usuarios']['nombre'] : '')  ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_nombre"] ?>" name="nombre" data-validation="required" required />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["apellido"] ?>:</label>
                            <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['apellido']) ? $_SESSION["stages"]['stage-1']['data']['apellido'] : (isset($_SESSION['usuarios']['apellido']) ? $_SESSION['usuarios']['apellido'] : '')  ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_apellido"] ?>" name="apellido" data-validation="required" required />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["email"] ?>:</label>
                            <input class="form-control  mb-10" type="email" value="<?= isset($_SESSION["stages"]['stage-1']['data']['email']) ? $_SESSION["stages"]['stage-1']['data']['email'] : (isset($_SESSION['usuarios']['email']) ? $_SESSION['usuarios']['email'] : '')  ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_email"] ?>" name="email" data-validation="required" required />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["postal"] ?>:</label>
                            <input class="form-control  mb-10" type="postal" value="<?= isset($_SESSION["stages"]['stage-1']['data']['postal']) ? $_SESSION["stages"]['stage-1']['data']['postal'] : (isset($_SESSION['usuarios']['postal']) ? $_SESSION['usuarios']['postal'] : '')  ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_postal"] ?>" name="postal" data-validation="required" required />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["telefono"] ?>:</label>
                            <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['telefono']) ? $_SESSION["stages"]['stage-1']['data']['telefono'] : (isset($_SESSION['usuarios']['telefono']) ? $_SESSION['usuarios']['telefono'] : '')  ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_telefono"] ?>" name="telefono" data-validation="required" required />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["celular"] ?>:</label>
                            <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['celular']) ? $_SESSION["stages"]['stage-1']['data']['celular'] : (isset($_SESSION['usuarios']['celular']) ? $_SESSION['usuarios']['celular'] : '')  ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_celular"] ?>" name="celular" data-validation="required" required />
                        </div>
                        <div class="col-md-4">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["provincia"] ?></label>
                            <!-- Dropdown -->
                            <select id='provincia' data-url="<?= URL ?>" class="form-control" name="provincia" data-validation="required" required>
                                <option value="" selected>
                                    <?= $_SESSION["lang-txt"]["checkout"]["shipping"]["seleccionar_provincia"] ?></option>
                                <?php
                                if (!empty($_SESSION["stages"]['stage-1']['data']['provincia'])) {
                                ?>
                                    <option value="<?= $_SESSION["stages"]['stage-1']['data']['provincia'] ?>" selected>
                                        <?= $_SESSION["stages"]['stage-1']['data']['provincia'] ?>
                                    </option>
                                <?php }
                                $f->provincias(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 ">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["seleccionar_localidad"] ?></label>
                            <select id='localidad' class="form-control" name="localidad" data-validation="required" required>
                                <?php if (!empty($_SESSION["stages"]['stage-1']['data']['localidad'])) { ?>
                                    <option value="<?= $_SESSION["stages"]['stage-1']['data']['localidad'] ?>"> <?= $_SESSION["stages"]['stage-1']['data']['localidad'] ?></option>
                                <?php } else { ?>
                                    <option value="" selected> <?= $_SESSION["lang-txt"]["checkout"]["shipping"]["seleccionar_localidad"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-10"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["direccion"] ?>:</label>
                            <input class="form-control  mb-10" type="text" value="<?= isset($_SESSION["stages"]['stage-1']['data']['calle']) ? $_SESSION["stages"]['stage-1']['data']['calle'] : '' ?>" placeholder="<?= $_SESSION["lang-txt"]["checkout"]["shipping"]["placeholder_direccion"] ?>" name="direccion" data-validation="required" required />
                        </div>
                        <div class="col d-none" id="dataMaster">
                            <label class="mt-10 text-uppercase fs-12"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["hora_entrega"] ?></label>
                            <br>
                            <div class="row">
                                <!-- RANGO FECHA -->
                                <div class="col-md-6 data d-none" id="rango-fecha">
                                    <input type="text" name="rango_fecha" class="form-control dateSelectRangeShipping" placeholder="Elegir Fecha">
                                </div>
                                <!-- FECHA ESPECIFICA -->
                                <div class="col-md-6 data d-none" id="fecha">
                                    <input type="text" name="fecha" class="form-control dataSelectShipping" placeholder="Elegir Fecha">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12 col-xs-12 mt-10 mb-10 fs-15 text-uppercase">
                            <hr />
                            <input type="checkbox" name="similar" value="1" <?php
                                                                            if (!empty($_SESSION['stages']['stage-1'])) {
                                                                                if (!empty($_SESSION['stages']['stage-1']['data']['similar'])) {
                                                                                    echo "checked";
                                                                                }
                                                                            }
                                                                            ?>>
                            <?= $_SESSION["lang-txt"]["checkout"]["shipping"]["cambiar_similar"] ?>
                            <i class="d-block fs-12 normal" style="font-weight: normal;text-transform:initial">*
                                <?= $_SESSION["lang-txt"]["checkout"]["shipping"]["rta_cambiar_similar"] ?></i>
                        </label>
                        <div class="col-md-12">
                            <hr />
                            <a href="<?= URL ?>/carrito" class="btn btn-default" style="line-height: 46px"><i class="fa fa-chevron-left"></i> <?= $_SESSION["lang-txt"]["checkout"]["shipping"]["volver"] ?></a>
                            <button class="btn btn-next-checkout pull-right text-uppercase" type="submit" id="btn-shipping-1"><?= $_SESSION["lang-txt"]["checkout"]["shipping"]["siguiente"] ?> <i class="fa fa-chevron-circle-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
    } else {
        $f->headerMove(URL . '/checkout/detail');
    }
} else {
    $f->headerMove(URL . '/carrito');
}
?>