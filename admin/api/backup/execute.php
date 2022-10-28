<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$backup  = new Clases\Backup();
if (!isset($_POST["url_absolute"]) || empty($_POST["url_absolute"])) echo json_encode(array("status" => false, "message" => "Error al obtener el backup"));
$link = $f->antihack_mysqli($_POST["url_absolute"]);

$backup->run_sql_file(dirname(__DIR__, 3) .  $link);
echo json_encode(array("status" => true, "message" => "Backup restaurado con exito"));
