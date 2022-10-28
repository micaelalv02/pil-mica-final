<?php
$f = new Clases\PublicFunction();
$productos_relacionados = new Clases\ProductosRelacionados();
$productos = new Clases\Productos();
$idiomas = new Clases\Idiomas();
$filter = [];
isset($_GET["busqueda"]) ? $filter[] = 'titulo like "%' . $f->antihack_mysqli($_GET["busqueda"]) . '%"' : '';

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$productos_relacionados_ = $productos_relacionados->listAdmin($filter, $idiomaGet);

?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        <h4 class="mt-20 pull-left">Productos Relacionados</h4>
                        <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                            <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=productos-relacionados&accion=agregar&idioma=<?= $idiomaGet ?>">
                                AGREGAR NUEVA RELACION
                            </a>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <hr />

                        <form method="get">
                            <input name="op" value="productos-relacionados" type="hidden" />
                            <input name="accion" value="ver" type="hidden" />
                            <input name="pagina" value="<?= $pagina ?>" type="hidden" />
                            <input class="form-control" name="busqueda" type="text" placeholder="Buscar.." />
                        </form>
                        <ul class="nav nav-tabs mt-10">
                            <?php
                            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                $url =  URL_ADMIN . "/index.php?op=productos-relacionados&accion=ver&idioma=" . $idioma_["data"]["cod"];
                            ?>
                                <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                            <?php } ?>
                        </ul>
                        <div class="table-responsive">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php
                                        foreach ($productos_relacionados_ as $productos_relacionados__) {
                                            $productos_relacionados_explode = explode(",", $productos_relacionados__['data']['productos_cod']);
                                        ?>
                                            <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th width="300px" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">
                                                            <?= $productos_relacionados__['data']['titulo'] ?>
                                                        </th>
                                                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" >
                                                            Código de Productos
                                                        </th>
                                                        <th  class="text-right" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" >
                                                            <div class="btn-group text-right" role="group" aria-label="Basic example">
                                                                <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                                                    <!-- Modificar -->
                                                                    <a class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL_ADMIN ?>/index.php?op=productos-relacionados&accion=modificar&cod=<?= $productos_relacionados__["data"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                                        <div class="fonticon-wrap">
                                                                            <i class="bx bx-cog fs-12"></i>
                                                                        </div>
                                                                    </a>
                                                                    <!-- Agregar (=Modificar) -->
                                                                    <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Agregar" href="<?= URL_ADMIN ?>/index.php?op=productos-relacionados&accion=modificar&cod=<?= $productos_relacionados__["data"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                                        <div class="fonticon-wrap">
                                                                            <i class="bx bx-plus fs-12"></i>
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                                <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                                                    <!-- Eliminar -->
                                                                    <a class="btn btn-danger deleteConfirm" href="<?= URL_ADMIN ?>/index.php?op=productos-relacionados&accion=ver&borrar=<?= $productos_relacionados__["data"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                                                        <div class="fonticon-wrap">
                                                                            <i class="bx bx-trash fs-12"></i>
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                if (is_array($productos_relacionados_explode)) {
                                                    foreach ($productos_relacionados_explode as $producto_explode) {
                                                        $productos_ = $productos->list(["filter" => ["productos.cod_producto = '$producto_explode'"]], $idiomaGet, true);
                                                        if (!empty($productos_)) {
                                                ?>
                                                            <tbody>
                                                                <tr>
                                                                    <td> <?= mb_strtoupper($productos_["data"]["titulo"]) ?> </td>
                                                                    <td> <?= mb_strtoupper($productos_["data"]["cod_producto"]) ?> </td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </table>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
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
    $productos_relacionados->set("cod", $cod);
    $productos_relacionados->set("idioma", $idiomaGet);
    $productos_relacionados->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos-relacionados&accion=ver&idioma=$idiomaGet");
}
?>