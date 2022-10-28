<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$favoritos = new Clases\Favoritos();
if (isset($_SESSION['usuarios']['cod'])) {
    $product = isset($_REQUEST["product"]) ? $f->antihack_mysqli($_REQUEST["product"]) : '';
    $idioma = isset($_REQUEST["idioma"]) ? $f->antihack_mysqli($_REQUEST["idioma"]) : '';
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST":
            $favoritos->add($_SESSION['usuarios']['cod'], $product,$idioma);
            echo json_encode("Producto agregado a favoritos");
            break;
        case "GET":
            $favs = $favoritos->list(["usuario = '" . $_SESSION['usuarios']['cod'] . "'"], true, true, true);
            $user = (!empty($_SESSION['usuarios']['cod'])) ? $_SESSION['usuarios']['cod'] : '';
            if (!empty($favs)) {
                echo json_encode(["products" => $favs, "user" => $user]);
            }
            break;
        case "DELETE":
            $data = $f->parseInput();
            $favorito = $favoritos->view($_SESSION['usuarios']['cod'], $data['product'],$data['idioma']);
            (!empty($favorito['data'])) ? $favoritos->delete($favorito['data']['id']) : '';
            break;
    }
}
