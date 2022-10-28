<?php
$contenido = new Clases\Contenidos();
$idiomas = new Clases\Idiomas();
$area = new Clases\Area();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$funciones = new Clases\PublicFunction();
$opciones = new Clases\Opciones();
$opcionesValor = new Clases\OpcionesValor();

$getArea = isset($_GET["area"]) ? $funciones->antihack_mysqli($_GET["area"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$areaData = $area->list(["cod = '$getArea'"], '', '', $idiomaGet, true);
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
$categoriasData = $categorias->list(["area = '$getArea'"], "titulo ASC", "", $idiomaGet);


$categoriasOpciones = $categorias->list(array("area = 'opciones'"), "", "", $idiomaGet);
$optionsWithoutCat = $opciones->list($idiomaGet, ["ISNULL(`opciones`.`categoria`)"], false, "");

$cod = substr(md5(uniqid(rand())), 0, 10);

if (isset($_POST["agregar"])) {
    unset($_POST["agregar"]);
    if (!isset($_POST["destacado"])) $_POST["destacado"] = "0";
    if (isset($_POST["idiomasInput"])) {
        $idiomasInputPost =  $_POST["idiomasInput"];
        $idiomasInputPost[] = $idiomaGet;
    } else {
        $idiomasInputPost = [$idiomaGet];
    }
    unset($_POST["idiomasInput"]);

    $cod = $funciones->antihack_mysqli($_POST["cod"]);
    $array = $funciones->antihackMulti($_POST);
    $opcionesData = $_POST["opcion"];
    unset($_POST["opcion"]);
    if (isset($idiomasInputPost) && !empty($idiomasInputPost)) {
        foreach ($idiomasInputPost as $idiomasInputItem) {
            foreach ($opcionesData as $key => $optionData) {
                if ($optionData == "-- Sin seleccionar --" || $optionData == NULL) continue;
                $opcionesValor->set("relacion_cod", $cod);
                $opcionesValor->set("idioma", $idiomasInputItem);
                $opcionesValor->set("opcion_cod", $key);
                $opcionesValor->set("valor", $optionData);
                $opcionesValor->set("cod", substr(md5(uniqid(rand())), 0, 10));
                $opcionesValor->add();
            }
            unset($array['opcion']);
            $array["idioma"] = $idiomasInputItem;
            $contenido->add($array);
        }
    }

    if (isset($_FILES['files'])) {
        var_dump($array["titulo"]);
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($array["titulo"])), $idiomasInputPost);
    }
    if ($getArea != 'landing-area') {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=contenidos&accion=ver&area=" . $areaData['data']['cod'] . "&idioma=" . $idiomaGet);
    } else {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=landing");
    }
}
?>

<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                <?= $areaData['data']['titulo'] ?>
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <hr />
                    <input type="hidden" name="area" value="<?= $getArea ?>">
                    <label class="col-md-5">Título
                        <input type="text" name="titulo" required>
                    </label>
                    <label class="col-md-5">Subtitulo
                        <input type="text" id="sub" name="subtitulo">
                    </label>
                    <label class="col-md-2">Código:<br />
                        <input type="text" name="cod" value="<?= $cod ?>">
                    </label>
                    <label class="col-md-4">
                        Categoría:<br />
                        <select name="categoria">
                            <option value="" selected>-- categorías --</option>
                            <?php
                            foreach ($categoriasData as $categoria) {
                                echo "<option value='" . $categoria["data"]["cod"] . "'>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-4">
                        Subcategoría:<br />
                        <select name="subcategoria">
                            <option value="" selected>-- Sin subcategoría --</option>
                            <?php
                            foreach ($categoriasData as $categoria) {
                            ?>
                                <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                                    <?php
                                    foreach ($categoria["subcategories"] as $subcategorias) {
                                        echo "<option value='" . $subcategorias["data"]["cod"] . "'>" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                                    }
                                    ?>
                                </optgroup>
                            <?php
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-3">Fecha:<br />
                        <input type="date" name="fecha" value="<?= date('Y-m-d') ?>">
                    </label>
                    <label class="col-md-1">Orden:<br />
                        <input type="text" name="orden" value="0">
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-12">Contenido:<br />
                        <textarea name="contenido" class="ckeditorTextarea" required></textarea>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-12">Palabras claves dividas por ,<br />
                        <input type="text" name="keywords">
                    </label>
                    <label class="col-md-12">Descripción breve<br />
                        <textarea name="description"></textarea>
                    </label>
                    <br />
                    <label class="col-md-12">Link
                        <input type="text" id="link" name="link">
                    </label>
                    <br>
                    <label class="col-md-12">
                        <?php if (!empty($categoriasOpciones)) {
                            foreach ($categoriasOpciones as $cat) {
                                $catCod = $cat['data']['cod'];
                                $opcionesContenido = $opciones->list($idiomaGet, ["`opciones`.`categoria` = '$catCod'", "`opciones`.`area` = '$getArea'"], false, "");
                                if (!empty($opcionesContenido)) {
                        ?>
                                    <div class="repeater-default">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="bold mb-10 col-md-12"><?= $cat['data']['titulo'] ?></div>
                                                <?php foreach ($opcionesContenido as $opcionItem) { ?>
                                                    <div class="col-sm-3 col-12">
                                                        <h6 class="invoice-to"><?= $opcionItem["data"]["titulo"] ?> <span style="font-size:10px!important">(<?= $opcionItem["data"]["tipo_mostrar"] ?>)</span></h6>
                                                        <?php if ($opcionItem["data"]["tipo"] == "text") { ?>
                                                            <input type="text" class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]" value="">
                                                        <?php } ?>
                                                        <?php if ($opcionItem["data"]["tipo"] == "int") { ?>
                                                            <input type="number" class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]" value="">
                                                        <?php } ?>
                                                        <?php if ($opcionItem["data"]["tipo"] == "boolean") { ?>
                                                            <select class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]">
                                                                <option>-- Sin seleccionar --</option>
                                                                <option value="true">Si</option>
                                                                <option value="false">No</option>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            }
                        }
                        if (!empty($optionsWithoutCat)) { ?>
                            <div class="repeater-default">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="bold mb-10 col-md-12 mt-10">Opciones Sin Categorizar</div>
                                        <?php foreach ($optionsWithoutCat as $optionItem) {  ?>
                                            <div class="col-sm-3 col-12">
                                                <h6 class="invoice-to"><?= $optionItem["data"]["titulo"] ?> <span style="font-size:10px!important">(<?= $optionItem["data"]["tipo_mostrar"] ?>)</span></h6>
                                                <?php if ($optionItem["data"]["tipo"] == "text") { ?>
                                                    <input type="text" class="form-control" name="opcion[<?= $optionItem["data"]["cod"] ?>]" value="">
                                                <?php } ?>
                                                <?php if ($optionItem["data"]["tipo"] == "int") { ?>
                                                    <input type="number" class="form-control" name="opcion[<?= $optionItem["data"]["cod"] ?>]" value="">
                                                <?php } ?>
                                                <?php if ($optionItem["data"]["tipo"] == "boolean") { ?>
                                                    <select class="form-control" name="opcion[<?= $optionItem["data"]["cod"] ?>]">
                                                        <option>-- Sin seleccionar --</option>
                                                        <option value="true">Si</option>
                                                        <option value="false">No</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php }  ?>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-12">Imágenes:<br />
                        <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                    </label>
                    <?php
                    if (count($idiomasData) >= 1) { ?>
                        <div class="col-md-12">
                            <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar contenido en otros idiomas</div>
                            <div id="idiomasCheckBox">
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
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-block btn-primary" name="agregar" value="Agregar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#idiomasCheckBox').hide();
</script>