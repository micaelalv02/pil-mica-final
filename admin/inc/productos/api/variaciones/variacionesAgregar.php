<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$config = new Clases\Config();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();

$idioma = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : '';
if (isset($_POST['cod'])) {
    var_dump("ENTRE");
    $atributo->set("productoCod", $_POST["cod"]);
    $atributo->set("idioma", $idioma);
    $combinacion->set("codProducto", $_POST["cod"]);
    $combinacion->idioma = $idioma;
    $atributosData = $atributo->list();
    $codCombinacion = substr(md5(uniqid(rand())), 0, 10);
    $codOnlyComb = $combinacion->listByProductCod();
    $array = array();
    foreach ($atributosData as $atributosData_) {
        if (isset($_POST[$atributosData_['atribute']['cod']])) {
            if ($_POST[$atributosData_['atribute']['cod']] != '') {
                array_push($array, $_POST[$atributosData_['atribute']['cod']]);
            }
        }
    }
    asort($array);
    $resultValidate = 0;
    $implodeArray = implode(",", $array);
    for ($i = 0; $i < count($codOnlyComb); $i++) {
        asort($codOnlyComb[$i]["combination"]);
        $implodeCod = implode(",", $codOnlyComb[$i]["combination"][0]);
        if ($implodeArray === $implodeCod) {
            $resultValidate = 1;
        }
    }
    if ($resultValidate === 0) {
        $precio = isset($_POST["precio"]) ? $funciones->antihack_mysqli($_POST["precio"]) : '';
        $stock = isset($_POST["stock"]) ? $funciones->antihack_mysqli($_POST["stock"]) : '';
        $precioMayorista = isset($_POST["precioMayorista"]) ? $funciones->antihack_mysqli($_POST["precioMayorista"]) : '';

        foreach ($atributosData as $atributosData_) {
            if (isset($_POST[$atributosData_['atribute']['cod']])) {
                if ($_POST[$atributosData_['atribute']['cod']] != '') {
                    $combinacion->set("cod", $codCombinacion);
                    $combinacion->set("codSubatributo", isset($_POST[$atributosData_['atribute']['cod']]) ? $funciones->antihack_mysqli($_POST[$atributosData_['atribute']['cod']]) : '');
                    $combinacion->set("codProducto", $_POST['cod']);
                    $combinacion->set("idioma", $idioma);
                    array_push($array, $_POST[$atributosData_['atribute']['cod']]);
                    var_dump($atributosData_);
                    $combinacion->add();
                }
            }
        }
        $detalleCombinacion->set("codCombinacion", $codCombinacion);
        $detalleCombinacion->set("precio", $precio);
        $detalleCombinacion->set("stock", $stock);
        $detalleCombinacion->set("mayorista", $precioMayorista);
        $detalleCombinacion->set("idioma", $idioma);
        $detalleCombinacion->add();
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">Existe la misma opción</div>";
    }
} else {
    $cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
    $atributo->set("productoCod", $cod);
    $atributo->set("idioma", $idioma);
    $atributos = $atributo->list();
?>
    <div id="resultado"></div>
    <div class="row">
        <form method="post" class="col-md-12" id="form-modal" action="<?= "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
            <div class="col-md-12">
                <?php
                foreach ($atributos as $atributos_) {
                    echo $atributos_["atribute"]["value"];
                    echo "<select class='form-control' name='" . $atributos_["atribute"]["cod"] . "' required>";
                    echo "<option value='' selected>--sin elegir--</option>";
                    foreach ($atributos_['atribute']['subatributes'] as $subatributos_) {
                        echo "<option value='" . $subatributos_["cod"] . "'>" . $subatributos_["value"] . "</option>";
                    }
                    echo "</select>";
                }
                ?>
            </div>
            <input type="hidden" name="cod" value="<?= $_GET["cod"] ?>" />
            <div class="col-md-12">
                Precio
                <input type="number" step="any" min="0" class="form-control" name="precio" required>
                Stock
                <input type="number" min="0" class="form-control" name="stock" required>
                Precio Mayorista
                <input type="number" step="any" min="0" class="form-control" name="precioMayorista">
            </div>
            <br>
            <div class="col-md-12">
                <input type="submit" class="btn btn-primary btn-sm" id="guardar" name="agregar-var" value="Guardar Combinación" />
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
                        $("#resultado").html(html);
                        checkCombProducts();
                        // $('#moda-page-ajax').modal('toggle');
                    }
                });
                e.preventDefault();
            });
        });
    </script>
<?php
}
