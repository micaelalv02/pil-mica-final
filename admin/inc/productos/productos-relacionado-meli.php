<?php
$producto = new Clases\Productos();
$productoMeli = new Clases\MercadoLibre();
$config = new Clases\Config();
$funciones = new Clases\PublicFunction();
$pagina = isset($_GET["pagina"]) ? $funciones->antihack_mysqli($_GET["pagina"]) : '0';
$filter = array();

if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}

if (@count($_GET) == 0) {
    $anidador = "?";
} else {
    if ($pagina >= 0) {
        $anidador = "&";
    } else {
        $anidador = "?";
    }
}
$data = [
    "filter" => $filter,
    "admin" => false,
    "category" => true,
    "subcategory" => true,
    "images" => true,
    "limit" => (100 * $pagina) . ',' . 100,
];

$productos = $producto->list($data,$_SESSION['lang']);
$productoPaginador = $producto->paginador("", 100);


?>



<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4 class="mt-20 pull-left">Productos Relacionados en MercadoLibre</h4>
        <div class="clearfix"></div>
        <hr />
        <table class="table  table-bordered">
            <thead>
                <th>Codigo</th>
                <th>Tipo</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Producto</th>
                <th>Nombre del producto</th>
                <th></th>
            </thead>
            <tbody>
                <?php
                if (is_array($productos)) {
                    foreach ($productos as $producto__) {
                        $cod = $producto__["data"]["cod"];
                        foreach ($producto->viewProductMeliImport($cod) as $producto_) {
                            if ($producto_['product'] == $producto__['data']['cod']) {
                                $name = "";
                                $producto_['product'] == $producto__['data']['cod'] ? $name = $producto__['data']['titulo'] : '';
                ?>
                                <tr>
                                    <td>
                                        <input class="borderInputBottom" style='width:auto' id='titulo-<?= $cod ?>' name='titulo' value='<?= $producto_["code"] ?>' />
                                    </td>
                                    <td width="200"><input class="borderInputBottom" style='width:auto' value='<?= $producto_["type"] ?>' /></td>
                                    <td width="150">$ <input class="borderInputBottom" style='width:auto' value='<?= $producto_["price"] ?>' /></td>
                                    <td width="150"><input class="borderInputBottom" style='width:auto' value='<?= $producto_["stock"] ?>' /></td>
                                    <td width="150"><input class="borderInputBottom" style='width:auto' value='<?= $producto_["product"] ?>' /></td>
                                    <td width="300"><input class="borderInputBottom" style='width:auto' value='<?= $name ?>' /></td>
                                    <td>
                                        <a class="btn btn-danger deleteConfirm" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN ?>/index.php?op=productos&accion=productos-relacionado-meli&borrar=<?= $producto_["code"] ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                <?php
                            }
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination ">
                <?php
                if ($productoPaginador != 1 && $productoPaginador != 0) {
                    $url_final = $funciones->eliminar_get(CANONICAL, "pagina");
                    $links = '';
                    $links .= "<li class='page-item' ><a class='page-link' href='" . $url_final . $anidador . "pagina=1'>1</a></li>";
                    $i = max(2, $pagina - 5);

                    if ($i > 2) {
                        $links .= "<li class='page-item' ><a class='page-link' href='#'>...</a></li>";
                    }
                    for (; $i <= min($pagina + 35, $productoPaginador); $i++) {
                        $links .= "<li class='page-item' ><a class='page-link' href='" . $url_final . $anidador . "pagina=" . $i . "'>" . $i . "</a></li>";
                    }
                    if ($i - 1 != $productoPaginador) {
                        $links .= "<li class='page-item' ><a class='page-link' href='#'>...</a></li>";
                        $links .= "<li class='page-item' ><a class='page-link' href='" . $url_final . $anidador . "pagina=" . $productoPaginador . "'>" . $productoPaginador . "</a></li>";
                    }
                    echo $links;
                    echo "";
                }
                ?>
            </ul>
        </nav>
    </div>
</div>
<?php
if (isset($_GET["borrar"])) {
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $productoMeli->set("code", $cod);
    $productoMeli->removeMeli();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos&accion=productos-relacionado-meli");
}
?>