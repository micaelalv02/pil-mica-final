<?php
$area = new Clases\Area();
$idiomas = new Clases\Idiomas();
$menu = new Clases\Menu();
$idioma = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$menu->createAreaList($idioma);

$areaData = $area->list([], "", "", $idioma);
?>
<div>
    <div class="content-body">
        <div class="card">
            <div class="card-content">
                <div class="card-body lista">
                    <h4 class="mt-20 pull-left">Áreas</h4>
                    <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                        <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=area&accion=agregar&idioma=<?= $idioma ?>">
                            AGREGAR Áreas
                        </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <hr />
                    <fieldset class="form-group position-relative has-icon-left mb-20">
                        <input type="search" class="form-control" id="myInput" type="text" placeholder="Buscar...">
                        <div class="form-control-position">
                            <i class="bx bx-search"></i>
                        </div>
                    </fieldset>
                    <hr />
                    <div class="table-responsive">
                        <table id="users-list-datatable" class="table">
                            <thead>
                                <tr role="row">
                                    <th>
                                        Título
                                    </th>
                                    <th>
                                        Código
                                    </th>
                                    <th>
                                        Ajustes
                                    </th>
                                </tr>
                            </thead>
                            <ul class="nav nav-tabs">
                                <?php
                                foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                    $url =  URL_ADMIN . "/index.php?op=area&accion=ver&idioma=" . $idioma_["data"]["cod"];
                                ?>
                                    <a class="nav-link <?= $idioma_["data"]["cod"] == $idioma ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                <?php } ?>
                            </ul>
                            <tbody>
                                <?php
                                if (is_array($areaData)) {
                                    foreach ($areaData as $data) { ?>
                                        <tr>
                                            <td width="50%">
                                                <span class="invoice-customer"><?= mb_strtoupper($data['data']['titulo']) ?></span>
                                            </td>
                                            <td width="30%">
                                                <span class="invoice-customer"><?= mb_strtoupper($data['data']['cod']) ?></span>
                                            </td>
                                            <td width="20%">


                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                        <a data-toggle="tooltip" data-placement="top" class="btn btn-default" title="Modificar" href="<?= URL_ADMIN . '/index.php?op=area&accion=modificar&cod=' . $data['data']['cod'] . '&idioma=' . $data['data']['idioma'] ?>">
                                                            <div class="fonticon-wrap">
                                                                <i class="bx bx-cog fs-20"></i>
                                                            </div>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                        <a data-toggle="tooltip" class="deleteConfirm btn btn-danger" data-placement="top" title="Eliminar" href="<?= URL_ADMIN . '/index.php?op=area&accion=ver&borrar=' . $data['data']['cod'] . '&idioma=' . $data['data']['idioma'] ?>">
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
<?php
$borrar = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
if ($borrar != '' && $_SESSION["admin"]["crud"]["eliminar"]) {
    $menu = new Clases\Menu();
    $area->set("cod", $borrar);
    $area->delete();
    #Elimino el area del menu
    $link = "/index.php?op=contenidos&accion=ver&area=$borrar&idioma=$idioma";
    $areaMenu = $menu->list(["area = 'admin'", "link= '$link'"], $idioma, true)[0];
    if (!empty($areaMenu)) {
        $menu->set("id", $areaMenu["id"]);
        $menu->delete();
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=area&accion=ver&idioma=" . $idioma);
}
?>