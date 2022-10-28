<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$comentarios = new Clases\Comentarios();
$config = new Clases\Config();


$cod_url = isset($_POST['cod_url']) ?  $f->antihack_mysqli($_POST['cod_url']) : '';
$id_comentario = isset($_POST['id_comentario']) ?  $f->antihack_mysqli($_POST['id_comentario']) : '';
$usuario = isset($_POST['usuario']) ?  $f->antihack_mysqli($_POST['usuario']) : '';
$comentario = isset($_POST['comentario']) ?  $f->antihack_mysqli($_POST['comentario']) : '';

// Verify the reCAPTCHA response
$captchaData = $config->viewCaptcha();

$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaData['data']['captcha_secret'] . '&response=' . $_POST['captcha-response']);
$responseData = json_decode($verifyResponse);
if ($responseData->success) {
    if (!empty($cod_url)) {
        // Check if the submitted form variables exist
        if (isset($_POST['usuario'], $_POST['comentario'])) {
            // POST variables exist, insert a new comment into the MySQL comments table (user submitted form)
            $comentarios->set("cod_url", $cod_url);
            $comentarios->set("id_comentario", $id_comentario);
            $comentarios->set("usuario", $usuario);
            $comentarios->set("comentario", $comentario);

            if ($comentarios->add()) {
                $result = array("status" => true, "message" => "¡Comentario cargado!");
                echo json_encode($result);
            }
        }
    } else {
        $result = array("status" => false, "message" => "¡No existe codigo de pagina!");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "¡Completar el CAPTCHA correctamente!");
    echo json_encode($result);
}
