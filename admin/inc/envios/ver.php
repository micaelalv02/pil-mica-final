<?php
$envios = new Clases\Envios();
$idiomas = new Clases\Idiomas();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$data = $envios->list(["idioma = '$idiomaGet'"], "", "", $idiomaGet);
?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                    <h4 class="mt-20 pull-left">mÉTODOS DE ENVÍOS</h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=envios&accion=agregar&idioma=<?= $idiomaGet ?>">
                            AGREGAR MÉTODOS DE ENVIO
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
                        <div class="clearfix"></div>
                        <hr />
                        <div class="table-responsive">
                            <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr role="row">
                                        <th width="280px" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Título</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Peso</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Precio</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Limite</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Usuarios</th>
                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Ajustes</th>
                                    </tr>
                                </thead>
                                <ul class="nav nav-tabs">
                                    <?php
                                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                        $url =  URL_ADMIN . "/index.php?op=envios&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                    ?>
                                        <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?>" href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                    <?php } ?>
                                </ul>
                                <tbody>
                                    <?php
                                    if (is_array($data)) {
                                        foreach ($data as $data_) { ?>
                                            <tr role="row" class="odd">
                                                <td><?= strtoupper($data_['data']["titulo"]) ?></td>
                                                <td class="hidden-md-down"><?= strtoupper($data_['data']["peso"]) ?> kg</td>
                                                <td class="hidden-md-down">$<?= strtoupper($data_['data']["precio"]) ?></td>
                                                <td class="hidden-md-down">$<?= strtoupper($data_['data']["limite"]) ?></td>
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
                                                    }  ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group text-right" role="group" aria-label="Basic example">

                                                        <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                            <?php if ($data_['data']["estado"] == 1) { ?>
                                                                <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Activo" href="<?= URL_ADMIN ?>/index.php?op=envios&cod=<?= $data_['data']['cod'] ?>&active=0&idioma=<?= $idiomaGet ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="bx bx-show-alt"></i>
                                                                    </div>
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="No activo" href="<?= URL_ADMIN ?>/index.php?op=envios&cod=<?= $data_['data']['cod'] ?>&active=1&idioma=<?= $idiomaGet ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="bx bx-hide"></i>
                                                                    </div>
                                                                </a>
                                                            <?php
                                                            } ?>
                                                            <a data-toggle="tooltip" class="btn btn-default" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=envios&accion=modificar&cod=<?= $data_['data']["cod"] ?>&idioma=&idioma=<?= $idiomaGet ?>">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-cog"></i>
                                                                </div>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                            <a class="deleteConfirm btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=envios&accion=ver&borrar=<?= $data_['data']["cod"] ?>&idioma=&idioma=<?= $idiomaGet ?>">
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
    $idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
    $envios->set("cod", $cod);
    $envios->set("idioma", $idiomaGet);
    $envios->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios&accion=ver&idioma=$idiomaGet");
}
if (isset($_GET["active"]) && $_SESSION["admin"]["crud"]["editar"]) {
    $idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
    $envios->set("estado", isset($_GET["active"]) ? $funciones->antihack_mysqli($_GET["active"]) : '');
    $envios->set("cod", isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '');
    $envios->set("idioma", $idiomaGet);
    $envios->changeState();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios&accion=ver&idioma=$idiomaGet");
}
?>