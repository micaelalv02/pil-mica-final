<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$idioma = new Clases\Idiomas();
$f = new Clases\PublicFunction();
$cod = isset($_POST["cod"]) ? $f->antihack_mysqli($_POST["cod"]) : '';

$idioma->changeDefault($cod);
$_SESSION['lang'] = $cod;
echo json_encode(["status" => true]);
