<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$backup  = new Clases\Backup();

$link = isset($_POST["url_absolute"]) ? $f->antihack_mysqli($_POST["url_absolute"]) : '';
$data = $backup->delete(dirname(__DIR__, 3) . "/" . $link);
echo json_encode($data);
