<?php
$funciones = new Clases\PublicFunction();
$area = new Clases\Area();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$areaSingle = $area->list(["cod = '$cod'"], '', '', $idiomaGet, true);

if (isset($_POST["guardar"])) {
    unset($_POST["guardar"]);
    $array = $funciones->antihackMulti($_POST);
    $area->edit($array, ["cod = '$cod' AND idioma = '$idiomaGet'"]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=area&accion=ver&idioma=$idiomaGet");
}

?>

<div class="content-body mt-20">
    <div class="card">
        <div class="card-content">
            <div class="mt-20">
                <h4 class="card-title text-uppercase text-center">
                    Modificar Áreas
                </h4>
                <hr style="border-style: dashed;">
                <div class="clearfix"></div>

                <form method="post" class="row" style="justify-content: center;" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $areaSingle['data']["idioma"] ?>" name="idioma">
                    <div class="col-md-6">Título
                        <input type="text" value="<?= $areaSingle['data']["titulo"] ?>" name="titulo">
                    </div>
                    <div class="col-md-6">Código
                        <input type="text" name="cod" disabled value="<?= $areaSingle["data"]["cod"] ?>">
                    </div>

                    <div class="col-12 mt-20">
                        <input type="submit" class="btn btn-block btn-primary" name="guardar" value="Modificar" />
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>