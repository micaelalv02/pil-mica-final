<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();
$ml = new Clases\MercadoLibre();
$product = isset($_POST['product']) ? $f->antihack_mysqli($_POST['product']) : '';
$type = isset($_POST['type']) ? $f->antihack_mysqli($_POST['type']) : '';
$form = isset($_POST['form']) ?  $_POST['form']  : '';
$producto->set("cod", $product);
$productData = $producto->view($product,'',false,false,false);


$ml->set("product", $product);
$images = [];
// foreach ($productData["images"] as $img) {
//     $images[]["source"] = URL . "/" . $img["ruta"];
// }

$desarrollo = preg_replace("/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($productData["data"]["desarrollo"]))));


$meli_product = $ml->list(["product = '$product' AND type = '$type'"], "", "",true);

if (!$meli_product) {
    //add   
    $response = $ml->create($productData["data"]["titulo"], $productData["data"]["precio"], $desarrollo, $productData["data"]["stock"], $images, $type);
    if ($response["status"]) {
        $ml->set("code", $response["data"]["id"]);
        $ml->set("price", $response["data"]["price"]);
        $ml->set("type", $type);
        $ml->set("stock", $response["data"]["available_quantity"]);
        $ml->set("product", $productData["data"]["cod"]);
        $ml->add();
    }
    $result[] = $response;
} else {
    //edit
    $result = [];
    foreach ($meli_product as $meli_product_) {
        ($productData['data']['stock'] > 0) ? $ml->changeStatus($meli_product_["data"]["code"], 'active') : $ml->changeStatus($meli_product_["data"]["code"], 'pause');
        $response = $ml->updatePriceStock($meli_product_["data"]["code"], $productData["data"]["precio"], $productData["data"]["stock"],$meli_product_["data"]["type"]);
            if ($response["status"]) {
            $ml->set("code", $response["data"]["id"]);
            $ml->set("price", $response["data"]["price"]);
            $ml->set("type", $type);
            $ml->set("stock", $response["data"]["available_quantity"]);
            $ml->set("product", $productData["data"]["cod"]);
            $ml->edit();
        }
        $result[] = $response;
    }
}
echo json_encode($result);
