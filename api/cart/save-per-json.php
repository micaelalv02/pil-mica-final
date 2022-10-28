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

$dirFile = dirname(__DIR__, 2) ."/json/cart/" . $_SESSION['usuarios']['cod'] . ".json";
$cart = $_SESSION["carrito"];
$cart["fecha"] = date('j/m/y h:i:s');
$cod = substr(md5(uniqid(rand())), 0, 10);
if (file_exists($dirFile)) {
    $fileContent = json_decode(file_get_contents($dirFile, false, stream_context_create($arrContextOptions)), true);
    $fileContent[$cod] = $cart;
    file_put_contents($dirFile, json_encode($fileContent));
    echo json_encode(["status" => true, "message" => "¡ Carrito Guardado !"]);
} else {
    $tmpfile = tmpfile();
    $data[$cod]  = $cart;
    if (file_put_contents($dirFile, json_encode($data))) {
        echo json_encode(["status" => true, "message" => "¡ Carrito Guardado !"]);
    } else {
        echo json_encode(["status" => false, "message" => "¡ Ocurrio un error !"]);
    }
}
