<?php
$contenido = new Clases\Contenidos();
$area = new Clases\Area();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$opciones = new Clases\Opciones();
$opcionesValor = new Clases\OpcionesValor();
$getArea = isset($_GET["area"]) ? $funciones->antihack_mysqli($_GET["area"]) : '';
$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$categoriasOpciones = $categorias->list(array("area = 'opciones'"), "", "", $idiomaGet);
$optionsWithoutCat = $opciones->list($idiomaGet, ["ISNULL(`opciones`.`categoria`)"], true, $cod);

$areaData = $area->list(["cod = '$getArea'"], '', '', $idiomaGet, true);

$categoriasData = $categorias->list(["area = '$getArea'"], "titulo ASC", '', $idiomaGet);
$contenidoSingle = $contenido->list(["filter" => ["contenidos.cod = '$cod'"], "images" => true], $idiomaGet, true);

//CAMBIAR ORDEN DE LAS IMAGENES
if (isset($_GET["ordenImg"]) && isset($_GET["idImg"])) {
    $imagenes->set("id", $funciones->antihack_mysqli($_GET["idImg"]));
    $imagenes->orden = $funciones->antihack_mysqli($_GET["ordenImg"]);
    $imagenes->setOrder();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=contenidos&area=$getArea&accion=modificar&cod=$cod&idioma=$idiomaGet");
}
//BORRAR IMAGEN
if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=contenidos&area=$getArea&accion=modificar&cod=$cod&idioma=$idiomaGet");
}
if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    unset($_POST["idioma"]);
    unset($_POST["cod"]);
    if (!isset($_POST["destacado"])) $_POST["destacado"] = "0";
    $opcionesData = $_POST["opcion"];
    unset($_POST["opcion"]);
    $array = $funciones->antihackMulti($_POST);
    foreach ($opcionesData as $key => $optionData) {
        if ($optionData == "-- Sin seleccionar --" || $optionData == NULL) continue;
        $opcionesValor->set("relacion_cod", $cod);
        $opcionesValor->set("idioma", $idiomaGet);
        $opcionesValor->set("opcion_cod", $key);
        $opcionesValor->set("valor", $optionData);
        $exist = $opcionesValor->checkIfExist();
        $codOpcionValor = (!empty($exist)) ? $exist["data"]["cod"] : substr(md5(uniqid(rand())), 0, 10);
        $opcionesValor->set("cod", $codOpcionValor);
        (!empty($exist)) ? $opcionesValor->edit() : $opcionesValor->add();
    }
    $contenido->edit($array, ["cod = '$cod'", "idioma = '$idiomaGet'"]);
    if (!empty($_FILES['files']['name'][0])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($array["titulo"])), [$idiomaGet]);
    }
    if ($getArea != 'landing-area') {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=contenidos&accion=ver&area=" . $areaData['data']['cod'] . "&idioma=" . $idiomaGet);
    } else {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=landing");
    }
}
?>
<div class="mt-20 card">
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
                    <input name="destacado" type="hidden" value="<?= $contenidoSingle['data']['destacado'] ?> ">
                    <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                    <label class="col-md-5">
                        Título
                        <input type="text" value="<?= $contenidoSingle['data']["titulo"] ?>" name="titulo">
                    </label>
                    <label class="col-md-4">
                        Subtitulo
                        <input type="text" id="sub" value="<?= $contenidoSingle['data']["subtitulo"] ?>" name="subtitulo">
                    </label>
                    <label class="col-md-3">
                        Código:<br />
                        <input type="text" name="cod" disabled value="<?= $contenidoSingle["data"]["cod"] ?>">
                    </label>
                    <label class="col-md-5">
                        Categoría:<br />
                        <select name="categoria">
                            <option value="">-- categorías --</option>
                            <?php
                            foreach ($categoriasData as $categoria) {
                                $selected = ($contenidoSingle["data"]["categoria"] == $categoria["data"]["cod"]) ? "selected" : '';
                                echo "<option value='" . $categoria["data"]["cod"] . "' $selected >" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-4">
                        Subcategoría:<br />
                        <select name="subcategoria">
                            <option value="">-- Sin subcategoría --</option>
                            <?php
                            foreach ($categoriasData as $categoria) {
                            ?>
                                <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                                    <?php
                                    foreach ($categoria["subcategories"] as $subcategorias) {
                                        $selected = ($contenidoSingle["data"]["subcategoria"] == $subcategorias["data"]["cod"]) ? "selected" : '';
                                        echo "<option value='" . $subcategorias["data"]["cod"] . "' $selected >" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                                    }
                                    ?>
                                </optgroup>
                            <?php
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-2">
                        Fecha:<br />
                        <input type="date" name="fecha" value="<?= $contenidoSingle["data"]["fecha"] ?>">
                    </label>
                    <label class="col-md-1">
                        Orden:<br />
                        <input type="text" name="orden" value="<?= $contenidoSingle["data"]["orden"] ?>">
                    </label>

                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-12">
                        Contenido:<br />
                        <textarea name="contenido" class="ckeditorTextarea" required>
                            <?= $contenidoSingle["data"]["contenido"]; ?>
                        </textarea>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-12">
                        Palabras claves dividas por ,<br />
                        <input type="text" name="keywords" value="<?= $contenidoSingle["data"]["keywords"] ?>">
                    </label>
                    <label class="col-md-12">
                        Descripción breve<br />
                        <textarea name="description"><?= $contenidoSingle["data"]["description"] ?></textarea>
                    </label>
                    <br />
                    <label class="col-md-12">Link
                        <input type="text" id="link" name="link" value="<?= $contenidoSingle["data"]["link"] ?>">
                    </label>
                    <label class="col-md-12">
                        <?php if (!empty($categoriasOpciones)) {
                            foreach ($categoriasOpciones as $cat) {
                                $catCod = $cat['data']['cod'];
                                $opcionesContenido = $opciones->list($idiomaGet, ["`opciones`.`categoria` = '$catCod'", "`opciones`.`area` = '$getArea'"], true, $cod);
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
                                                            <input type="text" class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]" value="<?= $opcionItem["data"]["valor"] ?>">
                                                        <?php } ?>
                                                        <?php if ($opcionItem["data"]["tipo"] == "int") { ?>
                                                            <input type="number" class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]" value="<?= $opcionItem["data"]["valor"] ?>">
                                                        <?php } ?>
                                                        <?php if ($opcionItem["data"]["tipo"] == "boolean") { ?>
                                                            <select class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]">
                                                                <option>-- Sin seleccionar --</option>
                                                                <option <?= ($opcionItem["data"]["valor"]) == "true" ? "selected" : '' ?> value="true">Si</option>
                                                                <option <?= ($opcionItem["data"]["valor"]) == "false" ? "selected" : '' ?> value="false">No</option>
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
                                        <?php foreach ($optionsWithoutCat as $optionItem) { ?>
                                            <div class="col-sm-3 col-12">
                                                <h6 class="invoice-to"><?= $optionItem["data"]["titulo"] ?> <span style="font-size:10px!important">(<?= $optionItem["data"]["tipo_mostrar"] ?>)</span></h6>
                                                <?php if ($optionItem["data"]["tipo"] == "text") { ?>
                                                    <input type="text" class="form-control" name="opcion[<?= $optionItem["data"]["cod"] ?>]" value="<?= (!empty($optionItem["data"]["valor"])) ? $optionItem["data"]["valor"] : "" ?>">
                                                <?php } ?>
                                                <?php if ($optionItem["data"]["tipo"] == "int") { ?>
                                                    <input type="number" class="form-control" name="opcion[<?= $optionItem["data"]["cod"] ?>]" value="<?= (!empty($optionItem["data"]["valor"])) ? $optionItem["data"]["valor"] : "" ?>">
                                                <?php } ?>
                                                <?php if ($optionItem["data"]["tipo"] == "boolean") { ?>
                                                    <select class="form-control" name="opcion[<?= $optionItem["data"]["cod"] ?>]">
                                                        <option>-- Sin seleccionar --</option>
                                                        <option <?= ($optionItem["data"]["valor"]) == "true" ? "selected" : '' ?> value="true">Si</option>
                                                        <option <?= ($optionItem["data"]["valor"]) == "false" ? "selected" : '' ?> value="false">No</option>
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
                        <div class="row">
                            <?php
                            if (isset($contenidoSingle['images']) && !empty($contenidoSingle['images'])) {
                                foreach ($contenidoSingle['images'] as $key => $img) {
                                    $img_id = $img['id'];

                            ?>
                                    <div class='col-md-2 mb-20 mt-20'>
                                        <div style="height:200px;background:url('<?= $img['url'] ?>') no-repeat center center/contain;">
                                        </div>
                                        <div class="row mt-10">
                                            <div class="col-md-6 mt-10">
                                                <a href="<?= URL_ADMIN . '/index.php?op=contenidos&accion=modificar&area=' . $getArea . '&cod=' . $cod . '&borrarImg=' . $img_id . '&idioma=' . $idiomaGet ?>" class="btn btn-sm btn-block btn-danger">
                                                    <div class="fonticon-wrap">
                                                        <i class="bx bx-trash fs-20"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-6 mt-10">
                                                <select onchange="changeOrderImg('<?= $img_id ?>',$(this).val(),'<?= URL_ADMIN ?>')">
                                                    <?php
                                                    for ($i = 0; $i < count($contenidoSingle['images']); $i++) {
                                                    ?>
                                                        <option value='<?= $i ?>' <?= ($img['orden'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <i>orden</i>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="clearfix">
                    </div>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-12">
                        Imágenes:<br />
                        <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                    </label>
                    <div class="clearfix">
                    </div>
                    <br />
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-block btn-primary" name="modificar" value="Modificar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>