<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$productos = new Clases\Productos();
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();

$data = isset($_GET['term']) ? $f->normalizar_link($_GET['term']) : '';

$productosArray = $productos->listSearch($data, 20, $_SESSION['lang']);

echo json_encode($productosArray);
