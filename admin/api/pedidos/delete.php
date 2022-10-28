<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$pedidos = new Clases\Pedidos();
$detallePedidos  = new Clases\DetallePedidos();

$id = isset($_POST["id"]) ? $_POST["id"] : '';
if ($detallePedidos->delete($id)) {
    echo "Eliminado";
} else {
    echo "error";
}
