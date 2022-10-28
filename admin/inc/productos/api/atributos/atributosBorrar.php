<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();


$config = new Clases\Config();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$f = new Clases\PublicFunction();

$attr = isset($_GET["attr"]) ? $f->antihack_mysqli($_GET["attr"]) : '';
$subattr = isset($_GET["subattr"]) ? $f->antihack_mysqli($_GET["subattr"]) : '';
$idioma = isset($_GET['idioma']) ? $f->antihack_mysqli($_GET['idioma']) : '';

if ($attr != '') {
    $atributo->set("cod", $attr);
    $atributo->set("idioma", $idioma);
    $atributo->delete();
}