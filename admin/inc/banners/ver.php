<?php
$banner = new Clases\Banners();
$idiomas = new Clases\Idiomas();


$filter = '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$banners = $banner->list(["idioma" => $idiomaGet], '', '');

?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <h4 class="mt-20 pull-left">Banners</h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success pull-right text-uppercase mt-15" href="<?= URL_ADMIN ?>/index.php?op=banners&accion=agregar&idioma=<?= $idiomaGet ?>">
                                AGREGAR BANNER
                            </a>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <hr />
                        <fieldset class="form-group position-relative has-icon-left mb-20">
                            <input type="search" class="form-control" id="myInput" type="text" placeholder="Buscar..">
                            <div class="form-control-position">
                                <i class="bx bx-search"></i>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr role="row">
                                        <th></th>
                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Título</th>
                                        <th class="hidden-md-down text-center" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Subtitulo</th>
                                        <th class="hidden-md-down text-center" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Link</th>
                                        <th class="hidden-md-down text-center" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Categoria</th>
                                        <th class="hidden-md-down text-center" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Orden</th>
                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Ajustes</th>
                                    </tr>
                                </thead>
                                <ul class="nav nav-tabs">
                                    <?php
                                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                        $url =  URL_ADMIN . "/index.php?op=banners&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                    ?>
                                        <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                    <?php } ?>
                                </ul>
                                <tbody>
                                    <?php
                                    if (is_array($banners)) {
                                        foreach ($banners as $key => $data) {
                                            ($key == 0) ? $idiomaCheck = $data['data']['idioma'] : '';
                                            echo (isset($idiomaCheck) && $idiomaCheck != $data['data']['idioma']) ? "<tr><td></td><td></td><td></td><td></td><td></td></tr>" : '';
                                            $idiomaCheck = $data['data']['idioma'];
                                    ?>
                                            <tr role="row" class="odd">
                                                <td style="padding: 0.5rem 0.5rem;" style="text-align: -webkit-center;">
                                                    <img class="" src="<?= URL_ADMIN ?>/img/idiomas/<?= $data['data']["idioma"] ?>.png" width="25" />
                                                </td>
                                                <td style="padding: 0.5rem 0.5rem;">
                                                    <?php if (isset($data['data']["titulo"])) { ?>
                                                        <?= strtoupper($data['data']["titulo"]) ?>
                                                        <span class="pull-right <?= $data['data']["titulo_on"] == 1 ? "badge badge-light-success"  : "badge badge-light-danger" ?>">
                                                            <?=
                                                            $data['data']["titulo_on"] == 1 ? $titleStatus = "on" :  $titleStatus = "off";
                                                            ?>
                                                        </span>
                                                    <?php } ?>
                                                </td>
                                                <td class="hidden-md-down text-center">
                                                    <?php if (isset($data['data']["subtitulo"])) { ?>
                                                        <span class=" <?= $data['data']["subtitulo_on"] == 1 ? "badge badge-light-success" : "badge badge-light-danger" ?>">
                                                            <?=
                                                            $data['data']["subtitulo_on"] == 1 ? $subtitleStatus = "on" :  $subtitleStatus = "off";
                                                            ?>
                                                        </span>
                                                    <?php } ?>
                                                </td>
                                                <td class="hidden-md-down text-center">
                                                    <?php if (isset($data['data']["link"])) { ?>
                                                        <span class=" <?= $data['data']["link_on"] == 1 ? "badge badge-light-success" : "badge badge-light-danger" ?>">
                                                            <?=
                                                            $data['data']["link_on"] == 1 ? $linkStatus = "on" :  $linkStatus = "off";
                                                            ?>
                                                        </span>
                                                    <?php } ?>
                                                </td>

                                                <td class="hidden-md-down text-center" style="padding: 0.5rem 0.5rem;">
                                                    <?= isset($data['category']['titulo']) ? $data['category']['titulo'] : '' ?>
                                                </td>
                                                <td class="hidden-md-down text-center">
                                                    <?= $data['data']['orden'] ?>
                                                </td>
                                                <td style="padding: 0.5rem 0.5rem;">

                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                            <a data-toggle="tooltip" class="btn btn-default" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=banners&accion=modificar&cod=<?= $data['data']["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-cog fs-20"></i>
                                                                </div>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                            <a class="deleteConfirm btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=banners&accion=ver&borrar=<?= $data['data']["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                                <div class="fonticon-wrap">
                                                                    <i class="bx bx-trash fs-20"></i>
                                                                </div>
                                                            </a>
                                                        <?php } ?>
                                                    </div>

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
    $banner->delete(["cod" => $cod, "idioma" => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=banners&accion=ver&idioma=" . $idiomaGet);
}
?>