<?php
require '../vendor/autoload.php';
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categoria = new Clases\Categorias();
$atributo = new Clases\Atributos();
$opciones = new Clases\Opciones();
$opcionesValor = new Clases\OpcionesValor();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];



$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
$categoriasData = $categoria->list(array("area = 'productos'"), "titulo ASC", "", $idiomaGet);
$data = [
    "filter" => ["productos.cod = '$cod'"],
    "admin" => true,
    "promos" => true,
    "category" => true,
    "subcategory" => true,
    "tercercategory" => true,
    "images" => true,
];

$producto = $productos->list($data, $idiomaGet, true);

$categoriasOpciones = $categoria->list(array("area = 'opciones'"), "", "", $idiomaGet);
$optionsWithoutCat = $opciones->list($idiomaGet, ["ISNULL(`opciones`.`categoria`)"], true, $cod);

//BORRAR IMAGEN
if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos&accion=modificar&cod=$cod&idioma=$idiomaGet");
}

if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    unset($_POST["lleva"]);
    unset($_POST["paga"]);
    $_POST["mostrar_web"] = isset($_POST["mostrar_web"]) ? $_POST["mostrar_web"] : "0";
    $_POST["envio_gratis"] = isset($_POST["envio_gratis"]) ? $_POST["envio_gratis"] : "0";
    $_POST["destacado"] = isset($_POST["destacado"]) ? $_POST["destacado"] : "0";

    if (isset($_POST["opcion"])) $opciones = $funciones->antihackMulti($_POST["opcion"]);
    unset($_POST["opcion"]);

    $array = $funciones->antihackMulti($_POST);

    foreach ($opciones as $key => $optionData) {
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

    $productos->edit($array, ["cod = '$cod'", "idioma = '$idiomaGet'"]);
    if (!empty($_FILES['files']['name'][0])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($array["titulo"]), [$idiomaGet]);
    }



    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos&accion=ver&idioma=$idiomaGet");
}
?>

<section class="invoice-edit-wrapper mt-40">
    <div class="card-header">
        <h4 class="card-title text-uppercase text-center">
            EDITAR PRODUCTO
        </h4>
        <hr style="border-style: dashed;">
    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12 col-12 order-1">
            <?php
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger" role="alert"><?= $error; ?></div>
            <?php
            }
            ?>
            <form method="post" id="form" enctype="multipart/form-data">
                <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                <input type="hidden" name="destacado" value="<?= $producto['data']['destacado'] ?>">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body py-0">
                            <div class="row my-2 py-50">
                                <div class="col-sm-8 col-12 order-1 order-sm-1">
                                    <h6 class="invoice-to">Titulo</h6>
                                    <input name="titulo" type="text" class="form-control" placeholder="Nombre del Producto" value="<?= $producto["data"]["titulo"] ?>" required>
                                </div>
                                <div class="col-sm-4 col-12 order-2 order-sm-1">
                                    <h6 class="invoice-to">Codigo</h6>
                                    <input name="cod_producto" type="text" class="form-control" placeholder="0000" value="<?= $producto["data"]["cod_producto"] ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="row my-2 py-50">
                                <div class="col-sm-3 col-12 order-1 order-sm-1  mb-30">
                                    <h6 class="invoice-to">Categoria</h6>
                                    <select name="categoria" class="form-control bg-transparent select2" id="categoria" onchange="getCategory('<?= URL_ADMIN ?>','subcategory','categoria','subcategoria','<?= $idiomaGet ?>')">
                                        <option value="">-- categorías --</option>
                                        <?php
                                        foreach ($categoriasData as $categoria) {
                                            if ($producto["data"]["categoria"] == $categoria["data"]["cod"]) {
                                                echo "<option value='" . $categoria["data"]["cod"] . "' selected>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                                            } else {
                                                echo "<option value='" . $categoria["data"]["cod"] . "'>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3 col-12 order-1 order-sm-1 mb-30">
                                    <h6 class="invoice-to">Subcategoria</h6>
                                    <select name="subcategoria" class="form-control bg-transparent select2" id="subcategoria" onchange="getSubcategory('<?= URL_ADMIN ?>','tercercategory','subcategoria','tercercategoria','<?= $idiomaGet ?>')">
                                        <option selected value="<?= $producto["data"]["subcategoria"] ?>"><?= $producto["data"]["subcategoria_titulo"] ?></option>
                                    </select>
                                </div>
                                <div class="col-sm-3 col-12 order-1 order-sm-1 mb-30">
                                    <h6 class="invoice-to">Tercercategoria</h6>
                                    <select name="tercercategoria" class="form-control bg-transparent select2" id="tercercategoria">
                                        <option selected value="<?= $producto["data"]["tercercategoria"] ?>"><?= ($producto["data"]["tercercategoria_titulo"] != null) ? $producto["data"]["tercercategoria_titulo"] : '' ?></option>
                                    </select>
                                </div>
                                <div class="col-sm-3 col-12 order-1 order-sm-1 mb-30">
                                    <h6 class="invoice-to">Promoción</h6>
                                    <div class="input-group">
                                        <input class="form-control" type="number" min="1" id="lleva" placeholder="3" onchange='editPromo("<?= $idiomaGet ?>","<?= $producto["data"]["cod"] ?>","<?= URL_ADMIN ?>","true")' name='lleva' value='<?= $producto["data"]["promoLleva"] ?>' />
                                        <span class="input-group-text" style="border-radius: 0px !important;">x</span>
                                        <input class="form-control" type="number" min="1" id="paga" placeholder="2" onchange='editPromo("<?= $idiomaGet ?>","<?= $producto["data"]["cod"] ?>","<?= URL_ADMIN ?>","true")' name='paga' value='<?= $producto["data"]["promoPaga"] ?>' />
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 order-1 order-sm-1">
                                    <h6 class="invoice-to">Precio</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input name="precio" type="number" step="any" class="form-control" placeholder="0.00" value="<?= $producto["data"]["precio"] ? $producto["data"]["precio"] : '0' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12 order-2 order-sm-2">
                                    <h6 class="invoice-to">Precio Descuento</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input name="precio_descuento" type="number" step="any" class="form-control" placeholder="0.00" value="<?= $producto["data"]["precio_descuento"] ? $producto["data"]["precio_descuento"] : '0' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2  col-sm-12 order-3 order-sm-3">
                                    <h6 class="invoice-to">Precio Mayorista</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input name="precio_mayorista" type="number" step="any" class="form-control" placeholder="0.00" value="<?= $producto["data"]["precio_mayorista"] ? $producto["data"]["precio_mayorista"] : '0' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2  col-sm-12 order-4 order-sm-4">
                                    <h6 class="invoice-to">Peso</h6>
                                    <div class="input-group">
                                        <input name="peso" type="number" step="any" class="form-control" placeholder="0.00" value="<?= $producto["data"]["peso"] ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2  col-sm-12 order-5 order-sm-5">
                                    <h6 class="invoice-to">Stock</h6>
                                    <input name="stock" type="text" class="form-control" placeholder="0000" value="<?= $producto["data"]["stock"] ?>">
                                </div>

                            </div>
                            <hr>
                            <div class="row my-2 py-50">
                                <div class="col-sm-6 col-12 order-1 order-sm-1">
                                    <button href="<?= URL_ADMIN ?>/inc/productos/api/atributos/atributosAgregar.php?cod=<?= $cod ?>&idioma=<?= $producto["data"]['idioma'] ?>" data-title="Agregar Atributos" class="btn btn-info modal-page-ajax">AGREGAR ATRIBUTOS +</button>
                                    <div id="listAttr">

                                    </div>
                                </div>
                                <div class="col-sm-6 col-12 order-2 order-sm-2">
                                    <button href="<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesAgregar.php?cod=<?= $cod ?>&idioma=<?= $producto["data"]['idioma'] ?>" data-title="Agregar Variaciones" id="variaciones" class="btn btn-info modal-page-ajax">AGREGAR VARIACIONES +</button>
                                    <div id="listComb" class=""></div>
                                </div>
                            </div>
                            <hr>

                            <div class="row my-2 py-50">
                                <div class="col-sm-12 col-12 order-1 order-sm-1">
                                    <h6 class="invoice-to">Desarrollo</h6>
                                    <textarea name="desarrollo" class="form-control ckeditorTextarea"><?= $producto["data"]["desarrollo"] ?></textarea>
                                </div>
                                <div class="col-sm-12 col-12 order-2 order-sm-2 mt-20">
                                    <h6 class="invoice-to">Palabras Claves divididas por " , "</h6>
                                    <input name="keywords" class="form-control" type="text" value="<?= $producto["data"]["keywords"] ?>">
                                </div>
                                <div class="col-sm-12 col-12 order-3 order-sm-3  mt-20">
                                    <h6 class="invoice-to">Descripcion Breve</h6>
                                    <textarea name="description" class="form-control char-textarea" data-lenght="200" maxlength="200"><?= $producto["data"]["description"] ?></textarea>
                                    <small class="counter-value float-right mr-0" style="background-color: rgb(90, 141, 238);"><span class="char-count"><?= strlen($producto["data"]["description"]) ?></span> / 200 </small>
                                </div>
                            </div>
                            <hr>
                            <?php if (!empty($categoriasOpciones)) {
                                foreach ($categoriasOpciones as $cat) {
                                    $catCod = $cat['data']['cod'];
                                    $opcionesPr = $opciones->list($idiomaGet, ["`opciones`.`categoria` = '$catCod'"], true, $cod);
                            ?>
                                    <div class="repeater-default">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="bold mb-10 col-md-12"><?= $cat['data']['titulo'] ?></div>
                                                <?php foreach ($opcionesPr as $opcionItem) { ?>
                                                    <div class="col-sm-3 col-12">
                                                        <h6 class="invoice-to"><?= $opcionItem["data"]["titulo"] ?> <span style="font-size:10px!important">(<?= $opcionItem["data"]["tipo_mostrar"] ?>)</span></h6>
                                                        <?php if ($opcionItem["data"]["tipo"] == "text") { ?>
                                                            <input type="text" class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]" value="<?= (!empty($opcionItem["data"]["valor"])) ? $opcionItem["data"]["valor"] : "" ?>">
                                                        <?php } ?>
                                                        <?php if ($opcionItem["data"]["tipo"] == "int") { ?>
                                                            <input type="number" class="form-control" name="opcion[<?= $opcionItem["data"]["cod"] ?>]" value="<?= (!empty($opcionItem["data"]["valor"])) ? $opcionItem["data"]["valor"] : "" ?>">
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
                            if (!empty($optionsWithoutCat)) { ?>
                                <div class="repeater-default">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="bold mb-10 col-md-12">Opciones Sin Categorizar</div>
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
                            <hr>
                            <div class="row my-2 py-50">
                                <?php
                                if (isset($producto['images'][0]['id']) && !empty($producto['images'])) {
                                    foreach ($producto['images'] as $key => $img) {
                                        $img_id = $img['id'];
                                ?>
                                        <div class='col-md-2 mb-20 mt-20'>
                                            <div style="height:200px;background:url('<?= $img['url'] ?>') no-repeat center center/contain;">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mt-10">
                                                    <a href="<?= URL_ADMIN . '/index.php?op=productos&accion=modificar&cod=' . $cod . '&borrarImg=' . $img_id . '&idioma=' . $idiomaGet ?>" class="btn btn-sm btn-block btn-danger">
                                                        <div class="fonticon-wrap">
                                                            <i class="bx bx-trash fs-20"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-6 mt-10">
                                                    <select onchange="changeOrderImg('<?= $img_id ?>',$(this).val(),'<?= URL_ADMIN ?>')">
                                                        <?php
                                                        for ($i = 0; $i < count($producto['images']); $i++) {
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

                                <div class="col-12 order-1 order-sm-1">
                                    <h6 class="invoice-to">Imágenes</h6>
                                    <div class="custom-file" style="margin-top: 5px;">
                                        <input name="files[]" type="file" class="custom-file-input" type="file" id="file" multiple="multiple" accept="image/*">
                                        <label class="custom-file-label" for="file_import"></label>
                                    </div>
                                </div>

                            </div>
                            <div class="row my-2 py-50">
                                <div class="col-12   mt-20">
                                    <div class="custom-control custom-switch custom-switch-glow ml-10">
                                        <span class="invoice-terms-title"> Mostrar en la Web</span>
                                        <input name="mostrar_web" type="checkbox" id="mostrar_web" class="custom-control-input" value="1" <?= ($producto["data"]["mostrar_web"] == 1) ? "checked" : "" ?>>
                                        <label class="custom-control-label" for="mostrar_web">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12    mt-20">
                                    <div class="custom-control custom-switch custom-switch-glow ml-10">
                                        <span class="invoice-terms-title"> Envio Gratis</span>
                                        <input name="envio_gratis" type="checkbox" id="envioGratis" class="custom-control-input" value="1" <?= ($producto["data"]["envio_gratis"] == 1) ? "checked" : "" ?>>
                                        <label class="custom-control-label" for="envioGratis">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-20 ">
                                    <input type="submit" class="btn btn-primary btn-block subtotal-preview-btn" id="guardar" name="modificar" value="Modificar Producto" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


<script>
    function checkAttrProducts() {
        $.ajax({
            url: "<?= URL_ADMIN ?>/inc/productos/api/atributos/atributosVer.php?cod=<?= $cod ?>&idioma=<?= $idiomaGet ?>",
            success: function(data) {
                data = JSON.parse(data);
                $('#listAttr').html('');
                if (data.length != 0) {
                    for (i = 0; i < data.length; i++) {
                        var texto = "<strong>" + data[i]["atribute"]["value"] + ": </strong>";
                        for (o = 0; o < data[i]["atribute"]["subatributes"].length; o++) {
                            texto += data[i]["atribute"]["subatributes"][o]["value"] + " | ";
                        }
                        $('#listAttr').append("<span class='text-uppercase'>" + texto + "</span>");
                        $('#listAttr').append(
                            "<span class='mt-10 ml-10 btn btn-warning btn-sm text-uppercase' onclick='openModal(\"<?= URL_ADMIN ?>/inc/productos/api/atributos/atributosModificar.php?cod=" + data[i]['atribute']['cod'] + "&idioma=" + data[i]['atribute']['idioma'] + "\",\"Modificar " + data[i]['atribute']['value'] + "\")'><i class='fa fa-edit'></i></span><br/>");
                    }
                }
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    function checkCombProducts() {
        $.ajax({
            url: "<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesVer.php?cod=<?= $cod ?>&idioma=<?= $idiomaGet ?>",
            success: function(data) {
                data = JSON.parse(data);
                $('#listComb').html('');
                if (data.length != 0) {
                    for (i = 0; i < data.length; i++) {
                        var texto = "";
                        for (o = 0; o < data[i]["combination"].length; o++) {
                            texto += data[i]["combination"][o]["value"] + " | ";
                        }
                        if (data[i]['detail']) {
                            texto += " <strong>Precio:</strong> $" + data[i]['detail']['precio'] + " <strong>Stock:</strong> " + data[i]['detail']['stock'];
                            if (data[i]['detail']['mayorista'] > 0) {
                                texto += " <strong>Precio Mayorista:</strong> $" + data[i]['detail']['mayorista'];
                            } else {
                                texto += " <strong>Precio Mayorista:</strong> No posee";
                            }
                            $('#listComb').append("<span class='text-uppercase '>" + texto + "</span>");
                            $('#listComb').append(
                                "<span class='mt-10 ml-10 btn btn-warning btn-sm text-uppercase' onclick='openModal(\"<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesModificar.php?cod=" + data[i]['detail']['cod_combinacion'] + "&product=" + data[i]['product'] + "&idioma=<?= $idiomaGet ?>" + "\",\"Modificar " + "\")'><i class='fa fa-edit'></i></span><br/>");
                        }
                    }
                }
            },
            error: function() {
                alert('Error occured');
            }
        });
    }
    checkAttrProducts();
    checkCombProducts();
</script>