var url = $("body").attr("data-url");

function alertSide(message) {
    toastr.warning(message, '', {
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

function success(latest) {
    toastr.success('Nuevo articulo agregado', latest, {
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

///Agregado 23/07/2020
function refreshFront() {
    var url = $('#cart-f').attr("data-url");
    $.ajax({
        url: url + "/api/atributes/refreshFront.php",
        type: "POST",
        data: $('#cart-f').serialize(),
        success: function(data) {
            $('.hidden-data').val(data);
            data = JSON.parse(data);
            if (data['status'] == true) {
                if (data['combination'] == false) {
                    //ATRIBUTO
                    $("#btn-a-1").prop("disabled", false);
                } else {
                    if (data['combination']['cod_combinacion'] != null) {
                        //COMBINACION
                        $("#btn-a-1").prop("disabled", false);
                        $("#combination").val(data['combination']);
                        $("#btn-a-1").prop("disabled", false);
                        $("input[name=stock]").attr({
                            "max": data['stock']
                        });
                        $("#s-price").html('');
                        $("#s-price").append("$" + data['price']);
                        $("#btn-a-1").html("");
                        $("#btn-a-1").append(lang['productos']['agregar_carrito']);
                    } else {
                        //NO EXISTE COMBINACIONS
                        $("#btn-a-1").prop("disabled", true);
                        $("#btn-a-1").html("");
                        $("#btn-a-1").append(data['combination']);
                    }
                }
            } else {
                $("#btn-a-1").prop("disabled", true);
            }
        }
    });
}

function refreshWithImg(variable) {
    variable = variable.toUpperCase();
    $('option').not(new_selection).removeAttr('selected');
    var new_selection = $(".refreshWithImg").find('option:selected');
    $('option').not(new_selection).removeAttr('selected');
    if (variable != '') $('select option:contains("' + variable + '")').prop('selected', 'selected');
}

function refreshWithSelect() {
    var new_selection = $(".refreshWithImg").find('option:selected');
    $('option').not(new_selection).removeAttr('selected');
    new_selection.attr("selected", "selected");
    var attr = $(".refreshWithImg").find('option:selected').attr("data-value");
    attr = attr.toUpperCase();
    $('#' + attr).click();
}

///Agregado 06/01/2021
async function addComments(url, idForm) {
    event.preventDefault();

    grecaptcha.ready(function() {
        grecaptcha.execute(captchaKey, {
            action: 'submit'
        }).then(function(token) {
            $("#" + idForm + " input[name=captcha-response]").val(token);
            $.ajax({
                url: url + "/api/comments/addComments.php",
                type: "POST",
                data: $("#" + idForm).serialize(),
                success: function(data) {
                    data = JSON.parse(data);
                    if (data['status']) {
                        successMessage(data['message']);
                        location.reload();
                    } else {
                        alertSide(data['message']);
                    }
                }
            });
        });
    });
}

function deleteComments(admin, url, id) {
    url = url;
    if (admin == 1) {
        $.ajax({
            url: url + "/api/comments/deleteComments.php",
            type: "POST",
            data: {
                "id": id
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data['status']) {
                    successMessage(data['message']);
                    location.reload();
                } else {
                    alertSide(data['message']);
                }
            }
        });

    }
}

function cambiarProvincia() {
    var url = $('#provincia').attr("data-url");
    elegido = $('#provincia').val();
    $.ajax({
        type: "GET",
        url: url + "/assets/inc/localidades.inc.php",
        data: "elegido=" + elegido,
        dataType: "html",
        success: function(data) {
            $('#localidad option').remove();
            var substr = data.split(';');
            for (var i = 0; i < substr.length; i++) {
                var value = substr[i];
                $("#localidad").append(
                    $("<option></option>").attr("value", value).text(value)
                );
            }
        }
    });
}



function addFavorite(product, idioma) {
    $.ajax({
        url: url + "/api/favorites/favorite.php",
        type: "POST",
        data: {
            product: product,
            idioma: idioma
        },
        success: (data) => {
            console.log(data);
            $(".btn-addFavorite-" + product).addClass("d-none");
            $(".btn-deleteFavorite-" + product).removeClass("d-none");
        }
    });
}

function deleteFavorite(product, idioma) {
    $.ajax({
        url: url + "/api/favorites/favorite.php",
        type: "DELETE",
        data: {
            product: product,
            idioma: idioma
        },
        success: (data) => {
            $(".btn-addFavorite-" + product).removeClass("d-none");
            $(".btn-deleteFavorite-" + product).addClass("d-none");
        }
    });

}
$("#provincia").change(function() {
    cambiarProvincia()
});

$("#provincia").select2();
$("#localidad").select2();
$("#horaEntrega").select2();

function changeLang(url, cod = '') {
    var cod = (cod != '') ? cod : $('#cmbIdioma').val();
    $.ajax({
        url: url + "/api/idioma/change-default.php",
        type: "POST",
        data: {
            cod: cod
        },
        success: function(data) {
            data = JSON.parse(data);
            if (data["status"]) {
                location.reload();
            }
        }
    });
}
$("#cmbIdioma").select2({
    minimumResultsForSearch: -1,
    templateResult: function(state) {
        var iconUrl = $(state.element).attr('data-iconurl');
        if (!state.id) {
            return state.text;
        }
        var baseUrl = iconUrl;
        var $state = $(
            '<span><img src="' + baseUrl + '" class="img-flag" width="30px"/> ' + state.text + '</span>'
        );
        return $state;
    },
    templateSelection: function(state) {
        var iconUrl = $(state.element).attr('data-iconurl');
        if (!state.id) {
            return state.text;
        }
        var baseUrl = iconUrl;
        var $state = $(
            '<span><img src="' + baseUrl + '" class="img-flag" height="20px"/> ' + state.text + '</span>'
        );
        return $state;
    }
});