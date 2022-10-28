<?php
$config = new Clases\Config();
$funcion = new Clases\PublicFunction();
$tab = isset($_GET["tab"]) ? $funciones->antihack_mysqli($_GET["tab"]) : '';
$emailData = $config->viewEmail();
$marketingData = $config->viewMarketing();
$contactoData = $config->viewContact();
$hubspotData = $config->viewHubspot();
$socialData = $config->viewSocial();
$mercadoLibreData = $config->viewMercadoLibre();
$andreaniData = $config->viewAndreani();
$captchaData = $config->viewCaptcha();
$configHeader = $config->viewConfigHeader();
$exportadorMeliData = $config->viewExportadorMeli();


//Metodos de pagos
$config->set("id", 1);
$pagosData1 = $config->viewPayment();
$config->set("id", 2);
$pagosData2 = $config->viewPayment();
$config->set("id", 3);
$pagosData3 = $config->viewPayment();
$config->set("id", 4);
$pagosData4 = $config->viewPayment();
$config->set("id", 5);
$pagosData5 = $config->viewPayment();
?>

<section id="tabs" class="project-tab text-capitalize mb-20 mt-40">
    <h2 class="text-uppercase fs-20">Configuraciones técnicas</h2>
    <hr />
    <div class="sidebar-left">
        <div class="sidebar">
            <div class="todo-sidebar d-flex">
                <div class="todo-app-menu">
                    <div class="sidebar-menu-list">
                        <div class="list-group">
                            <a class="list-group-item border-0 " id="email-tab" data-toggle="tab" href="#email-home" role="tab" aria-controls="nav-home" aria-selected="true">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:gear.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Configuración Email
                                </span>
                            </a>
                            <a class="list-group-item border-0 " id="menu-tab" data-toggle="tab" href="#menu-home" role="tab" aria-controls="nav-home" aria-selected="true">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:list.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Menu
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="api-tab" data-toggle="tab" href="#api-home" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:hand-bottom.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Api
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="pagos-tab" data-toggle="tab" href="#pagos-home" role="tab" aria-controls="nav-contact" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:credit-card-in.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Pagos
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="checkout-tab" data-toggle="tab" href="#checkout-home" role="tab" aria-controls="nav-contact" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:map.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Checkout
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="captcha-tab" data-toggle="tab" href="#captcha-home" role="tab" aria-controls="nav-contact" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:step-one-fifth.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Captcha
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="cnf-tab" data-toggle="tab" href="#config-header" role="tab" aria-controls="nav-contact" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:wide-screen.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Header
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="exportadorMeli-tab" data-toggle="tab" href="#exportadorMeli-home" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:file-export.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Exportador Meli
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="backups-tab" data-toggle="tab" href="#backups-home" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:file-export.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Backups
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content" id="nav-tabContent">

        <div class="tab-pane fade" id="email-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php include("options/cfg_email.php"); ?>
        </div>
        <div class="tab-pane fade" id="menu-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php include("options/cfg_menu.php"); ?>
        </div>
        <!-- DATOS DE API ANDREANI Y MERCADOLIBRE -->
        <div class="tab-pane fade" id="api-home" role="tabpanel" aria-labelledby="nav-contact-tab">
            <?php include("options/cfg_api.php"); ?>
        </div>

        <!-- DATOS DE METODOS DE PAGOS -->
        <div class="tab-pane fade" id="pagos-home" role="tabpanel" aria-labelledby="nav-contact-tab">
            <?php include("options/cfg_payment.php"); ?>
        </div>

        <!-- OPCIONES CHECKOUT -->
        <div class="tab-pane fade" id="checkout-home" role="tabpanel" aria-labelledby="nav-contact-tab">
            <?php include("options/cfg_checkout.php"); ?>
        </div>

        <!-- DATOS DE CAPTCHA -->
        <div class="tab-pane fade" id="captcha-home" role="tabpanel" aria-labelledby="nav-contact-tab">
            <?php include("options/cfg_captcha.php"); ?>
        </div>

        <!-- HARDCODEAR HEADER -->
        <div class="tab-pane fade" id="config-header" role="tabpanel" aria-labelledby="nav-header-tab">
            <?php include("options/cfg_header.php"); ?>
        </div>

        <!-- DATOS DE EXPORTADOR DE MERCADOLIBRE -->
        <div class="tab-pane fade" id="exportadorMeli-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php include("options/cfg_exportMeli.php"); ?>
        </div>
        <div class="tab-pane fade" id="backups-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php include("options/cfg_backups.php"); ?>
        </div>
    </div>

</section>
<div style="width:100%;height:100px;clear:both" class="d-block clearfix"></div>


<div class="modal fade text-left modal-borderless" id="modal-waiting-backup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <div class="spinner-border text-success mr-10" role="status">
                        <span class="sr-only">Cargando... </span>
                    </div>
                    Procesando Backup
                </h2>
            </div>
            <div class="modal-body fs-16">
                Este proceso puede demorar varios minutos por favor <span class="bold fs-18 text-danger"><u>NO</u></span> cerrar la ventana hasta que el proceso finalice.
            </div>

        </div>
    </div>
</div>




<script>
    $(document).ready(function() {
        $('#<?= $tab ?>').click();
    })

    function deleteBackup(url) {
        swal({
            title: "¿ESTÁS SEGURO DE ELIMINAR ESTE REGISTRO?",
            text: "No podrás recuperar este registro, una vez borrado.",
            icon: "warning",
            buttons: ["Cancelar", true],
            dangerMode: true,
        }).then((result) => {
            if (result) {
                $.ajax({
                    url: "<?= URL_ADMIN ?>/api/backup/delete.php",
                    type: 'POST',
                    data: {
                        url_absolute: url,
                    },
                    success: function(result) {
                        data = JSON.parse(result);
                        if (data["status"] == true) {
                            swal({
                                title: 'Eliminado',
                                text: data["msg"],
                                type: 'success',
                                icon: "success",
                            }).then((result) => {
                                window.location.reload();
                            });
                        } else {
                            swal({
                                title: 'Error',
                                icon: "Error",
                                text: data["msg"],
                                type: 'error',
                            });
                        }
                    }
                });
            } else {
                swal({
                    title: 'Cancelado',
                    text: 'No se ha eliminado el archivo',
                    type: 'error',
                });
            }
        });
    }

    function executeBackup(url) {
        swal({
            title: "¿ESTÁS SEGURO DE CARGAR ESTE BACKUP?",
            icon: "warning",
            buttons: ["Cancelar", true],
            dangerMode: true,
        }).then((result) => {
            if (result) {
                $.ajax({
                    url: "<?= URL_ADMIN ?>/api/backup/execute.php",
                    type: 'POST',
                    data: {
                        url_absolute: url,
                    },
                    beforeSend: function() {
                        $('#modal-waiting-backup').modal();
                    },
                    success: function(result) {
                        data = JSON.parse(result);
                        if (data["status"] == true) {
                            $('#modal-waiting-backup').modal('toggle');
                            swal({
                                title: 'Cargado',
                                text: data["msg"],
                                type: 'success',
                                icon: "success",
                            }).then((result) => {
                                window.location.reload();
                            });
                        } else {
                            swal({
                                title: 'Error',
                                icon: "Error",
                                text: data["msg"],
                                type: 'error',
                            });
                        }
                    }
                });
            } else {
                swal({
                    title: 'Cancelado',
                    text: 'No se ha cargado el backup',
                    type: 'error',
                });
            }
        });
    }
</script>