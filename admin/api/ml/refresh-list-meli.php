<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$meli = new Clases\MercadoLibre();


echo json_encode($meli->list('', '', ''));
