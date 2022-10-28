<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();
$promo = new Clases\Promos();
$categoria = new Clases\Categorias();

#Variables GET
$tituloGet = isset($_GET["titulo"]) ? $f->antihack_mysqli(str_replace("-", " ", $_GET["titulo"])) : '';
$categoriaGet = isset($_GET["categoria"]) ? $f->antihack_mysqli($_GET["categoria"]) : '';

$subcategoriaGet = isset($_GET["subcategoria"]) ? $f->antihack_mysqli($_GET["subcategoria"]) : '';
$tercercategoriaGet = isset($_GET["tercercategoria"]) ? $f->antihack_mysqli($_GET["tercercategoria"]) : '';

#List de categorías del área productos
$filtroPromo = $promo->exist();
$filtroConDescuento = $producto->list(['filter' => ["precio_descuento > 0", "mostrar_web = '1'"], "limit" => 1], $_SESSION['lang'], true);
$filtroStock = $producto->list(['filter' => ["stock = 0", "mostrar_web = '1'"], "limit" => 1], $_SESSION['lang'], true);
$categoriasData = $categoria->listIfHave('productos');
#Información de cabecera
$template->set("title", "Productos | " . TITULO);
$template->set("description", "");
$template->set("keywords", "");
$template->themeInit();

?>
<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis">
                    <div class="section-title">
                        <h2><?= $_SESSION['lang-txt']['general']['productos'] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION['lang-txt']['general']['inicio'] ?></a></li>
                                <?php if (!empty($categoriaGet)) { ?>
                                    <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= URL ?>"><?= $categoriaGet ?></a></li>
                                <?php } ?>
                                <?php if (!empty($subcategoriaGet)) { ?>
                                    <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= URL ?>"><?= $subcategoriaGet ?></a></li>
                                <?php } ?>
                                <?php if (!empty($tercercategoriaGet)) { ?>
                                    <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= URL ?>"><?= $tercercategoriaGet ?></a></li>
                                <?php } ?>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="product-tab bg-white pt-30 px-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-12 col-custom" id="filters">
                <form id="filter-form" onsubmit="event.preventDefault();getData()">
                    <aside class="sidebar_widget mt-10 mt-lg-0">
                        <div class="container">
                            <div class="search-filter">
                                <div class="sidbar-widget pt-0">
                                    <h4 class="title"><?= $_SESSION["lang-txt"]["productos"]["filtros"] ?></h4>
                                </div>
                            </div>
                            <div class="sidebar-search mb-20 mt-20 hidden-md-up">
                                <div class="sidebar-search-form">
                                    <div class="row">
                                        <div class="col-9">
                                            <input type="text" class="form-control" value="<?= (!empty($tituloGet)) ? $tituloGet : '' ?>" name="title" placeholder="<?= $_SESSION["lang-txt"]["productos"]["buscar_productos"] ?>">
                                        </div>
                                        <div class="col-3">
                                            <button type="submit" class="btnSearch" onclick="$('#filters').hide();">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-widget-list-left mt-20 ">
                                <?php if (!empty($filtroPromo)) { ?>
                                    <label for="en_promocion" class="fs-14 text-uppercase">
                                        <input class="auto-save" type="checkbox" name="en_promocion" id="en_promocion" value="1" onclick="resetPage();">
                                        <?= $_SESSION["lang-txt"]["productos"]["en_promocion"] ?>
                                    </label>
                                <?php } ?>
                                <?php if (!empty($filtroConDescuento)) { ?>
                                    <br>
                                    <label for="en_descuento" class="fs-14 text-uppercase">
                                        <input class="auto-save" type="checkbox" name="en_descuento" id="en_descuento" value="1" onclick="resetPage();">
                                        <?= $_SESSION["lang-txt"]["productos"]["en_descuento"] ?>
                                    </label>
                                <?php } ?>
                                <?php if (!empty($filtroStock)) { ?>
                                    <br>
                                    <label for="con_stock" class="fs-14 text-uppercase mt-10">
                                        <input class="auto-save" type="checkbox" name="con_stock" id="con_stock" value="1" onclick="resetPage();">
                                        <?= $_SESSION["lang-txt"]["productos"]["con_stock"] ?>
                                    </label>
                                <?php } ?>
                            </div>
                            <div class="widget-list mb-10 mt-20">
                                <div class="search-filter">
                                    <div class="sidbar-widget pt-0">
                                        <h4 class="title"><?= $_SESSION["lang-txt"]["productos"]["categorias"] ?></h4>
                                    </div>
                                </div>
                                <ul class="ulProducts">
                                    <?php
                                    if (!empty($categoriasData)) {
                                        foreach ($categoriasData as $key => $cat) {
                                            $link_cat =  URL . "/productos/b/categoria/" . $cat['data']['cod'];
                                    ?>
                                            <li class=" list-style-none mb-10 text-uppercase drop menu-item-has-children categorias  fs-14">
                                                <div class="sidebar-widget-list-left ">
                                                    <label for="cat-<?= $cat['data']['cod'] ?>" class="fs-14 text-uppercase">
                                                        <input id="cat-<?= $cat['data']['cod'] ?>" value="<?= $cat['data']['cod'] ?>" <?= ($categoriaGet == $cat["data"]["cod"]) ? 'checked' : '' ?> name="categories[]" class="check auto-save-categories" type="checkbox" onchange="changeURL();changeSelect('<?= $cat['data']['cod'] ?>');getData();">
                                                        <?= $cat['data']['titulo'] ?>
                                                    </label>
                                                </div>
                                                <ul id="<?= $cat['data']['cod'] ?>SubCat" class="ulProductsDropdown subcategorias pl-20 dropdown" style="<?= ($categoriaGet == $cat["data"]["cod"]) ? '' : 'display:none' ?>">
                                                    <?php

                                                    foreach ($cat["subcategories"] as $key_ => $sub) {
                                                    ?>
                                                        <li class="list-style-none">
                                                            <div class="sidebar-widget-list-left  fs-14">
                                                                <label>
                                                                    <input id="sub-<?= $cat['data']['cod'] ?>-<?= $sub['data']['cod'] ?>" value="<?= $sub['data']['cod'] ?>" <?= ($subcategoriaGet == $sub["data"]["cod"]) ? 'checked' : '' ?> class="check auto-save-subcategories" name="subcategories[]" type="checkbox" onchange="changeURL();changeSelect('<?= $cat['data']['cod'] ?>','<?= $sub['data']['cod'] ?>','deleteTerCat');getData()">
                                                                    <?= $sub['data']['titulo'] ?>
                                                                </label>
                                                                <ul id="<?= $sub['data']['cod'] ?>TerCat" class="ulProductsDropdown tercercategorias pl-20 dropdown" style="<?= ($subcategoriaGet == $sub["data"]["cod"]) ? '' : 'display:none' ?>">
                                                                    <?php
                                                                    if (!empty($sub["tercercategories"])) {
                                                                        foreach ($sub["tercercategories"] as $key3 => $ter) { ?>
                                                                            <li class="list-style-none">
                                                                                <label class="fs-14 text-uppercase">
                                                                                    <input id="ter-<?= $ter["data"]["cod"] ?>" value="<?= $ter['data']['cod'] ?>" <?= ($tercercategoriaGet == $ter['data']['cod']) ? 'checked' : '' ?> class="check auto-save-tercercategories" name="tercercategories[]" type="checkbox" onchange="changeURL();getData()">
                                                                                    <?= $ter['data']['titulo'] ?>
                                                                                </label>
                                                                            </li>
                                                                    <?php }
                                                                    } ?>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            <hr />
                            <div class="row hidden-md-up">
                                <div class="col-6">
                                    <div onclick="$('#filters').hide();" class="btn-filter-options"><i class="fa fa-times-circle"></i> CERRAR</div>
                                </div>
                                <div class="col-6">
                                    <div onclick="$('#filters').hide();" class="btn-filter-options"><i class="fa fa-check-circle"></i> APLICAR</div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </form>
            </div>
            <div class="col-lg-7 mb-30">
                <div class="grid-nav-wraper bg-lighten2 mb-30">
                    <div class="row align-items-center">
                        <div class="position-relative">
                            <div class="shop-grid-button d-flex align-items-center">
                                <span><?= $_SESSION["lang-txt"]["productos"]["ordenar"] ?></span>
                                <select id="order" class="form-select custom-select auto-save" style="width: 80%;margin-left: 10px;" onchange="getData()">
                                    <option value="1">
                                        <?= $_SESSION["lang-txt"]["productos"]["ultimos"] ?>
                                    </option>
                                    <option value="2">
                                        <?= $_SESSION["lang-txt"]["productos"]["menor_mayor"] ?>
                                    </option>
                                    <option value="3">
                                        <?= $_SESSION["lang-txt"]["productos"]["mayor_menor"] ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- product-tab-nav end -->
                <div class="tab-content" id="pills-tabContent">
                    <!-- first tab-pane -->
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="row grid-view theme1">
                            <div class="pull-right hidden-md-up" style="width: 100%">
                                <button id="filter-button" class="btn btn-primary btn-filter" onclick="$('#filters').show();"> <b><?= $_SESSION["lang-txt"]["productos"]["ver_filtros"] ?></b></button>
                            </div>
                            <div class="products-section shop mt-0" style="width: 100%">
                                <div class=" shop_wrapper grid_3">
                                    <div class="row grid-products" data-url="<?= URL ?>"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center mt-40">
                                <button id="grid-products-btn" class="btn btn-cart loadMoreportfolio" onclick="loadMore()">
                                    <span><?= $_SESSION["lang-txt"]["productos"]["cargar_mas"] ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3  pull-right d-none d-lg-block navCart">
                <div id="sideCart">
                    <div class="offcanvas-cart-content">
                        <h2 class="offcanvas-cart-title mb-10 fs-16  text-uppercase ">
                            <i class="fa fa-shopping-cart"></i> <?= $_SESSION["lang-txt"]["carrito"]["mi_carrito"] ?>
                        </h2>
                        <cart></cart>
                            <btn-finish-cart></btn-finish-cart>
                        <?php if (isset($_SESSION["usuarios"]["cod"])) { ?>
                            <div class="row mt-10 mb-20">
                                <div class="col-md-6 col-6"><a onclick="saveCartPerFile('<?= URL ?>')" class="btn-cart pl-10 pr-10 pull-left"><i class="fa fa-save" aria-hidden="true"></i> GUARDAR CARRITO</a></div>
                                <div class="col-md-6 col-6"><a href="<?= URL ?>/sesion/carritos" class="btn-cart pl-10 pr-10 pull-right">VER GUARDADOS</a></div>
                            <?php } else { ?>
                                <div class="row mt-10">
                                    <div class="col-md-12 col-12"><a href="<?= URL ?>/usuarios?carrito=1" class="btn-cart pl-10 pr-10  fs-14"><i class="fa fa-save" aria-hidden="true"></i> GUARDAR CARRITO</a></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$template->themeEnd();
?>

<script>
    jQuery(document).ready(function() {
        initPage();
    });
</script>