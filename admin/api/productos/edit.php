<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$productos = new Clases\Productos();

$attr = isset($_POST["attr"]) ? $_POST["attr"] : '';
$value = isset($_POST["value"]) ? $_POST["value"] : '';
$cod = isset($_POST["cod"]) ? $_POST["cod"] : '';
$idioma = isset($_POST["idioma"]) ? $_POST["idioma"] : '';
if (!empty($attr) && !empty($value) && !empty($cod)) {
    $productos->set("cod", $cod);
    if ($productos->editSingle($attr, $value, $idioma)) {
        echo "Actualizado";
    }
}
