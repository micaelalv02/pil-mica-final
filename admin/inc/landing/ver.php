<?php
$landing = new Clases\Landing();
$contenidos = new Clases\Contenidos();
$funciones = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$landingList = $contenidos->list(["filter" => ["`contenidos`.`area` = 'landing-area'"]], $idiomaGet);

?>

<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">Landing</h4>
                <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                    <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=contenidos&accion=agregar&area=landing-area&idioma=<?= $idiomaGet ?>">
                        AGREGAR Landing
                    </a>
                <?php } ?>
                <a class="btn btn-warning pull-right text-uppercase mt-15 mr-10" href="<?= URL_ADMIN ?>/index.php?op=landing&accion=ver-form">
                    Formularios
                </a>
                <div class="clearfix"></div>
                <hr />
                <fieldset class="form-group position-relative has-icon-left mb-20">
                    <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
                    <div class="form-control-position">
                        <i class="bx bx-search"></i>
                    </div>
                </fieldset>
                <ul class="nav nav-tabs">
                    <?php
                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                        $url =  URL_ADMIN . "/index.php?op=landing&accion=ver&idioma=" . $idioma_["data"]["cod"];
                    ?>
                        <a class="nav-link  <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                    <?php } ?>
                </ul>
                <div class="clearfix"></div>
                <hr />
                <div class="table-responsive">
                    <table id="users-list-datatable" class="table">
                        <thead>
                            <th>
                                Título
                            </th>
                            <th>
                                Link de Campaña
                            </th>
                            <th>
                                Ajustes
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($landingList)) {
                                foreach ($landingList as $landingItem) {
                                    $link = URL . "/landing/" . $funciones->normalizar_link($landingItem['data']["titulo"]) . "/" . $landingItem['data']["cod"];
                            ?>
                                    <tr>
                                        <td>
                                            <?= strtoupper($landingItem['data']["titulo"]) ?>
                                        </td>
                                        <td>
                                            <a href="<?= $link ?>" style="color:#666" target="_blank"><?= $link ?></a>
                                        </td>
                                        <td>
                                            <a style="height: 30px; margin-top:20px; margin-bottom: 20px;" data-toggle="tooltip" data-placement="top" title="Peticiones" href="<?= URL_ADMIN ?>/index.php?op=landing&accion=verSubs&cod=<?= $landingItem['data']["cod"] ?>">
                                                <span class=" badge badge-light-info">
                                                    <div class="fonticon-wrap">
                                                        <i class="bx bx-user-x fs-20"></i>
                                                    </div>
                                                </span>
                                            </a>

                                            <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>

                                                <a style="height: 30px; margin-top:20px; margin-bottom: 20px;" data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL_ADMIN . '/index.php?op=contenidos&accion=modificar&cod=' . $landingItem['data']['cod'] . "&area=landing-area&idioma=" . $idiomaGet ?>">
                                                    <span class=" badge badge-light-warning">
                                                        <div class="fonticon-wrap">
                                                            <i class="bx bx-cog fs-20"></i>
                                                        </div>
                                                    </span>
                                                </a>
                                            <?php } ?>
                                            <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                <a style="height: 30px; margin-top:20px; margin-bottom: 20px;" class="deleteConfirm" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=landing&accion=ver&borrar=<?= $landingItem['data']["cod"] ?>">
                                                    <span class="badge badge-light-danger">
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
        </section>
    </div>
</div>
<?php
$borrar = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
if (isset($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $contenidos->delete(['cod' => $borrar, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing");
}
?>