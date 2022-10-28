<?php
$seo = new Clases\Seo();
$idiomas = new Clases\Idiomas();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$urls = $seo->list('', '', '', $idiomaGet);

?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">

                <h4 class="mt-20 pull-left">SEO</h4>
                <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                    <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=seo&accion=agregar&idioma=<?= $idiomaGet ?>">
                        AGREGAR URL
                    </a>
                <?php } ?>
                <div class="clearfix"></div>
                <hr />
                <div class="table-responsive">
                    <table id="users-list-datatable" class="table">
                        <thead>
                            <th>
                                URL
                            </th>
                            <th>
                                Ajustes
                            </th>
                        </thead>
                        <ul class="nav nav-tabs">
                            <?php
                            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                $url =  URL_ADMIN . "/index.php?op=seo&accion=ver&idioma=" . $idioma_["data"]["cod"];
                            ?>
                                <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                            <?php } ?>
                        </ul>
                        <tbody>
                            <?php
                            if (is_array($urls)) {
                                foreach ($urls as $url) { ?>
                                    <tr>
                                        <td>
                                            <?= strtoupper($url['data']["url"]) ?>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                <a data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=seo&accion=modificar&cod=<?= $url['data']["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                    <span class=" badge badge-light-secondary">
                                                        <div class="fonticon-wrap">
                                                            <i class="bx bx-cog fs-20"></i>
                                                        </div>
                                                    </span>
                                                </a>
                                            <?php } ?>
                                            <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                <a class="deleteConfirm ml-6" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=seo&accion=ver&borrar=<?= $url['data']["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                    <span class=" badge badge-light-danger">
                                                        <div class="fonticon-wrap">
                                                            <i class="bx bx-trash fs-20"></i>
                                                        </div>
                                                    </span>
                                                </a>
                                            <?php  } ?>

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
if (isset($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $seo->delete(['cod' => $cod, 'idioma' => $idiomaGet]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=seo&idioma=$idiomaGet");
}
?>