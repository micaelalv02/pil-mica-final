<?php
require_once "../../Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
$config = new Clases\Config();
$hubspot = new Clases\Hubspot();
$checkout = new Clases\Checkout();
// Verify the reCAPTCHA response
$captchaData = $config->viewCaptcha();

$password1 = isset($_POST["r-password1"]) ? $funciones->antihack_mysqli($_POST["r-password1"]) : "";
$password2 = isset($_POST["r-password2"]) ? $funciones->antihack_mysqli($_POST["r-password2"]) : "";
$stage = !empty($_SESSION['stages']) ? $_SESSION['stages'] : '';


$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaData['data']['captcha_secret'] . '&response=' . $_POST['captcha-response']);
$responseData = json_decode($verifyResponse);
if ($responseData->success) {
    if ($password1 == $password2) {
        $nombre = isset($_POST["firstname"]) ? $funciones->antihack_mysqli($_POST["firstname"]) : '';
        $apellido = isset($_POST["lastname"]) ? $funciones->antihack_mysqli($_POST["lastname"]) : '';
        $email = isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '';
        $direccion = isset($_POST["address"]) ? $funciones->antihack_mysqli($_POST["address"]) : '';
        $localidad = isset($_POST["city"]) ? $funciones->antihack_mysqli($_POST["city"]) : '';
        $provincia = isset($_POST["provincia"]) ? $funciones->antihack_mysqli($_POST["provincia"]) : '';
        $pais = isset($_POST["r-pais"]) ? $funciones->antihack_mysqli($_POST["r-pais"]) : '';
        $postal = isset($_POST["zip"]) ? $funciones->antihack_mysqli($_POST["zip"]) : '';
        $telefono = isset($_POST["phone"]) ? $funciones->antihack_mysqli($_POST["phone"]) : '';
        $celular = isset($_POST["mobilephone"]) ? $funciones->antihack_mysqli($_POST["mobilephone"]) : '';
        if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($direccion) && !empty($telefono) && !empty($celular)) {

            $cod = substr(md5(uniqid(rand())), 0, 10);
            $fecha = getdate();
            $fecha = $fecha['year'] . '-' . $fecha['mon'] . '-' . $fecha['mday'];

            $usuario->set("cod", $cod);
            $usuario->set("nombre", $nombre);
            $usuario->set("apellido", $apellido);
            $usuario->set("doc", "");
            $usuario->set("email", $email);
            $usuario->set("direccion", $direccion);
            $usuario->set("telefono", $telefono);
            $usuario->set("celular", $celular);
            $usuario->set("minorista", "1");
            $usuario->set("invitado", 0);
            $usuario->set("descuento", 0);
            $usuario->set("localidad", $localidad);
            $usuario->set("provincia", $provincia);
            $usuario->set("pais", $pais);
            $usuario->set("postal", $postal);
            $usuario->set("password", $password1);
            $usuario->set("fecha", $fecha);
            $usuario->set("estado", 1);

            $response = $usuario->validate();
            if ($response['status']) {
                if ($response['data']['invitado'] != '1') {
                    if ($response['data']['email'] == $email && $response['data']['password'] == $usuario->hash()) {
                        $usuario->login();
                        if (!empty($stage)) {
                            $checkout->user($_SESSION['usuarios']['cod'], 'USER');
                        }
                        $result = array("status" => true, "cod" => $response['data']['cod']);
                        echo json_encode($result);
                    } else {
                        $url = URL;
                        $result = array("status" => false, "message" => "Ya hay un usuario con ese email, si este correo es suyo y no recuerda la contraseña, recupere la contraseña en el siguiente link <a href='$url/recuperar'>recuperar contraseña</a>.");
                        echo json_encode($result);
                    }
                } else {
                    $usuario->set("cod", $response['data']['cod']);
                    if ($usuario->edit()) {
                        $usuario->set("email", $email);
                        $usuario->set("password", $password1);
                        $usuario->login();
                        if (!empty($stage)) {
                            $checkout->user($_SESSION['usuarios']['cod'], 'NEWER');
                        }
                        $result = array("status" => true, "cod" => $cod);
                        echo json_encode($result);
                    } else {
                        $result = array("status" => false, "message" => "Ocurrió un error, vuelva a cargar la página.1");
                        echo json_encode($result);
                    }
                }
            } else {
                if ($usuario->add()) {
                    $hubspot->set("nombre", $nombre);
                    $hubspot->set("apellido", $apellido);
                    $hubspot->set("email", $email);
                    $hubspot->set("telefono", $telefono);
                    $hubspot->set("celular", $celular);
                    $hubspot->set("provincia", $provincia);
                    $hubspot->set("localidad", $localidad);
                    $hubspot->set("direccion", $direccion);
                    $hubspot->set("postal", $postal);
                    $hubspot->addContact();
                    $usuario->set("email", $email);
                    $usuario->set("password", $password1);
                    $usuario->login();
                    if (!empty($stage)) {
                        $checkout->user($_SESSION['usuarios']['cod'], 'NEWER');
                    }
                    $result = array("status" => true, "cod" => $cod);
                    echo json_encode($result);
                } else {
                    $result = array("status" => false, "message" => "Ocurrió un error, vuelva a cargar la página.2");
                    echo json_encode($result);
                }
            }
        } else {
            $result = array("status" => false, "message" => "Hay campos incompletos");
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" => "Las contraseñas no coinciden.");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "¡Completar el CAPTCHA correctamente!");
    echo json_encode($result);
}
