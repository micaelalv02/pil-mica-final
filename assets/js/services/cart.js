$('cart').html('<div class="cart-products product_bar"></div><div class="cart-discount"> </div><div class="cart-total"> </div><div class="cart-product-btn mt-4 btn-finalizar-carrito">');
$('btn-finalizar-compra').html('<div class="btn-finalizar-compra"></div>');

// refresh del carrito 
setInterval(() => {
    refreshCart($('body').attr('data-url'))
}, 1500);


function addToCart(form = "", product, url, flag) {
    event.preventDefault();
    var amount = ($('#product-stockModal-' + product).val() != null) ? $('#product-stockModal-' + product).val() : $('#product-stock-' + product).val();

    var data = (form) ? $("#" + form).serialize() : {
        product: product,
        amount: amount,
        flag: flag
    };
    $('#messageQuickAdd').hide();
    if ((product != '' && amount != '' && url != '') || form) {
        $.ajax({
            url: url + "/api/cart/add.php",
            type: "POST",
            data: data,
            success: function(data) {
                data = JSON.parse(data);
                if (data['status']) {
                    success(data['message']);
                    $("#btn-a-1").prop("disabled", false);
                    $("#btn-a-1").html("");
                    $("#btn-a-1").append(lang['productos']['agregar_carrito']);
                    viewCart(url);
                } else {
                    alertSide(data['message']);
                    $("#btn-a-1").prop("disabled", true);
                }
            }
        });
    }
}

function editCantidad(value, url, key, id) {
    $('.stock-' + id).val(value)
    var cantidad = value;
    if (cantidad > 0) {

        $.ajax({
            url: url + "/api/cart/edit.php",
            type: "POST",
            data: {
                id: id,
                key: key,
                cantidad: cantidad
            },
            success: (data) => {

                (window.location.href.indexOf("payment") >= 0) ? window.location.assign(url + '/checkout/shipping'): "";
                var data = JSON.parse(data);
                if (data["status"] == true) {
                    viewCart(url);
                }
            }
        });
    }
}

function deleteItem(url, id) {
    $.ajax({
        url: url + "/api/cart/delete.php",
        type: "POST",
        data: {
            id: id
        },
        success: (data) => {
            (window.location.href.indexOf("payment") >= 0) ? window.location.assign(url + '/checkout/shipping'): "";
            viewCart(url);
        }
    });
}


function showMessage() {
    success("");
    $('#messageQuickAdd').html(`<div class='alert alert-success fs-16 bold'>` + lang['carrito']['producto_agregado'] + ` </div><hr/>`);
    $('#messageQuickAdd').show(100);
}



function getProductsRelatioship(url, data, flag) {
    var relatedProducts_primary = $('#relations-products').html();
    $('#relations-products').html();
    if (!flag) {
        $.ajax({
            url: url + "/api/products/list-related-products.php",
            type: "POST",
            data: data,
            dataType: "html",
            success: function(data) {
                if (data != '') {
                    $('#relations-products').html('<h4 class="fs-20 mb-10">' + lang['carrito']['sumar_compra'] + '</h4>' + data);
                    $('#relations-products').html('<h4 class="fs-20 mb-10">' + lang['carrito']['sumar_compra'] + '</h4>' + data);
                    $('#relations-products').html(data);
                }
            }
        });
        $('#modalSP').modal('toggle');
    } else {
        $('#relations-products').html(relatedProducts_primary);
    }
}

function refreshCart(url) {
    $.ajax({
        url: url + "/api/cart/total-price.php",
        type: "GET",
        success: function(data) {
            data = JSON.parse(data);
            $('.totalPriceCartNav').html("$" + data.total);
            $('.amountCartNav').html(data.amount);
        }
    });
}

//VIEW 
function viewCart(url) {
    $('.cart-products').html('');
    $('.cart-discount').html('');
    $.ajax({
        url: url + "/api/cart/view.php",
        type: "POST",
        success: (data) => {
            var cartProduct = '';
            var total = 0;
            var discount = '';
            var cart = JSON.parse(data);
            if (cart != 0) {
                cart.items.forEach(function(element, key) {
                    if (element['cart']) {
                        (element['cart']['tipo'] == "pr") ? cartProduct += elementProduct(element, key, url): discount += elementExtra(element, url, cart.items);
                        if (element['user'] == '' || element['user'] != null || element['user'] != '') {
                            cantidad = (element['cart']['promo'] != '') ? element['cart']['promo'] : element['cart']['cantidad'];
                            total = total + (element['cart']['precio'] * cantidad);
                        }
                    }
                });
                $('.btn-finalizar-carrito').removeClass('d-none');
            } else {
                cartProduct = "<hr class='mb-10 mt-10'> " + lang['carrito']['carrito_vacio'];
                $('.btn-finalizar-carrito').addClass('d-none');
            }
            $('.cart-products').append(cartProduct);
            $('.cart-discount').append(discount);
            if (total > 0 && cart.total != NaN && cart.total > 0) {
                cartTotalPrice(total, cart.total);
            }
        }
    });
}

function saveCartPerFile(url) {
    $.ajax({
        url: url + "/api/cart/save-per-json.php",
        success: function(data) {
            data = JSON.parse(data);
            if (data["status"]) {
                successMessage(data["message"]);
            } else {
                alertSide(data["message"]);
            }
        }
    });
}

function elementExtra(element, url, items) {
    if (element['cart']['tipo'] == 'cp') {
        body = `<div class="d-block w-100">
        <span class="value text-uppercase pull-left">Descuento: <span class="text-uppercase" >` + element['cart']['descuento']['cod'] + `</span>
        <button class="btn btn-outline-dark btn-sm mr-10 ml-10 collapseDetail" type="button" data-toggle="collapse" data-target="#discountDetail" aria-expanded="false" aria-controls="discountDetail">
        <i class='fa fa-info'></i></button><a href="#" class="btn btn-outline-dark btn-sm" onclick="deleteItem('` + url + `','discount')"><i class="fa fa-trash"></i></a></span>
        <span class="price pull-right"> $ ` + element['cart']['precio'].toFixed(2) + ` </span></div>
        <div class="clearfix"></div>
        <div class="collapse" id="discountDetail">
        <div class="card card-body mt-1">`;
        items.filter(item => item.cart.descuento != '' && item.cart.tipo == 'pr').forEach(function(element) {
            body += `<div class="fs-12 discountText "> ` + element['cart']['titulo'] + ` ` + element['cart']['descuento']['detalle'] + `</div>`;
        });
        body += `</div></div>`;
    } else {
        var body = '';
        if (element['cart']['tipo'] == 'me') {
            var price = element['cart']['precio'] != 0 ? "$" + element['cart']['precio'] : '';
            body = `<span class="value text-uppercase fs-14">Costo de Envio: <span class="text-uppercase" >` + element['cart']['titulo'] + `</span></span>
        <span class="price fs-14">` + price + `</span>`;
        }
        if (element['cart']['tipo'] == 'mp') {
            var price = element['cart']['precio'] != 0 ? "$" + element['cart']['precio'] : '';
            body = `<span class="value text-uppercase fs-14">Impuesto de facturación:</span><span class="price fs-14">` + price + `</span>`;
        }
    }
    discount = `<div class="cart-product-total">` + body + `</div><div class="clearfix"> </div>`;
    return discount;
}



function elementProduct(element, key, url) {
    var price = element["cart"]["precio"];
    if (element["cart"]["opciones"]["combinacion"]["cod_combinacion"] != '') {
        var price_old = '';
    } else {
        var price_old = element["cart"]["precio_inicial"] == element["cart"]["precio"] ? '' : "$" + element["cart"]["precio_inicial"];
    }
    var stock = element["cart"]["stock"];

    var productPriceTotal = price * ((element['cart']['promo'] != '') ? element['cart']['promo'] : element['cart']['cantidad']);
    var priceWithoutPromo = (element['cart']['promo'] != '') ? `$` + (element['cart']['cantidad'] * price).toFixed(2) : '';
    var title = element["cart"]["titulo"];
    cartProduct = `
    <div class="product-` + element['cart']['id'] + `">
    <hr class="mb-10">
        <div class="cart-product-wrapper mb-6">
            <div class="row mb-3">
                <div class="col-md-12 col-12">
                    <span><a class="fs-12" href="` + element["cart"]["link"] + `">` + title.toUpperCase() + `</a></span>
                </div>
                <div class="col-md-10 col-10" >
                    <span class=" new fs-12" style="color:#e15987"><span style="color:gray;text-decoration: line-through;">` + price_old + `</span> $` + price + `  </span> x
                    <input  min="1" value="` + element['cart']['cantidad'] + `" max="` + stock + `" onchange="editCantidad(this.value,'` + url + `','` + key + `','` + element['cart']['id'] + `')" style="height:25px; width:45px; margin-left: 2px" type="number" name="stock" class="numberStock stock-` + element["cart"]["id"] + `">
                </div>
                <div class="col-md-2 col-2">
                <a class="pull-right fs-12" onclick="deleteItem('` + url + `','` + key + `')"><i class="fa fa-trash"></i></a>
                </div>
                <div class="col-md-12 col-12">
                    <span class="fs-12">Producto:</span>
                    <span class="new bold fs-14 totalPrice-` + element['cart']['id'] + `"> $` + productPriceTotal.toFixed(2) + ` </span> <span class="ml-1" style="color:gray;text-decoration: line-through;"> ` + priceWithoutPromo + `</span>
                </div>
            </div>
        </div>
    </div>`;
    return cartProduct;
}
var url = $("body").attr("data-url");

function cartTotalPrice(total, finalPrice) {
    $('.cart-total').html('');
    total = parseFloat(total).toFixed(2);
    finalPrice = parseFloat(finalPrice);
    total = (total == finalPrice) ? '' : '$' + total;
    var body = ` 
    <div class="cart-product-total text-uppercase pb-10">
    <hr/>
    <div class="value bold fs-20 pull-left">` + langText['carrito']['total'] + `</div>
    <div class="price fs-18 pull-right"><strike class="old mr-10 fs-16" style="color:red"> ` + total + `</strike> $` + finalPrice.toFixed(2) + `</div>        
    </div>`;
    $('.cart-total').append(body);
    //se genera el boton de finalizar compra/monto insuficiente 
    checkFinalprice();

}

function checkFinalprice() {
    $('btn-finish-cart').html('');
    $.ajax({
        url: url + "/api/cart/checkMiniumLimits.php",
        type: "POST",
        success: (data) => {
            data = JSON.parse(data);
            if (data["status"] || data["minimo"] == 0) {
                $('btn-finish-cart').html('<div class="container"><div class="row"><a class="btn-cart text-center fs-14 col-md-12 mb-10" href="' + data["link"] + '" ><i class="fa fa-check-circle"></i> ' + lang['carrito']['finalizar_compra'] + '</a ></div></div>');
            } else {
                $('btn-finish-cart').html(' <div class="alert alert-warning  bold fs-20 mt-30" disabled><i class="fa fa-times-circle-o"></i> ' + lang['carrito']['no_finalizar_compra'] + ': $' + data["minimo"] + ' </div>');
            }

        }
    });
}
// END VIEW