<?php
$idiomas = new Clases\Idiomas();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);

$form = json_decode(file_get_contents(dirname(__DIR__) . '/landing/campos-form.json', false, stream_context_create($arrContextOptions)), true);
?>

<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">Formularios de landing</h4>
                <a class="btn btn-warning pull-right text-uppercase mt-15 mr-10" href="<?= URL_ADMIN ?>/index.php?op=landing&accion=agregar-form&idioma=<?= $idiomaGet ?>">
                    Agregar
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
                        $url =  URL_ADMIN . "/index.php?op=landing&accion=ver-form&idioma=" . $idioma_["data"]["cod"];
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
                                Ajustes
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($form)) {
                                foreach ($form as $data_) {
                                    if ($data_["idioma"] != $idiomaGet) continue;
                            ?>
                                    <tr>
                                        <td>
                                            <?= strtoupper($data_["titulo"]) ?>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                <a href="<?= URL_ADMIN ?>/index.php?op=landing&accion=modificar-form&cod=<?= $data_["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                    <span class=" badge badge-light-warning">
                                                        <div class="fonticon-wrap">
                                                            <i class="bx bx-cog fs-20"></i>
                                                        </div>
                                                    </span>
                                                </a>
                                            <?php } ?>
                                            <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                <a class="deleteConfirm" href="<?= URL_ADMIN ?>/index.php?op=landing&accion=ver-form&borrar=<?= $data_["cod"] ?>&idioma=<?= $idiomaGet ?>">
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
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    foreach ($form as $key => $value) {
        if ($value["cod"] == $cod && $value["idioma"] == $idiomaGet) {
            unset($form[$key]);
        }
    }
    file_put_contents(dirname(__DIR__) . '/landing/campos-form.json', json_encode($form));
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing&accion=ver-form");
}
?>