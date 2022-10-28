<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$pedido =  new Clases\Pedidos();
$pagos = new Clases\Pagos();
$order = isset($_GET['order']) ? $f->antihack_mysqli($_GET['order']) : $f->headerMove(URL . "/carrito?reset=1");
$pedido->set("cod", $order);
$pedidoData = $pedido->view();
$type = '';
foreach ($pedidoData["detail"] as $pedidoItem) {
    if ($pedidoItem["tipo"] == "MP") {
        $titulo = $pedidoItem["producto"];
        $pago = $pagos->list(["titulo = '$titulo'"], "", "1",$_SESSION['lang'])[0];
        if ($pago["data"]["tipo"] == "5") {
            if ($pago["data"]["leyenda"] != "1" && $pago["data"]["leyenda"] != "") {
                $type = "credit";
            } else {
                $type = "all";
            }
        }
    }
}
?>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="<?= URL ?>/assets/css/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/main-rocha.css">
    <script type="text/javascript" src="index.js" defer></script>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<body>
    <section class="payment-form dark mt-100">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <img src="<?= LOGO ?>" width="200" />
                    <a href="<?= URL ?>/carrito?reset=1" type="submit" class="mt-50 pull-left" style="font-size:22px"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a>
                </div>
            </div>
            <hr>
            <form id="formulario" onsubmit="sendForm('<?= URL ?>')" method="POST" class="mt-3">
                <input type="hidden" name="order" value="<?= $order ?>">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="card_type">Tipo de tarjeta</label>

                        <?php
                        if ($type == "credit") { ?>
                            <select name="card_type" class="form-control" required>
                                <option value="1">Visa</option>
                                <option value="104">MasterCard</option>
                                <option value="63">Cabal</option>
                                <!-- <option value="1">Visa</option>
                                <option value="8">Diners Club</option>
                                <option value="23">Tarjeta Shopping</option>
                                <option value="24">Tarjeta Naranja</option>
                                <option value="29">Italcred</option>
                                <option value="30">ArgenCard</option>
                                <option value="34">CoopePlus</option>
                                <option value="37">Nexo</option>
                                <option value="38">Credimás</option>
                                <option value="39">Tarjeta Nevada</option>
                                <option value="42">Nativa</option>
                                <option value="43">Tarjeta Cencosud</option>
                                <option value="44">Tarjeta Carrefour / Cetelem</option>
                                <option value="45">Tarjeta PymeNacion</option>
                                <option value="48">Caja de Pagos</option>
                                <option value="50">BBPS</option>
                                <option value="51">Cobro Express</option>
                                <option value="52">Qida</option>
                                <option value="54">Grupar</option>
                                <option value="55">Patagonia 365</option>
                                <option value="56">Tarjeta Club Día</option>
                                <option value="59">Tuya</option>
                                <option value="60">Distribution</option>
                                <option value="61">Tarjeta La Anónima</option>
                                <option value="62">CrediGuia</option>
                                <option value="63">Cabal Prisma</option>
                                <option value="64">Tarjeta SOL</option>
                                <option value="65">American Express</option>
                                <option value="103">Favacard</option>
                                <option value="104">MasterCard Prisma</option>
                                <option value="109">Nativa Prisma</option>
                                <option value="111">American Express Prisma</option> -->
                            </select>
                        <?php } elseif ($type == "all") { ?>
                            <select name="card_type" class="form-control" required>
                                <!-- <option disabled class="bold">-- DÉBITO --</option>
                                <option value="31">Visa Débito</option>
                                <option value="105">MasterCard Debit Prisma</option>
                                <option value="106">Maestro Prisma</option>
                                <option value="108">Cabal Débito Prisma</option> -->
                                <!-- <option disabled class="bold">-- CREDITO --</option> -->
                                <option value="1">Visa</option>
                                <option value="104">MasterCard</option>
                                <option value="63">Cabal</option>
                            </select>
                        <?php } ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="card_number">Numero de tarjeta:</label>
                        <input class="form-control" type="text" name="card_number" placeholder="XXXXXXXXXXXXXXXX" required />
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="security_code">Codigo de seguridad:</label>
                        <input type="text" class="form-control" name="security_code" placeholder="XXX" required />
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="card_expiration_month">Mes de vencimiento:</label>
                        <input type="text" name="card_expiration_month" maxlength="2" class="form-control" placeholder="MM" required />
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="card_expiration_year">Año de vencimiento:</label>
                        <input type="text" class="form-control" maxlength="2" name="card_expiration_year" placeholder="AA" required>
                    </div>
                    <div id="issuerInput" class="form-group col-sm-4">
                        <label for="card_holder_name">Nombre del titular:</label>
                        <input type="text" name="card_holder_name" placeholder="TITULAR" class="form-control" required />
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="card_holder_identification[type]">Tipo de documento:</label>
                        <select name="card_holder_identification[type]" class="form-control" >
                            <option value="dni">DNI</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="card_holder_identification[type]">Numero de documento:</label>
                        <input type="text" name="card_holder_identification[number]" placeholder="XXXXXXXXXX" class="form-control" required />
                    </div>
                    <div class="form-group col-sm-12">
                        <button type="submit" class="btn btn-success btn-block" name="enviar">Pagar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

</body>

</html>
<script>
    function alertSide(message) {
        toastr.warning(message, '', {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-full-width",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1500",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        });
    }

    function successMessage(latest) {
        toastr.success(latest, '', {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-full-width",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1500",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        });
    }
</script>
<script src="<?= URL  ?>/assets/js/jquery-2.1.1.min.js"></script>
<script src="<?= URL  ?>/assets/js/toastr.min.js"></script>
<script>
    function sendForm(url) {
        event.preventDefault();
        var dataForm = $("#formulario").serialize();
        $.ajax({
            url: url + "/api/payments/decidir.php",
            type: "POST",
            data: dataForm,
            success: (data) => {
                console.log(data);
                data = JSON.parse(data);
                if (data["status"] == true) {
                    document.location.href = url + '/checkout/detail';
                } else {
                    alertSide(data["message"]);
                    if (data["goBack"]) {
                        document.location.href = url + '/carrito';
                    }
                }
            }
        });
    }
</script>