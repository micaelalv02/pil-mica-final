<?php
//
$productos = new Clases\Productos();
$categoria = new Clases\Categorias();
$idiomas = new Clases\Idiomas();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$categoriasData = $categoria->list(["area = 'productos'"], "", "", $idiomaGet);
$cod = substr(md5(uniqid(rand())), 0, 10);

if (isset($_POST["agregar"])) {
    $subcategoria = isset($_POST["subcategoria"]) ? $funciones->antihack_mysqli($_POST["subcategoria"]) : '';
    $producto = $productos->list(["filter" => ["productos.subcategoria = '$subcategoria'"]], $idiomaGet);
    if (!empty($producto)) {
        foreach ($producto as $product_) {
            if ($product_['data']['subcategoria'] == $subcategoria) {
                $productos->set("cod", $product_["data"]['cod']);
                $precio = number_format(($product_["data"]['precio'] * $_POST["porcentaje"] / 100 + $product_["data"]['precio']), 2, ".", "");
                $precio_descuento = number_format(($product_["data"]['precio_descuento'] * $_POST["porcentaje"] / 100 + $product_["data"]['precio_descuento']), 2, ".", "");
                $precio_mayorista = number_format(($product_["data"]['precio_mayorista'] * $_POST["porcentaje"] / 100 + $product_["data"]['precio_mayorista']), 2, ".", "");
                $productos->editSingle('precio', $precio, $idiomaGet);
                $productos->editSingle('precio_descuento', $precio_descuento, $idiomaGet);
                $productos->editSingle('precio_mayorista', $precio_mayorista, $idiomaGet);
            }
        }
    }
    $funciones->headerMove(CANONICAL);
}
?>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        <h4 class="mt-20 pull-left">Descuento o Recargo por Subcategoria</h4>

                        <div class="clearfix"></div>
                        <hr />
                        <ul class="nav nav-tabs mt-10">
                            <?php
                            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                                $url =  URL_ADMIN . "/index.php?op=productos&accion=porcentaje-subcategoria&idioma=" . $idioma_["data"]["cod"];
                            ?>
                                <a class="nav-link <?= CANONICAL == $url ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                            <?php } ?>
                        </ul>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <?php
                                            if (!empty($error)) {
                                            ?>
                                                <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                                            <?php
                                            }
                                            ?>
                                            <form method="post" class="row" enctype="multipart/form-data">
                                                <input type="hidden" name="cod" value="<?= $cod; ?>" />
                                                <label class="col-md-6">
                                                    Subcategoría:<br />
                                                    <select name="subcategoria" required>
                                                        <option value="">-- Sin subcategoría --</option>
                                                        <?php
                                                        foreach ($categoriasData as $categoria) {
                                                        ?>
                                                            <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                                                                <?php foreach ($categoria["subcategories"] as $subcategorias) { ?>
                                                                    <option name="subcategorias" value="<?= $subcategorias["data"]["cod"] ?>"><?= mb_strtoupper($subcategorias["data"]["titulo"]) ?></option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </label>

                                                <label class="col-md-6">Porcentaje:(Ej: 25 o -25)*<br />
                                                    <input data-suffix="%" id="pes" name="porcentaje" required type="number" min="-100" />
                                                    <span style="color: red;">*Descontara o Aumentara el valor final del producto</span>
                                                </label>
                                                <hr>
                                                <div class="col-md-12 mt-20">
                                                    <input type="submit" class="btn btn-primary btn-block" id="guardar" name="agregar" value="Modificar Porcentaje de Subcategoria" />
                                                </div>
                                            </form>
                                        </div>
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