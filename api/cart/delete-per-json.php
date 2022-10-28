<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();

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
    unset($fileContent[$cod]);
    file_put_contents($dirFile, json_encode($fileContent));
    echo json_encode(["status" => true, "element" => $element]);
} else {
    echo json_encode(["status" => false, "message" => $_SESSION['lang-txt']['carrito']['no_carrito_encontrado']]);
}
