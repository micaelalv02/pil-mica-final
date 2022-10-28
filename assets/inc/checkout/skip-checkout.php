<?php
$f = new Clases\PublicFunction();
$config = new Clases\Config();

if ($_SESSION["usuarios"]["minorista"] == 1) {
    $checkoutData = $config->viewCheckout("minorista", $_SESSION['lang']);
} else {
    $checkoutData = $config->viewCheckout("mayorista", $_SESSION['lang']);
}

$data = [
    "envio" => $checkoutData['data']['envio'],
    "cod" =>  $checkoutData['data']['pago'],
    "nombre" => isset($_SESSION["usuarios"]["nombre"]) ? $_SESSION["usuarios"]["nombre"] : 'Sin datos',
    "apellido" => isset($_SESSION["usuarios"]["apellido"]) ? $_SESSION["usuarios"]["apellido"] : 'Sin datos',
    "dni" => !empty($_SESSION["usuarios"]["doc"]) ? $_SESSION["usuarios"]["doc"] : 'Sin datos',
    "email" => isset($_SESSION["usuarios"]["email"]) ? $_SESSION["usuarios"]["email"] : "Sin datos",
    "celular" => isset($_SESSION["usuarios"]["celular"]) ? $_SESSION["usuarios"]["celular"] : "Sin datos",
    "postal" => isset($_SESSION["usuarios"]["postal"]) ? $_SESSION["usuarios"]["postal"] : "Sin datos",
    "telefono" => isset($_SESSION["usuarios"]["telefono"]) ? $_SESSION["usuarios"]["telefono"] : "Sin datos",
    "provincia" => isset($_SESSION["usuarios"]["provincia"]) ? $_SESSION["usuarios"]["provincia"] : "Sin datos",
    "localidad" => isset($_SESSION["usuarios"]["localidad"]) ? $_SESSION["usuarios"]["localidad"] : "Sin datos",
    "direccion" => isset($_SESSION["usuarios"]["direccion"]) ? $_SESSION["usuarios"]["direccion"] : "Sin datos",
    "hora" => isset($_SESSION["usuarios"]["hora"]) ? $_SESSION["usuarios"]["hora"] : "Sin datos",
    "similar" => isset($_SESSION["usuarios"]["similar"]) ? $_SESSION["usuarios"]["similar"] : "Sin datos",
    "facturar" => 1
];
?>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="<?= URL ?>/assets/js/checkout/script.js"></script>
<script src="<?= URL ?>/assets/js/checkout/stages.js"></script>
<script>
    stage1('<?= json_encode($data) ?>', '<?= URL ?>');
</script>