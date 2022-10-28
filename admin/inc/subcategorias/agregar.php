<?php
$categorias = new Clases\Categorias();
$imagen = new Clases\Imagenes();
$subcategorias = new Clases\Subcategorias();
$funciones = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
$data_categorias = $categorias->list([], '', '', $idiomaGet);



$cod = substr(md5(uniqid(rand())), 0, 10);

if (isset($_POST["agregar"])) {
    unset($_POST["agregar"]);
    if (isset($_POST["idiomasInput"])) {
        $idiomasInputPost =  $_POST["idiomasInput"];
        $idiomasInputPost[] = $idiomaGet;
    } else {
        $idiomasInputPost = [$idiomaGet];
    }
    unset($_POST["idiomasInput"]);
    $cod = $funciones->antihack_mysqli($_POST["cod"]);
    $array = $funciones->antihackMulti($_POST);
    if (isset($idiomasInputPost) && !empty($idiomasInputPost)) {
        foreach ($idiomasInputPost as $idiomasInputItem) {
            $array["idioma"] = $idiomasInputItem;
            $subcategorias->add($array);
        }
    }
    if (!empty($_FILES['files']['name'][0])) {
        $imagen->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($array["titulo"]), $idiomasInputPost);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accionaccion=ver&idioma=$idiomaGet");
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
                    <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                    <label class="col-md-4">Código:<br />
                        <input type="text" name="cod" value="<?= $cod ?>" required>
                    </label>
                    <label class="col-md-4">
                        Título:<br />
                        <input type="text" name="titulo" required>
                    </label>
                    <label class="col-md-4">
                        Categoria:<br />
                        <select name="categoria">
                            <?php
                            foreach ($data_categorias as $categoria_) {
                                echo "<option value='" . $categoria_["data"]["cod"] . "'>" . mb_strtoupper($categoria_["data"]["area"]) . " -> " . mb_strtoupper($categoria_["data"]["titulo"]) . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-7">Imagen:<br />
                        <input type="file" id="file" name="files[]" accept="image/*" />
                    </label>
                    <?php if (count($idiomasData) >= 1) { ?>
                        <div class="col-md-12">
                            <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar categoria en otros idiomas</div>
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
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Subcategoría" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#idiomasCheckBox').hide();
</script>