<?php
require_once "../Config/Autoload.php";
Config\Autoload::run();

$f = new Clases\PublicFunction();
$productos = new Clases\Productos();
$contenidos = new Clases\Contenidos();

$idioma = isset($_GET["idioma"]) ? $_GET["idioma"] : $_SESSION['lang'];

$otras = array("sesion", "productos", "empresa", "novedades", "contacto");

$xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$xml .=  '<url><loc>' . URL . '/</loc><lastmod>' . date("Y-m-d") . '</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>';
foreach ($productos->list(["category" => true, "subcategory" => true, "tercercategory" => true], $idioma) as $productoItem) {
    $xml .= '<url><loc>' . $productoItem["link"] . '</loc><lastmod>' . $productoItem["data"]["fecha"] . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    if (!empty($productoItem["data"]["categoria"])) {
        $xml .= '<url><loc>' . URL . '/productos/b/categoria/' . $productoItem["data"]["categoria"] . '</loc><lastmod>' . $productoItem["data"]["fecha"] . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    }
    if (!empty($productoItem["data"]["subcategoria"])) {
        $xml .= '<url><loc>' . URL . '/productos/b/categoria/' . $productoItem["data"]["categoria"] . "/subcategoria/" . $productoItem["data"]["subcategoria"] . '</loc><lastmod>' . $productoItem["data"]["fecha"] . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    }
    if (!empty($productoItem["data"]["tercercategoria"])) {
        $xml .= '<url><loc>' . URL . '/productos/b/categoria/' . $productoItem["data"]["categoria"] . "/subcategoria/" . $productoItem["data"]["subcategoria"] . "/tercercategoria/" . $productoItem["data"]["tercercategoria"] . '</loc><lastmod>' . $productoItem["data"]["fecha"] . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    }
}

foreach ($contenidos->list("", $idioma) as $contenidoItem) {
    $area = $f->normalizar_link($contenidoItem["data"]["area"]);
    $titulo = $f->normalizar_link(trim($contenidoItem["data"]["titulo"]));
    $cod = $f->normalizar_link($contenidoItem["data"]["cod"]);

    $xml .=  '<url><loc>' . URL . '/c/' . $area . '/' . $titulo . '/' . $cod . '</loc><lastmod>' . date("Y-m-d") . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach ($otras as $otro) {
    $xml .=  '<url><loc>' . URL . '/' . $otro . '</loc><lastmod>' . date("Y-m-d") . '</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>';
}

$xml .= '</urlset>';

header("Content-Type: text/xml;");
echo $xml;
