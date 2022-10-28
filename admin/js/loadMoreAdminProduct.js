var start = 0;
var limit = 20;
var order = '';
var url_admin = $("#grid-products").attr("data-url");
var idioma = $("#grid-products").attr("data-idioma");
const url = $("#grid-products").attr("data-url");
var nameColumn = localStorage.getItem("key") ? localStorage.getItem("key") : '';
var shcolumn;
var position = 0;

$(document).ready(() => {
    if (localStorage.getItem("key") == null) {
        localStorage.setItem("key", 'peso,precio_descuento,precio_mayorista,stock,mostrar_web,categoria,subcategoria,keywords,meli,envio_gratis');
    }
    getData();
});

function orderBy(value) {
    order = value;
    getData();
}

function loadMore() {
    disableLoadMore();
    start += limit;
    getData('add');
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

function toggleColumn(name = '') {
    var nameColumn = localStorage.getItem("key") ? localStorage.getItem("key") : '';
    console.log(nameColumn);
    if (name != '' || name != undefined || name != null) {
        if (nameColumn.indexOf("," + name) == -1) {
            newNameColumn = nameColumn + "," + name;
            localStorage.setItem("key", newNameColumn);
            var shcolumn = "." + name;
            $(shcolumn).toggle();
        } else {
            $("." + name).show();
            newNameColumn = nameColumn.replace("," + name, "")
            localStorage.setItem("key", newNameColumn);
        }
    }
}



function hideColumnLoadMore() {
    if (localStorage.getItem("key") != null) {
        key = localStorage.getItem("key").split(",");
        key.forEach(name => {
            if (name) {
                $("#lb-" + name).attr("checked", "true");
                $("." + name).hide();
            }
        });
    }
}

function getData(type, cod_ = '') {
    const list = (type != 'add') ? true : false;
    disableLoadMore();

    var eliminar = ($("#permisos").attr("data-eliminar") == 1) ? "true" : "false";
    var editar = ($("#permisos").attr("data-editar") == 1) ? "true" : "false";

    if (url_admin) {}
    $.ajax({
        url: url_admin + "/api/productos/list.php?start=" + start + "&limit=" + limit + "&order=" + order + "&idioma=" + idioma,
        type: "POST",
        data: $('#filter-form').serialize(),
        success: async(data) => {
            var data = JSON.parse(data);
            if (data || !list) {
                (list) ? reset(): enableLoadMore();
                (data.product.length) ? enableLoadMore(): '';
                $('#error-msg').html('');
                data.product.forEach(elementProduct => {
                    var cod = elementProduct['data']['cod'];
                    var idioma = elementProduct['data']['idioma'];
                    var cod_product = elementProduct['data']['cod_producto'];
                    var meliAdd = (elementProduct['data']['meli'] == 1) ? 'd-none' : '';
                    var meliDelete = (elementProduct['data']['meli'] == 1) ? '' : 'd-none';
                    var mostrar_web = (elementProduct['data']['mostrar_web'] == 1) ? 'checked' : '';
                    var envio_gratis = (elementProduct['data']['envio_gratis'] == 1) ? 'checked' : '';
                    var destacado = (elementProduct['data']['destacado'] == 1) ? 'checked' : '';
                    var precio = elementProduct['data']['precio'] == null ? ' ' : elementProduct['data']['precio'];
                    var precio_descuento = elementProduct['data']['precio_descuento'] == null ? ' ' : elementProduct['data']['precio_descuento'];
                    var precio_mayorista = elementProduct['data']['precio_mayorista'] == null ? ' ' : elementProduct['data']['precio_mayorista'];
                    var keywords = elementProduct['data']['keywords'] == null ? ' ' : elementProduct['data']['keywords'];
                    var stock = elementProduct['data']['stock'] == null ? ' ' : elementProduct['data']['stock'];
                    var peso = elementProduct['data']['peso'] == null ? ' ' : elementProduct['data']['peso'];
                    if (data.category != '') {
                        var catData = listOptionCat(data.category, elementProduct['data']['categoria']);
                        var subcatData = listOptionSubcat(data.category, elementProduct['data']['subcategoria'], elementProduct['data']['categoria']);
                    }
                    var btnEliminar = '';
                    var btnEditar = '';
                    if (eliminar == "true") {
                        btnEliminar = `
                        <a data-toggle="tooltip" data-placement="top" class="btn btn-danger deleteConfirm " title="Eliminar" href="` + url_admin + `/index.php?op=productos&accion=ver&borrar=` + elementProduct['data']['cod'] + `&idioma=` + elementProduct['data']['idioma'] + ` " class="deleteConfirm">
                                 <div class="fonticon-wrap">
                                    <i class="bx bx-trash fs-20"></i>
                                </div>
                         </a>`;
                    }
                    if (editar == "true") {
                        btnEditar = `
                        <a data-toggle="tooltip" data-placement="top" title="Modificar" class="btn btn-default" href="` + url_admin + `/index.php?op=productos&accion=modificar&cod=` + elementProduct['data']['cod'] + `&idioma=` + elementProduct['data']['idioma'] + `">
                                 <div class="fonticon-wrap">
                                    <i class="bx bx-cog fs-20"></i>
                                </div>
                         </a>`;
                    }
                    btnCopiar = `<button onclick="copyLink('` + cod + `')" class="btn btn-warning " data-toggle="tooltip" data-placement="top" title="Copiar url del producto"><i class="fa fa-link" aria-hidden="true"></i></button>`;
                    var productData = `
                    <tr id='` + cod + `'  >
                    <td class="titulo"  >
                        <input class="borderInputBottom invoice-customer"   onchange='editProduct("` + idioma + `","titulo-` + cod + `","` + url_admin + `","` + editar + `")' id='titulo-` + cod + `' name='titulo' value='` + elementProduct['data']['titulo'] + `' />                        
                    </td>
                    <td class="precio"><input class="borderInputBottom invoice-amount" style='width:auto' onchange='editProduct("` + idioma + `","precio-` + cod + `","` + url_admin + `","` + editar + `")' id='precio-` + cod + `' name='precio' value='` + precio + `' /></td>
                    <td class="precio_descuento"> <input class="borderInputBottom" style='width:auto' onchange='editProduct("` + idioma + `","precio_descuento-` + cod + `","` + url_admin + `","` + editar + `")' id='precio_descuento-` + cod + `' name='precio_descuento' value='` + precio_descuento + `' /></td>
        
                    <td class="precio_mayorista"> <input class="borderInputBottom" style='width:auto' onchange='editProduct("` + idioma + `","precio_mayorista-` + cod + `","` + url_admin + `","` + editar + `")' id='precio_mayorista-` + cod + `' name='precio_mayorista' value='` + precio_mayorista + `' /></td>
                    <td class="categoria">
                        <select style="width: 150px;" class="form-control fs-12 invoice-item-select" onchange='editProduct("` + idioma + `","categoria-` + cod + `","` + url_admin + `","` + editar + `")' id='categoria-` + cod + `' name='categoria' value='#categoria option:selected'>
                        <option value="">-- categorías --</option>
                         ` + catData + `
                        </select >
                    </td >
                     <td class="subcategoria">
                        <select style="width: 150px;" class="form-control fs-12 invoice-item-select select2" onchange='editProduct("` + idioma + `","subcategoria-` + cod + `","` + url_admin + `","` + editar + `")' id='subcategoria-` + cod + `' name='subcategoria' value='#subcategoria option:selected'>
                            ` + subcatData + `
                        </select>
                    </td>
                    <td class="keywords"><input class=" borderInputBottom" style='width:auto' onchange='editProduct("` + idioma + `","keywords-` + cod + `","` + url_admin + `","` + editar + `")' id='keywords-` + cod + `' name='keywords' value='` + keywords + `' /></td>
                    <td class="stock"><input class=" borderInputBottom" style='width:auto' onchange='editProduct("` + idioma + `","stock-` + cod + `","` + url_admin + `","` + editar + `")' id='stock-` + cod + `' name='stock' value='` + stock + `' /></td>
                    <td class="peso"><input class="borderInputBottom" style='width:auto' onchange='editProduct("` + idioma + `","peso-` + cod + `","` + url_admin + `","` + editar + `")' id='peso-` + cod + `' name='peso' value='` + peso + `' />kg</td>
                    <td class="meli" width="80" class="text-center">
                    <button class="btn btn-info ` + meliAdd + `" data-toggle="modal" data-target="#modalAdd" id="btn-add-modal-` + cod_product + `" onclick="meliModal('` + cod + `')">VINCULAR</button>
                    <button class="btn btn-danger deleteConfirm  ` + meliDelete + `"  id="btn-delete-modal-` + cod_product + `" >Vinculado</button>
                    </td>
                    <td class="envio_gratis" width="80" class="text-center">
                        <input type="checkbox" class=" borderInputBottom" style='width:auto' onchange='changeStatus("envio_gratis-` + cod + `","` + url_admin + `","` + editar + `")' id='envio_gratis-` + cod + `' name='envio_gratis' ` + envio_gratis + ` />
                    </td>
                    <td class="destacado" width="80" class="text-center">
                        <input type="checkbox" class=" borderInputBottom" style='width:auto' onchange='changeStatus("destacado-` + cod + `","` + url_admin + `","` + editar + `")' id='destacado-` + cod + `' name='destacado' ` + destacado + ` />
                    </td>
                    <td class="mostrar_web" width="80" class="text-center">
                        <input type="checkbox" class=" borderInputBottom" style='width:auto' onchange='changeStatus("mostrar_web-` + cod + `","` + url_admin + `","` + editar + `")' id='mostrar_web-` + cod + `' name='mostrar_web' ` + mostrar_web + ` />
                    </td>
                    <td class="text-right ">
                        <div class="btn-group">
                    <input type="text" style="position:absolute;left:-1000px;top:-1000px;" id="link-` + cod + `" value="` + elementProduct['link'] + `">

                        ` + btnCopiar + `
                        ` + btnEditar + `
                        ` + btnEliminar + `
                        </div>
                    </td>
                </tr >`;
                    $('#grid-products').append(productData);
                });

                await hideColumnLoadMore();
            } else {
                $('#grid-products').html('');
                $('#error-msg').html("<h4 class='mt-10'>NO SE ENCONTRARON PRODUCTOS CON LAS CARACTERISTICAS BUSCADAS</h4>");
            }
        }
    });
}

function listOptionCat(category, productCod) {
    var catData = "";
    category.forEach(elementCategory => {
        if (productCod == elementCategory['data']['cod']) {
            catData += ` <option value='` + elementCategory['data']['cod'] + `' selected >` + elementCategory['data']['titulo'].toUpperCase() + `</option>`;
        } else {
            catData += ` <option value='` + elementCategory['data']['cod'] + `'>` + elementCategory['data']['titulo'].toUpperCase() + `</option>`;
        }
    });
    return catData;
}

function listOptionSubcat(category, productCodSubCat, codCat) {
    var subcatData = "";
    category.forEach(elementCategory => {
        if (elementCategory['subcategories'] != '') {
            elementCategory['subcategories'].forEach(elementSubcategory => {
                if (elementSubcategory['data']['categoria'] == codCat) {
                    if (productCodSubCat == elementSubcategory['data']['cod']) {
                        subcatData += ` <option value='` + elementSubcategory['data']['cod'] + `' selected >` + elementSubcategory['data']['titulo'].toUpperCase() + `</option>`;
                    } else {
                        subcatData += ` <option value='` + elementSubcategory['data']['cod'] + `'>` + elementSubcategory['data']['titulo'].toUpperCase() + `</option>`;
                    }
                }
            });
        }
    });
    return subcatData;
}


function changeSelect(cat, sub = '') {

    if (cat && !sub) {
        let subcat_list = $('#' + cat + 'SubCat');

        $("#cat-" + cat).removeClass("check"); //remover clase de .check de la cateogria clickeada

        $(".ulProductsDropdown").hide(); //hide oculta todas las clases ulProductsDropdown

        $(".check").prop("checked", false);

        ($("#cat-" + cat).prop("checked")) ? subcat_list.show(): subcat_list.hide();

        $("#cat-" + cat).addClass("check");

        getData();
    }

    if (cat && sub) {
        let subcat_list = $('#' + sub + 'TerCat');

        $("#sub-" + cat + "-" + sub).removeClass("check"); //remover clase de .check de la cateogria clickeada

        $('#' + sub + 'SubCat .ulProductsDropdown').hide(); //hide oculta todas las clases ulProductsDropdown

        $(".tercercategorias .check").prop("checked", false);

        ($("#sub-" + cat + "-" + sub).prop("checked")) ? subcat_list.show(): subcat_list.hide();

        $("#sub-" + cat + "-" + sub).addClass("check");

        getData();
    }


}

function copyLink(id) {
    var copyText = document.getElementById("link-" + id);
    copyText.select();
    document.execCommand("copy");
    successMessage("Link copiado: " + copyText.value);
}