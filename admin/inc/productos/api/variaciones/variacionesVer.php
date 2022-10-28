<?php
require_once dirname(__DIR__, 5) . "/Config/Autoload.php";
Config\Autoload::run();

$combinacion = new Clases\Combinaciones();

$combinacion->set("codProducto", isset($_GET["cod"]) ? $_GET["cod"] : '');
$combinacion->idioma = isset($_GET["idioma"]) ? $_GET["idioma"] : '';
$combinacionData = $combinacion->listByProductCod();

echo json_encode($combinacionData);
