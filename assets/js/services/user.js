async function loginUser() {
    event.preventDefault();
    $("#ingresar").hide();
    $("#btn-l").append(
        '<button id="btn-login" class="btn ld-ext-right running" disabled><div class=\'ld ld-ring ld-spin\'></div></button>'
    );
    $("#l-error").html("");
    var url = $("#login").attr("data-url");
    var carritoAdd = $("#login").attr("data-carrito");
    var link = $("#login").attr("data-link") ?
        $("#login").attr("data-link") :
        false;
    if (!link) {
        var link = url + "/sesion";
    }

    grecaptcha.ready(function() {
        grecaptcha.execute(captchaKey, {
            action: 'submit'
        }).then(function(token) {
            $("#login input[name=captcha-response]").val(token);
            $.ajax({
                url: url + "/api/user/login.php",
                type: "POST",
                data: $("#login").serialize(),
                success: function(data) {
                    data = JSON.parse(data);
                    if (data["status"]) {
                        if (carritoAdd == 1) {
                            saveCartPerFile(url);
                        }
                        window.location.assign(link);
                    } else {
                        alertSide(data["message"]);
                        $("l-pass").html("");
                        $("#ingresar").show();
                        $("#btn-login").remove();
                    }
                },
                error: function() {
                    alertSide("Ocurrio un error, vuelva a recargar la página.");
                },
            });
        });
    });
}

async function registerUser() {
    event.preventDefault();
    $("#registrar").hide();
    $('#btn-r').append("<button id=\"btn-register\" class=\"btn ld-ext-right running\" disabled><div class='ld ld-ring ld-spin'></div></button>");
    var url = $('#register').attr("data-url");
    var carritoAdd = $("#register").attr("data-carrito");
    var type = $('#register').attr("data-type");
    var link = $("#register").attr("data-link") ? $("#register").attr("data-link") : false;

    grecaptcha.ready(function() {
        grecaptcha.execute(captchaKey, {
            action: 'submit'
        }).then(function(token) {
            $("#register input[name=captcha-response]").val(token);
            $.ajax({
                url: url + "/api/user/register.php",
                type: "POST",
                data: $("#register").serialize(),
                success: async function(data) {
                    data = JSON.parse(data);
                    if (data["status"]) {
                        if (carritoAdd == 1) {
                            saveCartPerFile(url);
                        }
                        await $.ajax({
                            url: url + "/api/email/sendRegister.php",
                            type: "POST",
                            data: {
                                cod: data["cod"],
                            },
                        });

                        if (link == false) {
                            if (type == "stages") {
                                window.location = url + "/checkout/shipping";
                            } else {
                                window.location = url + "/sesion";
                            }
                        } else {
                            window.location = link;
                        }
                    } else {
                        if ($("#modalS").hasClass("show")) {
                            $("#modalS").modal("toggle");
                        }
                        alertSide(data["message"]);
                        $("r-pass").html("");
                        $("#btn-register").remove();
                        $("#registrar").show();
                    }
                },
                error: function() {
                    $("#r-error").append(
                        "<div class='alert alert-danger'>Ocurrio un error, vuelva a recargar la página.</div>"
                    );
                },
            });
        });
    });
}