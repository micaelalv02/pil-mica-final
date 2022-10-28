<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();


$config = new Clases\Config();
$atributo = new Clases\Atributos();

$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$idioma = isset($_GET["idioma"]) ? $_GET["idioma"] : '';
$atributo->set("productoCod", $cod);
$atributo->set("idioma", $idioma);

echo json_encode($atributo->list());
