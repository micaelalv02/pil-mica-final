<?php
$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();
$banner = new Clases\Banners();
#Variables GET
$sesionActiva = isset($_SESSION['usuarios']['cod']) ? true :  false;

$tituloGet = isset($_GET["titulo"]) ? $f->antihack_mysqli(str_replace("-", " ", $_GET["titulo"])) : '';
$categoriaGet = isset($_GET["categoria"]) ? $f->antihack_mysqli(str_replace("-", " ", $_GET["categoria"])) : '';
$subcategoriaGet = isset($_GET["subcategoria"]) ? $f->antihack_mysqli(str_replace("-", " ", $_GET["subcategoria"])) : '';
$favoritos = ($tituloGet == 'favoritos') ? true : false;
?>
<div class="container">
    <div class="shop-area mt-50 pb-90">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
                <div class="portfolio-standard mt-0">
                    <div class="container">
                        <div class="row">
                            <div class="products-section shop mt-0">
                                <div class="row grid-favorites-sesion" data-col="6" data-url="<?= URL ?>" data-favorites="true"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                <button id="grid-products-btn" class="btn btn__secondary loadMoreportfolio" onclick="loadMore()">
                                    CARGAR MÁS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
