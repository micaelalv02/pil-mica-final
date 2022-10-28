<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();
$producto_relacionados = new Clases\ProductosRelacionados();
$productosVisitados = new Clases\ProductosVisitados();

#Variables GET
$cod = isset($_GET["cod"]) ?  $f->antihack_mysqli($_GET["cod"]) : '';
#View del producto actual

$data = [
    "filter" => ["productos.cod = " . "'$cod'"],
    "admin" => false,
    "category" => true,
    "subcategory" => true,
    "tercercategory" => true,
    "attribute" => true,
    "promos" => true,
    "combination" => true,
    "bultos" => true,
    "images" => true,
];

$productoData = $producto->list($data, $_SESSION['lang'], true);

#Agregamos el producto a la sesion que lo esta viendo
$productosVisitados->add($productoData['data']['cod'], $_SERVER['REMOTE_ADDR'], $productoData['data']['idioma']);
#Se redirecciona si el producto no existe
if (empty($productoData))  $f->headerMove(URL . '/productos');

$fav = (isset($_SESSION['usuarios']) && !empty($_SESSION['usuarios'])) ? true : false;

if (isset($productoData['favorite']['data']['id'])) {
    $hiddenAddFav = 'd-none';
    $hiddenDeleteFav = '';
} else {
    $hiddenAddFav = '';
    $hiddenDeleteFav = 'd-none';
}
$cod_producto = $productoData['data']['cod_producto'];
$producto_relacionados_ = $producto_relacionados->list(["productos_cod LIKE '%$cod_producto%'"], "", "");
#Información de cabecera
$template->set("title", ucfirst(mb_strtolower(strtoupper($productoData['data']['titulo']))) . " | " . TITULO);
$template->set("description", mb_substr(strip_tags($productoData['data']['desarrollo'], '<p><a>'), 0, 160));
$template->set("keywords", strip_tags($productoData['data']['keywords']));
$template->set("imagen", isset($productoData['images'][0]['url']) ? URL . '/' . $productoData['images'][0]['url'] : LOGO);
$template->themeInit();
?>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

<!-- Page Title Area Start -->
<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $productoData['data']['titulo'] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>">Home</a></li>
                                <?php if (!empty($productoData['data']['categoria'])) { ?>
                                    <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= URL . "/productos/c/" . $f->normalizar_link($productoData['data']['categoria_titulo']) . "/" .   $productoData['data']['categoria'] ?>"><?= $productoData['data']['categoria_titulo'] ?></a></li>
                                    <?php if (!empty($productoData['data']['subcategoria'])) { ?>
                                        <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= URL . "/productos/s/" . $f->normalizar_link($productoData['data']['categoria_titulo']) . "/" .   $productoData['data']['categoria'] . "/" . $f->normalizar_link($productoData['data']['subcategoria_titulo']) . "/" .   $productoData['data']['subcategoria'] ?>"><?= $productoData['data']['subcategoria_titulo'] ?></a></li>
                                        <?php if (!empty($productoData['data']['tercercategoria'])) { ?>
                                            <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= URL . "/productos/t/" . $f->normalizar_link($productoData['data']['categoria_titulo']) . "/" .   $productoData['data']['categoria'] . "/" . $f->normalizar_link($productoData['data']['subcategoria_titulo']) . "/" .   $productoData['data']['subcategoria'] . "/" . $f->normalizar_link($productoData['data']['tercercategoria_titulo']) . "/" .   $productoData['data']['tercercategoria'] ?>"><?= $productoData['data']['tercercategoria_titulo'] ?></a></li>
                                <?php }
                                    }
                                } ?>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Title area Ends -->

<section class="product-single theme1 pt-60">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-gallery-box product-gallery-box--default m-b-60">
                            <div class="product-image--large product-image--large-horizontal slider-single">
                                <?php foreach ($productoData['images'] as $prodImg) { ?>
                                    <img class="img-fluid" style="object-fit: contain;width:100%;height:335px" id="img-zoom" src="<?= $prodImg['url'] ?>" data-zoom-image="<?= $prodImg['url'] ?>" alt="">
                                <?php } ?>
                            </div>
                            <?php if (isset($productoData['images'][0]['url'])) { ?>
                                <div id="gallery-zoom" class="product-image--thumb product-image--thumb-horizontal pos-relative mt-10 ">
                                    <div class="brand-slider-section theme1 bg-white">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="brand-init border-top py-35 slick-nav-brand slider-nav">
                                                        <?php foreach ($productoData['images'] as $prodImg) { ?>
                                                            <div class="slider-item">
                                                                <div class="single-brand">
                                                                    <a class="zoom-active" data-zoom-image="<?= $prodImg['url'] ?>">
                                                                        <img style="height: 100px;width:100px;object-fit: contain;" class="img-fluid" src="<?= $prodImg['url'] ?>" alt="<?= $productoData['data']['titulo'] ?>" />
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6 shop-details">
                        <div class="single-product-info">
                            <div class="single-product-head">
                                <div class="product-details">
                                    <?php if (!empty($productoData['data']['categoria'])) { ?>
                                        <a class="blog-link theme-color text-uppercase mb-10" href="<?= URL . "/productos/c/" . $f->normalizar_link($productoData['data']['categoria_titulo']) . "/" .   $productoData['data']['categoria'] ?>" tabindex="0"><?= $productoData['data']['categoria_titulo'] ?></a>
                                        <?php if (!empty($productoData['data']['subcategoria'])) { ?>
                                            <span class="blog-link theme-color text-uppercase mb-10"> | </span>
                                            <a class="blog-link theme-color text-uppercase mb-10" href="<?= URL . "/productos/s/" . $f->normalizar_link($productoData['data']['categoria_titulo']) . "/" .   $productoData['data']['categoria'] . "/" . $f->normalizar_link($productoData['data']['subcategoria_titulo']) . "/" .   $productoData['data']['subcategoria'] ?>" tabindex="0"><?= $productoData['data']['subcategoria_titulo'] ?></a>
                                            <?php if (!empty($productoData['data']['tercercategoria'])) { ?>
                                                <span class="blog-link theme-color text-uppercase mb-10"> | </span>
                                                <a class="blog-link theme-color text-uppercase mb-10" href="<?= URL . "/productos/t/" . $f->normalizar_link($productoData['data']['categoria_titulo']) . "/" .   $productoData['data']['categoria'] . "/" . $f->normalizar_link($productoData['data']['subcategoria_titulo']) . "/" .   $productoData['data']['subcategoria'] . "/" . $f->normalizar_link($productoData['data']['tercercategoria_titulo']) . "/" .   $productoData['data']['tercercategoria'] ?>" tabindex="0"><?= $productoData['data']['tercercategoria_titulo'] ?></a>
                                    <?php }
                                        }
                                    } ?>
                                    <h5 class="text-uppercase"><?= $productoData['data']['titulo'] ?></h5>
                                    <h4 class="sub-title fs-16"><span><?= !empty($productoData['data']['cod_producto']) ? "COD:" : '' ?> <?= $productoData['data']['cod_producto'] ?> </span></h4>
                                    <p class="mt-10 mb-10 fs-16">
                                    <div class="price">
                                        <?php
                                        if (!empty($productoData['data']['precio'])) {
                                        ?>
                                            <span class="product-price mr-20">
                                                <?php if ($productoData["data"]["precio_descuento"]) { ?>
                                                    <del class="del fs-22">$<?= $productoData["data"]["precio"] ?></del>
                                                <?php } ?>
                                                <span class="onsale fs-30" id="s-price">$<?= $productoData["data"]["precio_final"] ?></span>
                                            </span>
                                        <?php
                                        } ?>
                                    </div>
                                    <div class="mt-4">
                                        <?= $productoData['data']['description'] ?>
                                    </div>
                                    </p>
                                    <form class="add-quantity" id="cart-f" data-url="<?= URL ?>" onsubmit="addToCart('cart-f','','<?= URL ?>', '')">
                                        <input type="hidden" name="idioma" value="<?= $_SESSION['lang'] ?>">
                                        <input type="hidden" name="combinationInfo" class="hidden-data">
                                        <div>
                                            <?php if (!empty($productoData["atributo"])) {
                                                foreach ($productoData["atributo"] as $key => $atrib) { ?>
                                                    <label class="mb-10 product-color d-block">
                                                        <h6><?= $atrib['atribute']['value'] ?></h6>
                                                        <select class="form-control" style="width: auto" onchange="refreshFront();" name="atribute[<?= $atrib['atribute']['cod'] ?>]" required>
                                                            <option disabled selected></option>
                                                            <?php
                                                            foreach ($atrib['atribute']['subatributes'] as $sub) {
                                                            ?>
                                                                <option data-value='<?= mb_strtoupper(str_replace(" ", "", $sub['value'])) ?>' value="<?= $sub['cod'] ?>">
                                                                    <?= mb_strtoupper($sub['value']) ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </label>
                                            <?php }
                                                echo "<input type='hidden' name='amount-atributes' value='" . count($productoData["atributo"]) . "'>";
                                                if (!empty($productoData["combination"])) {
                                                    echo "<input type='hidden' name='combination' value='combination'>";
                                                }
                                            } ?>
                                        </div>
                                        <?php if ($fav) { ?>
                                            <div class="addto-whish-list">
                                                <a onclick="addFavorite('<?= $productoData['data']['cod'] ?>','<?= $productoData['data']['idioma'] ?>')" id="btn-addFavorite-<?= $productoData["data"]["cod"] ?>" class="btn-addFavorite-<?= $productoData["data"]["cod"] ?> <?= $hiddenAddFav ?>"> <?= $_SESSION["lang-txt"]["productos"]["agregar_favoritos"] ?> <i class="fas fa-heart"></i></a>
                                                <a onclick="deleteFavorite('<?= $productoData['data']['cod'] ?>','<?= $productoData['data']['idioma'] ?>')" id="btn-deleteFavorite-<?= $productoData["data"]["cod"] ?>" class="btn-deleteFavorite-<?= $productoData["data"]["cod"] ?> <?= $hiddenDeleteFav ?>"> <?= $_SESSION["lang-txt"]["productos"]["eliminar_favoritos"] ?> <i class="fas fa-heart" style="color:red"></i></a>
                                            </div>
                                        <?php } ?>
                                        <div class="product-count mt-25" style="display: flex !important;">
                                            <div class="quty mr-2">
                                                <input type="number" class="qty" step="1" name="stock" id="product-stock-<?= $productoData["data"]["cod"] ?>" min="1" max="<?= $productoData["data"]["stock"] ?>" value="1">
                                            </div>
                                            <div class="add-tocart">
                                                <input type="hidden" name="product" value="<?= $productoData['data']['cod'] ?>">
                                                <?php if ($productoData['data']['stock'] > 0) { ?>
                                                    <button id="btn-a-1" class="btn-cart position-relative">
                                                        <span class="mr-2"><i class="ion-android-add"></i></span>
                                                        <?= $_SESSION["lang-txt"]["productos"]["agregar_carrito"] ?>
                                                    </button>
                                                <?php } else { ?>
                                                    <button id="btn-a-1" class="btn-cart position-relative" style="color: rgb(139 139 139) !important;" disabled>
                                                        <span class="mr-2"><i class="ion-android-add"></i></span>
                                                        <?= $_SESSION["lang-txt"]["productos"]["agregar_carrito"] ?>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </form>
                                    <div>
                                        <?php if (isset($productoData['bultos'])) { ?>
                                            <?php
                                            foreach ($productoData['bultos'] as $key => $bulto) {
                                                if ($bulto['data']['cod'] != $productoData['data']['cod']) {
                                                    if ($key == 0) { ?>
                                                        <h6 class="text-uppercase bold">
                                                            <hr />
                                                            <?= $_SESSION["lang-txt"]["productos"]["comprar_bulto"] ?>
                                                            <hr />
                                                        </h6>
                                                    <?php } ?>
                                                    <div>
                                                        <a href="<?= $bulto['link'] ?>" class="theme-color bold"># <?= $bulto['data']['titulo'] ?></a>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="single-product-quantity">
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($productoData['data']['desarrollo'])) { ?>
                    <div class="product-tab theme1 bg-white pt-60 pb-80">
                        <div class="container">
                            <div class="product-tab-nav">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <nav class="product-tab-menu single-product">
                                            <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active text-uppercase"><?= $_SESSION["lang-txt"]["productos"]["descripcion"] ?></a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="tab-content" id="pills-tabContent">
                                        <div>
                                            <div class="single-product-desc">
                                                <p>
                                                    <?= $productoData['data']['desarrollo'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
                if ($producto_relacionados_) { ?>
                    <section class="product-tab bg-white pt-50 pb-80">
                        <div class="container">
                            <div class="section-title text-center">
                                <h2 class="title pb-3 mb-3"><?= $_SESSION["lang-txt"]["productos"]["producto_relacionado"] ?></h2>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="product-slider-init theme1 slick-nav">
                                        <?php

                                        if ($producto_relacionados_) {
                                            foreach ($producto_relacionados_ as $producto_relacionados__) {
                                                if ($producto_relacionados__ !=  $cod_producto) {
                                                    $data = [
                                                        "filter" => ["productos.cod='$producto_relacionados__'"],
                                                        "admin" => false,
                                                        "category" => true,
                                                        "subcategory" => true,
                                                        "tercercategory" => true,
                                                        "attribute" => true,
                                                        "promos" => true,
                                                        "combination" => true,
                                                        "bultos" => true,
                                                        "images" => true,
                                                    ];
                                                    $realtedItem = $producto->list($data, $_SESSION['lang'], true);
                                                    if (!empty($realtedItem) && $realtedItem['data']['stock'] > 0 && $realtedItem['data']['precio'] > 0 && $realtedItem['data']['mostrar_web'] == 1) {
                                                        $user = isset($_SESSION['usuarios']['cod']) ? $_SESSION['usuarios']['cod'] : '';
                                                        $link = URL . '/producto/' . $f->normalizar_link($realtedItem["data"]["titulo"]) . '/' . $realtedItem["data"]["cod"];
                                        ?>

                                                        <div class="col-sm-6 col-md-4 col-lg-4 mb-30">
                                                            <div class="card product-card" style="min-height: 500px;">
                                                                <div class="card-body">
                                                                    <div class="product-thumbnail position-relative">
                                                                        <img onclick='window.location.assign("<?= $link ?>")' style="object-fit:contain;width:300px;height:300px" class="first-img" src="<?= $realtedItem["images"][0]["url"] ?>" alt="<?= mb_strtoupper($realtedItem['data']['titulo']) ?>">
                                                                        <ul class="actions d-flex justify-content-center hidden-md-down">
                                                                            <li>
                                                                                <div class="d-none">
                                                                                    <a title="Eliminar de Favorito" class="action wishlist d-none btn-deleteFavorite-<?= $realtedItem['data']['cod'] ?>" onclick="deleteFavorite('<?= $realtedItem['data']['cod'] ?>')"><i class="fa fa-heart" aria-hidden="true" style="color:red>"></i></a>
                                                                                    <a title="Agregar a Favorito" class="action wishlist  btn-addFavorite-<?= $realtedItem['data']['cod'] ?>" onclick="addFavorite('<?= $realtedItem['data']['cod'] ?>','<?= $realtedItem['data']['idioma'] ?>')"><i class="fa fa-heart" aria-hidden="true" style="color:white"></i></a>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="product-desc py-0 px-0" style="min-height:135px">
                                                                        <?php if (!empty($realtedItem['data']['categoria'])) { ?>
                                                                            <a class="blog-link theme-color text-uppercase fs-10" href="<?= URL . "/productos/b/categoria/" .   $realtedItem['data']['categoria'] ?>" tabindex="0"><?= $realtedItem['data']['categoria_titulo'] ?></a>
                                                                            <?php if (!empty($realtedItem['data']['subcategoria'])) { ?>
                                                                                <span class="blog-link theme-color text-uppercase"> | </span>
                                                                                <a class="blog-link theme-color text-uppercase fs-10" href="<?= URL . "/productos/b/categoria/" .   $realtedItem['data']['categoria'] . "/subcategoria/" .   $realtedItem['data']['subcategoria'] ?>" tabindex="0"><?= $realtedItem['data']['subcategoria_titulo'] ?></a>
                                                                                <?php if (!empty($realtedItem['data']['tercercategoria'])) { ?>
                                                                                    <span class="blog-link theme-color text-uppercase"> | </span>
                                                                                    <a class="blog-link theme-color text-uppercase fs-10" href="<?= URL . "/productos/b/categoria/" .   $realtedItem['data']['categoria'] . "/subcategoria/" .   $realtedItem['data']['subcategoria'] . "/tercercategoria/" .   $realtedItem['data']['tercercategoria'] ?>" tabindex="0"><?= @$realtedItem['data']['tercercategoria_titulo'] ?></a>
                                                                        <?php }
                                                                            }
                                                                        } ?>
                                                                        <h3 class="title fs-14">
                                                                            <a href="<?= $realtedItem["link"] ?>"><?= mb_strtoupper($realtedItem['data']['titulo']) ?></a>
                                                                        </h3>
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <span class="product-price">
                                                                                <del class="del fs-18 <?php if ($realtedItem['data']['precio_descuento'] == 0) echo "d-none"; ?>">$<?= $realtedItem['data']['precio'] ?></del>
                                                                                <span class="onsale fs-18">$<?= $realtedItem['data']['precio_final'] ?></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($realtedItem['data']['stock'] > 0) {
                                                                        if ($realtedItem["atributo"] == []) { ?>
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <input type="number" style="border-radius:5px 0px 0px 5px !important" step="1" class="form-control" name="stock" id="product-stock-<?= $realtedItem['data']['cod'] ?>" min="1" max="1" value="1">
                                                                                <button style="padding:10.5px;border-radius: 0px 5px 5px 0px   !important" class="btn btn-sm btn-block btn-outline-dark btn-hover-primary" onclick="addToCart('','<?= $realtedItem['data']['cod'] ?>','<?= URL ?>',false)" title="Agregar al carrito"><i class="icon-basket"></i></button>
                                                                            </div>
                                                                        <?php   } else { ?>
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <a href="<?= $realtedItem["link"] ?>" style='padding:10.5px;border-radius: 5px   !important' class="btn btn-sm btn-block btn-outline-dark btn-hover-primary" title="<?= mb_strtoupper($realtedItem['data']['titulo']) ?>"><i class="fa fa-search"></i></a>
                                                                            </div>
                                                                        <?php    }
                                                                    } else { ?>
                                                                        <div class=" d-flex align-items-center justify-content-between mb-10" style="place-content: center!important;color:red"><?= $_SESSION["lang-txt"]['productos']['sin_stock'] ?></div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                    <?php
                                                    }
                                                }
                                            }
                                        }
                                    } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
            </div>
        </div>
    </div>
</section>
<?php
$template->themeEnd();
?>
<style>
    .single-product-desc * ul {
        padding: 10px;
        margin: 10px;
    }

    .single-product-desc * li {
        margin-left: 30px;
        list-style: circle;

    }

    .slick-prev {
        background: none;
        border: none;
        position: absolute;
        top: 37px;
        left: -33px;
    }

    .slick-next {
        background: none;
        border: none;
        position: absolute;
        bottom: 37px;
        right: -31px;
    }
</style>

<!-- slick -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script>
    $('.slider-single').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: false,
        adaptiveHeight: true,
        infinite: false,
        useTransform: true,
        speed: 400,
        cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)',
    });

    $('.slider-nav')
        .on('init', function(event, slick) {
            $('.slider-nav .slick-slide.slick-current').addClass('is-active');
        })
        .slick({
            slidesToShow: 7,
            slidesToScroll: 7,
            dots: false,
            focusOnSelect: false,
            infinite: false,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5,
                }
            }, {
                breakpoint: 640,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            }, {
                breakpoint: 420,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            }]
        });

    $('.slider-single').on('afterChange', function(event, slick, currentSlide) {
        $('.slider-nav').slick('slickGoTo', currentSlide);
        var currrentNavSlideElem = '.slider-nav .slick-slide[data-slick-index="' + currentSlide + '"]';
        $('.slider-nav .slick-slide.is-active').removeClass('is-active');
        $(currrentNavSlideElem).addClass('is-active');
    });

    $('.slider-nav').on('click', '.slick-slide', function(event) {
        event.preventDefault();
        var goToSingleSlide = $(this).data('slick-index');

        $('.slider-single').slick('slickGoTo', goToSingleSlide);
    });

    $('.slick-next').html('<i class="fas fa-arrow-right"></i>')
    $('.slick-prev').html('<i class="fas fa-arrow-left"></i>')
</script>