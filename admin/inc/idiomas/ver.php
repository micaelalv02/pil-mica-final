<?php
$idiomas = new Clases\Idiomas();
$usuarios = new Clases\Usuarios();

$op = isset($_GET["op"]) ? $funciones->antihack_mysqli($_GET["op"]) : '';
$habilitar = isset($_GET["habilitar"]) ? $funciones->antihack_mysqli($_GET["habilitar"]) : '';
if (!empty($habilitar)) {
    $habilitar = explode("-", $habilitar);
    $cod = $habilitar[1];


    $defaultLang = $idiomas->viewDefault();
    if ($defaultLang["data"]["cod"] != $cod) {
        $idiomas->set("cod", $cod);
        $idiomas->editSingle("habilitado", $habilitar[0]);
        $usuariosData = $usuarios->list(["idioma = '$cod'"], "", "");
        foreach ($usuariosData as $userItem) {
            $usuarios->set("cod", $userItem["data"]["cod"]);
            $usuarios->editSingle("idioma", $defaultLang["data"]["cod"]);
        }
    }
}

?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">Idiomas</h4>
                <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                    <div class="pull-right">
                        <a href="<?= URL_ADMIN ?>/index.php?op=idiomas&accion=duplicar" class="btn btn-danger">
                            <i class="fa fa-clone" aria-hidden="true"></i> DUPLICAR DATOS EN OTROS IDIOMAS
                        </a>
                        <a href="<?= URL_ADMIN ?>/index.php?op=idiomas&accion=agregar" class="btn btn-secondary">
                            AGREGAR IDIOMAS
                        </a>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <hr />

                <table id="users-list-datatable" class="table">
                    <thead>
                        <th></th>
                        <th>
                            Título
                        </th>
                        <th>Textos</th>
                        <th>
                            Predeterminado
                        </th>
                        <th>
                            Habilitado
                        </th>
                        <th>Ajustes</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($idiomas->list('', '', '') as $idioma) { ?>
                            <tr>
                                <td style="text-align: -webkit-center;"><img src="<?= URL_ADMIN ?>/img/idiomas/<?= $idioma["data"]["cod"] ?>.png" width="25" /></td>
                                <td>
                                    <a target="_blank" href="<?= URL_ADMIN ?>/index.php?op=idiomas&accion=ver-json&idioma=<?= $idioma["data"]["cod"] ?>">
                                        <?= mb_strtoupper($idioma['data']["titulo"]) ?>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                        <a target="_blank" href="<?= URL_ADMIN ?>/index.php?op=idiomas&accion=ver-json&idioma=<?= $idioma["data"]["cod"] ?>">
                                            <i class="fa fa-language fs-20" aria-hidden="true"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                                <td class="motrar_web" class="text-center">
                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                        <div class="custom-control custom-switch custom-switch-glow ml-10">
                                            <input name="radio" type="radio" id="default-<?= $idioma['data']["cod"] ?>" class="custom-control-input" <?= $idioma['data']['default'] == 1 ? 'checked' : '' ?> value="1" onchange="changeLabel('<?= $idioma['data']['cod'] ?>','<?= URL ?>')">
                                            <label class="custom-control-label" for="default-<?= $idioma['data']["cod"] ?>"></label>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                        <?php $options =  ($idioma["data"]["habilitado"] == 1) ? ["icon" => "bx-show", "habilitar" => 0, "color" => "primary"] :  ["icon" => "bx-hide", "habilitar" => 1, "color" => "danger"]; ?>
                                        <a data-toggle="tooltip" data-placement="top" title="Habilitar/Deshabilitar" href="<?= URL_ADMIN ?>/index.php?op=idiomas&habilitar=<?= $options["habilitar"] ?>-<?= $idioma["data"]["cod"] ?>">
                                            <span class="badge badge-light-<?= $options["color"] ?>">
                                                <div class="fonticon-wrap">
                                                    <i class="bx <?= $options["icon"] ?> fs-20"></i>
                                                </div>
                                            </span>
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                        <a data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=idiomas&accion=modificar&cod=<?= $idioma["data"]["cod"] ?>">
                                            <span class=" badge badge-light-secondary">
                                                <div class="fonticon-wrap">
                                                    <i class="bx bx-cog fs-20"></i>
                                                </div>
                                            </span>
                                        </a>
                                    <?php } ?>
                                    <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                        <a class="deleteConfirm" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=idiomas&accion=ver&borrar=<?= $idioma["data"]["cod"] ?>">
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
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php
if (isset($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = isset($_GET["borrar"]) ?  $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $idiomas->set("cod", $cod);
    $idiomas->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=idiomas");
}
?>