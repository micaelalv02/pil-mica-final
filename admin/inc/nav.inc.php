<?php
if (isset($_SESSION["admin"])) {
    $config = new Clases\Config();
    $menu = new Clases\Menu();
    $f = new Clases\PublicFunction();
    $area = new Clases\Area();
?>
    <?php if ($_ENV["DEVELOPMENT"] == "0") { ?>
        <div id="loader"></div>
        <nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
            <div class="navbar-wrapper">
                <div class="navbar-container content pull-right">
                    <div class="navbar-collapse" id="navbar-mobile">
                        <div class="text-right">
                            <ul class="nav navbar-nav">
                                <li class="nav-item mobile-menu d-xl-none mr-auto"><a class=" nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                            </ul>
                            <ul class="nav navbar-nav bookmark-icons ">
                                <li class="nav-item d-none d-lg-block"><a class="" href="<?= URL_ADMIN ?>/index.php?op=pedidos&accion=ver" data-toggle="tooltip" data-placement="top" title="Pedidos"><i class="bx fs-17 bxs-package"></i></a></li>
                                <li class="nav-item d-none d-lg-block ml-10"><a class="" href="<?= URL_ADMIN ?>/index.php?op=productos&accion=ver" data-toggle="tooltip" data-placement="top" title="Productos"><i class="bx fs-17 bxs-store"></i></a></li>
                                <li class="nav-item d-none d-lg-block ml-10"><a class="" href="<?= URL_ADMIN ?>/index.php?op=pedidos&accion=estadisticas" data-toggle="tooltip" data-placement="top" title="Estadisticas"><i class="bx fs-17 bx-trending-up"></i></a></li>
                                <li class="nav-item d-none d-lg-block ml-10"><a class="" href="<?= URL_ADMIN ?>/index.php?op=excel&accion=excel" data-toggle="tooltip" data-placement="top" title="Importar/Exportar"><i class="bx fs-17 bx-export"></i></a></li>
                                <div class="ml-1  bold text-uppercase  d-none d-lg-block pull-right">
                                    <a class="pull-right fs-12" href="<?= URL_ADMIN ?>/manual/manual.pdf" download="Manual de uso CMS.pdf" target='_blank'><i class="fa fa-download fs-10"></i> Manual</a>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php } ?>
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow text-uppercase" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="<?= URL_ADMIN ?>">
                        <div class="brand-logo"><img class="logo" src="<?= URL_ADMIN ?>/img/logo-blanco.png" /></div>
                        <h2 class="brand-text mb-0"><b class=" fs-23"> </b></h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class=" modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content text-uppercase">
            <ul class='navigation navigation-main' id='main-menu-navigation' data-menu='menu-navigation' data-icon-style='lines'>
                <li class='nav-item' style="margin-bottom:0px!important">
                    <a href='<?= URL_ADMIN ?>'>
                        <i class='menu-livicon' data-icon='home'></i>
                        <span class='menu-title'>Inicio</span>
                    </a>
                </li>
            </ul>
            <?= $menu->build_admin_nav() ?>
            <ul class='navigation navigation-main ' id='main-menu-navigation' data-menu='menu-navigation' data-icon-style='lines'>
                <li class='nav-item' style="margin-bottom:0px!important">
                    <a href='<?= URL_ADMIN ?>/index.php?op=salir'>
                        <i class='menu-livicon' data-icon='close'></i>
                        Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">

            <?php } ?>