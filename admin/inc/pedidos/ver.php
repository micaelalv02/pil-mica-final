<?php
$pedidos = new Clases\Pedidos();
$funciones = new Clases\PublicFunction();
$usuarios = new Clases\Usuarios();
$carrito = new Clases\Carrito();
$estadosPedidos  = new Clases\EstadosPedidos();


$estadoFiltro = isset($_GET["estadoFiltro"]) ? $funciones->antihack_mysqli($_GET["estadoFiltro"]) : '';
$from = isset($_GET["from"]) ? $funciones->antihack_mysqli($_GET["from"]) : '';
$to = isset($_GET["to"]) ? $funciones->antihack_mysqli($_GET["to"]) : '';

$estadoPedido = $estadosPedidos->listByEstado();
if ($estadoFiltro != '') {
    foreach ($estadoPedido[$estadoFiltro]['data'] as $key__ => $filterEstado) {
        $filter['status'][$key__] = "estado = '" . $filterEstado['id'] . "'";
    }
}
if (!empty($from)) {
    $to_ = !empty($to) ? "'" . $to . "'" : "NOW()";
    $filter['date'] = ["fecha BETWEEN '" . $from . "' AND " . $to_];
}
#PAGINADOR 
$link = !empty($estadoFiltro) || is_numeric($estadoFiltro) ? URL_ADMIN . "/index.php?op=pedidos&accion=ver&estadoFiltro=" . $estadoFiltro : URL_ADMIN . "/index.php?op=pedidos&accion=ver";
$pagina = isset($_GET['pagina']) ? $funciones->antihack_mysqli($_GET['pagina']) : 1;
$limite = 30;
$start = $limite * ($pagina - 1);
$paginador = $pedidos->paginador($link, isset($filter) ? $filter : '', $limite, $pagina, 1, false);
$pedidosData = !empty($filter) ? $pedidos->list($filter, '', $start .  "," . $limite) : $pedidos->list('', '', $start .  "," . $limite);

if ($estadoFiltro != '') $filter = '';
$totalByStatus = $pedidos->getTotalByStatus(isset($filter) ? $filter : ''); // Cantidad total y monto de pedidos por area

$promedio = 0;
if ($totalByStatus["statusTotal"][2]["data"]["cantidad"] != 0) {
    $promedio = $totalByStatus["statusTotal"][2]["data"]["total"] / $totalByStatus["statusTotal"][2]["data"]["cantidad"];
}
if (isset($_SESSION["usuarios-ecommerce"])) {
    unset($_SESSION["cod_pedido"]);
    unset($_SESSION["usuarios-ecommerce"]);
    $carrito->destroy();
}
?>
<div class="mt-20">
    <section id="widgets-Statistics">
        <div class="row">
            <div class="col-12 mt-1">
                <h4 class="mt-20 col-xs-12 pull-left">Pedidos</h4><?php if ($_SESSION["admin"]["crud"]["crear"]) { ?><a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=usuarios&accion=ver&pedido=1">AGREGAR PEDIDOS </a><?php } ?>
                <form method="get" class="col-xs-12 pull-right mt-15" id="orderForm" name="orderForm" action="<?= CANONICAL ?>"><input type="hidden" name="op" value="pedidos" /><input type="hidden" name="accion" value="ver" />
                    <div class="row">
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <input type="date" name="to" value="<?= !empty($to) ? $to : '' ?>">
                        </div>
                        <div class="col-md-1 col-xs-12 col-sm-12">
                            <h6 class="mt-10">hasta</h6>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <input type="date" name="from" value="<?= !empty($from) ? $from : '' ?>" required>
                        </div>
                        <button class="btn btn-primary col-md-2 col-xs-12 col-sm-12 ml-10" type="submit">BUSCAR</button>
                    </div>
                </form>
                <div class="clearfix"></div>
                <hr class="mb-0" />
            </div>
        </div>
        <div class="row">
            <?php $tooltipData = $pedidos->tooltipData($totalByStatus['status']) ?>
            <?php foreach ($totalByStatus['statusTotal'] as $key_ => $statusData) {
                $link = URL_ADMIN . "/index.php?op=pedidos&accion=ver&estadoFiltro=" . $key_;
                switch ($key_) {
                    case 0:
                        $statusStyle = ["Carrito no cerrado", "bx-truck", "primary"];
                        if (isset($tooltipData[$key_])) {
                            $tooltipData_ = $tooltipData[$key_];
                        }
                        break;
                    case 1:
                        $statusStyle = ["Pendiente", "bxs-hourglass", "warning"];
                        if (isset($tooltipData[$key_])) {
                            $tooltipData_ = $tooltipData[$key_];
                        }
                        break;
                    case 2:
                        $statusStyle = ["Aprobado", "bx-money", "success"];
                        if (isset($tooltipData[$key_])) {
                            $tooltipData_ = $tooltipData[$key_];
                        }
                        break;
                    case 3:
                        $statusStyle = ["Pago no concretado", "bx-dislike", "danger"];
                        if (isset($tooltipData[$key_])) {
                            $tooltipData_ = $tooltipData[$key_];
                        }
                        break;
                }
            ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body btn  tooltip-light" style="" type="button" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?= $pedidos->getTooltip($tooltipData_) ?>" onclick="document.location.href='<?= (CANONICAL == $link) ?  URL_ADMIN . '/index.php?op=pedidos&accion=ver' : $link ?>'">
                                <div class="badge-circle badge-circle-lg  mx-auto my-1  <?= $estadoFiltro != '' ? ($estadoFiltro == $key_ ? 'badge-circle-light-' . $statusStyle[2] : 'badge-circle-light-secondary') : 'badge-circle-light-' . $statusStyle[2] ?>"><i class="fs-26 bx <?= $statusStyle[1] ?> fs-30 "></i></div>
                                <p class="text-muted mb-0 line-ellipsis"><?= $statusStyle[0] ?>(<?= isset($statusData['data']['cantidad']) ? $statusData['data']['cantidad'] : 0 ?>)</p>
                                <h3 class="mb-0 fs-18 bold">$<?= isset($statusData['data']['total']) ? number_format($statusData['data']['total'], 2, ",", ".") : 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- <div class="alert alert-secondary text-center fs-13 pt-10 pb-10 text-uppercase mt-10 mb-0  d-block"> Promedio de carritos aprobados: $<?= number_format($promedio, "2", ",", ".") ?></div> -->
    </section>
    <hr><?php foreach ($pedidosData as $key => $value) {
            $detalle = json_decode(preg_replace('/[\x00-\x1F]/', '<br/>', $value['data']['detalle']), true);
            $fecha = strftime("%d/%m/%Y - %H:%M", strtotime($value['data']['fecha']));
            $code = $value['data']['cod'];
            $flag = $value['data']['visto'];
        ?>
        <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2<?= $value['data']['id'] ?>">
            <div class="card collapse-header text-uppercase">
                <div onclick="check('<?= URL ?>','<?= $code ?>','<?= $flag ?>')" id="heading5<?= $value['data']['id'] ?>" class="card-header" data-toggle="collapse" data-target="#accordion5<?= $value['data']['id'] ?>" aria-expanded="false" aria-controls="accordion5<?= $value['data']['id'] ?>" role="tablist">
                    <span class="collapse-title">
                        <div class="row fs-12">
                            <div class="mx-0 py-0 col-md-5 col-xs-8 col-sm-8 ">
                                <i id='viewed<?= $code ?>' style=" margin-top: -7px" class="icon-pedido fa fa-eye  <?= ($value['data']['visto'] == 1) ? '' : 'hidden' ?>" aria-hidden="true"></i>
                                <i id='notOpen<?= $code ?>' style=" margin-top: -7px" class="icon-pedido fa fa-eye-slash  <?= ($value['data']['visto'] == 1) ? 'hidden' : '' ?>" aria-hidden="true"></i>
                                <span class="ml-40 "><b>Pedido: </b><?= $value['data']["cod"] ?></span>
                                <?php if (isset($value['data']["pago"])) { ?>
                                    <span class="hidden-md-down"> <b style="margin-left:4px"> Pago: </b><?= $value['data']["pago"] ?></span>
                                <?php } ?>
                            </div>
                            <div class="mx-0 py-0 col-md-5 hidden-md-down">
                                <span> <b> Fecha: </b><?= $fecha ?></span>
                                <span> <b style="margin-left:4px"> Nombre: </b><?= $value['user']['data']['nombre'] . ' ' . $value['user']['data']['apellido'] ?></span>
                                <span><?= (isset($detalle['pago']['factura']) && $detalle['pago']['factura'] == true) ? '- <b>FACTURA A</b>' : '' ?> </span>
                            </div>
                            <div class="mx-0 py-0 col-md-2 col-xs-2 col-sm-2">
                                <?= $estadosPedidos->getStateBadge($value['data']['estado']); ?>
                            </div>
                        </div>
                    </span>
                </div>
                <div id="accordion5<?= $value['data']['id'] ?>" role="tabpanel" data-parent="#accordionWrapa2<?= $value['data']['id'] ?>" aria-labelledby="heading5<?= $value['data']['id'] ?>" class="collapse">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="print-<?= $value['data']['id'] ?>" style="padding:10px;background:#fff" class="p-10">
                                <img src="<?= LOGO ?>" width="180" class="mt-15 mb-10" />
                                <hr />
                                <h2 class="fs-16 bold">DATOS DE COMPRA/PEDIDO</h2>
                                <hr />
                                <div id="pedido-<?= $value['data']["cod"] ?>">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 table-responsive">
                                            <table class="table table-striped table-sm table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>COD </th>
                                                        <th>Producto </th>
                                                        <th>Cantidad </th>
                                                        <th class="hidden-xs hidden-sm">Precio </th>
                                                        <th>Precio Final </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($value['detail'] as $key2 => $value2) {
                                                        $precio =  isset($value2["precio"]) ? $value2["precio"] : '0';
                                                        $producto_cod = ($value2["producto_cod"] != 'Descuento') ? $value2["producto_cod"] : $value2["cod_producto"];
                                                        $value2['descuento'] = json_decode($value2['descuento'], true);
                                                        if (isset($value2["descuento"]["products"])) $discount = $value2;
                                                        $desc = (isset($value2["descuento"]["products"])) ? '*' : '';
                                                    ?>
                                                        <?php if ($value2['cod'] == $value['data']["cod"]) { ?>
                                                            <tr id="<?= $value2['id'] ?>">
                                                                <td width="10%">
                                                                    <?= $producto_cod ?>
                                                                </td>
                                                                <td>
                                                                    <?= $value2["producto"] ?> <?= $desc  ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($value2["precio"] > 0) {
                                                                        echo $value2["cantidad"];
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($value2["precio"] > 0) {
                                                                        echo '$' . $value2["precio"];
                                                                        if (isset($descuento["cod"])) {
                                                                            echo '<b class="descuento-precio">  ' . $descuento["precio-antiguo"] . '</b>';
                                                                        }
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($value2["precio"] > 0 || $value2["precio"] < 0) {
                                                                        echo '$' . $value2["precio"] * $value2["cantidad"];
                                                                    } elseif ($value2["precio"] == 0) {
                                                                        echo 'Sin recargo';
                                                                    } ?>
                                                                </td>
                                                                <td> <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?><button class="btn btn-danger deleteConfirm" onclick="deletePedidoItem('<?= $value2['id'] ?>','<?= URL_ADMIN ?>','<?= ($value2['precio'] * $value2['cantidad']) ?>','<?= $value['data']['cod'] ?>')"><i class="fa fa-trash pull-left" aria-hidden="true"></i></button><?php } ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if (!empty($value['data']['entrega']) && $value['data']['entrega'] < $value['data']['total']) { ?>
                                                        <tr>
                                                            <td><b class="fs-18">SEÑADO </b></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <price><b>$<?= $value["data"]["entrega"] ?></b></price>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr class="pt-10 pb-10 mb-20">
                                                        <td><b class="fs-18">TOTAL </b></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">$</span>
                                                                </div>
                                                                <input id="total<?= $value["data"]["cod"] ?>" type="number" step="any" class="form-control" placeholder="0.00" <?= ($_SESSION["admin"]["crud"]["editar"]) ? "onchange='editPedido('" . $value["data"]["cod"] . "','" . URL_ADMIN . "','total')" : '' ?> value="<?= $value['data']['total'] ?>">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php if (isset($discount)) { ?>
                                                        <thead class="thead-dark ">
                                                            <th></th>
                                                            <th class="text-left" width="50%"> * <?= strtoupper($discount['producto']) ?></th>
                                                            <th>Descuento</th>
                                                            <th>Desc. u.</th>
                                                            <th>Desc. Total</th>
                                                        </thead>
                                                        <?php
                                                        foreach ($discount['descuento']['products'] as $desc) { ?>
                                                            <tr>
                                                                <td></td>
                                                                <td>
                                                                    <?= $desc['titulo'] ?>
                                                                </td>
                                                                <td>
                                                                    <?= $desc['monto'] ?>
                                                                </td>
                                                                <?php if (isset($desc['descuentoUnidad'])) { ?>
                                                                    <td>
                                                                        $<?= $desc['descuentoUnidad'] ?>
                                                                    </td>
                                                                    <td>
                                                                        $<?= $desc['descuentoTotal'] ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                    <?php  }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <hr>
                                            <h2 class="fs-16 bold">Observacion:</h2>
                                            <input type="text" id="observacion<?= $value["data"]["cod"] ?>" <?= ($_SESSION["admin"]["crud"]["editar"]) ? "onchange='editPedido('" . $value["data"]["cod"] . "','" . URL_ADMIN . "','observacion')'" : '' ?> value="<?= isset($value['data']['observacion']) ? $value['data']['observacion'] : '' ?>">
                                        </div>
                                    </div>
                                    <div>
                                        <hr />
                                        <h2 class="fs-16 bold">DATOS DEL USUARIO</h2>
                                        <hr />
                                        <b>MÉTODO DE PAGO: </b><?= isset($value['data']["pago"]) ? $value['data']["pago"]  : 'No especificada' ?>
                                        <?php
                                        if (!empty($detalle['leyenda'])) {
                                            echo "<b class='ml-20'>DESCRIPCIÓN DEL PAGO: </b>" . $detalle['leyenda'] . "<br/>";
                                        }
                                        if (!empty($detalle['descuento'])) {
                                            echo "<b class='ml-20'>SE UTILIZÓ EL CÓDIGO DE DESCUENTO: </b>" . $detalle['descuento'];
                                        }
                                        if (!empty($detalle['link'])) {
                                            echo "<b class='ml-20'>URL PARA PAGAR: </b><a href='" . $detalle['link'] . "' target='_blank'>CLICK AQUÍ</a>";
                                        }
                                        if (!empty($value['data']['detalle'])) { ?>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div class="row mb-15">
                                                <div class="col-md-4 col-xs-12 col-sm-12 ">
                                                    <b>INFORMACIÓN DE USUARIO</b>
                                                    <hr />
                                                    <p>
                                                        <b>Nombre: </b><?= $value['user']['data']['nombre'] . ' ' . $value['user']['data']['apellido'] ?><br />
                                                        <b>Dirección: </b><?= $value['user']['data']['direccion'] . ' - ' . $value['user']['data']['localidad'] . ' - ' . $value['user']['data']['provincia'] ?><br />
                                                        <b>Teléfono: </b><?= $value['user']['data']['telefono'] ?><br />
                                                        <?= isset($value['user']['data']['celular']) ? "<b>Celular: </b>" . $value['user']['data']['celular'] . "<br />" : '' ?>
                                                        <b>Email: </b><?= $value['user']['data']['email'] ?><br />
                                                    </p>
                                                </div>
                                                <?php if (isset($detalle['envio'])) { ?>
                                                    <div class="col-md-4  col-xs-12 col-sm-12"><b>INFORMACIÓN DE ENVIO</b>
                                                        <hr /><?= $pedidos->getInfoPedido($detalle, 'envio'); ?>
                                                        <p class='mb-0 fs-13'><b><?= $_SESSION["lang-txt"]["checkout"]["similar"] ?>: </b><?= $detalle['envio']['similar'] ? "Si" : "No" ?></p>
                                                    </div>
                                                <?php } ?>
                                                <?php if (isset($detalle['pago'])) { ?>
                                                    <div class="col-md-4  col-xs-12 col-sm-12"><b>INFORMACIÓN DE FACTURACIÓN</b>
                                                        <hr /><?= $pedidos->getInfoPedido($detalle, 'pago'); ?>
                                                        <?php if ($detalle['pago']['factura']) {
                                                            echo "<p class='mb-0 fs-13'><b>Factura A al CUIT: </b>" . $detalle['pago']['dni'] . "</p>";
                                                        } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php
                                        } else {
                                            if ($value['user']) { ?>
                                                <div class="col-md-4 mt-20 ">
                                                    <b>INFORMACIÓN DE USUARIO</b>
                                                    <hr />
                                                    <p>
                                                        <b>Nombre: </b><?= $value['user']['data']['nombre'] . ' ' . $value['user']['data']['apellido'] ?><br />
                                                        <b>Dirección: </b><?= $value['user']['data']['direccion'] . ' - ' . $value['user']['data']['localidad'] . ' - ' . $value['user']['data']['provincia'] ?><br />
                                                        <b>Teléfono: </b><?= $value['user']['data']['telefono'] ?><br />
                                                        <?= isset($value['user']['data']['celular']) ? "<b>Celular: </b>" . $value['user']['data']['celular'] . "<br />" : '' ?>
                                                        <b>Email: </b><?= $value['user']['data']['email'] ?><br />
                                                    </p>
                                                </div>
                                        <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <?php if (isset($url)) { ?>
                                    <hr />
                                    <?php if (!empty($value['user']['data']['celular'])) { ?>
                                        <a href="https://wa.me/<?= $value['user']['data']['celular'] ?>?text=<?= $url ?>" target="_blank" class="btn" style="background-color: lawngreen;"><i class="fa fa-phone"></i>Compartir por whatsapp </a>
                                    <?php } else { ?>
                                        <button class="btn" style="background-color: lawngreen;" title="El usuario no posee numero de celular" disabled><i class="fa fa-phone"></i>Compartir por whatsapp </button>
                                    <?php } ?>
                                <?php } ?>
                                <div class="hiddenPrint">
                                    <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>
                                        <hr />
                                        <b class="mt-10 mr-10 ">CAMBIAR ESTADO: </b>
                                        <div class=" ">
                                            <?php
                                            foreach ($estadoPedido as $key => $estado) {
                                                switch ($key) {
                                                    case 0:
                                                        $btnName = "Carrito no cerrado";
                                                        $btnColor = "btn-dark";
                                                        break;
                                                    case 1:
                                                        $btnName = "Pendiente";
                                                        $btnColor = "btn-warning";
                                                        break;
                                                    case 2:
                                                        $btnName = "Aprobado";
                                                        $btnColor = "btn-success";
                                                        break;
                                                    case 3:
                                                        $btnName = "Rechazado";
                                                        $btnColor = "btn-danger deleteConfirm";
                                                        break;
                                                }
                                            ?>
                                                <div class="btn-group mt-1 dropup mr-1 mb-1"><button type="button" class="btn <?= $btnColor ?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="<?= $value['data']['cod'] . "state"; ?>"><?= $btnName ?></button>
                                                    <div class="dropdown-menu" aria-labelledby="<?= $value['data']['cod'] . "state"; ?>">
                                                        <?php
                                                        foreach ($estado['data'] as $estadoItem) { ?>
                                                            <a id="<?= $estadoItem['id'] . "state"; ?>" onclick="editAndSendStatus('<?= URL ?>','<?= $value['data']['cod'] ?>','<?= $estadoItem['id'] ?>','<?= $estadoItem['enviar'] ?>')" class="dropdown-item"><?= $estadoItem['titulo'] ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <b class="mt-10 mr-10 ">MÁS OPCIONES: </b>
                                    <div class="  ">
                                        <button class="btn btn-primary pull-left mt-1 ml-10" onclick="linkMp('<?= $value['data']['cod'] ?>')">
                                            <i class="fa fa-usd"></i> LINK PAGO </button>
                                        <button class="btn btn-success pull-left mt-1 ml-10" onclick="printContent('print-<?= $value['data']['id'] ?>')">
                                            <i class="fa fa-print"></i> IMPRIMIR </button>
                                        <button class="btn btn-info pull-left mt-1 ml-10" onclick="exportPedido('<?= URL_ADMIN ?>', '<?= $value['data']['cod'] ?>')">
                                            <i class="fa fa-file-excel"></i> GUARDAR EN EXCEL</button>
                                        <a class="btn btn-info pull-left ml-10 mt-1" target="_blank" href="<?= URL ?>/api/pedidos/saveToPdf.php?cod=<?= $value['data']['cod'] ?>">
                                            <i class="fa fa-file-pdf"></i> GUARDAR EN PDF</a>
                                        <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                            <a href="<?= CANONICAL ?>&borrar=<?= $value['data']['cod'] ?>" class="btn btn-danger deleteConfirm deleteConfirm pull-left ml-10 mt-1">ELIMINAR PEDIDO</a>
                                        <?php } ?>
                                        <button class="btn btn-warning pull-left ml-10 mt-1" onclick="copyLink('<?= $value['data']['cod'] ?>')">Copiar Link Carrito Pre Armado</button>
                                        <div class="col-md-12">
                                            <input class="pull-right" style="opacity:0" type="text" value="<?= URL ?>/pedido/<?= $value['data']["cod"] ?>" id="linkCopy-<?= $value['data']["cod"] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="modalS" class="modal fade mt-120" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="textS" class="text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-12">
        <?= $paginador ?>
    </div>
</div>
<?php
if (!empty($_GET["borrar"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $pedidos->set("cod", isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '');
    $pedidos->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=pedidos");
}
?>
<script src="<?= URL_ADMIN ?>/js/script.js"></script>
<script>
    function copyLink(id) {
        var copyText = document.getElementById("linkCopy-" + id);
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        successMessage("Link de carrito pre armado: " + copyText.value);
    }

    function exportPedido(url, pedido) {
        $.ajax({
            url: url + "/api/pedidos/export.php",
            type: "POST",
            data: {
                cod: pedido
            },
            success: (data) => {
                data = JSON.parse(data);
                window.open(data.file, '_blank');
            }
        });

    }
    // function editCartPedido(url, pedido) {
    //     $.ajax({
    //         url: url + "/api/cart/pre-cart.php",
    //         type: "POST",
    //         data: {
    //             cod: pedido
    //         },
    //         success: (data) => {
    //             console.log(data);
    //             data = JSON.parse(data);
    //         }
    //     });

    // }
    function copyToClipboard(text) {
        if (window.clipboardData && window.clipboardData.setData) {
            // IE specific code path to prevent textarea being shown while dialog is visible.
            return clipboardData.setData("Text", text);

        } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            try {
                return document.execCommand("copy"); // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }

    function linkMp(pedido) {
        $.ajax({
            url: "<?= URL ?>" + "/api/payments/mp-total.php",
            type: "POST",
            data: {
                cod: pedido
            },
            beforeSend: () => {
                infoMessage("Generando link de pago...");
            },
            success: (data) => {
                console.log(data);
                data = JSON.parse(data);
                if (data['status']) {
                    var result = copyToClipboard(data['url']);
                    successMessage("Link de pago copiado: " + data['url']);
                } else {
                    errorMessage(data['message']);
                }
            }
        });

    }
</script>