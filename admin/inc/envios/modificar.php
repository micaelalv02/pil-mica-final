<?php
$envios = new Clases\Envios();
$imagenes = new Clases\Imagenes();
$idiomas = new Clases\Idiomas();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';


if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios&accion=modificar&cod=$cod&idioma=$idiomaGet");
}

$envios->set("cod", $cod);
$envios->set("idioma", $idiomaGet);
$envios_ = $envios->view();
$imagen = $imagenes->view($envios_["data"]["cod"]);

if (isset($_POST["modificar"])) {
    $count = 0;
    $cod = $envios_['data']["cod"];
    $envios->set("cod", $cod);
    $envios->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $envios->set("precio", isset($_POST["precio"]) ? $funciones->antihack_mysqli($_POST["precio"]) : '0');
    $envios->set("descripcion", isset($_POST["descripcion"]) ? $funciones->antihack_mysqli($_POST["descripcion"]) : '');
    $envios->set("opciones", isset($_POST["opciones"]) ? $funciones->antihack_mysqli($_POST["opciones"]) : '0');
    $envios->set("peso", isset($_POST["peso"]) ? $funciones->antihack_mysqli($_POST["peso"]) : '');
    $envios->set("estado", isset($_POST["estado"]) ? $funciones->antihack_mysqli($_POST["estado"]) : '');
    $envios->set("limite", isset($_POST["limite"]) ? $funciones->antihack_mysqli($_POST["limite"]) : '');
    $envios->set("tipo_usuario", isset($_POST["tipo_usuario"]) ? $funciones->antihack_mysqli($_POST["tipo_usuario"]) : "'0'");
    $envios->set("idioma", $idiomaGet);
    $envios->edit();
    if (!empty($_FILES['files']['name'][0])){
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($_POST["titulo"])), [$idiomaGet]);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios&accion=ver&idioma=$idiomaGet");
}
?>

<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Envios
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">
                        Título:<br />
                        <input type="text" value="<?= $envios_['data']["titulo"] ?>" name="titulo" required>
                    </label>
                    <label class="col-md-4">Estado:<br />
                        <select name="estado" required>
                            <option value="1" <?php if ($envios_['data']['estado'] == 1) {
                                                    echo "selected";
                                                } ?>>Activado
                            </option>
                            <option value="0" <?php if ($envios_['data']['estado'] == 0) {
                                                    echo "selected";
                                                } ?>>Desactivado
                            </option>
                        </select>
                    </label>
                    <label class="col-md-4">Tipo de Usuario:<br />
                        <select name="tipo_usuario" required>
                            <option value="0" <?php if ($envios_['data']['tipo_usuario'] == 0) {
                                                    echo "selected";
                                                } ?>>Ambos
                            </option>
                            <option value="1" <?php if ($envios_['data']['tipo_usuario'] == 1) {
                                                    echo "selected";
                                                } ?>>Minorista
                            </option>
                            <option value="2" <?php if ($envios_['data']['tipo_usuario'] == 2) {
                                                    echo "selected";
                                                } ?>>Mayorista
                            </option>
                        </select>
                    </label>
                    <label class="col-md-3">
                        Peso:<br />
                        <input value="<?= $envios_['data']["peso"] ?>" min="0" name="peso" type="text" required />
                    </label>
                    <label class="col-md-3">
                        Precio:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" min="0" value="<?= $envios_['data']["precio"] ?>" name="precio" required>
                        </div>
                    </label>
                    <label class="col-md-3">Limite:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" value="<?= $envios_['data']["limite"] ?>" name="limite">
                        </div>
                    </label>
                    <label class="col-md-3">Pedir datos adicionales:<br />
                        <select name="opciones" required>
                            <option value="0" <?= ($envios_['data']['opciones'] == 0) ? "selected" : '' ?>>Desactivado</option>
                            <option value="2" <?= ($envios_['data']['opciones'] == 2) ? "selected" : '' ?>>Hora y Fecha especifica</option>
                            <option value="3" <?= ($envios_['data']['opciones'] == 3) ? "selected" : '' ?>>Hora y Rango Fecha</option>
                        </select>
                    </label>
                    <label class="col-md-12">Descripción:<br />
                        <input type="text" name="descripcion" value="<?= $envios_['data']["descripcion"] ?>">
                    </label>
                    <label class="col-md-12"><br />
                        <?php
                        if (isset($imagen) && !empty($imagen)) {
                        ?>
                            <div class='col-md-2 mb-20 mt-20'>
                                <div style="height:200px;background:url('<?= URL . "/" . $imagen['ruta'] ?>') no-repeat center center/contain;"></div>
                                <div class="row mt-10">
                                    <a href="<?= URL_ADMIN . '/index.php?op=envios&accion=modificar&cod=' . $envios_['data']["cod"] . '&borrarImg=' . $imagen["id"] . '&idioma=' . $idiomaGet ?>" class="btn btn-sm btn-block btn-danger">
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
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar Envio" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("#pes").inputSpinner()
</script>