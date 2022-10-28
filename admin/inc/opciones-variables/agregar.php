<?php
$funciones = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();
$opcionesPorducto = new Clases\Opciones();
$area = new Clases\Area();
$categoria = new Clases\Categorias();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
$areas = $area->list([], "", "", $idiomaGet);
$categoriasData = $categoria->list(["`categorias`.`area` = 'opciones'"], '', '', $_SESSION['lang'], false, false);
if (isset($_POST["agregar"])) {
    unset($_POST["agregar"]);
    if (isset($_POST["idiomasInput"])) {
        $idiomasInputPost =  $_POST["idiomasInput"];
        $idiomasInputPost[] = $idiomaGet;
    } else {
        $idiomasInputPost = [$idiomaGet];
    }
    unset($_POST["idiomasInput"]);
    $error = false;
    if (!$_POST["titulo"]) $error = true;
    if (!$_POST["tipo"]) $error = true;
    if (!$error) {
        $cod = $funciones->antihack_mysqli($_POST["cod"]);
        $titulo = $funciones->antihack_mysqli($_POST["titulo"]);
        $tipo = $funciones->antihack_mysqli($_POST["tipo"]);
        $area = $funciones->antihack_mysqli($_POST["area"]);
        $categoria = $funciones->antihack_mysqli($_POST["categoria"]);
        if (isset($idiomasInputPost) && !empty($idiomasInputPost) && !empty($area)) {
            foreach ($idiomasInputPost as $idiomasInputItem) {
                $opcionesPorducto->set("cod", $cod);
                $opcionesPorducto->set("titulo", $titulo);
                $opcionesPorducto->set("tipo", $tipo);
                $opcionesPorducto->set("idioma", $idiomasInputItem);
                $opcionesPorducto->set("area", $area);
                $opcionesPorducto->set("categoria", $categoria);
                $opcionesPorducto->add();
            }
        }
        $funciones->headerMove(URL_ADMIN . "/index.php?op=opciones-variables&accion=ver&idioma=$idiomaGet");
    } else {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=opciones-variables&accion=ver&idioma=$idiomaGet&error=create");
    }
}
?>
<div>
    <div class="content-body mt-20">
        <div class="card">
            <div class="card-content">
                <div class=" agregar ">
                    <h4 class="card-title text-uppercase text-center">
                        Agregar Opción
                    </h4>
                    <hr style="border-style: dashed;">
                    <div class="clearfix"></div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" class="row" style="justify-content: center;" enctype="multipart/form-data">
                                <div class="col-md-3">Codigo
                                    <input type="text" name="cod" value="<?= substr(md5(uniqid(rand())), 0, 10) ?>" required>
                                </div>
                                <div class="col-md-5">Título
                                    <input type="text" name="titulo" required>
                                </div>
                                <div class="col-md-4">Tipo
                                    <select name="tipo" required>
                                        <option value="">--- Seleccionar tipo ---</option>
                                        <option value="int">Numérico</option>
                                        <option value="text">Texto</option>
                                        <option value="boolean">Si/No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">Area
                                    <select name="area" required>
                                        <option value="productos">Productos</option>
                                        <?php
                                        if (isset($areas)) {
                                            foreach ($areas as $areaItem) { ?>
                                                <option value="<?= $areaItem['data']['cod'] ?>"><?= $areaItem['data']['titulo'] ?></option>
                                        <?php }
                                        }
                                        ?>
                                        <option value="banners">Banners</option>
                                    </select>
                                </div>
                                <div class="col-md-6">Categoría
                                    <select name="categoria">
                                        <option value=""> </option>
                                        <?php
                                        if (isset($categoriasData)) {
                                            foreach ($categoriasData as $categoriasItem) { ?>
                                                <option value="<?= $categoriasItem['data']['cod'] ?>"><?= $categoriasItem['data']['titulo'] ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php if (count($idiomasData) >= 1) { ?>
                                    <div class="col-md-12">
                                        <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar opción en otros idiomas</div>
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