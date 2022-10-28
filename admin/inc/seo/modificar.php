<?php
$seo = new Clases\Seo();
$imagenes = new Clases\Imagenes();
$f = new Clases\PublicFunction();
$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$seo->set("cod", $cod);
$url = $seo->view();

$imagenes->set("cod", $url['data']["cod"]);
$imagenes->set("link", "seo&accion=modificar");

if (isset($_GET["ordenImg"]) && isset($_GET["idImg"])) {
    $imagenes->set("id", $_GET["idImg"]);
    $imagenes->set("orden", $_GET["ordenImg"]);
    $imagenes->setOrder();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=seo&accion=modificar&cod=$cod&idioma=$idiomaGet");
}

if ($borrarImg != '') {
    $imagenes->delete(['id' => $borrarImg, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=seo&accion=modificar&cod=$cod&idioma=$idiomaGet");
}

if (isset($_POST["modificar"])) {
    $count = 0;
    $cod = $url['data']["cod"];
    $seo->set("cod", $cod);
    $seo->set("url", isset($_POST["url"]) ? $funciones->antihack_mysqli($_POST["url"]) : '');
    $seo->set("title", isset($_POST["title"]) ? $funciones->antihack_mysqli($_POST["title"]) : '');
    $seo->set("description", isset($_POST["description"]) ? $funciones->antihack_mysqli($_POST["description"]) : '');
    $seo->set("keywords", isset($_POST["keywords"]) ? $funciones->antihack_mysqli($_POST["keywords"]) : '');
    $seo->set("idioma", $idiomaGet);
    
    if ($_FILES['files']['size'][0] != 0 && $_FILES['files']['error'][0] != 0) {
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", '', [$idiomaGet]);
    }
    $seo->edit();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=seo&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                SEO
            </h4>
            <hr style="border-style: dashed;">

        </div>
        <div class="card-content">
            <div class="card-body">

                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-8">URL:<br />
                        <input type="text" value="<?= $url['data']["url"] ?>" name="url" required>
                    </label>
                    <label class="col-md-4">Título:<br />
                        <input type="text" value="<?= $url['data']["title"] ?>" name="title">
                    </label>

                    <div class="clearfix"></div>
                    <label class="col-md-12">Palabras claves dividas por ,<br />
                        <input type="text" name="keywords" value="<?= $url['data']["keywords"] ?>">
                    </label>
                    <label class="col-md-12">Descripción<br />
                        <textarea name="description"><?= $url['data']["description"] ?></textarea>
                    </label>
                    <br />
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                            if (!empty($url['images'])) {
                                foreach ($url['images'] as $img) {
                            ?>
                                    <div class='col-md-2 mb-20 mt-20'>
                                        <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <a href="<?= URL_ADMIN . '/index.php?op=seo&accion=modificar&cod=' . $img['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                                    BORRAR IMAGEN
                                                </a>
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                                    <?php
                                                    for ($i = 0; $i <= count($url['images']); $i++) {
                                                        if ($img["orden"] == $i) {
                                                            echo "<option value='$i' selected>$i</option>";
                                                        } else {
                                                            echo "<option value='$i'>$i</option>";
                                                        }
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
                    <label class="col-md-12 mt-10">Imágenes:<br />
                        <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                    </label>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="modificar" value="Modificar parametros SEO" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>