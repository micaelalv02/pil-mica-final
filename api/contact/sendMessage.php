<?php
require_once "../../Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
$config = new Clases\Config();
$checkout = new Clases\Checkout();
$enviar = new Clases\Email();
// Verify the reCAPTCHA response
$captchaData = $config->viewCaptcha();
$emailData = $config->viewEmail();
$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaData['data']['captcha_secret'] . '&response=' . $_POST['g-recaptcha-response']);
$responseData = json_decode($verifyResponse);
$result = ["status" => false];
if ($responseData->success) {
    $nombre = isset($_POST["nombre"]) ?  $funciones->antihack_mysqli($_POST["nombre"]) : '';
    $apellido = isset($_POST["apellido"]) ?  $funciones->antihack_mysqli($_POST["apellido"]) : '';
    $email = isset($_POST["email"]) ?  $funciones->antihack_mysqli($_POST["email"]) : '';
    $telefono = isset($_POST["telefono"]) ?  $funciones->antihack_mysqli($_POST["telefono"]) : '';
    $mensaje = isset($_POST["mensaje"]) ?  $funciones->antihack_mysqli($_POST["mensaje"]) : '';

    if (!empty($nombre) || !empty($email) || !empty($mensaje)) {

        //MENSAJE A USUARIO
        $mensajeFinal = "<b>Gracias por realizar tu consulta, te contactaremos a la brevedad.</b><br/>";
        $mensajeFinal .= "<b>Consulta</b>: " . $mensaje . "<br/>";

        $enviar->set("asunto", "Realizaste tu consulta.");
        $enviar->set("receptor", $email);
        $enviar->set("emisor", $emailData['data']['remitente']);
        $enviar->set("mensaje", $mensajeFinal);
        if ($enviar->emailEnviar()) {
            $result = array("status" => true);
        }
        //MENSAJE AL ADMIN
        $mensajeFinalAdmin = "<b>Nueva consulta desde la web.</b><br/>";
        $mensajeFinalAdmin .= "<b>Nombre</b>: " . $nombre . " <br/>";
        $mensajeFinalAdmin .= "<b>Apellido</b>: " . $apellido . "<br/>";
        $mensajeFinalAdmin .= "<b>Email</b>: " . $email . "<br/>";
        $mensajeFinalAdmin .= "<b>Teléfono</b>: " . $telefono . "<br/>";
        $mensajeFinalAdmin .= "<b>Consulta</b>: " . $mensaje . "<br/>";

        $enviar->set("asunto", "Nueva consulta desde la web");
        $enviar->set("receptor", $emailData['data']['remitente']);
        $enviar->set("mensaje", $mensajeFinalAdmin);
        $enviar->emailEnviar();
    } else {
        $result = array("status" => false);
    }
} else {
    $result = array("status" => false);
}
echo json_encode($result);
