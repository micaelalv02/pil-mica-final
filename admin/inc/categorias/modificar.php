<?php
$categorias = new Clases\Categorias();
$imagenes = new Clases\Imagenes();
$area = new Clases\Area();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$data = $categorias->list(["cod = '$cod'"], "", "", $idiomaGet, true);

$imagenes->set("idioma", $idiomaGet);
$areas = $area->list([], "", "", $idiomaGet);

if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=modificar&idioma=$idiomaGet&cod=$cod");
}

if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    $array = $funciones->antihackMulti($_POST);
    $categorias->edit($array, ["cod = '$cod'", "idioma = '$idiomaGet'"]);

    if (!empty($_FILES['files']['name'][0])){
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($array["titulo"]), [$idiomaGet]);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=$idiomaGet");
}


?>
<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Categorías
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                    <label class="col-md-4">Código:<br />
                        <input type="text" value="<?= $data['data']["cod"] ?>" name="cod" disabled required>
                    </label>
                    <label class="col-md-4">Título:<br />
                        <input type="text" value="<?= $data['data']["titulo"] ?>" name="titulo" required>
                    </label>
                    <label class="col-md-4">Área:<br />
                        <select name="area" required>
                            <option value="<?= $data['data']["area"] ?>" selected><?= $data['data']["area"]  ?></option>
                            <option>---------------</option>
                            <?php
                            if (isset($areas)) {
                                foreach ($areas as $areaItem) { ?>
                                    <option value="<?= $areaItem['data']['cod'] ?>"><?= $areaItem['data']['titulo'] ?></option>
                            <?php }
                            }
                            ?>
                            <option value="banners">Banners</option>
                            <option value="productos">Productos</option>
                            <option value="landing">Landing</option>
                            <option value="menu">Menu</option>
                            <option value="opciones">Opciones</option>
                        </select>
                    </label>
                    <label class="col-md-12 mt-10">Descripción:<br />
                        <textarea class="form-control" name="descripcion"><?= $data['data']["descripcion"] ?></textarea>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <?php
                    foreach ($data['images'] as $key => $img) {
                        $img_id = $img['id'];

                    ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url('<?= URL . "/" . $img['ruta'] ?>') no-repeat center center/contain;">
                            </div>
                            <div class="row mt-10">
                                <div class="col-md-6 mt-10">
                                    <a href="<?= URL_ADMIN . '/index.php?op=categorias&accion=modificar&cod=' . $data['data']['cod'] . '&borrarImg=' . $img_id . '&idioma=' . $idiomaGet ?>" class="btn btn-sm pull-left btn-danger">
                                        <div class="fonticon-wrap">
                                            <i class="bx bx-trash fs-20"></i>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6 mt-10">
                                    <select onchange="changeOrderImg('<?= $img_id ?>',$(this).val(),'<?= URL_ADMIN ?>')">
                                        <?php
                                        for ($i = 0; $i < count($data['images']); $i++) {
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
                    ?>
                    <label class="col-md-12">Imágenes:<br />
                        <input type="file" id="file" name="files[]" accept="image/*" multiple />
                    </label>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar Categoría" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>