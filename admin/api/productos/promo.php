<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$promos = new Clases\Promos();

$idioma = isset($_POST["idioma"]) ? $f->antihack_mysqli($_POST["idioma"]) : $_SESSION['lang'];
$producto = isset($_POST["cod"]) ? $f->antihack_mysqli($_POST["cod"]) : false;
$lleva = isset($_POST["lleva"]) ? $f->antihack_mysqli($_POST["lleva"]) : '';
$paga = isset($_POST["paga"]) ? $f->antihack_mysqli($_POST["paga"]) : '';
if ($producto) {
    if (empty($paga) && empty($lleva)) {
        $result = $promos->delete($producto, $idioma);
    } else {
        $array = [
            "producto" => $producto,
            "lleva" => $lleva,
            "paga" => $paga,
            "idioma" => $idioma
        ];
        $result = $promos->insert($array);
    }
} else {
    $result = ["status" => false];
}



echo json_encode(["status" => $result, "producto" => $producto]);
