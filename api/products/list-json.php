<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();

$f = new Clases\PublicFunction();
$productos = new Clases\Productos();
$categorias = new Clases\Categorias();
$subcategorias = new Clases\Subcategorias();

#List de productos
$productosArray = $productos->list("");

#Declaración de array para mostrar como json
$arrayForJson = [];

foreach ($productosArray as $key => $productoItem) {

    $cod_producto = $productoItem["data"]["cod_producto"];
    $imagesData = $productoItem["images"];

    $desarrolloMeli = $productoItem["data"]["titulo"] . '';

    if (!empty($productoItem["data"]["cod_producto"])) {
        $arrayForJson[$key]["data"]["cod_producto"] = $productoItem["data"]["cod_producto"];
        $arrayForJson[$key]["data"]["titulo"] = !empty($productoItem["data"]["titulo"]) ? $productoItem["data"]["titulo"] : '';
        $arrayForJson[$key]["data"]["desarrollo"] = !empty($desarrolloMeli) ? $desarrolloMeli : '';
        $arrayForJson[$key]["data"]["precio"] = !empty($productoItem["data"]["precio"]) ? $productoItem["data"]["precio"] : 0;
        $arrayForJson[$key]["data"]["stock"] = !empty($productoItem["data"]["stock"]) ? $productoItem["data"]["stock"] : 0;
        $arrayForJson[$key]["category"]["data"]["titulo"] = !empty($productoItem["category"]["data"]["titulo"]) ? $productoItem["category"]["data"]["titulo"] : '';
        $arrayForJson[$key]["subcategory"]["data"]["titulo"] = !empty($productoItem["subcategory"]["data"]["titulo"]) ? $productoItem["subcategory"]["data"]["titulo"] : '';

        $orden = -1;
        foreach ($imagesData as $keyImg => $imageItem) {
            if (!strpos($imageItem['ruta'], 'sin_imagen')) {
                $arrayForJson[$key]["images"][] = [
                    "ruta" => URL . '/' . $imageItem['ruta'],
                    "orden" => $keyImg
                ];

                $orden = $keyImg;
            }
        }

        $arrayForJson[$key]["images"][] = [
            "ruta" => LOGO,
            "orden" => ($orden == -1) ? 0 : $orden + 1
        ];

        //Esto es en caso de tener que vincular productos que ya están cargados en mercadolibre
        /*if($productoItem["data"]["meli"]) {
             $arrayForJson[$key]["mercadolibre"]["code"] = !empty($productoItem["data"]["meli"]) ? $productoItem["data"]["meli"] : '';
             $arrayForJson[$key]["mercadolibre"]["type"] = "gold_special";
             $arrayForJson[$key]["mercadolibre"]["price"] = number_format(($productoItem["data"]["precio"] * 0.80), 2, ".", "");
             $arrayForJson[$key]["mercadolibre"]["percent"] = 10;
         }*/
    }
}

header("Content-Type: application/json");
echo json_encode(($arrayForJson), JSON_PRETTY_PRINT);
