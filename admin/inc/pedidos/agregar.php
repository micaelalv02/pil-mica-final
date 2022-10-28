<?php
$pedidos = new Clases\Pedidos();
$detalle = new Clases\DetallePedidos();
$productos = new Clases\Productos();
$carrito = new Clases\Carrito();
$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$usuarios = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();
$descuento = new Clases\Descuentos();

$descuentos = $descuento->list("", "", "");
$carroEnvio = $carrito->checkEnvio();
$carroPago = $carrito->checkPago();

$data = [
    "order" => "stock DESC",
    "limit" => "20",
    "attribute" => true,
    "combination" => true
];
$productos_array = $productos->list($data, $_SESSION['lang']);
$reset = isset($_GET['reset']) ? $funciones->antihack_mysqli($_GET['reset']) : '0';
if ($reset == 1) {
    unset($_SESSION['usuarios-ecommerce']);
    $carrito->destroy();
}
if (isset($_GET['usuario'])) {

    $usuarios->set("cod", $funciones->antihack_mysqli($_GET['usuario']));
    $usuarioData = $usuarios->view();
    $usuarios->userSession($usuarioData);
} else {
    unset($_SESSION['usuarios-ecommerce']);
    $funciones->headerMove(URL_ADMIN . '/index.php?op=usuarios&accion=ver&pedido=1');
}

if (isset($_POST["buscar"])) {
    $titulo = isset($_POST["buscar"]) ? $funciones->antihack_mysqli($_POST["buscar"]) : '';
    $titulo = explode(" ", $titulo);
    $buscar = '';
    foreach ($titulo as $tit) {
        $buscar .= "productos.titulo like '%$tit%' AND ";
    }
    $productos_array = $productos->list(["filter" => [substr($buscar, 0, -4)], "attribute" => true, "combination" => true], $_SESSION['lang']);
}
$error = '';

$descuento->refreshCartDescuento($carrito->return(), $usuarioData);
$carroData = $carrito->return();
?>
<section id="basic-datatable" class="mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <h4 class="mt-20 pull-left"> Agregar Pedidos</h4>
                    <a class="btn btn-success pull-right text-uppercase mt-15 " href="<?= URL_ADMIN ?>/index.php?op=pedidos&accion=ver">
                        VER PEDIDOS
                    </a>
                    <div class="clearfix"></div>
                    <hr />
                    <fieldset class="form-group position-relative d-block has-icon-left mb-20">
                        <form method="post">
                            <input class="form-control" type="text" placeholder="Buscar en productos" name="buscar" value="<?= isset($_POST["buscar"]) ? $_POST["buscar"] : '' ?>" />
                        </form>
                        <div class="form-control-position">
                            <i class="bx bx-search"></i>
                        </div>
                    </fieldset>
                    <hr />
                    <?php
                    if (isset($_SESSION['usuarios-ecommerce'])) {
                    ?>
                        <div class="alert alert-success" role="alert">
                            Usted está armando un carrito para el usuario: <strong><?= $_SESSION['usuarios-ecommerce']['nombre'] . ' ' . $_SESSION['usuarios-ecommerce']['apellido'] ?></strong><br>
                            Si desea eliminar este carrito para hacer uno nuevo con otro usuario, haga click <a href="<?= URL_ADMIN . '/index.php?op=pedidos&accion=agregar&reset=1' ?>">aquí</a>
                        </div>
                    <?php
                    }
                    if (!empty($error)) {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Productos</h5>
                                <hr />
                                <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr role="row">
                                            <th>PRODUCTO</th>
                                            <th>STOCK</th>
                                            <th>PRECIO</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($productos_array as $producto_) {
                                            echo "<tr>";
                                            echo "<td>" . mb_strtoupper($producto_['data']["titulo"]) . "<br> <b>COD:" . $producto_['data']["cod_producto"] . "</b></td>";
                                            echo "<td>" . $producto_['data']["stock"] . "</td>";
                                            echo "<td>$" . $producto_['data']["precio"] . "</td>";
                                        ?>
                                            <td class="text-center"> <?php if (!empty($producto_['data']['precio']) && $producto_['data']['stock'] > 0) { ?>
                                                    <button style="border:none;background:none;" data-toggle="tooltip" data-placement="top" title="Agregar a Carrito" type="button" data-toggle="modal" data-target="#myModal<?= $producto_['data']["cod"]; ?>" onclick="addAttrComb('<?= $producto_['data']['cod'] ?>','<?= $_SESSION['usuarios-ecommerce']['idioma'] ?>')">
                                                        <span class=" badge badge-light-primary">
                                                            <div class="fonticon-wrap">
                                                                <i class="fa fa-cart-plus"></i>
                                                            </div>
                                                        </span>
                                                    </button>
                                                <?php } else { ?>
                                                    <button style="border:none;background:none;" data-toggle="tooltip" data-placement="top" title="Sin Stock">
                                                        <span class=" badge badge-light-danger">
                                                            <div class="fonticon-wrap">
                                                                <i class="bx bx-block fs-20"></i>
                                                            </div>
                                                        </span>
                                                    </button>
                                                <?php } ?>
                                            </td>
                                            <?php echo "</tr>"; ?>
                                            <div id="myModal<?= $producto_['data']["cod"] ?>" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="pull-left modal-title">Agregar a Carrito</h4>
                                                        </div>
                                                        <div class="modal-body" id="contenidoForm">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <b>Título: </b>
                                                                    <input type="text" name="titulo" readonly value="<?= $producto_['data']["titulo"]; ?>" />
                                                                </div>
                                                                <div id="detail<?= $producto_['data']['cod'] ?>" class="col-md-12 mt-10"></div>
                                                                <div class="col-md-12">
                                                                    <hr />
                                                                </div>
                                                                <div class="col-md-6 mt-10">
                                                                    <button type="button" class="btn btn-warning btn-md" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                                <div class="col-md-6 mt-10">
                                                                    <button type="button" onclick="addToCart('<?= $producto_['data']['cod'] ?>')" class="pull-right btn btn-success btn-md" name="enviar">Agregar a carrito</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Pedido</h5>
                                <hr />
                                <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr role="row">
                                            <th>PRODUCTO</th>
                                            <th>PRECIO</th>
                                            <th>CANTIDAD</th>
                                            <th>TOTAL</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET["remover"])) {
                                            $carroPago = $carrito->checkPago();
                                            if ($carroPago != '') {
                                                $carrito->delete($carroPago);
                                            }
                                            $carroEnvio = $carrito->checkEnvio();
                                            if ($carroEnvio != '') {
                                                $carrito->delete($carroEnvio);
                                            }
                                            $carrito->delete($_GET["remover"]);
                                            $funciones->headerMove(URL_ADMIN . "/index.php?op=pedidos&accion=agregar&usuario=" . $_SESSION['usuarios-ecommerce']['cod']);
                                        }
                                        $i = 0;
                                        $precio = 0;
                                        foreach ($carroData as $key => $carroItem) {
                                            $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                            if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                                $clase = "text-bold";
                                                $none = "hidden";
                                            } else {
                                                $clase;
                                                $none = "";
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <b><?= mb_strtoupper($carroItem["titulo"]); ?></b>
                                                    <?php if (isset($carroItem["descuento"]["monto"])) { ?>
                                                        <br><b class="descuento-monto"><?= $carroItem["descuento"]["monto"]; ?></b>
                                                    <?php } ?>
                                                    <br>
                                                    <?php
                                                    if (is_array($carroItem['opciones'])) {
                                                        if (isset($carroItem['opciones']['texto'])) {
                                                            echo $carroItem['opciones']['texto'];
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span>
                                                    <?php if (isset($carroItem["descuento"]["precio-antiguo"])) { ?>
                                                        <span class="<?= $none ?> descuento-precio"><?= $carroItem["descuento"]["precio-antiguo"]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td><span class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></span></td>
                                                <td>
                                                    <?php
                                                    if ($carroItem["precio"] != 0) {
                                                        echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                                    } else {
                                                        echo "Sin recargo";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="<?= CANONICAL ?>&remover=<?= $key ?>" class="deleteConfirm" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                        <span class=" badge badge-light-danger">
                                                            <div class="fonticon-wrap">
                                                                <i class="bx bx-trash fs-20"></i>
                                                            </div>
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>$<?= number_format($carrito->totalPrice(), "2", ",", "."); ?></b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- CUPON DE DESCUENTO -->
                                <div class="col-md-12 hidden-xs hidden-sm hidden-lg hidden-md">
                                    <?php
                                    if ($descuentos) {
                                        if (isset($_POST["btn_codigo"])) {
                                            $codigoDescuento = isset($_POST["codigoDescuento"]) ? $f->antihack_mysqli($_POST["codigoDescuento"]) : '';
                                            $descuento->set("cod", $codigoDescuento);

                                            $response = $descuento->addCartDescuento($carro, $usuarioData);
                                            if ($response['status']['applied']) {
                                                $f->headerMove(URL . "/carrito");
                                            } else {
                                                echo "<div class='alert alert-danger'>" . $response['status']['error']['errorMsg'] . "</div>";
                                            }
                                        }
                                    }
                                    ?>
                                    <hr>
                                    <form method="post" class="row">
                                        <div class="col-md-12 text-center">
                                            <p class="mt-7"><b>¿Tenés algún código de descuento para tus compras?</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="codigoDescuento" class="form-control" placeholder="CÓDIGO DE DESCUENTO">
                                            <br class="d-md-none">
                                        </div>
                                        <div class="col-md-4">
                                            <input style="width: 100%" type="submit" value="USAR CÓDIGO" name="btn_codigo" class="btn btn-info" />
                                        </div>
                                    </form>
                                </div>
                                <br>
                                <!-- FIN CUPON DE DESCUENTO -->
                                <div class="envio" id="formulario-envio">
                                    <?php
                                    $pesoFinal = $carrito->finalWeight();
                                    $tope = $funciones->roundUpToAny($pesoFinal, 5);
                                    $metodos_de_envios = $envios->list(["((peso BETWEEN " . $pesoFinal . " AND " . $tope . ") OR peso=0)", "estado = 1"], '', '', $_SESSION['usuarios-ecommerce']["idioma"]);
                                    if ($carroEnvio == '' && !empty($carroData) == true) {
                                        echo "<b>Seleccioná el envio que más te convenga:</b>";
                                        if (isset($_POST["envio"])) {
                                            if ($carroEnvio != '') {
                                                $carrito->delete($carroEnvio);
                                            }
                                            $envio_final = $_POST["envio"];
                                            $envios->set("cod", $envio_final);
                                            $envios->set("idioma", $_SESSION['usuarios-ecommerce']["idioma"]);
                                            $envio_final_ = $envios->view();
                                            $carrito->set("id", "Envio-Seleccion");
                                            $carrito->set("cantidad", 1);
                                            $carrito->set("titulo", $envio_final_['data']["titulo"]);
                                            $carrito->set("precio", $envio_final_['data']["precio"]);
                                            $carrito->add();
                                            $funciones->headerMove(CANONICAL . "");
                                        }
                                    ?>
                                        <form method="post" id="envioForm">
                                            <select name="envio" class="form-control" id="envio" onchange="this.form.submit()">
                                                <option value="" selected disabled>Elegir envío</option>
                                                <?php
                                                foreach ($metodos_de_envios as $metodos_de_envio_) {
                                                    if ($metodos_de_envio_['data']["precio"] == 0) {
                                                        $metodos_de_envio_precio = "¡Gratis!";
                                                    } else {
                                                        $metodos_de_envio_precio = "$" . $metodos_de_envio_['data']["precio"];
                                                    }
                                                    echo "<option value='" . $metodos_de_envio_['data']["cod"] . "'>" . mb_strtoupper($metodos_de_envio_['data']["titulo"]) . " -> " . $metodos_de_envio_precio . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </form>
                                        <hr />
                                    <?php } ?>
                                </div>
                                <div class="pago" id="formulario-pago">
                                    <form method="post">
                                        <?php
                                        if ($carroPago == '' && !empty($carroEnvio) == true) {
                                            echo "<b>Seleccioná el método de pago que más te convenga:</b>";
                                            $metodo = isset($_POST["metodos-pago"]) ? $funciones->antihack_mysqli($_POST["metodos-pago"]) : '';
                                            $metodo_get = isset($_GET["metodos-pago"]) ? $funciones->antihack_mysqli($_GET["metodos-pago"]) : '';
                                            if ($metodo != '') {
                                                $key_metodo = $carrito->checkPago();
                                                $carrito->delete($key_metodo);
                                                $pagos->set("cod", $metodo);
                                                $pagos->set("idioma", $_SESSION["usuarios-ecommerce"]["idioma"]);
                                                $pago__ = $pagos->view();
                                                $precio_final_metodo = $carrito->totalPrice();
                                                if (!empty($pago__['data']["monto"])) {
                                                    if ($pago__['data']["monto"] > 0 && $pago__["data"]["monto"] != 0) {
                                                        $numero = (($precio_final_metodo * $pago__['data']["monto"]) / 100);
                                                        $carrito->set("id", "Metodo-Pago");
                                                        $carrito->set("cantidad", 1);
                                                        $carrito->set("titulo", "CARGO +" . $pago__['data']['monto'] . "% / " . mb_strtoupper($pago__['data']["titulo"]));
                                                        $carrito->set("precio", $numero);
                                                        $carrito->set("opciones", $pago__['data']['cod']);
                                                        $carrito->add();
                                                    } else {
                                                        $numero = (($precio_final_metodo * $pago__['data']["monto"]) / 100);
                                                        $carrito->set("id", "Metodo-Pago");
                                                        $carrito->set("cantidad", 1);
                                                        $carrito->set("titulo", "DESCUENTO " . $pago__['data']['monto'] . "% / " . mb_strtoupper($pago__['data']["titulo"]));
                                                        $carrito->set("precio", $numero * (-1));
                                                        $carrito->set("opciones", $pago__['data']['cod']);
                                                        $carrito->add();
                                                    }
                                                } else {
                                                    $carrito->set("id", "Metodo-Pago");
                                                    $carrito->set("cantidad", 1);
                                                    $carrito->set("titulo", mb_strtoupper($pago__['data']["titulo"]));
                                                    $carrito->set("precio", 0);
                                                    $carrito->set("opciones", $pago__['data']['cod']);
                                                    $carrito->add();
                                                }
                                                $funciones->headerMove(CANONICAL . "");
                                            }
                                        ?>
                                            <div class="form-bd">
                                                <?php $lista_pagos = $pagos->list(array("estado = 1 ","tipo != 2", "(" . $carrito->precioSinMetodoDePago() . " >= minimo OR minimo IS NULL) AND (" . $carrito->precioSinMetodoDePago() . " <= maximo OR maximo = 0 OR maximo IS NULL)"), '', '', $_SESSION['lang']); ?>
                                                <select name="metodos-pago" class="form-control" id="metodos-pago" onchange="this.form.submit()">
                                                    <option value="" selected disabled>Elegir metodo de pago</option>
                                                    <?php
                                                    foreach ($lista_pagos as $pago) {
                                                        $precio_total = $carrito->checkPriceOnPayments($pago);
                                                        echo "<option value='" . $pago['data']["cod"] . "'>" . mb_strtoupper($pago['data']["titulo"]) . " | $" . $precio_total . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </form>
                                </div>
                                <div class="usuario" id="formulario-usuario">
                                    <?php
                                    if ($carroPago != '' && $carroEnvio != '' && isset($_SESSION['usuarios-ecommerce']) == true) {
                                        $docGet = isset($_GET["doc"]) ? $funciones->antihack_mysqli($_GET["doc"]) : '0';
                                    ?>
                                        <form method="post" id="finalForm">
                                            <?php
                                            if ($docGet == 1) {
                                            ?>
                                                <label class=" mt-10 mb-10" style="font-size:16px">
                                                    <input type="checkbox" disabled checked> Solicitar FACTURA A
                                                    <input type="hidden" name="factura" value="1">
                                                </label>
                                                <?php
                                            } else {
                                                if (!empty($_SESSION['usuarios-ecommerce']['doc'])) {
                                                ?>
                                                    <label class=" mt-10 mb-10" style="font-size:16px">
                                                        <input type="checkbox" name="factura" value="1"> Solicitar FACTURA A
                                                    </label>
                                                <?php
                                                } else {
                                                ?>
                                                    <label class=" mt-10 mb-10" style="font-size:16px">
                                                        <input onclick="document.location.href='<?= URL_ADMIN . '/index.php?op=usuarios&accion=modificar&cod=' . $usuarioData['data']['cod'] . '&pedido=2'; ?>'" type="checkbox" name="factura" value="0"> Solicitar FACTURA A
                                                    </label>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <div class=" mb-50">
                                                <input class="btn btn-success addPedido" type="submit" value="¡Finalizar la compra!" onclick="addPedido()" />
                                            </div>
                                        </form>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function addAttrComb(cod, idioma) {
        $.ajax({
            url: "<?= URL_ADMIN ?>/api/atributes/view.php",
            type: "POST",
            data: {
                cod: cod,
                idioma: idioma
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    $('#detail' + cod).html('');
                    $('#detail' + cod).html(data['response']);
                    $('#myModal' + cod).modal('toggle');
                } else {
                    $('#error').html('');
                    $('#error').append(data['message']);
                    $('#modalE').modal('toggle');
                }
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    async function addPedido() {
        event.preventDefault();
        $('.addPedido').addClass('hidden');
        await $.ajax({
            url: "<?= URL_ADMIN ?>/api/pedidos/add.php",
            type: "POST",
            data: $('#finalForm').serialize(),
            success: async function(data) {
                data = JSON.parse(data);
                if (data['type'] == 'API') {
                    await $.ajax({
                        url: data['url'],
                        type: "POST",
                        data: {
                            cod: data["cod"],
                            admin: true
                        },
                        success: function(data) {
                            data = JSON.parse(data);
                            if (data['status'] == true) {
                                window.location = data['url'];
                            } else {
                                alert(data['message']);
                            }
                        },
                        error: function() {
                            alert(lang["checkout"]["payment"]["ocurrio_error"]);
                        }
                    });
                } else {
                    window.location = data['url'];
                }
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    function addToCart(cod) {
        if ($('#amount').val() != '' && $('#amount').val() > 0) {
            $.ajax({
                url: "<?= URL_ADMIN ?>/api/cart/add.php",
                type: "POST",
                data: $('#cartForm' + cod).serialize(),
                success: function(data) {
                    data = JSON.parse(data);
                    if (data['status'] == true) {
                        document.location.href = '<?= CANONICAL ?>';
                    } else {
                        alert(data['message']);
                    }
                },
                error: function() {
                    alert('Error occured');
                }
            });
        } else {
            alert("Ingresar una cantidad correcta.");
        }
    }
</script>