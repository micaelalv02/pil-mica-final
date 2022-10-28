<?php
require_once "../Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$productos = new Clases\Productos();
$xml = "<?xml version=\"1.0\"?><rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\"><channel><title>" . TITULO . "</title><link>" . URL . "</link><description>Tienda</description>";
$data = [
    "category" => true,
    "subcategory" => true,
    "tercercategory" => true,
    "images" => true,
];
foreach ($productosData = $productos->list($data, $_SESSION['lang']) as $product) {
    $cod = $product['data']["cod"];
    $link = utf8_encode($funciones->normalizar_link($product['data']["titulo"]));
    $link_ = URL . "/producto/$link/$cod";
    $titulo_producto = str_replace('ñ', 'n', $product["data"]["titulo"]);
    if (!empty($titulo_producto)) {
        $xml .= "<item>";
        $xml .= "<g:id>" . $product['data']['cod'] . "</g:id>";
        $xml .= "<g:title>" . htmlspecialchars(ucfirst(strtolower($titulo_producto))) . "</g:title>";
        $xml .= "<g:description>" . htmlspecialchars(ucfirst(strtolower($titulo_producto))) . "</g:description>";
        $xml .= "<g:link>" . $link_ . "</g:link>";
        $xml .= "<g:image_link>" . URL . "/" . $product["data"]['imagenes_rutas'][0] . "</g:image_link>";
        $xml .= "<g:brand>" . TITULO . "</g:brand>";
        $xml .= "<g:product_type>" . $product['data']['categoria_titulo'] . ">" . $product['data']['subcategoria_titulo'] . "</g:product_type>";
        $xml .= "<g:condition>new</g:condition>";
        $xml .= "<g:availability>in stock</g:availability>";
        $xml .= "<g:inventory>" . $product['data']['stock'] . "</g:inventory>";
        $xml .= "<g:price>" . $product['data']['precio'] . " ARS</g:price>";
        ($product['data']['precio_descuento'] != 0) ? $xml .= "<g:sale_price>" . $product['data']['precio_descuento'] . " ARS</g:sale_price>" : '';
        $xml .= "</item>";
    }
}

$xml .= "</channel></rss>";

// Opcion 2
header("Content-Type: text/xml;");
echo $xml;
