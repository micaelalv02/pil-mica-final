<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();

$imagenes = new Clases\Imagenes();

// A list of permitted file extensions
if (!empty($_FILES['upl']['name'][0])){
	$imagenes->uploadFileInFolder($_FILES['upl'], "assets/archivos/productos", $_FILES['upl']['name']);
	echo '{"status":"success"}';
	exit;
}
echo '{"status":"error"}';
exit;
