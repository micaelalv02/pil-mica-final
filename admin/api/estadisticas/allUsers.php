<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$usuarios = new Clases\Usuarios();
$f = new Clases\PublicFunction();
$filter = [];
$date = isset($_GET['date-all-users']) ?  explode(" - ", $f->antihack_mysqli($_GET['date-all-users'])) : '';
$provincia = isset($_GET['provincia_users']) ?   $f->antihack_mysqli($_GET['provincia_users']) : '';


if (!empty($provincia)) $filter[] = "`usuarios`.`provincia` = '$provincia'";


if (!empty($date)) $filter[] = "`pedidos`.`fecha` BETWEEN  STR_TO_DATE('" . $date[0] . "','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('" . $date[1] . "','%d/%m/%Y %H:%i:%s') ";
$userNews = $usuarios->allUsersPuchases($filter, "");
echo json_encode($userNews);
