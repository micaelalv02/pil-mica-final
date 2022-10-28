<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$productos = new Clases\Productos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();
if (!isset($_SESSION['usuarios']['cod'])) $f->headerMove(URL);

$arrContextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];

$dirFile = dirname(__DIR__, 2) . "/json/cart/" . $_SESSION['usuarios']['cod'] . ".json";

$cod = $f->antihack_mysqli($_POST["cod"]);
$element = [];

if (file_exists($dirFile)) {
    $fileContent = json_decode(file_get_contents($dirFile, false, stream_context_create($arrContextOptions)), true);
    unset($fileContent[$cod]["fecha"]);
    foreach ($fileContent[$cod] as $key => $content) {
        $element[$key]["product"] =  $content['id'];
        $element[$key]["amount"] =  $content['cantidad'];

        if (!empty($content['opciones']["subatributos"]) && empty($content['opciones']["combinacion"]["cod_combinacion"])) {
            $element[$key]["combinationInfo"]["attribute"] = $content["opciones"]["texto"];
            $element[$key]["combinationInfo"]["stock"] = $content["stock"];
            $element[$key]["combinationInfo"]["status"] = true;
            $element[$key]["combinationInfo"]["price"] = $content["precio"];
            $element[$key]["combinationInfo"]["subAtribute"] = $content["opciones"]["subatributos"];
            $element[$key]["combinationInfo"]["combination"] = false;
            $element[$key]["combinationInfo"]  = json_encode($element[$key]["combinationInfo"]);
        }
        if (!empty($content['opciones']["subatributos"]) && !empty($content['opciones']["combinacion"]["cod_combinacion"])) {
            $subAttr = str_replace(",", "", $content["opciones"]["subatributos"]);
            $attr = str_split($subAttr, 10);
            $resultValidate = $combinacion->check($attr, $content["id"]);
            $detalleCombinacion->set("codCombinacion", $resultValidate['combination']);
            $detalleCombinacion->set("idioma", $content["opciones"]["combinacion"]["idioma"]);
            $detalleData = $detalleCombinacion->view();

            $element[$key]["combinationInfo"]["attribute"] = $content["opciones"]["texto"];
            $element[$key]["combinationInfo"]["stock"] = $detalleData["stock"];
            $element[$key]["combinationInfo"]["status"] = true;
            $element[$key]["combinationInfo"]["price"] = $detalleData["precio"];
            $element[$key]["combinationInfo"]["subAtribute"] = $content["opciones"]["subatributos"];

            $element[$key]["combinationInfo"]["combination"]["id"] = $content["opciones"]["combinacion"]["id"];
            $element[$key]["combinationInfo"]["combination"]["cod_combinacion"] = $content["opciones"]["combinacion"]["cod_combinacion"];
            $element[$key]["combinationInfo"]["combination"]["precio"] = $detalleData["precio"];
            $element[$key]["combinationInfo"]["combination"]["stock"] = $detalleData["stock"];
            $element[$key]["combinationInfo"]["combination"]["mayorista"] = $detalleData["mayorista"];
            $element[$key]["combinationInfo"]["combination"]["idioma"] = $content["opciones"]["combinacion"]["idioma"];
            $element[$key]["combinationInfo"]  = json_encode($element[$key]["combinationInfo"]);
        }
    }
    unset($fileContent[$cod]);
    file_put_contents($dirFile, json_encode($fileContent));
    echo json_encode(["status" => true, "element" => $element]);
} else {
    echo json_encode(["status" => false, "message" => $_SESSION['lang-txt']['carrito']['no_carrito_encontrado']]);
}
