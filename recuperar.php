<?php
require_once "Config/Autoload.php";

Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$config = new Clases\Config();
$usuario = new Clases\Usuarios();

#Se obtiene la sesion actual del usuario
$userData = $usuario->viewSession();

#Redireccionar si existe la sesion porque puede cambiar su contraseña desde su perfil
!empty($userData) ? $f->headerMove(URL . '/sesion') : null;

#Información de cabecera
$template->set("title", "Recuperar contraseña | " . TITULO);
$template->themeInit();
?>


<!--== Start Page Content Wrapper ==-->
<div class="page-content-wrapper mt-50 mb-50 sp-y">
    <div class="container container-wide">
        <div class="row">
            <!--Login Form Start-->
            <div class="col-md-12 col-sm-12">
                <div class="customer-login-register">
                    <div class="form-login-title">
                        <h4>Recuperar contraseña</h4>
                    </div>
                    <div class="login-form">
                        <div id="error">

                        </div>
                        <form id="recover">
                            <div class="form-fild">
                                <label>Ingrese su correo electrónico <span class="required">*</span></label>
                                <input class="form-control" name="email" type="email" data-validation="email">
                            </div><br>
                            <div class="login-submit">
                                <input type="button" value="Recuperar" id="recuperar" class="btn btn-brand">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Login Form End-->
        </div>
    </div>
</div>
<!--Login Register section end-->
<div id="modalS" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div id="textS" class="text-center">
                </div>
            </div>
        </div>
    </div>
</div>

<?php $template->themeEnd(); ?>
<script>
    $("#recuperar").click(function(e) {
        $("#recuperar").hide();
        $('#btn-l').append("<button id=\"btn-login\" class=\"btn ld-ext-right running\" disabled><div class='ld ld-ring ld-spin'></div></button>");

        $('#error').html('');
        var valid = this.form.checkValidity();
        if (valid) {
            event.preventDefault();
            $.ajax({
                url: "<?= URL ?>/api/email/sendRecover.php",
                type: "POST",
                data: $('#recover').serialize(),
                beforeSend: function() {
                    $('#textS').append("<span class='fa fa-spinner fa-spin fa-3x'></span><br>");
                    $('#textS').append("<div class='text-uppercase text-center'>");
                    $('#textS').append("<p class='fs-18 mt-10'>EXCELENTE, AGUARDE UNOS MOMENTOS.</p>");
                    $('#textS').append("</div>");
                    $('#modalS').modal('toggle');
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data['status'] == true) {
                        $('#textS').html('');
                        $('#textS').append("<i class='fa fa-check-circle fs-80' style='color:green'></i><br>");
                        $('#textS').append("<div class='text-uppercase text-center'>");
                        $('#textS').append("<p class='fs-18 mt-10'>EMAIL ENVIADO EXITOSAMENTE.<BR/> MUCHAS GRACIAS.</p>");
                        $('#textS').append("</div>");
                    } else {
                        $('#textS').html('');
                        $('#textS').append("<i class='fa fa-times-circle fs-80' style='color:red'></i><br>");
                        $('#textS').append("<div class='text-uppercase text-center'>");
                        $('#textS').append("<p class='fs-18 mt-10'>EL EMAIL INGRESADO NO EXISTE.<BR>RECARGUE LA PAGINA E INTENTE NUEVAMENTE.</p>");
                        $('#textS').append("</div>");
                    }
                },
                error: function() {
                    $('#error').append("<div class='alert alert-danger'>Ocurrio un error, vuelva a recargar la página.</div>");
                }
            });
        } else {
            $('#error').append("<div class='alert alert-danger'>Completar los campos correctamente.</div>");
            $("#recuperar").show();
            $('#btn-login').remove();
        }
    });
</script>