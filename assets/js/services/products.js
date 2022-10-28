var start = 0;
var limit = 24;
var order = 'id ASC';
var url = $("body").attr("data-url");



function loadMore() {
    getData('add');
}

function disableLoadMore() {
    $('#grid-products-btn').hide();
}

function enableLoadMore() {
    $('#grid-products-btn').show();
}

function changeURL() {
    if (window.location.href.indexOf("/productos/b" > 0) && window.location.href != url + "/productos" && window.location.href != url + "/productos/") {
        window.history.pushState("", "", url + "/productos");
    }
}

function resetPage() {
    localStorage.setItem("page", 1);

    getData();
}

function initPage() {
    if (window.location.href.search("/productos/promocion") != -1) {
        $('#en_promocion').prop("checked", true);
    }

    $("input:checkbox:checked").each(function() {
        $("#" + $(this).val() + "SubCat").show();
        $("#" + $(this).val() + "TerCat").show();
    });

    var a = new StickySidebar('#sideCart', {
        topSpacing: 110,
    });

    getData();
}

function changeSelect(cat, sub = '', tercat = '') {
    if (cat && !sub) {
        let subcat_list = $('#' + cat + 'SubCat');
        $("#cat-" + cat).removeClass("check"); //remover clase de .check de la categoria clickeada
        $(".check").prop("checked", false);
        $("#cat-" + cat).addClass("check");
        $(".ulProductsDropdown").hide(); //hide oculta todas las clases ulProductsDropdown
        ($("#cat-" + cat).prop("checked")) ? subcat_list.show(): subcat_list.hide();
    }
    if (cat && sub) {
        let subcat_list = $('#' + sub + 'TerCat');
        $("#sub-" + cat + "-" + sub).removeClass("check"); //remover clase de .check de la categoria clickeada
        $('#' + sub + 'SubCat .ulProductsDropdown').hide(); //hide oculta todas las clases ulProductsDropdown
        $(".tercercategorias .check").prop("checked", false);
        ($("#sub-" + cat + "-" + sub).prop("checked")) ? subcat_list.show(): subcat_list.hide();
        $("#sub-" + cat + "-" + sub).addClass("check");
    }
}

function getData(type) {
    order = $("#order").val();
    const list = (type != 'add') ? true : false;
    start = (type == 'add') ? limit : 0;
    if (isNaN(parseInt(localStorage.getItem("page")))) localStorage.setItem("page", 1);
    if (type == 'add') localStorage.setItem("page", parseInt(localStorage.getItem("page")) + 1);
    page = parseInt(localStorage.getItem("page"));
    limit = 24 * page;
    start = (type != 'add') ? 0 : start;
    if (url) {
        $.ajax({
            url: url + "/api/products/get_products.php?start=" + start + "&limit=" + limit + "&order=" + order,
            type: "POST",
            data: $('#filter-form').serialize(),
            success: (data) => {
                if (data) {
                    var productsData = JSON.parse(data);
                    if (list) reset();
                    (parseInt(productsData.products.length) < limit) ? disableLoadMore(): enableLoadMore();
                    productsData.products.forEach(element => {
                        product = createElement(element, productsData.user);
                        $('.grid-products').append(product);
                    });
                } else {
                    $('.grid-products').html('<div class="alert alert-warning d-block">' + lang["productos"]["producto_no_encontrado"] + '</div>');
                    disableLoadMore();
                }
            }
        });
    }
}

function getDataOutstanding() {
    if (url) {
        $.ajax({
            url: url + "/api/products/get_products.php?start=" + start + "&limit=" + limit + "&order=" + order,
            type: "POST",
            data: { destacado: 1, },
            success: (data) => {
                if (data) {
                    var productsData = JSON.parse(data);
                    productsData.products.forEach(element => {
                        product = createElement(element, productsData.user);
                        $('.grid-products-outstanding').append(product);
                    });
                }
            }
        });
    }
}

function getDataFavorites() {
    var user = $(".grid-favorites").attr("data-favorites");
    var url__2 = $(".grid-favorites").attr("data-url");
    var col = $(".grid-favorites").attr("data-col");
    if (url__2) {
        $.ajax({
            url: url__2 + "/api/favorites/favorite.php",
            type: "GET",
            data: {
                user: user
            },
            success: (data) => {
                $('.grid-favorites').html('');
                if (data != '') {
                    var productsData = JSON.parse(data);
                    (productsData.products.length) ? disableLoadMore(): enableLoadMore();
                    productsData.products.forEach(element => {
                        product = createElement(element, true, col);
                        $('.grid-favorites').append(product);
                    });
                } else {
                    $('.grid-favorites').append('<div class="alert alert-warning d-block text-center">' + lang["sesion"]["sin_productos_favoritos"] + '</div>');
                    disableLoadMore();
                }
            }
        });
    }
}

function getDataFavoritesSesion() {
    var user = $(".grid-favorites-sesion").attr("data-favorites");
    var url__2 = $(".grid-favorites-sesion").attr("data-url");
    var col = $(".grid-favorites-sesion").attr("data-col");
    if (url__2) {
        $.ajax({
            url: url__2 + "/api/favorites/favorite.php",
            type: "GET",
            data: {
                user: user
            },
            success: (data) => {
                $('.grid-favorites-sesion').html('');
                if (data != '') {
                    var productsData = JSON.parse(data);
                    (productsData.products.length) ? disableLoadMore(): enableLoadMore();
                    productsData.products.forEach(element => {
                        product = createElement(element, true, col);
                        $('.grid-favorites-sesion').append(product);
                    });
                } else {
                    $('.grid-favorites-sesion').append('<div class="alert alert-warning d-block text-center">' + lang["sesion"]["sin_productos_favoritos"] + '</div>');
                    disableLoadMore();
                }
            }
        });
    }
}

function createElement(element, user, col = 4) {
    var price_old = '';
    if (user.minorista != 0) {
        var price_old = (element["data"]["precio_descuento"] != null && element["data"]["precio_descuento"] != 0) ? "$" + element["data"]["precio"] : "";
    }
    var price = (element["data"]["precio_final"]) ? "$" + element["data"]["precio_final"] : "";
    var img = (element["images"][0] != null) ? element["images"][0]["thumb"] : '';
    var link = element['link'];
    var title = element["data"]["titulo"] != null ? element["data"]["titulo"].toUpperCase() : '';
    var user_login = (user == '') ? 'd-none' : '';
    var fecha = element["nuevo"];
    var txtPorcent = '';
    var promo = (element["data"]["promoLleva"] != null && element["data"]["promoPaga"] != null) ? `<span class='badge rounded-pill bg-success top-left fs-12'>` + element["data"]["promoLleva"] + `x` + element["data"]["promoPaga"] + ` | $` + ((element['data']['precio_final'] * element['data']['promoPaga']) / element["data"]["promoLleva"]).toFixed(2) + `  x Un.</span>` : '';
    if (element["data"]["precio_descuento"]) {
        total = element["data"]["precio"];
        porcentaje = (element["data"]["precio_final"] / total) * 100 - 100;
        porcentaje = Math.floor(porcentaje);
        if (porcentaje < -4) {
            var txtPorcent = porcentaje + "%";
        }
    }
    if (element['favorite']['data'] != null) {
        var hiddenAddFav = 'd-none';
        var hiddenDeleteFav = '';
    } else {
        var hiddenAddFav = '';
        var hiddenDeleteFav = 'd-none';
    }
    if (element["data"]["stock"] > 0) {
        if (element["atributo"] == '') {
            //SI NO TIENE ATRIBUTO/COMBINACION
            var btnCompra =
                `<div class="d-flex align-items-center justify-content-between">
                <input type="number"  step="1"  class="form-control"   name="stock" id="product-stock-` + element["data"]["cod"] + `" min="1" max="` + element["data"]["stock"] + `"   value="1">
                <button   class="btn btn-sm btn-block btn-product-add btn-hover-primary" onclick="addToCart('','` + element["data"]["cod"] + `','` + url + `',false)" title="` + lang['productos']['agregar_carrito'] + `"><i class="fas fa-shopping-cart"></i></button>
            </div>`
        } else {
            var btnCompra =
                `<div class="d-flex align-items-center justify-content-between">
                    <a href="` + link + `"   !important' class="btn btn-sm btn-block btn-product-add btn-hover-primary" title="` + lang['productos']['ver_producto'] + `"><i class="fa fa-search"></i></a>
                </div>`
        }
    } else {
        var btnCompra = `<div class=" d-flex align-items-center justify-content-between" style="place-content: center!important;color:red">` + lang['productos']['sin_stock'] + `</div>`

    }
    var linksCategorias = '';
    if (element["data"]['categoria'] != null) {
        linksCategorias += `
         <a class="blog-link theme-color text-uppercase fs-10" href="` + url + `/productos/b/categoria/` + element["data"]['categoria'] + `" tabindex="0">` + element["data"]['categoria_titulo'] + `</a>`;
        if (element["data"]['subcategoria'] != null) {
            linksCategorias += ` <span class="blog-link theme-color text-uppercase"> | </span>
            <a class="blog-link theme-color text-uppercase fs-10" href="` + url + `/productos/b/categoria/` + element["data"]['categoria'] + `/subcategoria/` + element["data"]['subcategoria'] + `" tabindex="0">` + element["data"]['subcategoria_titulo'] + `</a>`;
            if (element["data"]['tercercategoria'] != null) {
                linksCategorias += ` <span class="blog-link theme-color text-uppercase"> | </span>
                <a class="blog-link theme-color text-uppercase fs-10" href="` + url + `/productos/b/categoria/` + element["data"]['categoria'] + `/subcategoria/` + element["data"]['subcategoria'] + `/tercercategoria/` + element["data"]['tercercategoria'] + `" tabindex="0">` + element["data"]['tercercategoria_titulo'] + `</a>`;
            }
        }
    }
    var product = `
    <div class="col-sm-6 col-md-4 col-lg-` + col + ` mb-30">
        <div class="card product-card height-530" >
            <div class="card-body">
                <div class="product-thumbnail position-relative height-300">
                    <span class="badge badge-success top-left">` + txtPorcent + `</span>
                    <span class="badge badge-danger top-right">` + fecha + `</span>
                    <span class="badge badge-primary bottom-left">` + promo + `</span>
                    <div class="arrival-img" style="text-align-last: center;">
                        <div class="` + user_login + ` fav-product">
                            <a title="` + lang['productos']['eliminar_fav'] + `" style="color:red" class="action wishlist ` + hiddenDeleteFav + ` btn-deleteFavorite-` + element["data"]["cod"] + `"  onclick="deleteFavorite('` + element["data"]["cod"] + `','` + element["data"]["idioma"] + `'); getDataFavorites();"><i class="fa fa-heart" aria-hidden="true" style="color:red>"></i></a>
                            <a title="` + lang['productos']['agregar_fav'] + `"    class="action wishlist ` + hiddenAddFav + ` btn-addFavorite-` + element["data"]["cod"] + `" onclick="addFavorite('` + element["data"]["cod"] + `','` + element["data"]["idioma"] + `'); getDataFavorites();"><i class="fa fa-heart" aria-hidden="true" style="color:white>"></i></a>
                        </div>
                        <a href="` + link + `">
                            <img style="object-fit:contain;width:100%;" class="align-items-center first-img" src="` + img + `" alt="` + title + `" />
                        </a>
                    </div>
                    <ul class="actions d-flex justify-content-center hidden-md-down">
                        <li>
                        </li>
                        <li>
                            <a class="action" href="#" data-toggle="modal" data-target="#quick-view" onclick="modalquickview('` + element["data"]["cod"] + `','` + user + `');">
                                <span data-toggle="tooltip" data-placement="bottom" title="Quick view" class="icon-magnifier" ></span>
                            </a>
                      </li>
                    </ul>
                </div>
                <div class="product-desc py-0 px-0 height-130 mt-4"  >
                  ` + linksCategorias + `
                    <h3 class="title fs-13">
                        <a href="` + link + `">` + title + `</a>
                    </h3>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="product-price">
                            <del class="del fs-18">` + price_old + `</del>
                            <span class="onsale fs-18">` + price + `</span>
                        </span>
                    </div>
                </div>
                ` + btnCompra + `
            </div>
        </div>
    </div>`;
    return product;
}

function modalquickview(cod) {
    $('#modalPr-precio').html('');
    $('#modalPr-cod').html('');
    $('#modalPr-desarrollo').html('');
    $('#modalPr-stock-finish').html('');

    $('#modalPr-fav').html('');
    $('#modalPr-img').html('');
    $('#modalPr-titulo').html('');
    $('#modalPr-variacion').html('');

    $.ajax({
        url: url + "/api/products/get_one_product.php",
        type: "POST",
        data: {
            cod: cod
        },
        success: (data) => {
            var productData = JSON.parse(data);
            var price_old = (productData['product']["data"]["precio_descuento"] != null && productData['product']["data"]["precio_descuento"] != 0) ? "<del class='del'>$" + productData['product']["data"]["precio"] + "</del>" : "";
            var price = (productData['product']["data"]["precio_final"]) ? "$" + productData['product']["data"]["precio_final"] : "";
            var cod_product = productData['product']["data"]["cod_producto"] != null ? "COD: " + productData['product']["data"]["cod_producto"] : '';
            var desarrollo = productData['product']["data"]["description"] != null ? productData['product']["data"]["description"] : '';
            var user_login = (productData['user'] != '') ? true : false;
            var promo = (productData["product"]["data"]["promoLleva"] != null && productData["product"]["data"]["promoPaga"] != null) ? "<span class='badge bg-success top-left fs-12'>Promo: Lleva " + productData["product"]["data"]["promoLleva"] + " Paga " + productData["product"]["data"]["promoPaga"] + "</span>" : '';
            var offertPromo = (promo != '') ? `<div class=" mt-1 mb-1 fs-12" style="margin-bottom: 0px">Llevando ` + productData["product"]["data"]["promoLleva"] + `: <span class="fs-12"> <span class="text-warning bold px-1 fs-14" >$` + ((productData["product"]["data"]["precio_final"] * productData["product"]["data"]["promoPaga"]) / productData["product"]["data"]["promoLleva"]).toFixed(2) + `</span>  x Un.</span></div>` : '';
            if (productData["product"]["favorite"]["data"] != null) {
                var hiddenAddFav = 'd-none';
                var hiddenDeleteFav = '';
            } else {
                var hiddenAddFav = '';
                var hiddenDeleteFav = 'd-none';
            }
            if (productData['product']["images"] != null) {
                img = promo + `<div class="product-sync-init mb-20"> `;
                img_nav = `<div class="product-sync-nav">`;
                productData.product.images.forEach(imgData => {
                    img += `
                    <div class="single-product">
                        <div class="product-thumb">
                            <img style="object-fit:contain;width:300px;height:300px" src="` + imgData.url + `" alt="product-thumb" />
                        </div>
                     </div>
                          `;
                    img_nav += `
                          <div class="single-product">
                            <div class="product-thumb">
                              <a href="javascript:void(0)"><img style="object-fit:contain;width:100px;height:100px" src="` + imgData.url + `"  alt="product-thumb" /></a>
                            </div>
                          </div>
                             `;
                });

                img += `</div>`;
                img_nav += `</div > `;
                $('#img-slick').html(img);
                $('#img-slick-nav').html(img_nav);
                $(".product-sync-init").slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    draggable: false,
                    arrows: false,
                    dots: false,
                    fade: true,
                    asNavFor: ".product-sync-nav"
                });
                $(".product-sync-nav").slick({
                    dots: false,
                    arrows: false,
                    infinite: true,
                    prevArrow: '<button class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
                    nextArrow: '<button class="slick-next"><i class="fas fa-arrow-right"></i></button>',
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: ".product-sync-init",
                    focusOnSelect: true,
                    draggable: false
                });
            } else {
                $('#modalPr-img').append(`<a class="swiper-slide" href = "#" > <img class="w-100" style="object-fit:contain;width:300px;height:300px" src="` + url + `/assets/archivos/sin_imagen.jpg" alt="Product"></a>`);
            }


            var linksCategorias = '';
            if (productData['product']["data"]['categoria'] != null) {
                linksCategorias += `
                 <a class="blog-link theme-color text-uppercase fs-10" href="` + url + `/productos/b/categoria/` + productData['product']["data"]['categoria'] + `" tabindex="0">` + productData['product']["data"]['categoria_titulo'] + ` </a>`;
                if (productData['product']["data"]['subcategoria'] != null) {
                    linksCategorias += `<span class="blog-link theme-color text-uppercase mb-10"> | </span>
                    <a class="blog-link theme-color text-uppercase fs-10" href="` + url + `/productos/b/categoria/` + productData['product']["data"]['categoria'] + `/subcategoria/` + productData['product']["data"]['subcategoria'] + `" tabindex="0">` + productData['product']["data"]['subcategoria_titulo'] + ` </a>`;
                    if (productData['product']["data"]['tercercategoria'] != null) {
                        linksCategorias += `<span class="blog-link theme-color text-uppercase mb-10"> | </span>
                        <a class="blog-link theme-color text-uppercase fs-10" href="` + url + `/productos/b/categoria/` + productData['product']["data"]['categoria'] + `/subcategoria/` + productData['product']["data"]['subcategoria'] + `/tercercategoria/` + productData['product']["data"]['tercercategoria'] + `" tabindex="0">` + productData['product']["data"]['tercercategoria_titulo'] + `</a>`;
                    }
                }
            }

            var combi = `<input type="hidden" name="combinationInfo" class="hidden-data">`;

            if (productData['product']["atributo"].length > 0) {
                productData['product']["atributo"].forEach(atrib => {
                    combi += `<label class="mb-10">` + atrib['atribute']['value'] + `
                        <select class="form-control" id="` + atrib['atribute']['cod'] + `" onchange="refreshFront();" name="atribute[` + atrib['atribute']['cod'] + `]" required>
                            <option disabled selected></option>
                         `;
                    atrib['atribute']['subatributes'].forEach(sub => {
                        combi += `                       
                                <option data-value='` + sub['value'].toUpperCase() + `' value="` + sub['cod'] + `">
                                ` + sub['value'].toUpperCase() + `
                                </option>`;
                    });
                    combi += `</select> </label> `;
                });
                combi += `<input type='hidden' name='amount-atributes' value='` + productData['product']["atributo"].length + `'>`;
                if (productData['product']["combination"] != '') {
                    combi += `<input type='hidden' name='combination' value='combination'>`;
                }
            }
            var prices = `
                    <span class="product-price fs-25 mr-20"> ` + price_old + `
                    <span class="onsale" id="s-price">` + price + `</span >`;
            if (user_login) {
                var favs = `
            <div class="addto-whish-list">
                <a  onclick="deleteFavorite('` + productData["product"]["data"]["cod"] + `','` + productData["product"]["data"]["idioma"] + `')"  class="` + hiddenDeleteFav + ` btn-deleteFavorite-` + productData['product']["data"]["cod"] + `"><i class="fa fa-heart" aria-hidden="true" style="color:red>"></i>` + lang['productos']['eliminar_fav'] + `</a>
                <a onclick="addFavorite('` + productData["product"]["data"]["cod"] + `','` + productData["product"]["data"]["idioma"] + `')" class="` + hiddenAddFav + ` btn-addFavorite-` + productData['product']["data"]["cod"] + ` "> <i class="fa fa-heart" aria-hidden="true" style="color:white>"></i>` + lang['productos']['agregar_fav'] + `</a>
            </div >`;
            } else {
                var favs = ``;
            }

            var stock = `
                    <p class="m-0 p-0">Cantidad: </p>
                    <input type="hidden" name="idioma" value="` + productData['product']['data']['idioma'] + `">
                    <input type="hidden" name="product" value="` + productData['product']["data"]["cod"] + `">
                   <div class="product-quantity pull-left mr-10">
                    <input name="stock" style="border-radius:0px !important;height: 53px;" id="product-stock-` + productData['product']["data"]["cod"] + `" value="1" min="1" max="` + productData['product']["data"]["stock"] + `" type="number">
                   </div>
                    `;
            var finish = `
            <div id="btn-a" class="add-to-link mb-10">
            <button id="btn-a-1" class="btn btn-dark btn--xl mt-5 mt-sm-0">
            <span class="mr-2"><i class="ion-android-add"></i></span>
            ` + lang['productos']['agregar_carrito'] + `</button></div>`;



            $('#modalPr-titulo').append(linksCategorias + '<h2 class="product-title">' + productData['product']["data"]["titulo"].toUpperCase() + '</h2>');
            $('#modalPr-precio').append(prices);
            $('#modalPr-cod').append('<span>' + cod_product + '</span>');
            $('#modalPr-desarrollo').append(offertPromo + desarrollo);
            $('#modalPr-fav').append(favs);
            $('#modalPr-variacion').append(combi);
            $('#modalPr-stock-finish').append(stock + finish);
        }
    });


}

function appendProducts(data) {
    if (Array.isArray(data)) {
        if (data.length < limit) {
            disableLoadMore();
        }

    } else {
        /*notFound();*/
    }
}

function loader() {
    $('.grid-products').append("" +
        "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12' id='loader'>" +
        "    <div class='product-wrap mb-10 mt-100 mb-400'>" +
        "        <div class='product-content text-center'>" +
        "            <i class='fa fa-circle-o-notch fa-spin fa-3x fs-70'></i>" +
        "        </div>" +
        "    </div>" +
        "</div>"
    );
}

function notFound() {
    reset();
    $('.grid-products').append("" +
        "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12'>" +
        "    <div class='product-wrap mb-35'>" +
        "        <div class='product-content text-center'>" +
        "            <i class='fa fa-times-circle fs-100' style='color: red'></i>" +
        "            <h4>No se encontró ningun producto con esas características.</h4>" +
        "        </div>" +
        "    </div>" +
        "</div>"
    );
    disableLoadMore();
}

function reset() {
    $('.grid-products').html('');
}