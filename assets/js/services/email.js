function sendBuyTimer(url, cod, estadoPedido, enviar, flag = false) {
    try {
        editAndSendStatus(url, cod, estadoPedido, enviar, flag);
    } catch (err) {
        setTimeout(sendBuyTimer, 1000);

    }
}

function editAndSendStatus(url, codPedido, estadoPedido, enviar, flag = false) {
    $.ajax({
        url: url + "/api/email/editAndSendStatus.php",
        type: "POST",
        data: {
            codPedido: codPedido,
            estadoPedido: estadoPedido,
            enviar: enviar,
            flag: flag
        },
        beforeSend: function () {
            $('#textS').append("<span class='fa fa-spinner fa-spin fa-3x'></span><br>");
            $('#textS').append("<div class='text-uppercase text-center'>");
            $('#textS').append("<p class='fs-18 mt-10'>" + lang["checkout"]["detail"]["generar_pedido"] + "</p>");
            $('#textS').append("</div>");
            $('#modalS').modal('toggle');
        },
        success: function (data) {
            $('#textS').html('');
            $('#textS').append("<i class='fa fa-check-circle fs-80' style='color:green'></i><br>");
            $('#textS').append("<div class='text-uppercase text-center'>");
            $('#textS').append("<p class='fs-18 mt-10'>" + lang["checkout"]["detail"]["email_enviado"] + "<BR/>" + lang["checkout"]["detail"]["gracias"] + "</p>");
            $('#textS').append("</div>");
            if (!$('#modalS').hasClass('show')) $('#modalS').modal('toggle');
        },
    });
}
function sendContact() {
    event.preventDefault();
    $("#sendContact").prop('disabled', true);
    const url = $("#contactForm").attr("data-url");
    $.ajax({
        url: url + "/api/contact/sendMessage.php",
        type: "POST",
        data: $('#contactForm').serialize(),
        beforeSend: function () {
            $('#textS').append("<span class='fa fa-spinner fa-spin fa-3x'></span><br>");
            $('#textS').append("<div class='text-uppercase text-center'>");
            $('#textS').append("<p class='fs-18 mt-10'>¡Estamos generando la consulta!</p>");
            $('#textS').append("</div>");
            $('#modalS').modal('toggle');
        },
        success: function (data) {
            $("#response").html("");
            data = JSON.parse(data);
            $('#modalS').modal('toggle');
            if (data["status"]) {
                $("#response").html('<div class="col-md-12 alert alert-success" role="alert">¡Consulta enviada exitosamente!</div>');
            } else {
                $("#response").html('<div class="col-md-12 alert alert-danger" role="alert">¡No se ha podido enviar la consulta!</div>');
            }
        },
        error: function () {
            $("#response").html('<div class="col-md-12 alert alert-danger" role="alert">¡No se ha podido enviar la consulta!</div>');
        }
    });
}