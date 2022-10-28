<?php
$funciones = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();
$area = new Clases\Area();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
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
    $cod = $funciones->antihack_mysqli($funciones->normalizar_link($_POST["cod"]));
    $array = $funciones->antihackMulti($_POST);
    if (isset($idiomasInputPost) && !empty($idiomasInputPost)) {
        foreach ($idiomasInputPost as $idiomasInputItem) {
            $array["idioma"] = $idiomasInputItem;
            $area->add($array);
        }
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=area&accion=ver&idioma=$idiomaGet");
}



?>
<div>
    <div class="content-body mt-20">
        <div class="card">
            <div class="card-content">
                <div class=" agregar ">
                    <h4 class="card-title text-uppercase text-center">
                        Agregar Áreas
                    </h4>
                    <hr style="border-style: dashed;">
                    <div class="clearfix"></div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" class="row" style="justify-content: center;" enctype="multipart/form-data">
                                <div class="col-md-6">Título *
                                    <input type="text" name="titulo" required>
                                </div>
                                <div class="col-md-6">Código
                                    <input type="text" name="cod">
                                </div>
                                <?php if (count($idiomasData) >= 1) { ?>
                                    <div class="col-md-12">
                                        <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar area en otros idiomas</div>
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
                                <div class="col-12 mt-20">
                                    <input type="submit" class="btn btn-block btn-primary " name="agregar" value="Crear" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#idiomasCheckBox').hide();
</script>