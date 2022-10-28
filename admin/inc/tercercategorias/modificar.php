<?php
$categorias = new Clases\Categorias();
$tercercategorias = new Clases\Tercercategorias();
$imagen = new Clases\Imagenes();
$funciones = new Clases\PublicFunction();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$dataTercercategoria = $tercercategorias->list(["cod = '$cod'"], '', '', $idiomaGet, true);
$categorias = $categorias->list(["area= 'productos'"], '', '', $idiomaGet);

$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
if ($borrarImg != '') {
    $imagen->delete(['id' => $borrarImg,'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=tercercategorias&accion=modificar&cod=$cod&idioma=" . $idiomaGet);
}

if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    $array = $funciones->antihackMulti($_POST);
    $tercercategorias->edit($array, ["cod = '$cod'", "idioma = '$idiomaGet'"]);

    if (!empty($_FILES['files']['name'][0])) {
        $imagen->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($array["titulo"]), [$idiomaGet]);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=$idiomaGet");
} ?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Tercercategorías
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                    <input type="hidden" name="orden" value="0">
                    <input type="hidden" name="descripcion" value=" ">
                    <label class="col-md-4">Código:<br />
                        <input type="text" name="cod" value="<?= $cod ?>" required>
                    </label>
                    <label class="col-md-4">
                        Título:<br />
                        <input type="text" name="titulo" value="<?= $dataTercercategoria["data"]["titulo"] ?>" required>
                    </label>      
                    <label class="col-md-4">
                        Subcategoria:<br />
                        <select name="subcategoria" required>
                            <?php 
                            foreach ($categorias as $cat) {
                                foreach ($cat['subcategories'] as $sub) {      
                                    $selected = ($dataTercercategoria["data"]["subcategoria"] == $sub["data"]["cod"]) ? "selected" : "";
                                    ?>
                                    <option value="<?= $sub["data"]["cod"]?>" <?= $selected ?> ><?= mb_strtoupper($cat["data"]["titulo"]) ?> -> <?= mb_strtoupper($sub["data"]["titulo"]) ?></option>
                                <?php }
                            }
                            ?>
                        </select>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <?php
                    if (!empty($dataTercercategoria['image'])) {
                    ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $dataTercercategoria['image']['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <a href="<?= URL_ADMIN . '/index.php?op=tercercategorias&accion=modificar&cod=' . $dataTercercategoria['data']['cod'] . '&borrarImg=' . $dataTercercategoria['image']['id'] . '&idioma=' . $idiomaGet ?>" class="btn btn-sm pull-left btn-danger">
                                BORRAR IMAGEN
                            </a>
                            <?php
                            if ($dataTercercategoria['image']["orden"] == 0) {
                            ?>
                                <a href="<?= URL_ADMIN . '/index.php?op=tercercategorias&accion=modificar&cod=' . $dataTercercategoria['data']['cod'] . '&ordenImg=' . $dataTercercategoria['image']['cod'] ?>" class="btn btn-sm pull-right btn-warning">
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
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar Tercercategoría" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>