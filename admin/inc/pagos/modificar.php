<?php
$pagos = new Clases\Pagos();
$config = new Clases\Config();
$estadoPedido = new Clases\EstadosPedidos();
$imagenes = new Clases\Imagenes();
$idiomas = new Clases\Idiomas();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';

if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=pagos&accion=modificar&cod=$cod&idioma=$idiomaGet");
}
$estadoData = $estadoPedido->list(["idioma = '" . $idiomaGet . "'"], "", "");
$pagos->set("cod", $cod);
$pagos->set("idioma", $idiomaGet);
$pagos_ = $pagos->view();
$payments = $config->listPayment();
$imagen = $imagenes->view($pagos_["data"]["cod"]);

if (isset($_POST["modificar"])) {
    $pagos->set("cod", $pagos_['data']["cod"]);
    $pagos->set("titulo", isset($_POST["titulo"]) ?  $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $pagos->set("leyenda", isset($_POST["leyenda"]) ?  $funciones->antihack_mysqli($_POST["leyenda"]) : '');
    $pagos->set("estado", isset($_POST["estado"]) ?  $funciones->antihack_mysqli($_POST["estado"]) : '');
    $pagos->set("tipo", isset($_POST["tipo"]) ?  $funciones->antihack_mysqli($_POST["tipo"]) : '');
    $pagos->set("idioma", $idiomaGet);
    $pagos->set("cuotas", isset($_POST["cuotas"]) ? $funciones->antihack_mysqli(intval($_POST["cuotas"])) : 0);
    // $aumento = isset($_POST["aumento"]) ?  $funciones->antihack_mysqli($_POST["aumento"]) : '';
    $pagos->monto = isset($_POST["monto"]) ? $funciones->antihack_mysqli($_POST["monto"]) : 0;
    $pagos->set("defecto", isset($_POST["defecto"]) ?  $funciones->antihack_mysqli($_POST["defecto"]) : '');
    $pagos->minimo = !empty($_POST["minimo"]) ? $_POST["minimo"] : 0;
    $pagos->maximo = !empty($_POST["maximo"]) ? $_POST["maximo"] : 0;
    $pagos->entrega = !empty($_POST["entrega"]) ? $_POST["entrega"] : 0;
    $pagos->set("tipo_usuario", isset($_POST["tipo_usuario"]) ? $funciones->antihack_mysqli($_POST["tipo_usuario"]) : '');
    $pagos->acumular = isset($_POST["acumular"]) ? $funciones->antihack_mysqli($_POST["acumular"]) : 0;
    $pagos->desc_usuario = isset($_POST["desc_usuario"]) ? $funciones->antihack_mysqli($_POST["desc_usuario"]) : 0;
    $pagos->desc_cupon = isset($_POST["desc_cupon"]) ? $funciones->antihack_mysqli($_POST["desc_cupon"]) : 0;
    $pagos->edit();

    if (!empty($_FILES['files']['name'][0])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($_POST["titulo"])), [$idiomaGet]);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=pagos&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Pagos
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="mt-10 mb-10 col-md-6">Método de pago:<br />
                        <input type="text" name="titulo" value="<?= $pagos_['data']["titulo"] ? $pagos_['data']["titulo"] : '' ?>" required>
                    </label>
                    <label class="mt-10 mb-10 col-md-3">Monto de Compra Minimo:<br />
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" name="minimo" value="<?= $pagos_['data']["minimo"] ? $pagos_['data']["minimo"] : '' ?>">
                        </div>
                    </label>
                    <label class="mt-10 mb-10 col-md-3">Monto de Compra Maximo:<br />
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" name="maximo" value="<?= $pagos_['data']["maximo"] ? $pagos_['data']["maximo"] : '' ?>">
                        </div>
                    </label>
                    <label class="mt-10 mb-10 col-md-12">Descripción del método de pago:<br />
                        <textarea name="leyenda"><?= $pagos_['data']["leyenda"] ? $pagos_['data']["leyenda"] : '' ?></textarea>
                    </label>
                    <label class="mt-10 mb-10 col-md-3">
                        Estado
                        <select name="estado" class="form-control" required>
                            <option value="1" <?php if ($pagos_['data']['estado'] == 1) {
                                                    echo "selected";
                                                } ?>>
                                Activo
                            </option>
                            <option value="0" <?php if ($pagos_['data']['estado'] == 0) {
                                                    echo "selected";
                                                } ?>>
                                Desactivado
                            </option>
                        </select>
                    </label>
                    <label class="mt-10 mb-10 col-md-3">
                        Tipo de pago online:
                        <select name="tipo" class="form-control">
                            <option value="" <?php if ($pagos_['data']['tipo'] == '') {
                                                    echo "selected";
                                                } ?>>
                                --- Sin elegir ---
                            </option>
                            <?php
                            if (!empty($payments)) {
                                foreach ($payments as $payment) {
                            ?>
                                    <option value="<?= $payment['data']['id']; ?>" <?php if ($pagos_['data']['tipo'] == $payment['data']['id']) {
                                                                                        echo "selected";
                                                                                    } ?>>
                                        <?= $payment['data']['empresa']; ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </label>

                    <label class="mt-10 mb-10 col-md-3">
                        Defecto:
                        <select name="defecto" class="form-control" required>
                            <?php foreach ($estadoData as $estado) {
                                $select = $pagos_['data']['defecto'] == $estado['data']['id'] ? 'selected' : '';
                            ?>
                                <option value="<?= $estado['data']['id'] ?>" <?= $select ?>><?= mb_strtoupper($estado['data']['titulo']) ?></option>
                            <?php
                            } ?>
                        </select>
                    </label>
                    <label class="mt-10 mb-10 col-md-3">Tipo Usuario:<br />
                        <select name="tipo_usuario" required>
                            <option value="0" <?php if ($pagos_['data']['tipo_usuario'] == 0) {
                                                    echo "selected";
                                                } ?>>Ambos
                            </option>
                            <option value="1" <?php if ($pagos_['data']['tipo_usuario'] == 1) {
                                                    echo "selected";
                                                } ?>>Minorista
                            </option>
                            <option value="2" <?php if ($pagos_['data']['tipo_usuario'] == 2) {
                                                    echo "selected";
                                                } ?>>Mayorista
                            </option>
                        </select>
                    </label>
                    <label class="mt-10 mb-10 col-md-4">
                        Aumento o Descuento (%)<br />
                        <input data-suffix="%" value="<?= $pagos_['data']['monto'] ?>" min="-100" max="100" type="number" name="monto" onkeydown="return (event.keyCode!=13);" />
                    </label>
                    <label class="mt-10 mb-10 col-md-4">
                        Compra Parcial / Seña (%)<br />
                        <input data-suffix="%" value="<?= $pagos_['data']['entrega'] ?>" min="0" max="100" type="number" name="entrega" onkeydown="return (event.keyCode!=13);" />
                    </label>
                    <label class="col-md-4 mt-10">
                        Cuotas (solo aplica al método de pago decidir):
                        <select name="cuotas" class="form-control" required>
                            <option value="1" <?php if ($pagos_['data']['cuotas'] == 1) {
                                                    echo "selected";
                                                } ?>>No
                            </option>
                            <option value="3" <?php if ($pagos_['data']['cuotas'] == 3) {
                                                    echo "selected";
                                                } ?>>3 Cuotas
                            </option>
                            <option value="6" <?php if ($pagos_['data']['cuotas'] == 6) {
                                                    echo "selected";
                                                } ?>>6 Cuotas
                            </option>
                            <option value="12" <?php if ($pagos_['data']['cuotas'] == 12) {
                                                    echo "selected";
                                                } ?>>12 Cuotas
                            </option>
                            <option value="18" <?php if ($pagos_['data']['cuotas'] == 18) {
                                                    echo "selected";
                                                } ?>>18 Cuotas
                            </option>
                            <option value="24" <?php if ($pagos_['data']['cuotas'] == 24) {
                                                    echo "selected";
                                                } ?>>24 Cuotas
                            </option>
                            <option value="30" <?php if ($pagos_['data']['cuotas'] == 30) {
                                                    echo "selected";
                                                } ?>>30 Cuotas
                            </option>
                        </select>
                    </label>
                    <div class="col-md-12 mt-10 mb-10">
                        <label>
                            <span class="fs-15">¿Descuento acumulable?</span>
                        </label>
                        <div class="mt-6">
                            <div class="custom-control custom-switch custom-switch-glow ml-10 col-md-3">
                                <span class="invoice-terms-title"> Aplicar</span>
                                <input name="acumular" type="checkbox" id="acumular" class="custom-control-input" value="1" <?= ($pagos_['data']['acumular'] == 1) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="acumular">
                                </label>
                            </div>
                        </div>
                        <i class="fs-14 d-block text-normal" style="color: red">* Al seleccionar esta opción el método de pago ejecutará beneficios si el producto ya posee descuentos, es decir que acumulará más descuentos.</i>
                    </div>
                    <label class="col-md-12"><br />
                        <?php
                        if (isset($imagen) && !empty($imagen)) {
                        ?>
                            <div class='col-md-2 mb-20 mt-20'>
                                <div style="height:200px;background:url('<?= URL . "/" . $imagen['ruta'] ?>') no-repeat center center/contain;"></div>
                                <div class="row mt-10">
                                    <a href="<?= URL_ADMIN . '/index.php?op=pagos&accion=modificar&cod=' . $pagos_['data']["cod"] . '&borrarImg=' . $imagen["id"] . '&idioma=' . $idiomaGet ?>" class="btn btn-sm btn-block btn-danger">
                                        <div class="fonticon-wrap">
                                            <i class="bx bx-trash fs-20"></i>
                                        </div>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <label class="col-md-12">Imágen:<br />
                                <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                            </label>
                        <?php } ?>
                    </label>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar Pago" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("input[type='number']").inputSpinner()
</script>