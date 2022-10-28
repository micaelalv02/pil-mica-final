<?php
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();
$imagen = new Clases\Imagenes();
$funciones = new Clases\PublicFunction();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$dataSubcategoria = $subcategoria->list(["cod = '$cod'"], '', '', $idiomaGet, true);
$categorias = $categoria->list([], '', '', $idiomaGet);

$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
if ($borrarImg != '') {
    $imagen->delete(['id' => $borrarImg,'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=subcategorias&accion=modificar&cod=$cod&idioma=" . $idiomaGet);
}

if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    $array = $funciones->antihackMulti($_POST);
    $subcategoria->edit($array, ["cod = '$cod'", "idioma = '$idiomaGet'"]);

    if (!empty($_FILES['files']['name'][0])) {
        $imagen->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($array["titulo"]), [$idiomaGet]);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=$idiomaGet");
} 
?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Subcategorías
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">Código:<br />
                        <input type="text" name="cod" value="<?= $cod ?>" required>
                    </label>
                    <label class="col-md-4">
                        Título:<br />
                        <input type="text" name="titulo" value="<?= $dataSubcategoria["data"]["titulo"] ?>" required>
                    </label>
                    <label class="col-md-4">
                        Categoria:<br />
                        <select name="categoria" required>
                            <?php
                            foreach ($categorias as $categoria_) {
                                $selected = ($dataSubcategoria["data"]["categoria"]  == $categoria_["data"]["cod"]) ? "selected" : "";
                                echo "<option value='" . $categoria_["data"]["cod"] . "' $selected>" . mb_strtoupper($categoria_["data"]["area"]) . " -> " . mb_strtoupper($categoria_["data"]["titulo"]) . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <?php
                    if (!empty($dataSubcategoria['image'])) {
                    ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $dataSubcategoria['image']['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <a href="<?= URL_ADMIN . '/index.php?op=subcategorias&accion=modificar&cod=' . $dataSubcategoria['data']['cod'] . '&borrarImg=' . $dataSubcategoria['image']['id'] . '&idioma=' . $idiomaGet ?>" class="btn btn-sm pull-left btn-danger">
                                BORRAR IMAGEN
                            </a>
                            <?php
                            if ($dataSubcategoria['image']["orden"] == 0) {
                            ?>
                                <a href="<?= URL_ADMIN . '/index.php?op=subcategorias&accion=modificar&cod=' . $dataSubcategoria['data']['cod'] . '&ordenImg=' . $dataSubcategoria['image']['cod'] ?>" class="btn btn-sm pull-right btn-warning">
                                    <i class="fa fa-star"></i>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href="#" class="btn btn-sm pull-right btn-success">
                                    <i class="fa fa-star"></i>
                                </a>
                            <?php
                            }
                            ?>
                            <div class="clearfix"></div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <label class="col-md-7">Imagen:<br />
                            <input type="file" id="file" name="files[]" accept="image/*" />
                        </label>
                    <?php
                    }
                    ?>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar Subcategoría" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>