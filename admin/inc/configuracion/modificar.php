<?php
$config = new Clases\Config();
$funcion = new Clases\PublicFunction();
$tab = isset($_GET["tab"]) ? $funciones->antihack_mysqli($_GET["tab"]) : '';
$emailData = $config->viewEmail();
$marketingData = $config->viewMarketing();
$contactoData = $config->viewContact();
$hubspotData = $config->viewHubspot();
$socialData = $config->viewSocial();

?>
<section id="tabs" class="project-tab text-capitalize mb-20 mt-40">
    <h2 class="text-uppercase text-center fs-20">Contenidos y Configuraciones</h2>
    <hr />
    <div class="sidebar-left">
        <div class="sidebar">
            <div class="todo-sidebar d-flex">
                <div class="todo-app-menu">
                    <div class="sidebar-menu-list">
                        <div class="list-group">
                            <a class="list-group-item border-0" id="marketing-tab" data-toggle="tab" href="#marketing-home" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:pie-chart.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Marketing
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="personalizacion-tab" data-toggle="tab" href="#personalizacion-home" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:image.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Personalización
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="contact-tab" data-toggle="tab" href="#contact-home" role="tab" aria-controls="nav-contact" aria-selected="false">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:building.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Datos Empresa
                                </span>
                            </a>
                            <a class="list-group-item border-0" id="social-tab" data-toggle="tab" href="#social-home" role="tab" aria-controls="nav-home" aria-selected="true">
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:smartphone.svg; size: 30px; style:lines; strokeColor:#666;"></i>
                                    Redes Sociales
                                </span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content" id="nav-tabContent">
        <!-- DATOS DE MARKETING -->
        <div class="tab-pane fade" id="marketing-home" role="tabpanel" aria-labelledby="nav-profile-tab">
            <?php include("options/cfg_marketing.php"); ?>
        </div>
        <!-- PERSONALIZACION -->
        <div class="tab-pane fade" id="personalizacion-home" role="tabpanel" aria-labelledby="nav-profile-tab">
            <?php include("options/cfg_personalizacion.php"); ?>
        </div>
        <!-- DATOS DE CONTACTO -->
        <div class="tab-pane fade" id="contact-home" role="tabpanel" aria-labelledby="nav-contact-tab">
            <?php include("options/cfg_contact.php"); ?>
        </div>
        <!-- DATOS DE REDES SOCIALES -->
        <div class="tab-pane fade" id="social-home" role="tabpanel" aria-labelledby="nav-contact-tab">
            <?php include("options/cfg_socialMedia.php"); ?>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#<?= $tab ?>').click();
    })
</script>