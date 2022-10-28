<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$contenidos = new Clases\Contenidos();
$f = new Clases\PublicFunction();
$id = isset($_POST["id"]) ? $f->antihack_mysqli($_POST["id"]) : '';
$destacado = isset($_POST["destacado"]) ? $f->antihack_mysqli($_POST["destacado"]) : '';
$contenidos->set("id", $id);
$contenidos->set("cod", $id);
$contenidos->editSingle("destacado", $destacado);
echo json_encode(["status" => false]);
