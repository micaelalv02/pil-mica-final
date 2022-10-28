<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();
$config = new Clases\Config();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$f = new Clases\PublicFunction();

//le cambie el nombre porque se pisaba con el post en productos
if (isset($_POST["nombre"])) {
    //quedaron hermosos los require_once, hay que ver como hacerlo bien a esto
    $idioma = isset($_GET['idioma']) ? $f->antihack_mysqli($_GET['idioma']) : '';
    $nombre = isset($_POST["nombre"]) ? $f->antihack_mysqli($_POST["nombre"]) : '';
    $atributos = isset($_POST["atributo"]) ? $_POST["atributo"] : '';

    if (!empty($nombre) && !empty($atributos)) {
        $codAtributo = substr(md5(uniqid(rand())), 0, 10);
        $atributo->set("productoCod", $_POST["cod"]);
        $atributo->set("cod", $codAtributo);
        $atributo->set("value", $nombre);
        $atributo->set("idioma", $idioma);
        if ($atributo->add()) {
            echo "<span class='alert alert-success'>Se subió exitosasmente</span>";
        } else {
            echo "<span class='alert alert-danger'>Hubo un error no se subió exitosasmente</span>";
        }

        foreach ($atributos as $atributos_) {
            $codSubatributo = substr(md5(uniqid(rand())), 0, 10);
            $subatributo->set("cod", $codSubatributo);
            $subatributo->set("codAtributo", $codAtributo);
            $subatributo->set("value", $atributos_);
            $subatributo->set("idioma", $idioma);
            $subatributo->add();
        }
    }
} else {
?>
    <div id="resultado"></div>
    <div class="row">
        <form method="post" class="col-md-12" id="form-modal" action="<?= "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
            <div class="col-md-12">
                Nombre del atributo:
                <input type="text" name="nombre" data-validation="required" />
                <button type="button" class="ml-10 mb-10 btn btn-info" onclick="agregar_atributo('atributos')"> +</button>
            </div>
            <div class="col-md-12" id="atributos"></div>
            <input type="hidden" name="cod" value="<?= $_GET["cod"] ?>" />
            <div class="col-md-12">
                <input type="submit" class="btn btn-primary btn-sm" id="guardar" name="agregar-atri" value="Guardar Atributos" />
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
                        $('#moda-page-ajax').modal('toggle');
                        checkAttrProducts();
                    }
                });
                e.preventDefault();
            });
        });
    </script>
<?php
}
?>