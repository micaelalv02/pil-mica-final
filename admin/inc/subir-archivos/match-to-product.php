<?php
$directorio = dirname(__DIR__, 3) . '/assets/archivos/productos';
$url = URL . '/assets/archivos/productos';
$producto = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$f = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();

$images = [];
$ficheros = scandir($directorio);
$idiomaList = $idiomas->list();
foreach ($idiomaList as $idioma) {
    $productos = $producto->getAllCods($idioma["data"]["cod"]);
    foreach ($ficheros as $ficheroItem) {

        $ext = @end(explode(".", $ficheroItem));
        $cod_img  = str_replace("." . $ext, '', $ficheroItem);
        $cod_img = explode("-", $cod_img)[0];
        $cod_img = explode("_", $cod_img)[0];
        if (array_search($cod_img, $productos) != false) {
            $origen = dirname(__DIR__, 3) . "/assets/archivos/productos/" . $ficheroItem;
            $destino =  dirname(__DIR__, 3) . "/assets/archivos/recortadas/" . $idioma["data"]["cod"] . "-" . $ficheroItem;
            $productData = $producto->list(["filter" => ["cod_producto = '$cod_img'"]], $idioma["data"]["cod"], true);
            if ($productData["data"] != '') {
                $imagenes->set("ruta", "/assets/archivos/recortadas/" . $idioma["data"]["cod"] . "-" . $ficheroItem);
                $imagenes->set("cod", $productData["data"]["cod"]);
                $imagenes->set("idioma", $idioma["data"]["cod"]);
                $imagenes->add();
                copy($origen, $destino);
                $images[] = $ficheroItem;
            }
        }
    }
}
if (!empty($images)) {
    foreach ($images as $fichero_) {
        @unlink(dirname(__DIR__, 3) . "/assets/archivos/productos/" . $fichero_);
    }
}
$f->headerMove(URL_ADMIN . "/index.php?op=subir-archivos");
