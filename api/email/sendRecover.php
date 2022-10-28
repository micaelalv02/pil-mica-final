<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$enviar = new Clases\Email();
$config = new Clases\Config();
$usuario = new Clases\Usuarios();
$emailData = $config->viewEmail();

$email = isset($_POST['email']) ? $f->antihack_mysqli($_POST['email']) : '';

if (!empty($email)) {
    $usuario->set("email", $email);
    $usuarioData = $usuario->validate();

    if ($usuarioData['status']) {
        if (!empty($usuarioData['data']['invitado'])) {
            echo json_encode(["status" => false]);
            die();
        }
        $usuario->set("cod", $usuarioData['data']['cod']);
        $password = substr(md5(uniqid(rand())), 0, 10);
        $usuario->editSingle("password", $password);

        //Envio de mail al usuario
        $mensaje = 'Hola ' . $usuarioData['data']['nombre'] . ' tu nueva contraseña es: ' . $password . '<br/>';
        $asunto = TITULO . ' - Recuperacion de contraseña';
        $receptor = $usuarioData['data']['email'];
        $emisor = $emailData['data']['remitente'];
        $enviar->set("asunto", $asunto);
        $enviar->set("receptor", $receptor);
        $enviar->set("emisor", $emisor);
        $enviar->set("mensaje", $mensaje);
        if ($enviar->emailEnviarCurl()) {
            echo json_encode(["status" => true]);
        } else {
            echo json_encode(["status" => false]);
        }
    }
} else {
    echo json_encode(["status" => false]);
}
