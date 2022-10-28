<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$usuarios = new Clases\Usuarios();
$f = new Clases\PublicFunction();

$realizo_compra = isset($_GET['realizo-compra']) ? $f->antihack_mysqli($_GET['realizo-compra']) : '';
//realizo_compra = 1 -> realizo compra
//realizo_compra = 2 -> no realizo compra
//realizo_compra = 3 -> ambos


//TODO: pasar parametro a la funcion para filtrar en los pedidos
$userNews = $usuarios->userNews($realizo_compra);
echo json_encode($userNews);
