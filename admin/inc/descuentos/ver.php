<?php
$descuento = new Clases\Descuentos();
$idiomas = new Clases\Idiomas();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$descuentos = $descuento->list(["idioma = '$idiomaGet'"], "id DESC", "");
?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <h4 class="mt-20 pull-left">Descuentos</h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=descuentos&accion=agregar&idioma=<?= $idiomaGet ?>">
                                AGREGAR DESCUENTO
                            </a>
                        <?php } ?>
                        <a class="btn btn-warning pull-right mr-10 mt-15" href="<?= URL_ADMIN ?>/index.php?op=descuentos&accion=detalle">
                            VER DETALLES DE LOS DESCUENTOS
                        </a>
                        <div class="clearfix"></div>
                        <hr />
                        <fieldset class="form-group position-relative has-icon-left mb-20">
                            <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
                            <div class="form-control-position">
                                <i class="bx bx-search"></i>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Título</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Código Descuento</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Monto</th>
                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Ajustes</th>
                                    </tr>
                                </thead>
                                <ul class="nav nav-tabs">
                                    <?php
                                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                        $url =  URL_ADMIN . "/index.php?op=descuentos&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                    ?>
                                        <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                    <?php } ?>
                                </ul>
                                <tbody>
                                    <?php
                                    if (is_array($descuentos)) {
                                        foreach ($descuentos as $descuento_) {
                                            if ($descuento_["data"]["tipo"] == 0) {
                                                $monto = '$' . $descuento_["data"]["monto"];
                                            } elseif ($descuento_["data"]["tipo"] == 1) {
                                                $monto = '%' . $descuento_["data"]["monto"];
                                            }

                                    ?>
                                            <tr role="row" class="odd">
                                                <td> <?= mb_strtoupper($descuento_["data"]["titulo"]) ?> </td>
                                                <td class="hidden-md-down"><?= mb_strtoupper($descuento_["data"]["cod"]) ?> </td>
                                                <td class="hidden-md-down"><?= mb_strtoupper($monto) ?></td>
                                                <td>
                                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                        <a data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=descuentos&accion=modificar&cod=<?= $descuento_["data"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                            <span class=" badge badge-light-secondary">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-cog fs-20"></i>
                                                                </div>
                                                            </span>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                        <a data-toggle="tooltip" class="deleteConfirm" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=descuentos&accion=ver&borrar=<?= $descuento_["data"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                            <span class=" badge badge-light-danger">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-trash fs-20"></i>
                                                                </div>
                                                            </span>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
if (isset($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
    $descuento->set("cod", $cod);
    $descuento->set("idioma", $idiomaGet);
    $descuento->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=descuentos&accion=ver&idioma=$idiomaGet");
}
?>