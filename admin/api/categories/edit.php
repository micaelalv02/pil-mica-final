<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$categoria = new Clases\Categorias();

$attr = isset($_POST["attr"]) ? $_POST["attr"] : '';
$value = isset($_POST["value"]) ? $_POST["value"] : '';
$cod = isset($_POST["cod"]) ? $_POST["cod"] : '';
$idioma = isset($_POST["idioma"]) ? $_POST["idioma"] : '';
$value = "'" . $value . "'";
if (!empty($attr) && !empty($value) && !empty($cod)) {
    $categoria->set("cod", $cod);
    $categoria->set("idioma", $idioma);
    if ($categoria->editSingle($attr, trim($value))) {
        echo "Actualizado";
    } else {
        echo "Error";
    }
}
