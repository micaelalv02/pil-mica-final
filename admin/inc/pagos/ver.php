<?php
$pagos = new Clases\Pagos();
$idiomas = new Clases\Idiomas();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$data = $pagos->list(["idioma = '$idiomaGet'"], '', '', $idiomaGet);
?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">

                    <h4 class="mt-20 pull-left">mÉTODOS DE PAGOS</h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=pagos&accion=agregar&idioma=<?= $idiomaGet ?>">
                            AGREGAR MÉTODO DE PAGO
                            </a>
                        <?php } ?>
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
                                        <th width="250px" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Título</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Recargo/Descuento</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Minimo</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Maximo</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Seña</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Usuarios</th>
                                        <th class="text-right" stabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"></th>
                                    </tr>
                                </thead>
                                <ul class="nav nav-tabs">
                                    <?php
                                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                        $url =  URL_ADMIN . "/index.php?op=pagos&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                    ?>
                                        <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                    <?php } ?>
                                </ul>
                                <tbody>
                                    <?php
                                    if (is_array($data)) {
                                        foreach ($data as $data_) { ?>
                                            <tr role="row" class="odd">
                                                <td><?= strtoupper($data_['data']["titulo"]) ?></td>
                                                <?php
                                                if ($data_['data']["monto"] > 0) {
                                                    echo "<td class='hidden-md-down'>" . $data_['data']["monto"] . "% de Recargo</td>";
                                                } elseif ($data_['data']["monto"] < 0) {
                                                    echo "<td class='hidden-md-down'>" . abs($data_['data']["monto"]) . "% de Descuento</td>";
                                                } else {
                                                    echo "<td class='hidden-md-down'>No posee</td>";
                                                }
                                                $minimo = isset($data_['data']["minimo"]) ?  $data_['data']["minimo"] : 0;
                                                $maximo = isset($data_['data']["maximo"]) ?  $data_['data']["maximo"] : 0;
                                                $entrega = (isset($data_['data']['entrega']) && !empty($data_['data']['entrega'])) ? $data_['data']['entrega'] . '%' : "<span style='color: red;'>NO</span>";
                                                ?>
                                                <td class="hidden-md-down">$ <?= $minimo ?></td>
                                                <td class="hidden-md-down">$ <?= $maximo ?></td>
                                                <td class="hidden-md-down"> <?= $entrega ?></td>

                                                <td class="hidden-md-down">
                                                    <?php switch ($data_['data']["tipo_usuario"]) {
                                                        case 1:
                                                            echo "MINORISTA";
                                                            break;
                                                        case 2:
                                                            echo "MAYORISTA";
                                                            break;
                                                        default:
                                                            echo "AMBOS";
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group text-right" role="group" aria-label="Basic example">

                                                        <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                            <?php if ($data_['data']["estado"] == 1) {
                                                            ?>
                                                                <a data-toggle="tooltip" class="btn btn-success" data-placement="top" title="Activo" href="<?= URL_ADMIN . '/index.php?op=pagos&cod=' . $data_['data']['cod'] . '&active=0&idioma=' . $idiomaGet ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="bx bx-show-alt"></i>
                                                                    </div>
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a data-toggle="tooltip" class="btn btn-warning" data-placement="top" title="No activo" href="<?= URL_ADMIN . '/index.php?op=pagos&cod=' . $data_['data']['cod'] . '&active=1&idioma=' . $idiomaGet ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="bx bx-hide"></i>
                                                                    </div>
                                                                </a>
                                                            <?php } ?>
                                                            <a data-toggle="tooltip" class="btn btn-default" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=pagos&accion=modificar&cod=<?= $data_['data']["cod"] . '&idioma=' . $idiomaGet ?>">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-cog"></i>
                                                                </div>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                            <a class="deleteConfirm btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=pagos&accion=ver&borrar=<?= $data_['data']["cod"] . '&idioma=' . $idiomaGet ?>">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-trash"></i>
                                                                </div>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php }
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
    $idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
    $pagos->set("cod", $cod);
    $pagos->set("idioma", $idiomaGet);
    $pagos->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=pagos&accion=ver&idioma=$idiomaGet");
}
if (isset($_GET["active"]) & $_SESSION["admin"]["crud"]["editar"]) {
    $pagos->set("estado", isset($_GET["active"]) ? $funciones->antihack_mysqli($_GET["active"]) : '');
    $pagos->set("cod", isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '');
    $pagos->set("idioma", isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang']);
    $pagos->changeState();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=pagos&accion=ver&idioma=$idiomaGet");
}
?>