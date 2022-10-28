<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
$config = new Clases\Config();
$checkout = new Clases\Checkout();
$captchaData = $config->viewCaptcha();

$user = isset($_POST['l-user']) ? $funciones->antihack_mysqli($_POST['l-user']) : '';
$pass = isset($_POST['l-pass']) ? $funciones->antihack_mysqli($_POST['l-pass']) : '';
$stage = isset($_POST['stg-l']) ? $funciones->antihack_mysqli($_POST['stg-l']) : '';


$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaData['data']['captcha_secret'] . '&response=' . $_POST['captcha-response']);
$responseData = json_decode($verifyResponse);
if ($responseData->success) {
    if (!empty($user) && !empty($pass)) {
        $usuario->set("email", $user);
        $usuario->set("password", $pass);
        $response = $usuario->login();
        if (isset($response['error'])) {
            if ($response['error'] == 1) {
                $result = array("status" => false, "message" => "El usuario no esta activado, comunicarse con el soporte.");
                echo json_encode($result);
            } else {
                $result = array("status" => false, "message" => "Email o contraseña incorrectos.");
                echo json_encode($result);
            }
        } else {
            if (!empty($stage)) {
                $checkout->user($_SESSION['usuarios']['cod'], 'USER');
            }
            $result = array("status" => true);
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" => "Completar ambos campos.");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "¡Completar el CAPTCHA correctamente!");
    echo json_encode($result);
}
