<?php
$estados = new Clases\EstadosPedidos();
$idiomas = new Clases\Idiomas();


$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$data = $estados->list(["id != 0", "idioma = '$idiomaGet'"], '', '');
?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard"> 

                        <h4 class="mt-20 pull-left">Estados de tus pedidos</h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success text-uppercase pull-right mt-15" href="<?= URL_ADMIN ?>/index.php?op=estados-pedidos&accion=modificar&idioma=<?= $idiomaGet ?>">
                            AGREGAR NUEVOS ESTADO
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
                                        <th width="280px" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Título</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Tipo de Estado</th>
                                        <th class="hidden-md-down" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Envio Email</th>
                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Ajustes</th>
                                    </tr>
                                </thead>
                                <ul class="nav nav-tabs">
                                    <?php
                                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                        $url =  URL_ADMIN . "/index.php?op=estados-pedidos&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                    ?>
                                        <a class="nav-link  <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?>" href=" <?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                    <?php } ?>
                                </ul>
                                <tbody>
                                    <?php
                                    if (is_array($data)) {
                                        foreach ($data as $data_) { ?>
                                            <tr role="row" class="odd">
                                                <td><?= mb_strtoupper($data_['data']["titulo"]) ?></td>
                                                <td class="hidden-md-down">
                                                    <?php switch ($data_['data']["estado"]) {
                                                        case 1:
                                                            echo "<span class='badge badge-circle-light-warning'><i class='bx bxs-hourglass'></i> PENDIENTE</span>";
                                                            break;
                                                        case 2:
                                                            echo "<span class='badge badge-circle-light-success'><i class='bx bx-money'></i> APROBADO</span>";
                                                            break;
                                                        case 3:
                                                            echo "<span class='badge badge-circle-light-danger'><i class='bx bx-dislike'></i> RECHAZADO</span>";
                                                            break;
                                                    }  ?>
                                                </td>
                                                <td class="hidden-md-down">
                                                    <span class=" <?= $data_['data']["enviar"] == 1 ? "badge badge-light-success"  : "badge badge-light-danger" ?>">
                                                        <?=
                                                        $data_['data']["enviar"] == 1 ? $enviarStatus = "on" :  $enviarStatus = "off";
                                                        ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                            <a data-toggle="tooltip"  class="btn btn-default " data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=estados-pedidos&accion=modificar&id=<?= $data_['data']["id"] ?>&idioma=<?= $idiomaGet ?>">
                                                                     <div class="fonticon-wrap">
                                                                        <i class="bx bx-cog fs-20"></i>
                                                                    </div>
                                                             <?php } ?>
                                                            <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                                <a data-toggle="tooltip" class="btn btn-danger deleteConfirm" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=estados-pedidos&accion=ver&borrar=<?= $data_['data']["id"] ?>&idioma=<?= $idiomaGet ?>">
                                                                         <div class="fonticon-wrap">
                                                                            <i class="bx bx-trash fs-20"></i>
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
    $idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
    $id = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $estados->set("id", $id);
    $estados->set("idioma", $idiomaGet);
    $estados->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=estados-pedidos&accion=ver&idioma=$idiomaGet");
}
// if (isset($_GET["active"])) {
//     $estados->set("estado", isset($_GET["active"]) ? $funciones->antihack_mysqli($_GET["active"]) : '');
//     $estados->set("id", isset($_GET["id"]) ? $funciones->antihack_mysqli($_GET["id"]) : '');
//     $estados->changeState();
//     $funciones->headerMove(URL_ADMIN . "/index.php?op=estados-pedidos");
// }
?>