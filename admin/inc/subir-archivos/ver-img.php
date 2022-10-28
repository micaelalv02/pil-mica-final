<?php
$producto = new Clases\Productos();
$funciones = new Clases\PublicFunction();
$idiomas = new Clases\Idiomas();
$categoria = new Clases\Categorias();

#Variables GET
$tituloGet = isset($_GET["titulo"]) ? $f->antihack_mysqli(str_replace("-", " ", $_GET["titulo"])) : '';
$mostrarGet =  isset($_GET["mostrar_web"]) ? $f->antihack_mysqli($_GET["mostrar_web"]) : 2;
$categoriaGet = isset($_GET["categoria"]) ? $f->antihack_mysqli($_GET["categoria"]) : '';
$subcategoriaGet = isset($_GET["subcategoria"]) ? $f->antihack_mysqli($_GET["subcategoria"]) : '';
$subcategoriaGet = explode(",", $subcategoriaGet);
$tercercategoriaGet = isset($_GET["tercercategoria"]) ? $f->antihack_mysqli($_GET["tercercategoria"]) : '';
#List de categorías del área productos
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$categoriasData = $categoria->listIfHave('productos', '', $idiomaGet);
?>
<section id="table-transactions" class="mt-30">
    <h4 class="mt-20 pull-left text-uppercase">IMÁGENES</h4>
    <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
        <form method="post" target="_blank" action="<?= URL_ADMIN . "/api/images/delete.php" ?>" onsubmit="reloadSystem()" id="unlink-all" class="inline-block  pull-right ">
            <button class="btn btn-primary deleteConfirm" type="submit" name="remove-all"><i class="fa fa-trash"></i> Eliminar seleccionadas</button>
        </form>
    <?php } ?>
    <button class="inline-block pull-right btn btn-warning mr-10" onclick="$('.check-images').attr('checked',true)"><i class="fa fa-check-square" aria-hidden="true"></i> Seleccionar Todas</button>
    <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
        <a class="btn btn-success inline-block pull-right mr-10" href="<?= URL_ADMIN ?>/index.php?op=subir-archivos&accion=ver&idioma=<?= $idiomaGet ?>"><i class="fa fa-upload"></i> Cargar imágenes</a>
    <?php } ?>
    <div class="clearfix"></div>

    <hr />
    <div class="pb-100">
        <div class="row mt-20">
            <div class="col-lg-3 col-12 pl-0 ml-0" id="filters">
                <form id="filter-form" onsubmit="event.preventDefault();getDataImages()">
                    <aside class="sidebar_widget mt-10 mt-lg-0">
                        <div class="container">
                            <div class="search-filter">
                                <div class="sidbar-widget pt-0">
                                    <h4 class="title fs-18">POR PALABRA</h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="" data-url="<?= URL ?>">
                                <div class="searchbar">
                                    <input class="search_input fs-14 pl-15 " type="text" name="title" value="<?= $tituloGet ?>" placeholder="<?= $_SESSION["lang-txt"]["productos"]["buscar_productos"] ?>">
                                </div>
                            </div>
                            <div class="mt-20">
                                <h4 class="title fs-18">DISPONIBLES</h4>
                                <hr />
                                <label for="mostrarWeb">
                                    <input type="radio" id="mostrarWeb" <?= ($mostrarGet == 1) ? 'checked' : '' ?> name="mostrar_web" value="1" onchange="getDataImages()">
                                    Si</label>
                                <label for="no_mostrarWeb">
                                    <input class="ml-10" type="radio" id="no_mostrarWeb" <?= ($mostrarGet == 0) ? 'checked' : '' ?> name="mostrar_web" value="0" onchange="getDataImages()">
                                    No</label>
                                <label for="todo_mostrarWeb">
                                    <input class="ml-10" type="radio" id="todo_mostrarWeb" <?= ($mostrarGet == 2) ? 'checked' : '' ?> name="mostrar_web" value="2" onchange="getDataImages()">
                                    Mostrar Ambos</label>
                                </p>
                            </div>
                            <div class="widget-list mb-10 mt-20">
                                <div class="search-filter">
                                    <div class="sidbar-widget pt-0">
                                        <h4 class="title fs-18">CATEGORIAS</h4>
                                        <hr />
                                    </div>
                                </div>
                                <ul class="ulProducts">
                                    <?php
                                    if (!empty($categoriasData)) {
                                        foreach ($categoriasData as $key => $cat) {
                                            $link_cat =  URL . "/productos/b/categoria/" . $cat['data']['cod'];
                                    ?>
                                            <li class=" list-style-none mb-10 text-uppercase drop menu-item-has-children categorias  fs-12">
                                                <div class="sidebar-widget-list-left ">
                                                    <label for="cat-<?= $cat['data']['cod'] ?>" class="fs-12 text-uppercase">
                                                        <input id="cat-<?= $cat['data']['cod'] ?>" value="<?= $cat['data']['cod'] ?>" <?= ($categoriaGet == $cat["data"]["cod"]) ? 'checked' : '' ?> name="categories[]" type="checkbox" class="check" onchange="changeSelect('<?= $cat['data']['cod'] ?>');">
                                                        <?= $cat['data']['titulo'] ?>
                                                    </label>
                                                </div>
                                                <ul id="<?= $cat['data']['cod'] ?>SubCat" class="ulProductsDropdown subcategorias pl-20 dropdown" style="<?= ($categoriaGet == $cat["data"]["cod"]) ? '' : 'display:none' ?>">
                                                    <?php
                                                    foreach ($cat["subcategories"] as $key_ => $sub) {
                                                    ?>
                                                        <li class="list-style-none">
                                                            <div class="sidebar-widget-list-left  fs-12">
                                                                <label>
                                                                    <input id="sub-<?= $cat['data']['cod'] ?>-<?= $sub['data']['cod'] ?>" value="<?= $sub['data']['cod'] ?>" <?= (in_array($sub['data']['cod'], $subcategoriaGet)) ? 'checked' : '' ?> class="check" name="subcategories[]" type="checkbox" onchange="changeSelect('<?= $cat['data']['cod'] ?>','<?= $sub['data']['cod'] ?>')">
                                                                    <?= $sub['data']['titulo'] ?>
                                                                </label>
                                                                <ul id="<?= $sub['data']['cod'] ?>TerCat" class="ulProductsDropdown tercercategorias pl-20 dropdown" style="<?= ($subcategoriaGet == $sub["data"]["cod"]) ? '' : 'display:none' ?>">
                                                                    <?php
                                                                    if (!empty($sub["tercercategories"])) {
                                                                        foreach ($sub["tercercategories"] as $key3 => $ter) { ?>
                                                                            <li class="list-style-none">
                                                                                <label class="fs-12 text-uppercase">
                                                                                    <input id="ter-<?= $ter["data"]["cod"] ?>" value="<?= $ter['data']['cod'] ?>" <?= ($tercercategoriaGet == $ter['data']['cod']) ? 'checked' : '' ?> class="check" name="tercercategories[]" type="checkbox" onchange="getDataImages()">
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
                        </div>
                    </aside>
                </form>
            </div>
            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <?php
                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                        $url =  URL_ADMIN . "/index.php?op=subir-archivos&accion=ver&idioma=" . $idioma_["data"]["cod"];
                    ?>
                        <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?>" href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                    <?php } ?>

                </ul>
                <div class="row" data-url="<?= URL_ADMIN ?>" id="grid-products" data-idioma="<?= $idiomaGet ?>"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="error-msg"></div>
                        <div class="text-center" id="grid-products-loader">
                            <button id="grid-products-btn" class="btn btn-lg" onclick="loadMore()">
                                CARGAR MÁS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var start = 0;
    var limit = 200;

    getDataImages()

    function getDataImages(type) {
        var eliminar = '<?= $_SESSION["admin"]["crud"]["eliminar"] ?>';
        const list = (type != 'add') ? true : false;
        disableLoadMore()
        result = '';
        $.ajax({
            url: "<?= URL_ADMIN ?>/api/productos/list.php?idioma=<?= $idiomaGet ?>&start=" + start + "&limit=" + limit + "&images=true&full=true",
            type: "POST",
            data: $('#filter-form').serialize(),
            success: async (data) => {
                result = JSON.parse(data);
                (result.product.length) ? enableLoadMore(): '';
                (list) ? reset(): enableLoadMore();
                result.product.forEach(element => {
                    element.images.forEach(img => {
                        if (!img.url.includes('sin_imagen')) {
                            var btnEliminar = '';
                            if (eliminar == true) var btnEliminar = `<a class="btn btn-sm pr-1 pl-1 btn-primary pull-right deleteConfirm" target="_new" onclick="hideUrl('` + img.url + `')" href="<?= URL_ADMIN ?>/api/images/delete.php?url=` + img.url + `"><i class="fa fa-trash"></i></a>`;
                            $('#grid-products').append(`
                                            <div class="col-md-2 imagenesAdmin" data-value='` + img.url + `' style="margin-bottom: 20px;">
                                                <div style="box-shadow: 2px 2px 14px 1px #ccc;padding: 10px">
                                                    <div class="mb-10" style="background: url('` + img.url + `') center/contain no-repeat; height: 100px;">
                                                       <input type="checkbox" form="unlink-all" class="check-images" name="img[]" value="'` + img.url + `'">
                                                       ` + btnEliminar + `
                                                    </div>
                                                    <div class="fs-12">
                                                        <a target="_new" href="` + img.url + `"> ` + element.data.titulo + ` </a>
                                                        <br/>${(element.data.cod_producto != null) ? element.data.cod_producto : ''}
                                                    </div>
                                                </div>
                                            </div>`);
                        }
                    });
                });
            }
        });
        return result;
    }

    function hideUrl(url) {
        $("#grid-products [data-value='" + url + "']").hide();
    }

    function reloadSystem() {
        $('.imagenesAdmin input[type=checkbox]:checked').map(function() {
            $("#grid-products [data-value=" + this.value + "]").hide();
        });
    }

    function loadMore() {
        disableLoadMore();
        start += limit;
        getDataImages('add');
    }

    function disableLoadMore() {
        $('#grid-products-btn').hide();
    }

    function enableLoadMore() {
        $('#grid-products-btn').show();
    }

    function reset() {
        $('#grid-products').html('');
    }

    function changeSelect(cat, sub = '') {

        if (cat && !sub) {
            let subcat_list = $('#' + cat + 'SubCat');

            $("#cat-" + cat).removeClass("check"); //remover clase de .check de la cateogria clickeada

            $(".ulProductsDropdown").hide(); //hide oculta todas las clases ulProductsDropdown

            $(".check").prop("checked", false);

            ($("#cat-" + cat).prop("checked")) ? subcat_list.show(): subcat_list.hide();

            $("#cat-" + cat).addClass("check");
            var start = 0;

            getDataImages();
        }

        if (cat && sub) {
            let subcat_list = $('#' + sub + 'TerCat');

            $("#sub-" + cat + "-" + sub).removeClass("check"); //remover clase de .check de la cateogria clickeada

            $('#' + sub + 'SubCat .ulProductsDropdown').hide(); //hide oculta todas las clases ulProductsDropdown

            $(".tercercategorias .check").prop("checked", false);

            ($("#sub-" + cat + "-" + sub).prop("checked")) ? subcat_list.show(): subcat_list.hide();

            $("#sub-" + cat + "-" + sub).addClass("check");
            var start = 0;

            getDataImages();
        }


    }
</script>