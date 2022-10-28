<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$pedidos = new Clases\Pedidos();

$attr = isset($_POST["attr"]) ? $_POST["attr"] : '';
$value = isset($_POST["value"]) ? $_POST["value"] : '';
$cod = isset($_POST["cod"]) ? $_POST["cod"] : '';
if (!empty($attr) && !empty($cod)) {
    $pedidos->set("cod", $cod);
    if ($pedidos->editSingle($attr, $value)) {
        echo json_encode(["price" => $value]);
    }
}
