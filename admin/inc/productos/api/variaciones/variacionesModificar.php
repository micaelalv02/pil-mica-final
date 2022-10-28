<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();

$config = new Clases\Config();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();
$funciones = new Clases\PublicFunction();

$idioma = $funciones->normalizar_link($_GET['idioma']);
if (isset($_POST["cod"])) {
    $cod = isset($_POST['cod']) ? $_POST['cod'] : '';
    $product = isset($_POST['product']) ? $_POST['product'] : '';
    $precio = isset($_POST["precio"]) ? $funciones->antihack_mysqli($_POST["precio"]) : '';
    $stock = isset($_POST["stock"]) ? $funciones->antihack_mysqli($_POST["stock"]) : '';
    $precioMayorista = isset($_POST["precioMayorista"]) ? $funciones->antihack_mysqli($_POST["precioMayorista"]) : '';

    if (!empty($cod) && !empty($product)) {
        $atributo->set("productoCod", $product);
        $atributo->set("idioma", $idioma);
        $atributosData = $atributo->list();

        $combinacion->set("cod", $cod);
        $combinacion->set("idioma", $idioma);
        $combinacion->delete();

        $detalleCombinacion->set("codCombinacion", $cod);
        $detalleCombinacion->set("idioma", $idioma);
        $detalleCombinacion->set("precio", $precio);
        $detalleCombinacion->set("stock", $stock);
        $detalleCombinacion->set("mayorista", $precioMayorista);

        $detalleCombinacion->delete();
        
        foreach ($atributosData as $atributosData_) {
            if (isset($_POST[$atributosData_['atribute']['cod']])) {
                $combinacion->set("cod", $cod);
                $combinacion->set("codSubatributo", isset($_POST[$atributosData_['atribute']['cod']]) ? $funciones->antihack_mysqli($_POST[$atributosData_['atribute']['cod']]) : '');
                $combinacion->set("codProducto", $product);
                $combinacion->set("idioma", $idioma);
                $combinacion->add();
            }
        }
        $detalleCombinacion->add();
        echo "<pre>";
        var_dump($combinacion);
        echo "</pre>";
        echo "---------------------";
        echo "<pre>";
        var_dump($detalleCombinacion);
        echo "</pre>";
        die;
    }
} else {
    $cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
    $producto = isset($_GET["product"]) ? $_GET["product"] : '';
    $atributo->set("productoCod", $producto);
    $atributo->set("idioma", $idioma);
    $atributos = $atributo->list();

    $combinacion->set("cod", $cod);
    $combinacion->idioma = $idioma;
    $combinacionData = $combinacion->listByCod();
    $txtComb = '/';

    foreach ($combinacionData as $key => $combinacionDatum) {
        $txtComb .= $combinacionDatum["cod"] . "/";
    }
?>
    <hr>
    <div id="resultado"></div>
    <div class="row">
        <form method="post" class="col-md-12" id="form-modal" action="<?= "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
            <div class="col-md-12">
                <?php
                foreach ($atributos as $atributos_) {
                    echo ($atributos_["atribute"]["value"]);
                    echo "<select class='form-control' name='" . $atributos_["atribute"]["cod"] . "' required>";
                    echo "<option value='' selected>--sin elegir--</option>";
                    foreach ($atributos_['atribute']['subatributes'] as $subatributos_) {
                        $selected = strpos($txtComb, $subatributos_['cod']) ? "selected" : '';
                        echo "<option value='" . $subatributos_["cod"] . "' $selected>" . $subatributos_["value"] . "</option>";
                    }
                    echo "</select>";
                }
                ?>
            </div>
            <div class="col-md-12" id="atributos"></div>
            <input type="hidden" name="cod" value="<?= $_GET["cod"] ?>" />
            <input type="hidden" name="product" value="<?= $_GET["product"] ?>" />
            <div class="clearfix"></div>
            <?php
            $detalleCombinacion->set("codCombinacion", $cod);
            $detalleCombinacion->set("idioma", $idioma);
            $detalle = $detalleCombinacion->view();
            ?>
            <div class="col-md-12">
                Precio
                <input type="number" step="any" min="0" class="form-control" value="<?= isset($detalle['precio']) ? $detalle['precio'] : '' ?>" name="precio" required>
                Stock
                <input type="number" min="0" class="form-control" value="<?= isset($detalle['stock']) ? $detalle['stock'] : '' ?>" name="stock" required>
                Precio Mayorista
                <input type="number" step="any" min="0" class="form-control" value="<?= isset($detalle['mayorista']) ? $detalle['mayorista'] : '' ?>" name="precioMayorista">
            </div>
            <br>
            <div class="col-md-12">
                <a href="#" onclick="deleteAttr('comb','<?= $cod ?>')" class="pull-left btn btn-sm btn-warning">Borrar Combinación</a>
                <input type="submit" class="btn btn-primary btn-sm pull-right" id="guardar" name="agregar-comb" value="Guardar Combinación" />
            </div>
            <br />
        </form>
    </div>
    <script>
        $(function() {
            $('#form-modal').on('submit', function(e) {
                $.ajax({
                    type: $('#form-modal').attr('method'),
                    url: $('#form-modal').attr('action'),
                    data: $('#form-modal').serialize(),
                    beforeSend: function() {
                        $("#resultado").html("CARGANDO");
                    },
                    success: function(html) {
                        checkCombProducts();
                        // $('#moda-page-ajax').modal('toggle');
                    }
                });
                e.preventDefault();
            });
        });

        function deleteAttr(type, cod) {
            var result = confirm("¿Seguro avanzar con ésta acción?");
            var url = "<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesBorrar.php?" + type + "=" + cod + "&idioma=<?= $idioma ?>";
            if (result) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(html) {
                        checkCombProducts();
                        // $('#moda-page-ajax').modal('toggle');
                    }
                });
            }
        }
    </script>
<?php
}
