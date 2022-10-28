<?php
$categorias = new Clases\Categorias();
$banners = new Clases\Banners();
$imagenes = new Clases\Imagenes();
$idiomas = new Clases\Idiomas();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$slide = $banners->list(["cod" => $cod, "idioma" => $idiomaGet], "", "", true);

$data = $categorias->list(["area = 'banners'"], "titulo ASC", '', $idiomaGet);

if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=banners&accion=modificar&cod=$cod&idioma=$idiomaGet");
}
if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    if (!isset($_POST["titulo_on"])) $_POST["titulo_on"] = "0";
    if (!isset($_POST["subtitulo_on"])) $_POST["subtitulo_on"] = "0";
    if (!isset($_POST["link_on"])) $_POST["link_on"] = "0";
    $array = $funciones->antihackMulti($_POST);
    $banners->edit($array, ["cod = '$cod'", "idioma = '$idiomaGet'"]);

    if (!empty($_FILES['files']['name'][0])){
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($array["titulo"])), [$idiomaGet]);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=banners&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Modificar banners
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">
                        Título (mostrar <input type="checkbox" name="titulo_on" value="1" <?php if ($slide['data']['titulo_on']) {
                                                                                                echo "checked";
                                                                                            } ?>>):<br />
                        <input type="text" value="<?= $slide['data']["titulo"] ?>" name="titulo">
                    </label>
                    <label class="col-md-4">
                        Subtitulo (mostrar <input type="checkbox" name="subtitulo_on" id="chsub" value="1" <?php if ($slide['data']['subtitulo_on']) {
                                                                                                                echo "checked";
                                                                                                            } ?>>):<br />
                        <input type="text" id="sub" value="<?= $slide['data']["subtitulo"] ?>" name="subtitulo">
                    </label>
                    <label class="col-md-2">Categoría:<br />
                        <select name="categoria">
                            <?php
                            foreach ($data as $categoria) {
                                if ($slide['data']["categoria"] == $categoria['data']["cod"]) {
                                    echo "<option value='" . $categoria['data']["cod"] . "' selected>" . $categoria['data']["titulo"] . "</option>";
                                } else {
                                    echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-2">Idioma:<br />
                        <select name="idioma">
                            <?php
                            foreach ($idiomas->list('', '', '') as $idioma) { ?>
                                <option value="<?= $idioma['data']['cod'] ?>" <?= ($idioma['data']['cod'] == $slide['data']['idioma']) ? "selected" : '' ?>><?= $idioma['data']['titulo'] ?></option>
                            <?php }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-10 mt-10">
                        Link mostrar(<input type="checkbox" id="chli" name="link_on" value="1" <?php if ($slide['data']['link_on']) {
                                                                                                    echo "checked";
                                                                                                } ?>>):<br />
                        <input type="text" id="link" value="<?= $slide['data']["link"] ?>" name="link">
                    </label>
                    <label class="col-md-2 mt-10">
                        Orden:<br />
                        <input type="number" min="1" id="orden" value="<?= $slide['data']["orden"] ?>" name="orden">
                    </label>
                    <br />
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">

                        <div class="row">
                            <?php
                            if (!empty($slide['image'])) {                            ?>
                                <div class='col-md-2 mb-20 mt-20'>
                                    <div style="height:200px;background:url(<?= '../' . $slide['image']['ruta']; ?>) no-repeat center center/contain;">
                                    </div>
                                    <div class="row mt-10">
                                        <a href="<?= URL_ADMIN . '/index.php?op=banners&accion=modificar&cod=' . $slide['image']['cod'] . '&borrarImg=' . $slide['image']['id'] . '&idioma=' . $idiomaGet ?>" class="btn btn-sm btn-block btn-danger">
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
                                <label class="col-md-7">Imágen:<br />
                                    <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                                </label>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="clearfix">
                    </div>
                    <br />
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar Banner" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    setInterval(checkSliderProps, 1000);
</script>