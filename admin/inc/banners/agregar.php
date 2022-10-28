<?php
$banners  = new Clases\Banners();
$imagen = new Clases\Imagenes();
$idiomas = new Clases\Idiomas();
$categorias = new Clases\Categorias();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$data = $categorias->list(["area = 'banners'"], '', '', $idiomaGet);
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
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
            $banners->add($array);
        }
    }

    if (!empty($_FILES['files']['name'][0])) {
        $imagen->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($array["titulo"])), $idiomasInputPost);
    }


    $funciones->headerMove(URL_ADMIN . "/index.php?op=banners&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Subir banners
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">

                <form method="post" class="row" enctype="multipart/form-data">
                    <input type="hidden" name="fecha" value="<?= date("Y-m-d"); ?>">
                    <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                    <label class="col-md-4">Título (mostrar <input type="checkbox" name="titulo_on" value="1">):<br />
                        <input type="text" name="titulo" required>
                    </label>
                    <label class="col-md-4">Subtitulo (mostrar <input type="checkbox" id="chsub" name="subtitulo_on" value="1">):<br />
                        <input type="text" id="sub" name="subtitulo">
                    </label>
                    <label class="col-md-2">Categoría:<br />
                        <select name="categoria" required>
                            <?php
                            foreach ($data as $categoria) {
                                echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-2">Código<br />
                        <input type="text" name="cod" value="<?= substr(md5(uniqid(rand())), 0, 10) ?>" required>
                    </label>
                    <label class=" col-md-10">Link mostrar(<input type="checkbox" id="chli" name="link_on" value="1">):<br />
                        <input type="text" id="link" name="link">
                    </label>
                    <label class="col-md-2 mt-10">
                        Orden:<br />
                        <input type="number" id="orden" min='1' value="1" name="orden">
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-7">Imágen:<br />
                        <input type="file" id="file" name="files[]" accept="image/*" required />
                    </label>
                    <br />
                    <?php if (count($idiomasData) >= 1) { ?>
                        <div class="col-md-12">
                            <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar banner en otros idiomas</div>
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
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Banner">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    setInterval(D, 1000);
    $('#idiomasCheckBox').hide();

    function D() {
        if ($('#chsub').prop('checked')) {
            $('#sub').attr('required', true);
        } else {
            $('#sub').attr('required', false);
        }
        if ($('#chli').prop('checked')) {
            $('#link').attr('required', true);
        } else {
            $('#link').attr('required', false);
        }
    }
</script>