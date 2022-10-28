<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$subcategorias = new Clases\Subcategorias();
$flag = isset($_POST["flag"]) ? $f->antihack_mysqli($_POST["flag"]) : '';
$value = isset($_POST["value"]) ? $f->antihack_mysqli($_POST["value"]) : '';
$idioma = isset($_POST["idioma"]) ? $f->antihack_mysqli($_POST["idioma"]) : '';
if ($flag == "subcategory") {
    echo json_encode($subcategorias->list(["categoria = '" . $value . "'"], "", "", $idioma));
}
