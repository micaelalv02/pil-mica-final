<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$pedidos = new Clases\Pedidos();
$combinaciones = new Clases\Combinaciones();
if (isset($_GET['cod'])) {
    $cod = $f->antihack_mysqli($_GET['cod']);
    $pedidos->set("cod", $cod);
    $pedido = $pedidos->view();
    (!isset($pedido['detail'])) ? $f->headerMove(URL) : '';
    foreach ($pedido['detail'] as $detail) {
        if ($detail['tipo'] == "PR") {
            if (!empty($detail['cod_combinacion'])) {
                $combinacion = $combinaciones->detail($detail['cod_combinacion']);
                foreach ($combinacion as $data) {
                    $attr[$data['data']['cod_atributo']] = $data['data']['cod_subatributo'];
                }
                $array[] = ['product' => $detail['cod_producto'], 'amount' => $detail['cantidad'], 'atribute' => $attr, 'combination' => true];
                $attr = false;
                $combinacion = false;
            } else {
                $array[] = ['product' => $detail['cod_producto'], 'amount' => $detail['cantidad'], 'atribute' => '', 'combination' => ''];
            }
        }
    }
    $arrayJson = json_encode($array);
} else {
    $f->headerMove(URL);
} ?>
<div class="skip-loader">
    <div style="display: flex;align-items: center;justify-content: center;top:0;left:0;width:100%;height:100vh;background:rgba(255,255,255,.5)">
        <div>
            <img style="margin-bottom: 20px;" src=" <?= LOGO ?>" width="300px" alt="">
            <div style="margin-bottom: 10px;margin-left: 10%;color:#666"><?= $_SESSION["lang-txt"]["checkout"]["generando_pedido"] ?></div>
            <img style="margin-left: 40%;" src="<?= URL ?>/assets/images/loader-skip-checkout.svg" width="50px" alt="">
        </div>
    </div>
</div>
<script src="<?= URL ?>/assets/theme/assets/js/vendor/jquery-3.5.1.min.js"></script>
<script>
    addCartByLink(<?= $arrayJson ?>, '<?= URL ?>');

    function addCartByLink(cart, url) {
        cart.forEach(async element => {
            var data = {
                product: element.product,
                amount: element.amount,
                atribute: element.atribute,
                combination: element.combination,
            };

            addCart(data, url);
        });
        setTimeout(function() {
            window.location = url + '/carrito';
        }, 5000);
    }

    function addCart(data, url) {
        console.log(data);
        $.ajax({
            url: url + "/api/cart/add.php",
            type: "POST",
            data: data,
            success: function(data) {
                console.log(data);
            }
        })
    };
</script>