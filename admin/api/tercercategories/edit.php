<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$tercerCategoria = new Clases\TercerCategorias();

$attr = isset($_POST["attr"]) ? $_POST["attr"] : '';
$value = isset($_POST["value"]) ? $_POST["value"] : '';
$cod = isset($_POST["cod"]) ? $_POST["cod"] : '';
$idioma = isset($_POST["idioma"]) ? $_POST["idioma"] : '';
var_dump($_POST);
$value = "'" . $value . "'";
if (!empty($attr) && !empty($value) && !empty($cod)) {
    $tercerCategoria->set("cod", $cod);
    $tercerCategoria->set("idioma", $idioma);
    if ($tercerCategoria->editSingle($attr, trim($value))) {
        echo "Actualizado";
    } else {
        echo "Error [1]";
    }
} else {
    echo "Error [2]";
}
