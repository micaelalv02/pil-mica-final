<?php
$contenidos = new Clases\Contenidos();
$area = new Clases\Area();
$categoria = new Clases\Categorias();
$funciones = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();

$tituloGet = isset($_GET["title"]) ? $funciones->antihack_mysqli(str_replace("-", " ", $_GET["title"])) : '';
$pagina = isset($_GET["pagina"]) ? $funciones->antihack_mysqli($_GET["pagina"]) : 1;
$getArea = isset($_GET["area"]) ? $funciones->antihack_mysqli($_GET["area"]) : '';
$idioma = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$areaData = $area->list(["cod = '$getArea'"], '', '', $idioma, true);
$categoriasData = $categoria->list(["area = '" . $areaData['data']['titulo'] . "'"], "titulo ASC", "", $idioma);
$limiteContenidos = 15;
if (!empty($tituloGet)) {
    $filterContenidos[] = "contenidos.titulo LIKE '%" . trim($tituloGet) . "%'";
}
$filterContenidos[] = "contenidos.area = '$getArea'";
$data = [
    "filter" => $filterContenidos,
    "category" => true,
    "limit" => ($limiteContenidos * ($pagina - 1)) . "," . $limiteContenidos
];
$contenidoData = $contenidos->list($data, $idioma);
$paginador = $contenidos->paginador((URL_ADMIN . "/index.php?op=contenidos&accion=ver&area=" . $areaData['data']['cod'] . "&idioma=" . $idioma), $filterContenidos, $limiteContenidos, $pagina, 5, false);
?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <h4 class="mt-20 pull-left"><?= $areaData['data']['titulo'] ?></h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=contenidos&accion=agregar&area=<?= $areaData['data']['cod'] ?>&idioma=<?= $idioma ?>">
                                AGREGAR <?= $areaData['data']['titulo'] ?>
                            </a>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <hr />
                        <form method="get">
                            <div class="mb-10">
                                <input name="op" value="contenidos" type="hidden" />
                                <input name="accion" value="ver" type="hidden" />
                                <input name="area" value="<?=$getArea?>" type="hidden" />
                                <input name="pagina" value="<?= $pagina ?>" type="hidden" />
                                <input class="form-control" name="title" type="text" value="<?= $tituloGet ?>" placeholder="Buscar en <?= $getArea ?>.." />
                            </div>
                        </form>
                        <div class="table-responsive">
                            <div id="table-extended-transactions_wrapper" class="dataTables_wrapper no-footer">
                                <table id="table-extended-transactions" class="table mb-0 dataTable no-footer" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Título</th>
                                            <th>Categoria</th>
                                            <th>Destacado</th>
                                            <th>Ajustes</th>
                                        </tr>
                                    </thead>
                                    <div>
                                        <ul class="nav nav-tabs">
                                            <?php
                                            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                                $url =  URL_ADMIN . "/index.php?op=contenidos&accion=ver&area=" . $areaData['data']['cod'] . "&idioma=" . $idioma_["data"]["cod"];
                                            ?>
                                                <a class="nav-link  <?= $idioma_["data"]["cod"] == $idioma ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                                            <?php } ?>
                                        </ul>
                                        <div class="tab-content pl-0">
                                            <tbody>
                                                <?php
                                                if (is_array($contenidoData)) {
                                                    foreach ($contenidoData as $contenido) { ?>
                                                        <input type="text" style="position:absolute;left:-1000px;top:-1000px;" id="link-<?= $contenido['data']['cod'] ?>" value="<?= URL . "/c/" . $contenido['data']['area'] . "/" . $funciones->normalizar_link($contenido['data']['titulo']) . "/" . $contenido['data']['cod'] ?>">
                                                        <tr role="row" class="odd">
                                                            <td style="text-align: -webkit-center;"><img src="<?= URL_ADMIN ?>/img/idiomas/<?= $contenido["data"]["idioma"] ?>.png" width="25" /></td>
                                                            <td>
                                                                <span class="invoice-customer"><?= mb_strtoupper($contenido['data']['titulo']) ?></span>
                                                            </td>
                                                            <td>
                                                                <?= mb_strtoupper($contenido["data"]["categoria_titulo"]) ?>
                                                            </td>
                                                            <td>
                                                                <input name="destacado" type="checkbox" id="destacado-<?= $contenido["data"]["id"] ?>" <?= $contenido['data']['destacado'] == 1 ? 'checked' : '' ?> value="<?= ($contenido['data']['destacado'] == 1) ? 0 : 1 ?> " onchange="changeDestacado('<?= $contenido['data']['id'] ?>','<?= URL ?>')">
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                                    <button onclick="copyLink('<?= $contenido['data']['cod'] ?>')" class="btn btn-warning " data-toggle="tooltip" data-placement="top" title="Copiar url del contenido"><i class="fa fa-link" aria-hidden="true"></i></button>
                                                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                                        <a data-toggle="tooltip" class="btn btn-default" data-placement="top" title="Modificar" href="<?= URL_ADMIN . '/index.php?op=contenidos&accion=modificar&cod=' . $contenido['data']['cod'] . "&area=" . $areaData['data']['cod'] . "&idioma=" . $idioma ?>">
                                                                            <div class="fonticon-wrap">
                                                                                <i class="bx bx-cog fs-20"></i>
                                                                            </div>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                                        <a class="deleteConfirm btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN . '/index.php?op=contenidos&accion=ver&area=' . $areaData['data']['cod'] . '&borrar=' . $contenido['data']['cod'] . '&idioma=' . $idioma ?>">
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
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <?= $paginador ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$borrar = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
if ($borrar != '' && $_SESSION["admin"]["crud"]["eliminar"]) {
    $contenidos->delete(['cod' => $borrar, 'idioma' => $idioma]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=contenidos&accion=ver&area=" . $areaData['data']['cod'] . "&idioma=" . $idioma);
}
?>
<script>
    function copyLink(id) {
        var copyText = document.getElementById("link-" + id);
        copyText.select();
        document.execCommand("copy");
        successMessage("Link copiado: " + copyText.value);
    }
</script>
<script>
    function changeDestacado(id, url) {
        event.preventDefault();
        $.ajax({
            url: url + "/admin/api/contents/edit-destacado.php",
            type: "POST",
            data: {
                id: id,
                destacado: $("#destacado-" + id).val()
            },
            success: function(data) {
                successMessage("¡ Contenido editado !");
            }
        });
    }
</script>