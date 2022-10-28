<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$opciones = new Clases\Opciones();
$f = new Clases\PublicFunction();

$filter = isset($_GET['filter']) ?  $f->antihack_mysqli($_GET['filter']) : "''";
$idioma = isset($_GET['idioma']) ?  $f->antihack_mysqli($_GET['idioma']) : "es";

if($filter == "todas"){
    $filter = "`opciones`.`area` != ''";
}else{    
    $filter = "`opciones`.`area` = '" . $filter . "'";
}

$opcionesData = $opciones->list($idioma, [$filter]);
echo json_encode($opcionesData);