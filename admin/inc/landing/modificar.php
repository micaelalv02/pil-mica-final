<?php
$landing = new Clases\Landing();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$borrarImg = isset($_GET["borrarImg"]) ? $funciones->antihack_mysqli($_GET["borrarImg"]) : '';

$landing->set("cod", $cod);
$landingInd = $landing->view();
$imagenes->set("cod", $landingInd['data']["cod"]);
$imagenes->set("link", "landing&accion=modificar");

$data = $categorias->list(["`categorias`.`area` = 'landing'"], '', '',$_SESSION['lang'],false,true);

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $landingInd['data']["cod"];
    $landing->set("cod", $cod);
    $landing->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $landing->set("categoria", isset($_POST["categoria"]) ? $funciones->antihack_mysqli($_POST["categoria"]) : '');
    $landing->set("desarrollo", isset($_POST["desarrollo"]) ? $funciones->antihack_mysqli($_POST["desarrollo"]) : '');
    $landing->set("fecha", isset($_POST["fecha"]) ? $funciones->antihack_mysqli($_POST["fecha"]) : '');
    $landing->set("description", trim(isset($_POST["description"]) ? $funciones->antihack_mysqli($_POST["description"]) : ''));
    $landing->set("keywords", isset($_POST["keywords"]) ? $funciones->antihack_mysqli($_POST["keywords"]) : '');

    // if (isset($_FILES['files'])) {
    //     $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    // }

    $landing->edit();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing");
}
?>

<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Landing
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">

                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">
                        Título:<br />
                        <input type="text" value="<?= $landingInd['data']["titulo"] ?>" name="titulo" required>
                    </label>
                    <label class="col-md-4">
                        Categoría:<br />
                        <select name="categoria">
                            <?php
                            foreach ($data as $categoria) {
                                if ($landingInd['data']["categoria"] == $categoria['data']["cod"]) {
                                    echo "<option value='" . $categoria['data']["cod"] . "' selected>" . $categoria['data']["titulo"] . "</option>";
                                } else {
                                    echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-4">
                        Fecha:<br />
                        <input type="date" name="fecha" value="<?= $landingInd['data']["fecha"] ?>">
                    </label>
                    <div class="clearfix">
                    </div>
                    <label class="col-md-12 mt-10">
                        Desarrollo:<br />
                        <textarea name="desarrollo" class="ckeditorTextarea">
                <?= $landingInd['data']["desarrollo"]; ?>
            </textarea>
                    </label>
                    <div class="clearfix">
                    </div>
                    <label class="col-md-12 mt-10">
                        Palabras claves dividas por ,<br />
                        <input type="text" name="keywords" value="<?= $landingInd['data']["keywords"] ?>">
                    </label>
                    <label class="col-md-12">
                        Descripción breve<br />
                        <textarea name="description"><?= $landingInd['data']["description"] ?></textarea>
                    </label>
                    <br />
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                            if (!empty($landingInd['images'])) {
                                foreach ($landingInd['images'] as $img) {
                            ?>
                                    <div class='col-md-2 mb-20 mt-20'>
                                        <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <a href="<?= URL_ADMIN . '/index.php?op=novedades&accion=modificar&cod=' . $img['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                                    BORRAR IMAGEN
                                                </a>
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                                    <?php
                                                    for ($i = 0; $i <= count($landingInd['images']); $i++) {
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
                    <div class="clearfix">
                    </div>
                    <label class="col-md-12 mt-10">
                        Imágenes:<br />
                        <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                    </label>
                    <div class="clearfix">
                    </div>
                    <br />
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Modificar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>