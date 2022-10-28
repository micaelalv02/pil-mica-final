<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$enviar = new Clases\Email();
$config = new Clases\Config();
$usuario = new Clases\Usuarios();
$emailData = $config->viewEmail();
$arrContextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];
$contentEmail = json_decode(file_get_contents(dirname(__DIR__, 2)  . '/lang/emailSendRegister/email.json', false, stream_context_create($arrContextOptions)), true);
$cod = isset($_POST['cod']) ? $f->antihack_mysqli($_POST['cod']) : '';

if (!empty($cod)) {
    $usuario->set("cod", $cod);
    $usuarioData = $usuario->view();

    if (!empty($usuarioData)) {
        $idioma = isset($usuarioData["data"]["idioma"]) && !empty($usuarioData["data"]["idioma"]) ? $usuarioData["data"]["idioma"] : $_SESSION['lang'];
        //Envio de mail al usuario
        $name = ucfirst($usuarioData['data']['nombre']) . ' ' . ucfirst($usuarioData['data']['apellido']);
        $mensaje = str_replace('(name)', $name, $contentEmail[$idioma]["contenido"]);

        $receptor = $usuarioData['data']['email'];
        $emisor = $emailData['data']['remitente'];
        $enviar->set("asunto",  $contentEmail[$idioma]["asunto"]);
        $enviar->set("receptor", $receptor);
        $enviar->set("emisor", $emisor);
        $enviar->set("mensaje",  $mensaje);
        $enviar->set("cc", $contentEmail[$idioma]["cc"]);
        $enviar->emailEnviarCurl();

        //Envio de mail a la empresa
        $mensaje2 = 'El usuario ' . ucfirst($usuarioData['data']['nombre']) . ' ' . ucfirst($usuarioData['data']['apellido']) . ' acaba de registrarse en nuestra plataforma' . '<br/>';
        $asunto2 = TITULO . ' - Registro';
        $receptor2 = $emailData['data']['remitente'];
        $emisor2 = $emailData['data']['remitente'];
        $enviar->set("asunto", $asunto2);
        $enviar->set("receptor", $receptor2);
        $enviar->set("emisor", $emisor2);
        $enviar->set("mensaje", $mensaje2);
        $enviar->emailEnviarCurl();
    }
}
