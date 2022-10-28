<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$enviar = new Clases\Email();
$config = new Clases\Config();


#Se carga la configuración de email
$dataEmail = $config->viewEmail();

#Información de cabecera
$template->set("title", "Gracias por completar el formulario | " . TITULO);
$template->themeInit();
?>

<div id="content" class="site-content mt-100 mb-50" tabindex="-1">
    <div class="container">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <article class="has-post-thumbnail hentry">
                    <div id="sns_content" class="wrap">
                        <div class="container text-center">
                            <h1>¡GRACIAS POR CONTACTARTE CON NOSOTROS!</h1>
                            <i class="fa fa-check-circle fs-50" style="font-size:80px !important;color:green"></i>
                        </div>
                    </div>
                </article>
            </main>
        </div>
    </div>
</div>

<?php if (isset($_POST["enviar"])) {
    $nombre = isset($_POST["nombre"]) ? $f->antihack_mysqli( $_POST["nombre"]) : '';
    $apellido = isset($_POST["apellido"]) ? $f->antihack_mysqli( $_POST["apellido"]) : '';
    $landing = isset($_POST["landing"]) ? $f->antihack_mysqli( $_POST["landing"]) : '';
    $telefono = isset($_POST["telefono"]) ? $f->antihack_mysqli( $_POST["telefono"]) : '';
    $email = isset($_POST["email"]) ? $f->antihack_mysqli( $_POST["email"]) : '';
    $mensaje = isset($_POST["mensaje"]) ? $f->antihack_mysqli( $_POST["mensaje"]) : '';

    #MENSAJE A USUARIO
    $mensajeFinal = "<b>Gracias por contactarte.</b><br/>";
    $mensajeFinal .= "<b>Motivo</b>: " . $landing . "<br/>";
    $mensajeFinal .= "<b>Mensaje</b>: " . $mensaje . "<br/>";

    $enviar->set("asunto", $landing);
    $enviar->set("receptor", $email);
    $enviar->set("emisor", $dataEmail['data']['remitente']);
    $enviar->set("mensaje", $mensajeFinal);
    if ($enviar->emailEnviar() == 1) {
        echo '<div class="col-md-12 alert alert-success" role="alert">¡Formulario enviado correctamente!</div>';
    }

    #MENSAJE AL ADMIN
    $mensajeFinalAdmin = "<b>Nuevo formulario desde la landing.</b><br/>";
    $mensajeFinalAdmin .= "<b>Motivo</b>: " . $landing . "<br/>";
    $mensajeFinalAdmin .= "<b>Nombre</b>: " . $nombre . " <br/>";
    $mensajeFinalAdmin .= "<b>Apellido</b>: " . $apellido . "<br/>";
    $mensajeFinalAdmin .= "<b>Email</b>: " . $email . "<br/>";
    $mensajeFinalAdmin .= "<b>Teléfono</b>: " . $telefono . "<br/>";
    $mensajeFinalAdmin .= "<b>Mensaje</b>: " . $mensaje . "<br/>";

    $enviar->set("receptor", $dataEmail['data']['remitente']);
    $enviar->set("mensaje", $mensajeFinalAdmin);
    if ($enviar->emailEnviar() == 0) {
        echo '<div class="col-md-12 alert alert-danger" role="alert">¡No se ha podido enviar el formulario!</div>';
    }
} ?>

<?php
$template->themeEnd();
?>