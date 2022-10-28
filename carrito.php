<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();
$descuento = new Clases\Descuentos();
$checkout = new Clases\Checkout();
#siempre limpiamos el metodo de pago y el metodo de envio
foreach ($_SESSION["carrito"] as $key => $cartItem) {
    if ($cartItem["id"] == "Envio-Seleccion" || $cartItem["id"] == "Metodo-Pago") {
        unset($_SESSION["carrito"][$key]);
    }
}

#Variables GET
$remover = $f->antihack_mysqli(isset($_GET["remover"]));

#Se carga la sesión del usuario
$usuarioData = $usuario->viewSession();

#List de descuentos
$descuentos = $descuento->list("", "", "");

#Se refrescan los descuentos por si se agrego algún producto nuevo
$descuento->refreshCartDescuento($carrito->return(), $usuarioData);

#Se cargan los productos del carrito
$carro = $carrito->return();

#Si existe la variable GET remover, entonces se elimina ese item del carrito
if (!empty($remover)) {
    $carrito->delete($_GET["remover"]);
    $f->headerMove(URL . "/carrito");
}

$type = !empty($_SESSION['usuarios']) ? "USER" : "GUEST";
$cod_user = ($type == "USER") ?  $_SESSION['usuarios']['cod'] : "";
$checkout->initial($type, $cod_user);

#Información de cabecera
$template->set("title", "Carrito de compra | " . TITULO);
$template->set("description", "Mirá tu compra y selecciona las formas de pagos y envios");
$template->set("keywords", "");
$template->themeInit();
?>
<div class="page-title-area pt-150 pb-55 mb-50">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $_SESSION["lang-txt"]["carrito"]["tu_compra"] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION["lang-txt"]["general"]["inicio"] ?></a></li>
                                <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= CANONICAL ?>"><?= $_SESSION["lang-txt"]["carrito"]["tu_compra"] ?></a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="content" class="site-content  mb-50" tabindex="-1">
    <section class="whish-list-section theme1 pb-80">
        <div class="container">
            <cart style="width: 100%;"></cart>
            <div class="row mt-10">
                <div class="col-md-12">
                    <?php
                    if ($descuentos) {
                        if (isset($_POST["btn_codigo"])) {
                            $codigoDescuento = isset($_POST["codigoDescuento"]) ? $f->antihack_mysqli($_POST["codigoDescuento"]) : '';
                            $descuento->set("cod", $codigoDescuento);
                            $descuento->set("idioma", $_SESSION['lang']);
                            $response = $descuento->addCartDescuento($carro, $usuarioData);
                            if ($response['status']['applied']) {
                            } else {
                                echo "<div class='alert alert-danger'>" . $response['status']['error']['errorMsg'] . "</div>";
                            }
                        }
                    }
                    ?>
                    <hr>
                    <form method="post" class="row mt-10">
                        <div class="col-md-4 text-center">
                            <p><b><?= $_SESSION["lang-txt"]["carrito"]["pregunta_descuento"] ?></b></p>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="codigoDescuento" class="form-control" placeholder="<?= $_SESSION["lang-txt"]["carrito"]["codigo_descuento"] ?>">
                            <br class="d-md-none">
                        </div>
                        <div class="col-md-3">
                            <input style="width: 100%" type="submit" value="<?= $_SESSION["lang-txt"]["carrito"]["usar_codigo"] ?>" name="btn_codigo" class="btn btn-primary check-out-btn" />
                        </div>
                    </form>
                    <btn-finish-cart></btn-finish-cart>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
$template->themeEnd();

if (!empty($error)) {
?>
    <script>
        $(document).ready(function() {
            alertSide('<?= $error ?>');
        });
    </script>
<?php
}
?>