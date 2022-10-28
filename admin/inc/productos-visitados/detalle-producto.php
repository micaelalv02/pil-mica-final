<?php
$f = new Clases\PublicFunction();
$productos_visitados = new Clases\ProductosVisitados();
$productos =  new Clases\Productos();

$producto = isset($_GET["producto"]) ? $funciones->antihack_mysqli($_GET["producto"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
$productosVisitados = $productos_visitados->getAllData('', $idiomaGet);
$cod = explode("|", $producto)[0];
$productData = $productos->list(["filter" => ["productos.cod = '$cod'"]], $idiomaGet, true);
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            <?= $productData["data"]["cod_producto"] . " - " . $productData["data"]["titulo"] ?>
        </h4>
        <hr />
        <table class="table">
            <thead>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha de visita</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($productosVisitados["data"][$producto] as $key => $productItem) { ?>
                    <tr>
                        <td>
                            <a href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-usuario&usuario=<?= $productItem["usuario"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                <?= $productItem["usuario"]["nombre"] ?> <?= $productItem["usuario"]["apellido"] ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-usuario&usuario=<?= $productItem["usuario"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                <?= $productItem["usuario"]["email"] ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-usuario&usuario=<?= $productItem["usuario"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                <?= $productItem["usuario"]["visita_producto"] ?>
                            </a>
                        </td>
                        <td>
                            <a data-toggle="tooltip" data-placement="top" title="Productos visitados" class="btn btn-default" href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-usuario&usuario=<?= $productItem["usuario"]["cod"] ?>&idioma=<?= $idiomaGet ?>">
                                <div class="fonticon-wrap">
                                    <i style="color:white" class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>