$('table').addClass("table-hover");
$('input[type=text]').addClass("form-control");
$('input[type=date]').addClass("form-control");
$('input[type=url]').addClass("form-control");
$('input[type=number]').addClass("form-control");
$('select').addClass("form-control");
$('textarea').addClass("form-control");
var url_admin = $("body").attr("data-url");

$(function() {
    $('[data-toggle="tooltip"]').tooltip();
})

$('.deleteConfirm').on("click", function(e) {
    e.preventDefault();

    swal({
            title: "¿ESTÁS SEGURO DE ELIMINAR ESTE REGISTRO?",
            text: "No podrás recuperar este registro, una vez borrado.",
            icon: "warning",
            buttons: ["Cancelar", true],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal("¡FUE ELIMINADO EXITOSAMENTE!", {
                    icon: "success",
                });
                if ($(this).attr('href')) {
                    window.location.href = $(this).attr('href');
                } else {
                    window.location.reload();
                }
            } else {
                swal("¡LA ACCIÓN FUE CANCELADA!");
            }
        });
});

function editMenuItem(id, attr, value) {
    var value = value.split("|");
    $.ajax({
        url: url_admin + '/api/menu/edit.php',
        type: "POST",
        data: {
            attr: attr,
            value: value,
            id: id
        },
        success: (data) => {
            var data = JSON.parse(data);
            if (data["status"]) {
                successMessage(data["message"]);
            } else {
                warningMessage(data["message"]);
            }
        }
    });
}

function successMessage(latest) {
    toastr.success(latest, '', {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
}

function infoMessage(message) {
    toastr.info(message, '', {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "190",
        "hideDuration": "900",
        "timeOut": "1900",
        "extendedTimeOut": "900",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
}

function warningMessage(latest) {
    toastr.warning(latest, '', {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
}

function changeOrderImg(id, order, url) {
    $.ajax({
        url: url + "/api/images/editOrder.php",
        type: "POST",
        data: {
            idImg: id,
            ordenImg: order
        },
        success: function(data) {
            data = JSON.parse(data);
            successMessage(data)
        }
    });
}

$(".ckeditorTextarea").each(function() {
    CKEDITOR.replace(this, {
        customConfig: 'config.js',
        filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'ckfinder/ckfinder.html?type=Images&responseType=json',
        filebrowserFlashBrowseUrl: 'ckfinder/ckfinder.html?type=Flash&responseType=json',
        filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
        filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json',
        filebrowserFlashUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash&responseType=json'
    });
});
$(".ckeditorTextareaMin").each(function() {
    CKEDITOR.replace(this, {
        customConfig: 'configMin.js',
        filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'ckfinder/ckfinder.html?type=Images&responseType=json',
        filebrowserFlashBrowseUrl: 'ckfinder/ckfinder.html?type=Flash&responseType=json',
        filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
        filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json',
        filebrowserFlashUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash&responseType=json'
    });
});
$(document).ready(function() {
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

function agregar_input(div, name) {
    var cod = 1 + Math.floor(Math.random() * 999999);
    $('#' + div).append('<div class="col-md-12 input-group" id="' + cod + '"><input onkeydown="return (event.keyCode!=13);" type="text" class="form-control mb-10 mr-10" name="' + name + '[' + cod + '][atributo]"><input id="tg' + cod + '" onkeydown="return (event.keyCode!=13);" type="text" class="form-control mb-10 mr-10" name="' + name + '[' + cod + '][valores]"></div>');
    $('#' + cod).append(' <div class="input-group-addon"><a href="#" onclick="$(\'#' + cod + '\').remove()" class="btn btn-primary"> <i class="fas fa-minus"></i> </a> </div>');
    $('#tg' + cod).tagify();
}


function agregar_atributo(div) {
    var cod = 1 + Math.floor(Math.random() * 999999);
    $('#' + div).append('<div class="input-group" id="' + cod + '"><input onkeydown="return (event.keyCode!=13);" type="text" class="form-control mb-10 mr-10" name="atributo[]"></div>');
    $('#' + cod).append(' <div class="input-group-addon"><a href="#" onclick="$(\'#' + cod + '\').remove()" class="btn btn-primary"> <i class="fas fa-minus"></i> </a> </div>');
}


function AgregarCombinacion(id, destino, total) {
    if ($('[id=combinaciones]').length <= total - 1) {
        $random = Math.floor((Math.random() * 1000) + 1);

        var newItem = $("#" + id).clone();
        newItem.find("input[name]").each(function() {
            var nameCurrent = $(this).attr("name");
            nameCurrent = nameCurrent.slice(0, -1);
            $(this).attr("name", nameCurrent + $random + "]");
        });
        newItem.find("select option").each(function() {
            $(this).removeAttr("selected");
        });
        newItem.find("select").each(function() {
            $(this).children().each(function(key, value) {
                if (key == 0) {
                    $(this).attr("selected", "selected");
                }
            });
        });
        newItem.find("input[value]").each(function() {
            $(this).attr("value", 0);
        });
        newItem.appendTo("#" + destino);
    }
}

function _ajax(params, url, type) {
    $.ajax({
        url: url,
        type: type,
        data: {
            params
        },
        success: function(data) {
            return data;
        }
    });
}

$('.modal-page-ajax').click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var titulo = $(this).attr('data-title');
    $('#contenidoForm').load(url, function(result) {
        $('#moda-page-ajax').modal('show');
        $('.modal-title').html(titulo);
        e.preventDefault();
    })
});


function openModal(url, titulo) {
    $('#contenidoForm').load(url, function(result) {
        $('#moda-page-ajax').modal('show');
        $('.modal-title').html(titulo);
    })
};

function checkSliderProps() {
    if ($('#chsub').prop('checked')) {
        $('#sub').attr('required', true);
    } else {
        $('#sub').attr('required', false);
    }
    if ($('#chli').prop('checked')) {
        $('#link').attr('required', true);
    } else {
        $('#link').attr('required', false);
    }
}

function errorMessage(message) {
    toastr.error(message, '', {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
}

function successMessage(message) {
    toastr.success(message, '', {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
}


function changeStatus(id, url, permisos = "false") {
    var val;
    var value = $('#' + id).prop('checked');
    if (value) {
        val = "'1'";
    } else {
        val = "'0'";
    }
    console.log(permisos);
    editProduct(idioma, id, url, permisos, val);
}

function editProduct(idioma, id, url, permisos = "false", valueStatus = '') {
    event.preventDefault();
    if (permisos == "true") {
        var data_ = id.split("-");
        var url_admin = $("#grid-products").attr("data-url");
        $.ajax({
            url: url + '/api/productos/edit.php',
            type: "POST",
            data: {
                idioma: idioma,
                attr: data_[0],
                value: (valueStatus) ? valueStatus : "'" + $("#" + id).val() + "'",
                cod: data_[1]
            },
            success: function(data) {
                if (data) {
                    successMessage("Producto " + data_[1] + " actualizado correctamente");
                    if (data_[0] == 'categoria') {
                        getCategory(url_admin, 'subcategory', id, 'subcategoria-' + data_[1], idioma);
                    }
                } else {
                    errorMessage("El producto " + data_[1] + " no se ha actualizado");
                }
            }
        });
    } else {
        errorMessage("No tienes permisos para editar");
    }
}

function editPromo(idioma, cod, url, permisos = "false") {
    event.preventDefault();
    if (permisos == "true") {
        $.ajax({
            url: url + '/api/productos/promo.php',
            type: "POST",
            data: {
                cod: cod,
                lleva: $("#lleva").val(),
                paga: $("#paga").val(),
                idioma: idioma
            },
            success: function(data) {
                console.log(data);
                var data = JSON.parse(data);
                if (data['status']) {
                    successMessage("Producto " + data['producto'] + " actualizado correctamente");
                } else {
                    errorMessage("El producto " + data['producto'] + " no se ha actualizado");
                }
            }
        });
    } else {
        errorMessage("No tienes permisos para editar");

    }
}

function exportTableToExcel(tableID, filename = '') {
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }
}

function getCategory(url, flag, idSelect, idNextSelect, idioma) {
    var value = $('#' + idSelect).val();
    $.ajax({
        url: url + "/api/categories/getCategory.php",
        type: "POST",
        data: {
            flag: flag,
            value: value,
            idioma: idioma
        },
        success: function(data) {
            data = JSON.parse(data);
            $("#" + idNextSelect).html("<option value=''>Elegi opción</option>")
            data.forEach((data_) => {
                $("#" + idNextSelect).append("<option value='" + data_.data.cod + "'>" + data_.data.titulo.toUpperCase() + "</option>")
            })
        },
        error: function() {
            alert('Error occured');
        }
    });
}

function getSubcategory(url, flag, idSelect, idNextSelect, idioma) {
    var value = $('#' + idSelect).val();
    $.ajax({
        url: url + "/api/categories/getSubcategory.php",
        type: "POST",
        data: {
            flag: flag,
            value: value,
            idioma: idioma
        },
        success: function(data) {
            data = JSON.parse(data);
            $("#" + idNextSelect).html("<option value=''>Elegi opción</option>")
            data.forEach((data_) => {
                $("#" + idNextSelect).append("<option value='" + data_.data.cod + "'>" + data_.data.titulo.toUpperCase() + "</option>")
            })
        },
        error: function() {
            alert('Error occured');
        }
    });
}



function attrSelect(url) {
    var table = $("#select_table").val();
    var url = $('#select_table').attr("data-url");
    $.ajax({
        url: url + '/api/excel/get_attr.php',
        type: "POST",
        data: {
            table: table
        },
        success: (data) => {
            var data = JSON.parse(data);
            $('#select_attr').html("");
            if (data.status) {
                Object.keys(data.attr).forEach(function(key) {
                    var select = (key == 'cod_producto') ? 'hidden selected disabled' : '';
                    var option = `
                <option ` + select + `  value="` + key + `" data-icon="bx bx-user">` + data.attr[key] + `</option>
                `;
                    $('#select_attr').append(option);
                });
            }

        }
    });
}

function downloadExcel(linkEs) {
    $('#download').click(function(event) {
        // Prevenir que el browser siga el enlace
        event.preventDefault();
        // Lista de archivos
        var archivos = [linkEs];
        // Empezamos por 0 en el array
        var aIndex = 0;
        // Iniciamos un timer que se ejecute cada 100ms
        var Ainterval = setInterval(function() {
            // Si el numero del index(array) es menor seguir
            if (aIndex < archivos.length) {
                // Indicar el src al iframe
                $('#downloader').attr('src', archivos[aIndex]);
                // Subir el index(array)
                aIndex++;
                // En caso de que sea mayor, limpiar timer.
            } else {
                clearInterval(Ainterval);
            }
        }, 2000);
    });
}

function check(url, code, flag) {
    if (flag == 0) {
        flag = 1;
        $.ajax({
            url: url + "/admin/api/pedidos/checkView.php",
            type: "POST",
            data: {
                code: code,
                flag: flag
            },
            success: function(data) {
                $('#notOpen' + code).addClass("hidden");
                $('#viewed' + code).removeClass("hidden");
            }
        });
    }
}

function changeLabel(cod, url) {
    $.ajax({
        url: url + "/admin/api/idiomas/change-default.php",
        type: "POST",
        data: {
            cod: cod
        },
        success: function(data) {
            location.reload()
        }
    });
}

function editAndSendStatus(url, codPedido, estadoPedido, enviar) {
    event.preventDefault();
    $.ajax({
        url: url + "/api/email/editAndSendStatus.php",
        type: "POST",
        data: {
            codPedido: codPedido,
            estadoPedido: estadoPedido,
            enviar: enviar
        },
        beforeSend: function() {
            $('#textS').html('');
            $('#textS').append("<span class='fa fa-spinner fa-spin fa-3x'></span><br>");
            $('#textS').append("<div class='text-uppercase text-center'>");
            $('#textS').append("<p class='fs-18 mt-10'>EXCELENTE, ESTAMOS ENVIANDO UN EMAIL CON LA INFORMACION DEL ESTADO</p>");
            $('#textS').append("</div>");
            $('#modalS').modal('toggle');
        },
        success: function(data) {
            data = JSON.parse(data);
            if (data['status']) {
                $('#textS').html('');
                $('#textS').append("<div class='text-uppercase text-center'>");
                $('#textS').append("<p class='fs-18 mt-10'>");
                $('#textS').append(data['message']);
                $('#textS').append("</p>");
                $('#textS').append("</div>");
            }
            setTimeout(() => {
                location.reload();
            }, 1000)
        }
    });
}

function printContent(id) {
    var restorepage = $('body').html();
    var printcontent = $('#' + id).clone();

    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage);
}


function editPedido(cod, url, attr, value = '') {
    event.preventDefault();
    if (value == '') {
        var value = $("#" + attr + cod).val();
    }
    $.ajax({
        url: url + '/api/pedidos/edit.php',
        type: "POST",
        data: {
            attr: attr,
            value: value,
            cod: cod
        },
        success: function(data) {
            data = JSON.parse(data);
            if (data) {
                $("#total" + cod).val(value);
                successMessage("Pedido " + cod + " actualizado correctamente");
            } else {
                errorMessage("El pedido " + cod + " no se ha actualizado");
            }
        }
    });
}

function deletePedidoItem(id, url, priceItem, cod) {
    event.preventDefault();
    var value = $("#total" + cod).val();
    $.ajax({
        url: url + '/api/pedidos/delete.php',
        type: "POST",
        data: {
            id: id
        },
        success: function(data) {
            if (data) {
                editPedido(cod, url, 'total', (value - priceItem));
                $('#' + id).hide();
                successMessage("El item fue eliminado correctamente");
            } else {
                errorMessage("Error, el item no se ha eliminado");
            }
        }
    });
}

function editCategory(attr, url, cod, idioma) {
    event.preventDefault();
    $.ajax({
        url: url + '/api/categories/edit.php',
        type: "POST",
        data: {
            attr: attr,
            value: $("#" + attr + cod).val(),
            cod: cod,
            idioma: idioma
        },
        success: function(data) {
            if (data) {
                successMessage("Categoria actualizado correctamente");

            } else {
                errorMessage("La categoria no se ha actualizado");
            }
        }
    });
}

function editSubcategory(attr, url, cod, idioma) {
    event.preventDefault();
    $.ajax({
        url: url + '/api/subcategories/edit.php',
        type: "POST",
        data: {
            attr: attr,
            value: $("#" + attr + cod).val(),
            cod: cod,
            idioma: idioma
        },
        success: function(data) {
            if (data) {
                successMessage("Subcategoria actualizada correctamente");

            } else {
                errorMessage("La subcategoria no se ha actualizado");
            }
        }
    });
}

function editTercategory(attr, url, cod, idioma) {
    event.preventDefault();
    $.ajax({
        url: url + '/api/tercercategories/edit.php',
        type: "POST",
        data: {
            attr: attr,
            value: $("#" + attr + cod).val(),
            cod: cod,
            idioma: idioma
        },
        success: function(data) {
            if (data) {
                successMessage("Tercercategoria actualizada correctamente");

            } else {
                errorMessage("La tercercategoria no se ha actualizado");
            }
        }
    });
}