<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();

$menu = new Clases\Menu();
$f  = new Clases\PublicFunction();

$attr = isset($_POST["attr"]) ? $f->antihack_mysqli($_POST["attr"]) : '';
if (is_array($_POST["value"])) {
    $value = isset($_POST["value"][0]) ? $f->antihack_mysqli($_POST["value"][0]) : '';
    $lang = isset($_POST["value"][1]) ? $f->antihack_mysqli($_POST["value"][1]) : '';
} else {
    $value = isset($_POST["value"]) ? $f->antihack_mysqli($_POST["value"]) : '';
}
$id = isset($_POST["id"]) ? $f->antihack_mysqli($_POST["id"]) : '';

$menu->set("id", $id);
$menu->editSingle($attr, $value);
$result = ["status" => true, "message" => "¡Modificado!"];
echo json_encode($result);
