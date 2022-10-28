<?php

if (!isset($_SESSION['usuarios']['cod'])) $f->headerMove(URL);

$arrContextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];

$dirFile = dirname(__DIR__, 3) . "/json/cart/" . $_SESSION['usuarios']['cod'] . ".json";
$cart = $_SESSION["carrito"];
$cart["fecha"] = date('h:i:s, j/m/y');
$cod = substr(md5(uniqid(rand())), 0, 10);

if (file_exists($dirFile)) {
    $fileContent = json_decode(file_get_contents($dirFile, false, stream_context_create($arrContextOptions)), true);
    if ($fileContent) {
        foreach ($fileContent as $key => $cart) {
            $fecha = $cart["fecha"];
            unset($cart["fecha"]);
?>
            <div class="panel mt-10" id="<?= $key ?>" style="background: lightgray">
                <a data-toggle="collapse" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>" class="collapsed color_a" style="width: 100%">
                    <div class="panel-heading boton-cuenta bold" role="tab" id="heading" style="padding: 10px;background: #8c8c8c;color: #fff;">
                        <div class="row pedido-centro text-uppercase">
                            <div class="col-md-9" style="align-self: center;">
                                <span class="negro"><?= $_SESSION["lang-txt"]["carrito"]["carrito_guardado"] ?> <?= $fecha ?></span>
                            </div>
                            <div class="col-md-3">
                                <button class="pull-right btn btn-delete" onclick="deleteItemFile('<?= $key ?>')"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                            </div>
                        </div>
                    </div>
                </a>
                <div id="collapse<?= $key ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body panel-over" style="height: auto;background:#fff">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                Producto
                                            </th>
                                            <th>
                                                Código
                                            </th>
                                            <th>
                                                Cantidad
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart as $cartItem) { ?>
                                            <tr>
                                                <td><?= $cartItem["titulo"] ?></td>
                                                <td><?= isset($cartItem["producto_cod"]) ? $cartItem["producto_cod"] : '' ?></td>
                                                <td><?= $cartItem["cantidad"] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <button class="btn btn-success pull-right btn-md mr-20 mb-10" onclick="addToCartPerFile('<?= $key ?>')"><?= $_SESSION["lang-txt"]["carrito"]["agregar_carrito"] ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } else { ?>
        <p class="text-center fs-20"><?= $_SESSION["lang-txt"]["carrito"]["sin_items"] ?></p>
    <?php }
} else {
    ?>
    <p class="text-center fs-20"><?= $_SESSION["lang-txt"]["carrito"]["sin_items"] ?></p>
<?php } ?>
<script>
    function addToCartPerFile(cod) {
        $.ajax({
            url: "<?= URL ?>/api/cart/add-per-json.php",
            type: "POST",
            data: {
                cod: cod
            },
            success: function(data) {
                
                data = JSON.parse(data);
                if (data["status"]) {
                    data["element"].forEach(element => {
                        var data = {
                            product: element.product,
                            amount: element.amount,
                            combinationInfo: element.combinationInfo,
                        };
                        addCart(data, url);
                    });
                    $("#" + cod).addClass("d-none");
                    successMessage("¡ Productos Agregados !");
                    viewCart('<?= URL ?>');
                }
            },
            error: function() {}
        });
    }

    function addCart(data, url) {
        $.ajax({
            url: url + "/api/cart/add.php",
            type: "POST",
            data: data,
            success: function(data) {
                data = JSON.parse(data);
                if (data["status"] == true) {
                    successMessage("¡ Producto Agregado !");
                    viewCart('<?= URL ?>');
                } else {
                    alertSide(data["message"]);
                    viewCart('<?= URL ?>');
                }
            },
        })
    }

    function deleteItemFile(cod) {
        $.ajax({
            url: "<?= URL ?>/api/cart/delete-per-json.php",
            type: "POST",
            data: {
                cod: cod
            },
            success: function(data) {
                
                data = JSON.parse(data);
                if (data["status"]) {
                    successMessage("¡ Carrito Eliminado !");
                    $("#" + cod).addClass("d-none");
                }
            },
        });
    }
</script>