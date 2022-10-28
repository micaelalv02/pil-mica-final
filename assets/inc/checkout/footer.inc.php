<!--Footer section start-->
<footer class="footer-section section bg-gray">
    <div class="footer-bottom section">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-12 ft-border">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="copyright text-left">
                                <p class="text-center">2021 © Todos los derechos reservados <a href="https://www.estudiorochayasoc.com" target="_blank">Estudio Rocha & Asociados</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/toastr.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script src="<?= URL ?>/assets/js/services/lang.js"></script>
<script src="<?= URL ?>/assets/js/select2.min.js"></script>
<script src="<?= URL ?>/assets/js/bootstrap-notify.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/bootstrap.min.js"></script>
<script src="<?= URL ?>/assets/js/toastr.min.js"></script>
<script src="<?= URL ?>/assets/js/services/services.js"></script>
<script src="<?= URL ?>/assets/js/services/cart.js"></script>
<script src="<?= URL ?>/assets/js/services/user.js"></script>
<script src="<?= URL ?>/assets/js/pickers/daterange/moment.min.js"></script>
<script src="<?= URL ?>/assets/js/pickers/daterange/daterangepicker.js"></script>
<script src="<?= URL ?>/assets/js/checkout/script.js"></script>
<script>
    viewCart('<?= URL ?>');
    $("price").removeClass("hidden");
    $.validate({
        lang: 'es'
    });
</script>