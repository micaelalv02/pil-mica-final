<?php
$funciones = new Clases\PublicFunction();
$opcionesPorducto = new Clases\Opciones();
$area = new Clases\Area();
$categoria = new Clases\Categorias();


$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
if (empty($cod) || empty($idiomaGet)) $funciones->headerMove(URL_ADMIN . "/index.php?op=opciones-variables&accion=ver&error=edit");
$opcion = $opcionesPorducto->list($idiomaGet, ["cod = '$cod'"],false,"",true);
$areas = $area->list([], "", "", $idiomaGet);
$categorias = $categoria->list(["`categorias`.`area` = 'opciones'"], '', '', $_SESSION['lang'], false, false);

if (isset($_POST["guardar"])) {
    unset($_POST["guardar"]);
    $array = $funciones->antihackMulti($_POST);
    $opcionesPorducto->set("cod", $cod);
    $opcionesPorducto->set("titulo", $array["titulo"]);
    $opcionesPorducto->set("tipo", $array["tipo"]);
    $opcionesPorducto->set("area", $array["area"]);
    $opcionesPorducto->set("categoria", $array["categoria"]);
    $opcionesPorducto->set("idioma", $idiomaGet);
    $opcionesPorducto->edit();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=opciones-variables&accion=ver&idioma=$idiomaGet");
}
?>

<div class="content-body mt-20">
    <div class="card">
        <div class="card-content">
            <div class="mt-20">
                <h4 class="card-title text-uppercase text-center">
                    Modificar Opción
                </h4>
                <hr style="border-style: dashed;">
                <div class="clearfix"></div>

                <form method="post" class="row" style="justify-content: center;" enctype="multipart/form-data">
                    <div class="col-md-3">Codigo
                        <input type="text" value="<?= $opcion['data']["cod"] ?>" name="titulo" disabled>
                    </div>
                    <div class="col-md-5">Título
                        <input type="text" value="<?= $opcion['data']["titulo"] ?>" name="titulo">
                    </div>
                    <div class="col-md-4">Tipo
                        <select name="tipo" required>
                            <option <?= ($opcion["data"]["tipo"] == "int") ? "selected" : '' ?> value="int">Numérico</option>
                            <option <?= ($opcion["data"]["tipo"] == "text") ? "selected" : '' ?> value="text">Texto</option>
                            <option <?= ($opcion["data"]["tipo"] == "boolean") ? "selected" : '' ?> value="boolean">Si/No</option>
                        </select>
                    </div>
                    <div class="col-md-6">Area
                        <select name="area" required>
                            <?php
                            if (isset($areas)) {
                                foreach ($areas as $areaItem) { ?>
                                    <option <?= ($opcion['data']["area"] == $areaItem["data"]["cod"]) ? "selected" : '' ?> value="<?= $areaItem['data']['cod'] ?>"><?= $areaItem['data']['titulo'] ?></option>
                            <?php }
                            }
                            ?>
                            <option value="banners">Banners</option>
                            <option value="productos">Productos</option>
                        </select>
                    </div>
                    <div class="col-md-6">Categoria
                        <select name="categoria">
                            <option <?= ($opcion['data']["categoria"] == '') ? "selected" : '' ?> value=""> </option>
                            <?php
                            if (isset($categorias)) {
                                foreach ($categorias as $categoriaItem) { ?>
                                    <option <?= ($opcion['data']["categoria"] == $categoriaItem["data"]["cod"]) ? "selected" : '' ?> value="<?= $categoriaItem['data']['cod'] ?>"><?= $categoriaItem['data']['titulo'] ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 mt-20">
                        <input type="submit" class="btn btn-block btn-primary" name="guardar" value="Modificar" />
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>