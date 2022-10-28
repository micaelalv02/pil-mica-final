<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$carrito = new Clases\Carrito();
$usuario = new Clases\Usuarios();

#Variables GET
$op = isset($_GET["op"]) ? $f->antihack_mysqli($_GET["op"]) : '';
$logout = isset($_GET["logout"]) ? true : false;
#Se carga la sesión del usuario
$usuarioSesion = $usuario->viewSession();

#Si no existe una sesión se redirige a usuarios
empty($usuarioSesion) ? $f->headerMove(URL . '/usuarios') : null;

#Si existe una sesión, pero es invitado, se sale de la cuenta y se redirige a usuarios
if ($usuarioSesion['invitado'] == 1) {
    $usuario->logout();
    $f->headerMove(URL . '/usuarios');
}

#Si se encuentra la variable Get logout, se elimina el checkout y la sesión y se redirige a usuarios
if ($logout) {
    $checkout->destroy();
    $usuario->logout();
    $f->headerMove(URL . '/usuarios');
}

#Se busca pedidos y cuenta en la URL para ponerle el atributo active al boton
$pedidos = $f->antihack_mysqli(strpos($_SERVER['REQUEST_URI'], "pedidos"));
$cuenta = $f->antihack_mysqli(strpos($_SERVER['REQUEST_URI'], "cuenta"));
$carritos = $f->antihack_mysqli(strpos($_SERVER['REQUEST_URI'], "carritos"));
$favoritos = $f->antihack_mysqli(strpos($_SERVER['REQUEST_URI'], "favoritos"));
if ($pedidos == "" && $cuenta == "" && $favoritos == "" && $carritos == "") {
    $pedidos = "ok";
}

#Información de cabecera
$template->set("title", $_SESSION["lang-txt"]["sesion"]["title"] . " | " . TITULO);
$template->themeInit();
?>
<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $_SESSION["lang-txt"]["general"]["sesion"] ?> > <?= isset($op) ? $op : "" ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION["lang-txt"]["general"]["inicio"] ?></a></li>
                                <li class="breadcrumb-item position-relative active text-capitalize" aria-current="page"><a href="<?= CANONICAL ?>"><?= $_SESSION["lang-txt"]["general"]["sesion"] ?> > <?= isset($op) ? $op : "" ?></a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="content" class="site-content mt-50 mb-50" tabindex="-1">
    <!--My Account section start-->
    <div class="my-account-section section  pb-100 pb-lg-80 pb-md-70 pb-sm-60 pb-xs-50">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12 ">
                            <a style="border-radius:0!important" href="<?= URL ?>/productos" style="min-height: 100px" class="blanco  text-uppercase fs-18 btn btn-custom-sesion btn-lg d-block">
                                <i class="fa blanco fa-shopping-cart mb-10 d-block pt-10 "></i>
                                <span class="pb-10"><?= $_SESSION["lang-txt"]["sesion"]["ir_comprar"] ?></span>
                            </a>
                        </div>
                        <div class="col-md-12 ">
                            <a style="border-radius:0!important" href="<?= URL ?>/sesion/favoritos" style="min-height: 100px" class="blanco  text-uppercase fs-18 btn btn-custom-sesion<?= $favoritos ? '-active' : '' ?> btn-lg d-block ">
                                <i class="fa blanco fa-heart mb-10 d-block pt-10 "></i>
                                <span class="pb-10"><?= $_SESSION["lang-txt"]["sesion"]["mis_favoritos"] ?></span>
                            </a>
                        </div>
                        <div class="col-md-12 ">
                            <a style="border-radius:0!important" href="<?= URL ?>/sesion/pedidos" style="min-height: 100px" class="blanco  text-uppercase fs-18 btn btn-custom-sesion<?= $pedidos ? '-active' : '' ?> btn-lg d-block ">
                                <i class="fa blanco fa-list mb-10 d-block pt-10 "></i>
                                <span class="pb-10"><?= $_SESSION["lang-txt"]["sesion"]["mis_compras"] ?></span>
                            </a>
                        </div>

                        <div class="col-md-12 ">
                            <a style="border-radius:0!important" href="<?= URL ?>/sesion/cuenta" style="min-height: 100px" class="blanco  text-uppercase fs-18 btn btn-custom-sesion<?= $cuenta ? '-active' : '' ?> btn-lg d-block ">
                                <i class="fa blanco fa-edit mb-10 d-block pt-10 "></i>
                                <span class="pb-10"><?= $_SESSION["lang-txt"]["sesion"]["mis_datos"] ?></span>
                            </a>
                        </div>
                        <div class="col-md-12 ">
                            <a style="border-radius:0!important" href="<?= URL ?>/sesion/carritos" style="min-height: 100px" class="blanco  text-uppercase fs-18 btn btn-custom-sesion<?= $carritos ? '-active' : '' ?> btn-lg d-block ">
                                <i class="fa blanco fa-save mb-10 d-block pt-10 "></i>
                                <span class="pb-10"><?= $_SESSION["lang-txt"]["sesion"]["ver_carritos"] ?></span>
                            </a>
                        </div>
                        <div class="col-md-12 ">
                            <a style="border-radius:0!important" href="<?= URL ?>/sesion?logout" style="min-height: 100px" class="blanco  text-uppercase fs-18 btn btn-custom-sesion btn-lg d-block">
                                <i class="fas blanco fa-sign-out-alt mt-10 d-block pt-10 "></i>
                                <span class="pb-10"><?= $_SESSION["lang-txt"]["sesion"]["salir"] ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <?php
                    $op = isset($_GET["op"]) ? $_GET["op"] : 'pedidos';
                    if ($op != '') {
                        include("assets/inc/sesion/" . $op . ".php");
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $template->themeEnd(); ?>

<script>
      getDataFavoritesSesion();

</script>