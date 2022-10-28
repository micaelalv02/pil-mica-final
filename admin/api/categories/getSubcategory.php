<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$tercercategoria = new Clases\TercerCategorias();
$flag = isset($_POST["flag"]) ? $f->antihack_mysqli($_POST["flag"]) : '';
$value = isset($_POST["value"]) ? $f->antihack_mysqli($_POST["value"]) : '';
$idioma = isset($_POST["idioma"]) ? $f->antihack_mysqli($_POST["idioma"]) : '';
if ($flag == "tercercategory") {
    echo json_encode($tercercategoria->list(["subcategoria = '" . $value . "'"], "", "", $idioma));
}
