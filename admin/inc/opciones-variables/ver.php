<?php
$idiomas = new Clases\Idiomas();
$area = new Clases\Area();
$opciones = new Clases\Opciones();
$idioma = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$areaGet = isset($_GET["area"]) ? $funciones->antihack_mysqli($_GET["area"]) : "";

$areas = $area->list([], "", "", $idioma);

if ($areaGet == "contenidos") $areaFilter = "`opciones`.`area` != ''";
if ($areaGet != "contenidos") {
    $areaFilter = "`opciones`.`area` = '" . $areaGet . "'";
}

if (isset($_GET["error"])) {
    if ($_GET["error"] == "create") {
        echo "<div class='alert alert-danger'>";
        echo "<p>Error al crear la opción</p>";
        echo "</div>";
    }
    if ($_GET["error"] == "edit") {
        echo "<div class='alert alert-danger'>";
        echo "<p>Error al editar la opción</p>";
        echo "</div>";
    }
}
?>
<div id="url-adm" data-url="<?= URL_ADMIN ?>">
    <input type="hidden" id="p_el" value="<?= $_SESSION["admin"]["crud"]["eliminar"] ?>">
    <input type="hidden" id="p_ed" value="<?= $_SESSION["admin"]["crud"]["editar"] ?>">
    <div class="content-body">
        <div class="card">
            <div class="card-content">
                <div class="card-body lista">
                    <h4 class="mt-20 pull-left">Opciones Extras</h4>
                    <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                        <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=opciones-variables&accion=agregar&idioma=<?= $idioma ?>">
                            AGREGAR OPCIONES
                        </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <hr />
                    <div class="row">
                        <div class="col-md-10">
                            <label for="">Buscar</label>
                            <fieldset class="form-group position-relative has-icon-left mb-20">
                                <input type="search" class="form-control" id="myInput" type="text" placeholder="Buscar...">
                                <div class="form-control-position">
                                    <i class="bx bx-search"></i>
                                </div>
                            </fieldset>
                        </div>
                        <input type="hidden" id="idiomaGet" value="<?= $idioma ?>">
                        <div class="col-md-2">
                            <label for="">Filtrar Por Area</label>
                            <select name="area" id="filter-opcionesVariables" onchange="getOpcionesVariables()" required>
                                <option value="todas">Todas</option>
                                <?php
                                if (isset($areas)) {
                                    foreach ($areas as $areaItem) { ?>
                                        <option <?= ($areaGet == $areaItem["data"]["cod"]) ? "selected" : '' ?> value="<?= $areaItem['data']['cod'] ?>"><?= $areaItem['data']['titulo'] ?></option>
                                <?php }
                                }
                                ?>
                                <option <?= ($areaGet == "banners") ? "selected" : '' ?> value="banners">Banners</option>
                                <option <?= ($areaGet == "productos") ? "selected" : '' ?> value="productos">Productos</option>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <div class="table-responsive">
                        <table id="users-list-datatable" class="table">
                            <thead>
                                <tr role="row">
                                    <th>
                                        Título
                                    </th>
                                    <th>
                                        Tipo
                                    </th>
                                    <th>
                                        Area
                                    </th>
                                    <th>
                                        Ajustes
                                    </th>
                                </tr>
                            </thead>
                            <ul class="nav nav-tabs">
                                <?php
                                foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                    $url =  URL_ADMIN . "/index.php?op=opciones-variables&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                ?>
                                    <a class="nav-link <?= $idioma_["data"]["cod"] == $idioma ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                <?php } ?>
                            </ul>
                            <tbody id="opcionesVariablesBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_ADMIN ?>/js/opcionesVariables.js"></script>
<?php
$borrar = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
if ($borrar != '' && $_SESSION["admin"]["crud"]["eliminar"]) {
    $opciones->set("cod", $borrar);
    $opciones->set("idioma", $idioma);
    $opciones->delete();
    #Elimino el area del menu
    $funciones->headerMove(URL_ADMIN . "/index.php?op=opciones-variables&accion=ver&idioma=" . $idioma);
}
?>