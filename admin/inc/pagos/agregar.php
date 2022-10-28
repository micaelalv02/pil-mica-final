<?php
$pagos = new Clases\Pagos();
$config = new Clases\Config();
$idiomas = new Clases\Idiomas();
$estadoPedido = new Clases\EstadosPedidos();
$imagenes = new Clases\Imagenes();
$funciones = new Clases\PublicFunction();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");

$estadoData = $estadoPedido->list(["idioma = '$idiomaGet'"], "", "");
$payments = $config->listPayment();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $pagos->set("cod", $cod);
    $pagos->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $pagos->set("leyenda", isset($_POST["leyenda"]) ? $funciones->antihack_mysqli($_POST["leyenda"]) : '');
    $pagos->set("estado", isset($_POST["estado"]) ? $funciones->antihack_mysqli($_POST["estado"]) : '');
    $pagos->set("tipo", isset($_POST["tipo"]) ? $funciones->antihack_mysqli($_POST["tipo"]) : '');
    $pagos->set("idioma", $idiomaGet);
    $pagos->set("cuotas", isset($_POST["cuotas"]) ? $funciones->antihack_mysqli(intval($_POST["cuotas"])) : 0);
    $pagos->monto = isset($_POST["monto"]) ? $funciones->antihack_mysqli($_POST["monto"]) : 0;
    $pagos->set("defecto", isset($_POST["defecto"]) ? $funciones->antihack_mysqli($_POST["defecto"]) : '');
    $pagos->set("tipo_usuario", isset($_POST["tipo_usuario"]) ? $funciones->antihack_mysqli($_POST["tipo_usuario"]) : '');
    $pagos->minimo = !empty($_POST["minimo"]) ? $funciones->antihack_mysqli($_POST["minimo"]) : 0;
    $pagos->maximo = !empty($_POST["maximo"]) ? $funciones->antihack_mysqli($_POST["maximo"]) : 0;
    $pagos->entrega = !empty($_POST["entrega"]) ? $funciones->antihack_mysqli($_POST["entrega"]) : 0;
    $pagos->acumular = isset($_POST["acumular"]) ? $funciones->antihack_mysqli($_POST["acumular"]) : 0;
    $pagos->desc_usuario = isset($_POST["desc_usuario"]) ? $funciones->antihack_mysqli($_POST["desc_usuario"]) : 0;
    $pagos->desc_cupon = isset($_POST["desc_cupon"]) ? $funciones->antihack_mysqli($_POST["desc_cupon"]) : 0;
    $pagos->add();
    if (!empty($_FILES['files']['name'][0])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($_POST["titulo"])), [$idiomaGet]);
    }
    if (isset($_POST["idiomasInput"])) {
        foreach ($_POST["idiomasInput"] as $idioma_) {
            $pagos->set("idioma", $idioma_);
            $pagos->add();
            if (!empty($_FILES['files']['name'][0])) {
                $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($_POST["titulo"])), $idioma_);
            }
        }
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=pagos&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 ">
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
                    <label class="mb-20 col-md-6">
                        Método de pago:<br />
                        <input type="text" name="titulo" required>
                    </label>
                    <label class="mb-20 col-md-3">
                        Monto de Compra Minimo:<br />
                        <div class="input-group  ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" name="minimo">
                        </div>
                    </label>
                    <label class="mb-20 col-md-3">
                        Monto de Compra Maximo:<br />
                        <div class="input-group  ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" name="maximo">
                        </div>
                    </label>
                    <label class="mb-20 col-md-12">
                        Descripción del método de pago:<br />
                        <textarea name="leyenda"></textarea>
                    </label>
                    <label class="mb-20 col-md-3 mt-10">
                        Estado
                        <select name="estado" class="form-control" required>
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </label>
                    <label class="mb-20 col-md-3 mt-10">
                        Tipo de pago online:
                        <select name="tipo" class="form-control">
                            <option value="" disabled selected>--- Sin elegir ---</option>
                            <?php
                            if (!empty($payments)) {
                                foreach ($payments as $payment) {
                            ?>
                                    <option value="<?= $payment['data']['id']; ?>"><?= mb_strtoupper($payment['data']['empresa']); ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </label>
                    <label class="mb-20 col-md-3 mt-10">
                        Defecto:
                        <select name="defecto" class="form-control" required>
                            <?php foreach ($estadoData as $estado) { ?>
                                <option value="<?= $estado['data']['id'] ?>"><?= mb_strtoupper($estado['data']['titulo']) ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="mb-20 col-md-3 mt-10">
                        Tipo Usuario:
                        <select name="tipo_usuario" class="form-control" required>
                            <option value="0">Ambos</option>
                            <option value="1">Minorista</option>
                            <option value="2">Mayorista</option>
                        </select>
                    </label>
                    <label class="mb-20 col-md-4 mt-10">
                        Aumento o Descuento (%)<br />
                        <input data-suffix="%" value="0" min="-100" max="100" type="number" name="monto" onkeydown="return (event.keyCode!=13);" />
                    </label>
                    <label class="mb-20 col-md-4 mt-10">
                        Compra Parcial / Seña (%)<br />
                        <input data-suffix="%" value="0" min="0" max="100" type="number" name="entrega" onkeydown="return (event.keyCode!=13);" />
                    </label>
                    <label class="col-md-4 mt-10">
                        Cuotas (solo aplica al método de pago decidir):
                        <select name="cuotas" class="form-control" required>
                            <option value="1" selected>No</option>
                            <option value="3">3 Cuotas</option>
                            <option value="6">6 Cuotas</option>
                            <option value="12">12 Cuotas</option>
                            <option value="18">18 Cuotas</option>
                            <option value="24">24 Cuotas</option>
                            <option value="30">30 Cuotas</option>
                        </select>
                    </label>
                    <div class="clearfix"></div>
                    <div class="col-md-12 mt-10">
                        <label>
                            <span class="fs-15">¿Descuento acumulable?</span>
                        </label>
                        <div class=" mt-6">
                            <div class="custom-control custom-switch custom-switch-glow ml-10 col-md-3">
                                <span class="invoice-terms-title"> Aplicar</span>
                                <input name="acumular" type="checkbox" id="acumular" class="custom-control-input" value="1">
                                <label class="custom-control-label" for="acumular"></label>
                            </div>
                            <i class="fs-14 d-block text-normal" style="color: red">* Al seleccionar esta opción el descuento ejecutará beneficios extras si el producto ya posee descuentos, es decir que acumulará más descuentos.</i>
                        </div>
                    </div>
                    <?php if (count($idiomasData) >= 1) { ?>
                        <div class="col-md-12 mt-10">
                            <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar metodo de pago en otros idiomas</div>
                            <div class="row" id="idiomasCheckBox">
                                <?php foreach ($idiomasData as $idiomaItem) { ?>
                                    <div class="ml-10">
                                        <label for="idioma<?= $idiomaItem['data']['cod'] ?>">
                                            <input type="checkbox" name="idiomasInput[]" value="<?= $idiomaItem['data']['cod'] ?>" id="idioma<?= $idiomaItem['data']['cod'] ?>"> <?= $idiomaItem['data']['titulo'] ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-12">
                        <h6 class="invoice-to">Imagen</h6>
                        <input name="files[]" class="form-control form-control-md" style="border:none" type="file" id="file" accept="image/*" />
                    </div>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Pago" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#idiomasCheckBox').hide();
</script>
<script>
    $("input[type='number']").inputSpinner()
</script>