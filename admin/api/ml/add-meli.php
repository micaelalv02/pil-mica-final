<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$product = new Clases\Productos();
$meli = new Clases\MercadoLibre();

if (!empty($_POST["codProduct"]) && !empty($_POST["codMeli"])) {
    $formData = $f->antihackMulti($_POST);
    $productData = $product->viewByCod($formData['codProduct']);
    if (!empty($productData['data'])) {

        $meli->set("code", $formData['codMeli']);
        $meli->set("price", $productData['data']['precio']);
        $meli->set("stock", $productData['data']['stock']);
        $meli->set("product",  $productData['data']['cod']);
        $meli->set("type", $formData['typeMeli']);
        if ($meli->add()) {
            $product->set("cod",$productData['data']['cod']);
            $product->editSingle("meli",1);
            echo json_encode(["status" => true, "message" => "Vinculacion Exitosa"]);
        } else {
            echo json_encode(["status" => false, "message" => "Ocurrio un error en la carga de la vinculacion"]);
        }
    } else {
        echo json_encode(["status" => false, "message" => "No existe el codigo de producto cargado"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Ingresar correctamente codigo de Producto/Mercadolibre"]);
}
