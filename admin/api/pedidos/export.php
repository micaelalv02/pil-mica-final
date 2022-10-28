<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$excel = new Clases\Excel();
$pedidos = new Clases\Pedidos();
$cod = isset($_POST["cod"]) ? $f->antihack_mysqli($_POST["cod"]) : '';
$pedidos->set("cod", $cod);
$array = $pedidos->view();

$export = $excel->exportPedido($array);
echo json_encode(["file" => URL."/export/".$export]);
