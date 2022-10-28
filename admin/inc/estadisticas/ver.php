<?php
$pedidos = new Clases\Pedidos();
$funciones = new Clases\PublicFunction();
$usuarios = new Clases\Usuarios();
$estadosPedidos  = new Clases\EstadosPedidos();
$detalles = new Clases\DetallePedidos();
$categorias = new Clases\Categorias();
$subcategorias = new Clases\Subcategorias();



$topTenAprobado = $detalles->topBuy( 200);
$userDataTop = $usuarios->userPurchases();
$userNews = $usuarios->userNews("3");
$estadosAceptados = $estadosPedidos->list("", '', '');
?>


<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/charts/apexcharts.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
<!-- END: Vendor CSS-->
<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="<?= URL_ADMIN ?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
<div class="">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="pr-1 mb-0" id="url-adm" data-url="<?= URL_ADMIN ?>">Estadisticas</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                        Facturacion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                        Ranking
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="messages-tab-fill" data-toggle="tab" href="#messages-fill" role="tab" aria-controls="messages-fill" aria-selected="false">
                        Gestion LTV
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="getAllProducts()" id="messages-tab-fill" id="products" data-toggle="tab" href="#productos" role="tab" aria-controls="productos" aria-selected="false">
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a onclick="getNewUsers()" class="nav-link" id="settings-tab-fill" data-toggle="tab" href="#usuarios" role="tab" aria-controls="usuarios" aria-selected="false">
                        Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a onclick="getAllOrders()" class="nav-link" id="settings-tab-fill" data-toggle="tab" href="#pedidos" role="tab" aria-controls="pedidos" aria-selected="false">
                        Pedidos
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                    <?php include(dirname(__DIR__, 1) . "/estadisticas/facturacion.php") ?>
                </div>
                <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                    <?php include(dirname(__DIR__, 1) . "/estadisticas/ranking.php") ?>
                </div>
                <div class="tab-pane" id="messages-fill" role="tabpanel" aria-labelledby="messages-tab-fill">
                    <?php include(dirname(__DIR__, 1) . "/estadisticas/ltv.php") ?>
                </div>
                <div class="tab-pane" id="productos" role="tabpanel" aria-labelledby="products">
                    <?php include(dirname(__DIR__, 1) . "/estadisticas/allProducts.php") ?>
                </div>
                <div class="tab-pane" id="usuarios" role="tabpanel" aria-labelledby="settings-tab-fill">
                    <?php include(dirname(__DIR__, 1) . "/estadisticas/users.php") ?>
                </div>
                <div class="tab-pane" id="pedidos" role="tabpanel" aria-labelledby="settings-tab-fill">
                    <?php include(dirname(__DIR__, 1) . "/estadisticas/orders.php") ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PRODUCTS FILTER -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Filtrar</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form onsubmit="filterProducts()">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <select name="provincias">
                                <!-- TODO: LIST FROM PROVINCIAS -->
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <select name="categorias">
                                <!-- TODO: listIfHave categorias -->
                            </select>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script src="<?= URL_ADMIN ?>/theme/app-assets/vendors/js/charts/apexcharts.min.js"></script>
<script src="<?= URL_ADMIN ?>/theme/app-assets/vendors/js/pickers/daterange/moment.min.js"></script>
<script src="<?= URL_ADMIN ?>/theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
<script src="<?= URL_ADMIN ?>/js/estadisticas.js"></script>