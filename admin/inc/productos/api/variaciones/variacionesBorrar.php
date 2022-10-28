<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();

$config = new Clases\Config();
$combinaciones = new Clases\Combinaciones();
$detalleCombinaciones = new Clases\DetalleCombinaciones();

$comb = isset($_GET["comb"]) ? $_GET["comb"] : '';
$idioma = isset($_GET["idioma"]) ? $_GET["idioma"] : '';

if ($comb != '') {
    $combinaciones->set("cod", $comb);
    $combinaciones->set("idioma", $idioma);
    $combinaciones->delete();
    $detalleCombinaciones->set("codCombinacion", $comb);
    $detalleCombinaciones->set("idioma", $idioma);
    $detalleCombinaciones->delete();
}
