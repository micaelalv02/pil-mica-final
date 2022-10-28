<?php
$f = new Clases\PublicFunction();
$productos_visitados = new Clases\ProductosVisitados();
$productos =  new Clases\Productos();
$idiomas = new Clases\Idiomas();

$from = isset($_GET["from"]) ? $funciones->antihack_mysqli($_GET["from"]) : '';
$to = isset($_GET["to"]) ? $funciones->antihack_mysqli($_GET["to"]) : '';
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
$fecha = '';
if (!empty($from)) {
    $to_ = !empty($to) ? "'" . $to . "'" : "NOW()";
    $fecha = "AND `productos_visitados`.`fecha` BETWEEN '" . $from . "' AND " . $to_;
}
$productosVisitados = $productos_visitados->getAllData($fecha, $idiomaGet);
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-md-5">
                <h4>
                    Productos Visitados
                </h4>
            </div>
            <form method="get" id="orderForm" name="orderForm" action="<?= CANONICAL ?>">
                <input type="hidden" name="op" value="productos-visitados" />
                <input type="hidden" name="accion" value="ver" />
            </form>
            <div class="col-md-2 pull-right">
                <input type="date" name="from" form="orderForm" value="<?= !empty($from) ? $from : '' ?>" required>
            </div>
            <div class="col-md-2 pull-right">
                <input type="date" name="to" form="orderForm" value="<?= !empty($to) ? $to : '' ?>">
            </div>
            <div class="col-md-3 pull-right">
                <button type="submit" form="orderForm" class="btn btn-info mr-10">BUSCAR</button>
                <button onclick="exportProducts('<?= !empty($from) ? $from : '' ?>','<?= !empty($to) ? $to : '' ?>')" class="btn btn-warning">EXPORTAR</button>
            </div>
        </div>
        <hr />
        <ul class="nav nav-tabs">
            <?php
            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                $url =  URL_ADMIN . "/index.php?op=productos-visitados&accion=ver&idioma=" . $idioma_["data"]["cod"];
            ?>
                <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
            <?php } ?>
        </ul>
        <table class="table">
            <thead>
                <th>Productos</th>
                <th>Usuarios</th>
                <th>Visitas</th>
                <th></th>
            </thead>
            <tbody>
                <?php
                if (!empty($productosVisitados["data"])) {
                    foreach ($productosVisitados["data"] as $key => $productItem) {
                        $data = explode("|", $key);
                        $cod = $data[0];
                        $productData = $productos->list(["filter" => ["productos.cod = '$cod'"]], $idiomaGet, true);
                ?>
                        <tr>
                            <td>
                                <a href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-producto&producto=<?= $key ?>&idioma=<?= $idiomaGet ?>">
                                    <?= $productData["data"]["cod_producto"] . " - " . $productData["data"]["titulo"] ?>
                                </a>
                            </td>
                            <td>
                                <?= $productos_visitados->countBy("usuario_ip", ["producto = '$cod'", "idioma = '$idiomaGet'"], "usuario_ip") ?>
                            </td>
                            <td> <?= count($productItem) ?> </td>
                            <td>
                                <a data-toggle="tooltip" data-placement="top" title="Ver usuarios" class="btn btn-default" href="<?= URL_ADMIN ?>/index.php?op=productos-visitados&accion=detalle-producto&producto=<?= $key ?>&idioma=<?= $idiomaGet ?>">
                                    <div class="fonticon-wrap">
                                        <i style="color:white" class="fa fa-search" aria-hidden="true"></i>
                                    </div>
                                </a>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function exportProducts(from, to) {
        $.ajax({
            url: '<?= URL_ADMIN ?>' + "/api/productos-visitados/exportar.php",
            type: "GET",
            data: {
                from: from,
                to: to,
                idioma: '<?= $idiomaGet ?>'
            },
            success: function(data) {
                data = JSON.parse(data);
                window.location.href = data;
            }
        });
    }
</script>