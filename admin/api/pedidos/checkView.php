<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$pedidos = new Clases\Pedidos();
$funciones = new Clases\PublicFunction();


$code = isset($_POST["code"]) ? $funciones->antihack_mysqli($_POST["code"]) : '';
$flag = isset($_POST["flag"]) ? $funciones->antihack_mysqli($_POST["flag"]) : '';

if ($code != '' && $flag != '') {
    $pedidos->set('cod', $code);
    $pedidos->editSingle('visto', $flag);
}