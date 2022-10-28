<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$admin = new Clases\Admin();

$data = isset($_GET['term']) ? $f->normalizar_link($_GET['term']) : '';
$userArray = $admin->listSearch($data, 20);
echo json_encode($userArray);
