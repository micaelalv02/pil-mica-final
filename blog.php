<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$contenidos = new Clases\Contenidos();

$data_inicio = [
    "images" => true,
    "filter" => ["contenidos.area = 'inicio'"],
];
//$empresaData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='empresa'"]], $_SESSION['lang'], false);

$inicio = $contenidos->list($data_inicio, $_SESSION['lang']);
//$equipoData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='equipo'"]], $_SESSION['lang'], false);
$serviciosData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='servicios'"]], $_SESSION['lang'], false);


$template->themeInit();
