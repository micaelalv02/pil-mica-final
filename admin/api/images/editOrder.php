<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$imagenes = new Clases\Imagenes();


$imagenes->set("id", $funciones->antihack_mysqli($_POST["idImg"]));
$imagenes->orden = $funciones->antihack_mysqli($_POST["ordenImg"]);
$imagenes->setOrder();
echo json_encode("Orden modificado correctamente");
